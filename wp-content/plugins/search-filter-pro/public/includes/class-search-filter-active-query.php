<?php
/**
 * Search & Filter Pro
 * 
 * @package   class Search_Filter_Active_Query
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2015 Designs & Code
 */
 		
//class to grab the current query performed with the search, such as taxonomies highlighted, number ranges and post meta selections

class Search_Filter_Active_Query {
	
	public $sfid 					= 0;
	private $is_set 				= false;
	private $query_array 			= array();
	private $form_fields 			= array();
	
	public function __construct($sfid, $settings, $fields)
	{
		global $wpdb;
		
		if($this->sfid == 0)
		{
			$this->sfid = $sfid;
			$this->form_fields = $fields;
			$this->form_settings = $settings;
		}
	}

	private function set_array()
	{
		//the the object has already been set up don't bother setting up again
		if($this->is_set)
		{
			return;
		}
		
		//now set flag to true so we can't come here again
		$this->is_set = true;

		//now loop through URL vars and grab the user selection
		
		//grab search term for prefilling search input
		
		if(isset($_GET['_sf_s']))
		{
			$this->searchterm = esc_attr(trim(stripslashes($_GET['_sf_s'])));
		}
		
		
		$taxs = array();
		
		//loop through all the query vars
		if(isset($_GET))
		{
			foreach($_GET as $key=>$val)
			{
				if (strpos($key, SF_TAX_PRE) === 0)
				{
					$tax_object = $this->get_taxonomy($key);
					$this->query_array[$key] = $tax_object;	
				}
				else if (strpos($key, SF_META_PRE) === 0)
				{
					$meta_data = $this->get_post_meta($key);
					$this->query_array[$key] = $meta_data;					
					
				}
				else if ($key=="post_types")
				{
					$post_type = $this->get_post_type($key);
					$this->query_array[$key] = $post_type;					
					
				}
				else if ($key=="authors")
				{
					$post_type = $this->get_author($key);
					$this->query_array[$key] = $post_type;					
					
				}
			}
		}
		
		$post_date = array("","");
		if(isset($_GET['post_date']))
		{
			$post_date = array_map('urldecode', explode("+", esc_attr(urlencode($_GET['post_date']))));
			if(count($post_date)==1)
			{
				$post_date[1] = "";
			}
		}
		$this->query_array[SF_FPRE.'post_date'] = $post_date;
		
		
		
		
		$sort_order = array();
		if(isset($_GET['sort_order']))
		{
			$sort_order = explode(",",esc_attr(urlencode($_GET['sort_order'])));
		}
		$this->query_array[SF_FPRE.'sort_order'] = $sort_order;
		
		

	}


	public function get_taxonomy_terms_string($sf_taxonomy_key, $term_delim_arr = array(", "), $show_if_not_selected = true, $use_smart_labels = true)
	{
		$taxonomy = $this->get_taxonomy($sf_taxonomy_key);

		$active_terms = $taxonomy['active_terms'];
		$no_active_terms = count($active_terms);

		
		$term_string = "";

		$taxonomy_string = "";

		if($no_active_terms==0)
		{
			if($show_if_not_selected)
			{
				$term_string = $taxonomy['all_items_label'];
			}
		}
		else
		{
			//$active_term_names = array_map(function($el){ return $el['name']; }, $active_terms);
			$term_delim = "";
			if(count($term_delim_arr)==1)
			{//then use the same for both and / or , scenarios
				$term_delim = $term_delim_arr[0];
			}
			
			$term_string = implode($term_delim, array_map(array($this, 'implode_name'), $active_terms));
			
			

		}

		if($term_string!="")
		{
			$taxonomy_string = $term_string;
		}

		return $taxonomy_string;
	}

	public function implode_name($term)
	{
		return $term['name'];		
	}
	
	public function get_fields_html($field_names, $args = array())
	{
		$fields_strs = array();

		$defaults = array(

			"str" 					=> '%1$s: %2$s',
			"delim" 				=> array(", ", " - "), //first value is for regular delim, second value is for range fields
			"field_delim"			=> '<br />',
			"show_all_if_empty"		=> true,
			"use_smart_lables" 		=> true,
			"labels"		 		=> array()

		);


		if(is_array($args))
		{
			$fields_args = array_replace($defaults, $args);
		}
		else
		{
			$fields_args = $defaults;
		}
		

		foreach($field_names as $field_name)
		{
			if($field_name)
			{
				$field_args = $fields_args;
				
				$field_args['labels'] = array();

				if(isset($fields_args['labels']))
				{
					if(isset($fields_args['labels'][$field_name]))
					{

						$field_args['labels'] = $fields_args['labels'][$field_name];
						
					}
				}

				$field_str = $this->get_field_string($field_name, $field_args);
				if($field_str!="")
				{
					array_push($fields_strs, $field_str);
				}
			}
		}


		return implode($fields_args['field_delim'], $fields_strs);

	}

	public function get_field_string($sf_field_key, $args = array())
	{
		$defaults = array(

			"str" 					=> '%1$s: %2$s',
			"delim" 				=> array(", ", " - "),
			"show_all_if_empty"		=> true,
			"use_smart_lables" 		=> true,
			"labels"				=> array(
										"name" 				=> "",
										"singular_name" 	=> "",
										"all_items_label"	=> ""
									)

		);

		$field_args = array_replace($defaults, $args);

		$field_as_string = "";

		$is_choice_field = true;

		if (strpos($sf_field_key, SF_TAX_PRE) === 0)
		{
			$field = $this->get_taxonomy($sf_field_key);
		}
		else if (strpos($sf_field_key, SF_META_PRE) === 0)
		{
			if(!isset($this->form_fields[$sf_field_key]))
			{
				return;
			}

			$post_meta_field = $this->form_fields[$sf_field_key];

			if($post_meta_field['meta_type']!="choice")
			{
				$field_args['use_smart_lables'] = false;
				$is_choice_field = false;
			}

			$field = $this->get_post_meta($sf_field_key, $field_args['labels']);

		}
		else if($sf_field_key=="_sf_authors")
		{
			$field = $this->get_author($sf_field_key, $field_args['labels']);
		}
		else if($sf_field_key=="_sf_post_types")
		{
			$field = $this->get_post_type($sf_field_key, $field_args['labels']);
		}
		else
		{
			return;
		}

		//calculate delims
		if(!is_array($field_args['delim']))
		{
			$delim = $field_args['delim'];
		}
		else
		{
			if(count($field_args['delim'])>0)
			{
				$delim_arr = $field_args['delim'];
			}
			else
			{
				$delim_arr = $defaults['delim'];
			}

			if(count($delim_arr)==1)
			{
				$delim = $delim_arr[0];
			}
			else
			{
				if($is_choice_field)
				{
					$delim = $delim_arr[0];
				}
				else
				{
					$delim = $delim_arr[1];
				}
			}

		}





		$active_terms = $field['active_terms'];
		$no_active_terms = count($active_terms);

		$taxonomy_label = $field['name'];
		$term_string = "";

		if($field_args['use_smart_lables'])
		{
			if($no_active_terms==1)
			{
				$taxonomy_label = $field['singular_name'];
			}
		}

		$field_string = "";
		$term_string = "";
		
		if($no_active_terms==0)
		{
			if($field_args['show_all_if_empty'])
			{
				$term_string =$field['all_items_label'];
			}
		}
		else
		{
			$term_string = implode($delim, array_map(array($this, 'implode_name'), $active_terms));
		}
		
		if(($taxonomy_label!="")&&($term_string!=""))
		{
			$field_as_string = sprintf($field_args['str'], $taxonomy_label, $term_string);
		}
		
		return $field_as_string;
	}



	
	public function get_post_meta($sf_post_meta_key, $labels = array())
	{
		global $wp_query;
		global $wpdb;

		$post_meta_obj = array();

		//remove sf prefix fom taxonomy
		$post_meta_key = substr($sf_post_meta_key, strlen(SF_META_PRE));
		
		//first get the taxonomy singular and plural label
		//$post_meta = $this->get_post_meta_field($sf_post_meta_key);

		/*if(!$taxonomy)
		{
			return false;
		}*/

		$post_meta_obj['name'] = isset($labels['name']) ? $labels['name'] : "";
		$post_meta_obj['singular_name'] = isset($labels['singular_name']) ? $labels['singular_name'] : "";
		$post_meta_obj['all_items_label'] = isset($labels['all_items_label']) ? $labels['all_items_label'] : "";
		$post_meta_obj['type'] = "post_meta";
		
		$post_meta_obj['active_terms'] = array();

		if(isset($_GET[$sf_post_meta_key]))
		{
			
			if(isset($this->form_fields[$sf_post_meta_key]))
			{
				$post_meta_field = $this->form_fields[$sf_post_meta_key];
				
				if($post_meta_field['meta_type']=="choice")
				{
					$post_meta_options_list = $post_meta_field['meta_options'];

					if($post_meta_field["operator"]=="or")
					{
						$ochar = "-,-";
						$post_meta_values = explode($ochar, esc_attr($_GET[$sf_post_meta_key]));
					}
					else
					{
						$ochar = "-+-";
						$post_meta_values = explode($ochar, esc_attr(urlencode($_GET[$sf_post_meta_key])));
						$post_meta_values = array_map( 'urldecode', ($meta_data) );
					}

					foreach ($post_meta_values as $post_meta_value)
					{
						$tax_term = array();

						$post_meta_option_index = $this->search_meta_option_by_value($post_meta_value, $post_meta_options_list);

						$post_meta_option_full = $post_meta_options_list[$post_meta_option_index];

						$post_meta_option = array();
						$post_meta_option["name"] = $post_meta_option_full['option_label'];
						$post_meta_option["value"] = $post_meta_option_full['option_value'];

						array_push($post_meta_obj['active_terms'], $post_meta_option);

					}
				}
				else if($post_meta_field['meta_type']=="number")
				{
					$post_meta_values = array();

					//var_dump($post_meta_field);

					$post_meta_values = (preg_split("/[,\+ ]/", esc_attr(($_GET[$sf_post_meta_key])))); //explode with 2 delims
					
					foreach($post_meta_values as $post_meta_value)
					{
						$post_meta_option = array();

						$post_meta_option["name"] = $post_meta_field['range_value_prefix'].$post_meta_value.$post_meta_field['range_value_postfix'];
						$post_meta_option["value"] = $post_meta_value;

						array_push($post_meta_obj['active_terms'], $post_meta_option);
					}
					
				}
				else if ($post_meta_field['meta_type']=="date") {

					$post_meta_values = array();
					
					$post_meta_values = array_map('urldecode', explode("+", esc_attr(urlencode($_GET[$sf_post_meta_key]))));
					
					foreach($post_meta_values as $post_meta_value)
					{
						$post_meta_option = array();
						$post_meta_option["name"] = $post_meta_value;
						$post_meta_option["value"] = $post_meta_value;

						array_push($post_meta_obj['active_terms'], $post_meta_option);
					}
				}
			}
		}
		
		return $post_meta_obj;
		

	}
	public function search_meta_option_by_value($value, $array) {
	   foreach ($array as $key => $val) {
	       if ($val['option_value'] === $value) {
	           return $key;
	       }
	   }
	   return null;
	}
	public function get_taxonomy($sf_taxonomy_key)
	{
		global $wp_query;
		global $wpdb;

		$taxonomy_obj = array();

		//remove sf prefix fom taxonomy
		$taxonomy_key = substr($sf_taxonomy_key, strlen(SF_TAX_PRE));
		
		//first get the taxonomy singular and plural label
		$taxonomy = get_taxonomy($taxonomy_key);

		if(!$taxonomy)
		{
			return false;
		}

		$taxonomy_obj['name'] = $taxonomy->labels->name;
		$taxonomy_obj['singular_name'] = $taxonomy->labels->singular_name;
		$taxonomy_obj['all_items_label'] = $taxonomy->labels->all_items;
		$taxonomy_obj['type'] = "taxonomy";
		
		$taxonomy_obj['active_terms'] = array();

		if(isset($_GET[$sf_taxonomy_key]))
		{
			$tax_values_str = esc_attr(trim($_GET[$sf_taxonomy_key]));

			$tax_term_slugs = (preg_split("/[,\+ ]/", $tax_values_str)); //explode with 2 delims

			foreach ($tax_term_slugs as $tax_term_slug)
			{
				$tax_term_full = get_term_by('slug', $tax_term_slug, $taxonomy_key);
				$tax_term = array();
				$tax_term["id"] = $tax_term_full->term_id;
				$tax_term["name"] = $tax_term_full->name;
				$tax_term["value"] = $tax_term_full->slug;

				array_push($taxonomy_obj['active_terms'], $tax_term);

			}

			
		}
		return $taxonomy_obj;
		

	}

	public function get_post_type($sf_field_key, $labels = array())
	{
		global $wp_query;
		global $wpdb;

		$field_obj = array();

		$label_defaults = array(

			"name" 					=> "Post Types",
			"singular_name"			=> 'Post Type',
			"all_items_label"		=> "All Post Types"

		);


		if((is_array($labels))&&(!empty($labels)))
		{
			$labels = array_replace($label_defaults, $labels);
		}
		else
		{
			$labels = $label_defaults;
		}

		$field_obj['name'] = $labels['name'];
		$field_obj['singular_name'] = $labels['singular_name'];
		$field_obj['all_items_label'] = $labels['all_items_label'];
		$field_obj['type'] = "post_type";
		
		$field_obj['active_terms'] = array();

		
		if (strpos($sf_field_key, SF_FPRE) === 0)
		{
			$sf_get_key = substr($sf_field_key, strlen(SF_FPRE));
		}
		else
		{
			$sf_get_key = $sf_field_key;
		}

		if(isset($_GET[$sf_get_key]))
		{
			$post_type_values_str = esc_attr(trim($_GET[$sf_get_key]));

			$post_type_slugs = (preg_split("/[,\+ ]/", $post_type_values_str)); //explode with 2 delims

			foreach ($post_type_slugs as $post_type_slug)
			{
				$post_type_object = get_post_type_object($post_type_slug);

				$post_type_term = array();
				$post_type_term["name"] = $post_type_object->labels->name;
				$post_type_term["value"] = $post_type_slug;

				array_push($field_obj['active_terms'], $post_type_term);

			}

			
		}

		return $field_obj;
	}

	public function get_author($sf_field_key, $labels = array())
	{
		global $wp_query;
		global $wpdb;

		$field_obj = array();

		$label_defaults = array(

			"name" 					=> "Authors",
			"singular_name"			=> 'Author',
			"all_items_label"		=> "All Authors"

		);
		
		if((is_array($labels))&&(!empty($labels)))
		{
			$labels = array_replace($label_defaults, $labels);
		}
		else
		{
			$labels = $label_defaults;
		}

		$field_obj['name'] = $labels['name'];
		$field_obj['singular_name'] = $labels['singular_name'];
		$field_obj['all_items_label'] = $labels['all_items_label'];
		$field_obj['type'] = "post_type";
		
		$field_obj['active_terms'] = array();

		if (strpos($sf_field_key, SF_FPRE) === 0)
		{
			$sf_get_key = substr($sf_field_key, strlen(SF_FPRE));
		}
		else
		{
			$sf_get_key = $sf_field_key;
		}

		if(isset($_GET[$sf_get_key]))
		{
			$field_values_str = esc_attr(trim($_GET[$sf_get_key]));

			$field_vals = (preg_split("/[,\+ ]/", $field_values_str)); //explode with 2 delims

			foreach ($field_vals as $field_val)
			{
				$field_object = get_userdata($field_val);

				$field_term = array();
				$field_term["id"] = $field_object->ID;
				$field_term["name"] = $field_object->user_nicename;
				$field_term["value"] = $field_object->user_nicename;

				array_push($field_obj['active_terms'], $field_term);

			}

			
		}

		return $field_obj;
	}

	public function get_array()
	{
		
		$this->set_array();

		return $this->query_array;
	}
	
	public function get_search_term()
	{
		$search_term = "";
		
		if(isset($_GET['_sf_s']))
		{
			$search_term = esc_attr(trim(stripslashes($_GET['_sf_s'])));
		}
		
		return $search_term;
		
	}

	
}
