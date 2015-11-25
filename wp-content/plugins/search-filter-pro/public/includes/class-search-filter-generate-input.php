<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Generate_Input
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2015 Designs & Code
 */

class Search_Filter_Generate_Input {
	
	public function __construct($plugin_slug, $sfid) {

		$this->plugin_slug = $plugin_slug;
		$this->sfid = $sfid;
	}
		
	/*
	 * Display various inputs
	*/
	//use wp array walker to enable hierarchical display and other options
	public function generate_wp_dropdown($args, $name, $labels = null)
	{
		$elem_attr = "";
		$returnvar = "";
		
		if(isset($args['elem_attr']))
		{
			$elem_attr .= $args['elem_attr'];
		}

		$input_class = SF_CLASS_PRE."input-select";
		
		$returnvar .= '<select name="'.$args['sf_name'].'[]" class="'.$input_class.'"'.$elem_attr.'>';
		$returnvar .= $this->walk_taxonomy('select', $args);
		$returnvar .= "</select>";
		
		return $returnvar;
	}
	
	public function generate_wp_multiselect($args, $name, $labels = null)
	{
		$elem_attr = "";
		if(isset($args['elem_attr']))
		{
			$elem_attr .= $args['elem_attr'];
		}
		$input_class = SF_CLASS_PRE."input-multiselect";
		$returnvar = '<select multiple="multiple" name="'.$args['sf_name'].'[]" class="'.$input_class.'"'.$elem_attr.'>';
		$returnvar .= $this->walk_taxonomy('multiselect', $args);
		$returnvar .= "</select>";
		
		return $returnvar;
	}
	
	public function generate_wp_list($args, $name, $labels = null)
	{
		$elem_attr = "";
		if(isset($args['elem_attr']))
		{
			$elem_attr .= $args['elem_attr'];
		}
		
		$returnvar = '<ul'.$elem_attr.'>';
		$returnvar .= $this->walk_taxonomy('list', $args);
		$returnvar .= "</ul>";
		
		return $returnvar;
	}
	public function generate_wp_checkbox($args, $name, $labels = null)
	{
		$elem_attr = "";
		if(isset($args['elem_attr']))
		{
			$elem_attr .= $args['elem_attr'];
		}
		
		$returnvar = '<ul'.$elem_attr.'>';
		$returnvar .= $this->walk_taxonomy('checkbox', $args);
		$returnvar .= "</ul>";
		
		return $returnvar;
	}
	
	public function generate_wp_radio($args, $name, $labels = null)
	{
		$returnvar = '<ul>';
		$returnvar .= $this->walk_taxonomy('radio', $args);
		$returnvar .= "</ul>";
		
		return $returnvar;
	}
	
	//generate generic form inputs for use elsewhere, such as post types and non taxonomy fields
	public function generate_select($dropdata, $name, $defaults, $all_items_label = null, $elem_attr = "", $show_count = "", $hide_empty = "")
	{
		$returnvar = "";

		$input_class = SF_CLASS_PRE."input-select";
		
		$returnvar .= '<select class="'.$input_class.'" name="'.$name.'[]"'.$elem_attr.'>';
		if(isset($all_items_label))
		{
			if($all_items_label!="")
			{//check to see if all items has been registered in field then use this label
				$returnvar .= '<option class="level-0" value="">'.esc_html($all_items_label).'</option>';
			}
		}

		foreach($dropdata as $dropdown)
		{
			$selected = "";
			
			if(isset($defaults))
			{
				if(is_array($defaults))
				{
					foreach($defaults as $defaultid)
					{
						if($defaultid==$dropdown->term_id)
						{
							$selected = ' selected="selected"';
						}
					}
				}
			}
			
			$show_option = 1;
			$term_count_text = "";
			if(($this->is_taxonomy_key($name))||($this->is_meta_value($name)))
			{
				global $searchandfilter;
				$this->auto_count = $searchandfilter->get($this->sfid)->settings("enable_auto_count");

				if($this->auto_count==1)
				{
					$show_option = 0;
					
					global $searchandfilter;
					
					$term_count = $searchandfilter->get($this->sfid)->get_count_var($name, esc_attr($dropdown->term_id));
						
					$term_count_text = "";
					if($show_count==1)
					{
						$term_count_text = " <span class='sf-count'>($term_count)</spann>";
					}
					
					if(($hide_empty!=1)||($term_count!=0))
					{
						$show_option = 1;
					}
				}
			}
			
			if($show_option==1)
			{
				$returnvar .= '<option class="level-0" value="'.esc_attr($dropdown->term_id).'"'.$selected.'>'.esc_html($dropdown->cat_name).$term_count_text.'</option>';
			}

		}
		$returnvar .= "</select>";

		return $returnvar;
	}
	
	
	public function generate_multiselect($dropdata, $name, $defaults, $elem_attr = "", $show_count = "", $hide_empty = "")
	{
		$returnvar = "";

		$input_class = SF_CLASS_PRE."input-multiselect";

		$returnvar .= '<select multiple="multiple" class="'.$input_class.'" name="'.$name.'[]"'.$elem_attr.'>';
		
		foreach($dropdata as $dropdown)
		{
			$selected = "";

			if(isset($defaults))
			{
				if(is_array($defaults)) //there should never be more than 1 default in a select, if there are then don't set any, user is obviously searching multiple values, in the case of a select this must be "all"
				{
					foreach($defaults as $defaultid)
					{
						if($defaultid==$dropdown->term_id)
						{
							$selected = ' selected="selected"';
						}
					}
				}
			}
			
			
			
			$show_option = 1;
			$term_count_text = "";
			if(($this->is_taxonomy_key($name))||($this->is_meta_value($name)))
			{
				global $searchandfilter;
				$this->auto_count = $searchandfilter->get($this->sfid)->settings("enable_auto_count");

				if($this->auto_count==1)
				{
					$show_option = 0;
					
					global $searchandfilter;
					
					$term_count = $searchandfilter->get($this->sfid)->get_count_var($name, esc_attr($dropdown->term_id));
					

					$term_count_text = "";
					if($show_count==1)
					{
						$term_count_text = " <span class='sf-count'>($term_count)</span>";
					}
					
					if(($hide_empty!=1)||($term_count!=0))
					{
						$show_option = 1;
					}
				}
			}
			
			if($show_option==1)
			{
				$returnvar .= '<option class="level-0" value="'.esc_attr($dropdown->term_id).'"'.$selected.'>'.esc_html($dropdown->cat_name).$term_count_text.'</option>';
			}

		}
		$returnvar .= "</select>";

		return $returnvar;
	}
	
	public function generate_checkbox($dropdata, $name, $defaults, $elem_attr = "", $show_count = "", $hide_empty = "")
	{
		$returnvar = '<ul'.$elem_attr.'>';
		$input_class = SF_CLASS_PRE."input-checkbox";
		
		foreach($dropdata as $dropdown)
		{
			$checked = "";
			
			//check a default has been set
			if(isset($defaults))
			{
				if(is_array($defaults))
				{
					foreach($defaults as $defaultid)
					{
						if($defaultid==$dropdown->term_id)
						{
							$checked = ' checked="checked"';
						}
					}
				}				
			}
			
			
			
			$show_option = 1;
			$term_count_text = "";
			if(($this->is_taxonomy_key($name))||($this->is_meta_value($name)))
			{
				global $searchandfilter;
				$this->auto_count = $searchandfilter->get($this->sfid)->settings("enable_auto_count");

				if($this->auto_count==1)
				{
					$show_option = 0;
					
					global $searchandfilter;
					
					$term_count = $searchandfilter->get($this->sfid)->get_count_var($name, esc_attr($dropdown->term_id));
					
						
					$term_count_text = "";
					if($show_count==1)
					{
						$term_count_text = " <span class='sf-count'>($term_count)</span>";
					}
					
					if(($hide_empty!=1)||($term_count!=0))
					{
						$show_option = 1;
					}
				}
			}
			
			if($show_option==1)
			{
				$input_id = SF_INPUT_ID_PRE.sanitize_html_class($name."_".$dropdown->term_id);
				$returnvar .= '<li><input class="'.$input_class.'" id="'.$input_id.'" type="checkbox" name="'.$name.'[]" value="'.esc_attr($dropdown->term_id).'"'.$checked.'><label for="'.$input_id.'">'.esc_html($dropdown->cat_name).$term_count_text.'</label></li>';
			}
		}
		
		$returnvar .= '</ul>';
		
		return $returnvar;
	}
	
	public function generate_radio($dropdata, $name, $defaults, $all_items_label = null, $show_count = "", $hide_empty = "")
	{
		$returnvar = '<ul>';

		$input_class = SF_CLASS_PRE."input-radio";
		
		if(isset($all_items_label))
		{
			if($all_items_label!="")
			{
				$checked = "";
				
				if(isset($defaults))
				{
					if(!is_array($defaults))
					{
						if($defaults=="")
						{
							$checked = ' checked="checked"';
						}
					}
				}
				$input_id = SF_INPUT_ID_PRE.sanitize_html_class($name."_0_all_items");
				
				$returnvar .= '<li><input class="'.$input_class.'" id="'.$input_id.'" type="radio" name="'.$name.'[]" value=""'.$checked.'><label for="'.$input_id.'">'.esc_html($all_items_label).'</label></li>';
			}
		}
		
		foreach($dropdata as $dropdown)
		{
			$checked = "";
			
			//check a default has been set
			if(isset($defaults))
			{
				if(is_array($defaults))
				{
					foreach($defaults as $defaultid)
					{
						if($defaultid==$dropdown->term_id)
						{
							$checked = ' checked="checked"';
						}
					}
				}
			}
			

			$show_option = 1;
			$term_count_text = "";
			if(($this->is_taxonomy_key($name))||($this->is_meta_value($name)))
			{
				global $searchandfilter;
				$this->auto_count = $searchandfilter->get($this->sfid)->settings("enable_auto_count");

				if($this->auto_count==1)
				{
					$show_option = 0;
					
					global $searchandfilter;
					
					$term_count = $searchandfilter->get($this->sfid)->get_count_var($name, esc_attr($dropdown->term_id));
					
					$term_count_text = "";
					if($show_count==1)
					{
						$term_count_text = " <span class='sf-count'>($term_count)</span>";
					}
					
					if(($hide_empty!=1)||($term_count!=0))
					{
						$show_option = 1;
					}
				}
			}
			
			if($show_option==1)
			{
				$input_id = SF_INPUT_ID_PRE.sanitize_html_class($name."_".$dropdown->term_id);
				$returnvar .= '<li><input class="'.$input_class.'" id="'.$input_id.'" type="radio" name="'.$name.'[]" value="'.esc_attr($dropdown->term_id).'"'.$checked.'><label for="'.$input_id.'">'.esc_html($dropdown->cat_name).$term_count_text.'</label></li>';
			}
		}
		
		$returnvar .= '</ul>';
		
		return $returnvar;
	}
	
	public function generate_date($name, $defaults, $placeholder = "mm/dd/yyyy", $prefix, $postfix, $currentid = 0)
	{
		$returnvar = '';
		$current_date = '';
		//check a default has been set - upto two possible vars for array 

		$input_class = SF_CLASS_PRE."input-date";
		
		if(isset($defaults))
		{
			$noselected = count($defaults);
			
			if(($noselected>0)&&(is_array($defaults)))
			{
				$current_date = $defaults[$currentid];
			}
		}

		if($prefix!="")
		{
			$returnvar .= "<span class='".SF_CLASS_PRE."date-prefix'>".$prefix."</span>";
		}

		$returnvar .= '<input class="'.SF_CLASS_PRE.'datepicker '.$input_class.'" type="text" name="'.$name.'[]" value="' . esc_attr($current_date) . '" placeholder="'.$placeholder.'" />';

		if($postfix!="")
		{
			$returnvar .= "<span class='".SF_CLASS_PRE."date-postfix'>".$postfix."</span>";
		}
		
		return $returnvar;
	}
	
	public function walk_taxonomy( $type = "checkbox", $args = array() )
	{
		$args['walker'] = new Search_Filter_Taxonomy_Walker($type, $args['sf_name']);
		
		$output = wp_list_categories($args);
		if ( $output )
			return $output;
	}
	
	public function walk_author( $type = "checkbox", $args = array() ) {

		$walker = new Search_Filter_Author_Walker($type, $args['name']);
		$args['echo'] = false;
		$output = $walker->wp_authors($type, $args);
		
		if ( $output )
			return $output;
	}
	
	public function generate_range_slider($field, $min, $max, $step, $smin, $smax, $value_prefix = "", $value_postfix = "")
	{
		$returnvar = "";
		$input_class = SF_CLASS_PRE."input-range-number";

		if($value_prefix!="")
		{
			$value_prefix = $value_prefix." ";
		}
		if($value_postfix!="")
		{
			$value_postfix = " ".$value_postfix;
		}		
		
		if((int)$smax<(int)$smin)
		{
			$smax = $smin;
		}
		
		$smin = (int)$smin;
		if((int)$smax<(int)$smin)
		{
			$smax = $smin;
		}
		$smax = (int)$smax;
		
		$returnvar .= '<div class="meta-range" data-start-min="'.esc_attr($smin).'" data-start-max="'.esc_attr($smax).'" data-min="'.esc_attr($min).'" data-max="'.esc_attr($max).'" data-step="'.esc_attr($step).'">';
		$returnvar .= $value_prefix.'<input name="'.$field.'[]" type="number" min="'.esc_attr($min).'" max="'.esc_attr($max).'" step="'.esc_attr($step).'" class="range-min '.$input_class.'" value="'.(int)$smin.'" />'.$value_postfix;
		$returnvar .= ' - ';
		$returnvar .= $value_prefix.'<input name="'.$field.'[]" type="number" min="'.esc_attr($min).'" max="'.esc_attr($max).'" step="'.esc_attr($step).'" class="range-max '.$input_class.'" value="'.(int)$smax.'" />'.$value_postfix;
		$returnvar .= '<div class="meta-slider"></div>';
		$returnvar .= '</div>';
		
		return $returnvar;
	}
	
	public function generate_range_number($field, $min, $max, $step, $smin, $smax, $value_prefix = "", $value_postfix = "")
	{
		$returnvar = "";
		$input_class = SF_CLASS_PRE."input-range-number";

		if($value_prefix!="")
		{
			$value_prefix = $value_prefix." ";
		}
		if($value_postfix!="")
		{
			$value_postfix = " ".$value_postfix;
		}
		
		$smin = (int)$smin;
		if((int)$smax<(int)$smin)
		{
			$smax = $smin;
		}
		$smax = (int)$smax;
		
		$returnvar .= '<div class="meta-range" data-start-min="'.esc_attr($smin).'" data-start-max="'.esc_attr($smax).'" data-min="'.esc_attr($min).'" data-max="'.esc_attr($max).'" data-step="'.esc_attr($step).'">';
		$returnvar .= $value_prefix.'<input name="'.$field.'[]" type="number" min="'.esc_attr($min).'" max="'.esc_attr($max).'" step="'.esc_attr($step).'" class="range-min '.$input_class.'" value="'.(int)$smin.'" />'.$value_postfix;
		$returnvar .= ' - ';
		$returnvar .= $value_prefix.'<input name="'.$field.'[]" type="number" min="'.esc_attr($min).'" max="'.esc_attr($max).'" step="'.esc_attr($step).'" class="range-max '.$input_class.'" value="'.(int)$smax.'" />'.$value_postfix;
		$returnvar .= '</div>';
		
		return $returnvar;
	}
	public function generate_range_radio($field, $min, $max, $step, $default, $value_prefix = "", $value_postfix = "")
	{
		$returnvar = '<ul>';

		$input_class = SF_CLASS_PRE."input-radio";
		
		$startval = $min;
		$endval = $max;
		$diff = $endval - $startval;
		$istep = ceil($diff/$step);
		
		
		for($i=0; $i<($istep); $i++)
		{
			$radio_value = $startval + ($i * $step);
			$radio_top_value = ($radio_value + $step - 1);
			
			if($radio_top_value>$endval)
			{
				$radio_top_value = $endval;
			}
			
			$radio_label = $value_prefix.$radio_value.$value_postfix." - ".$value_prefix.$radio_top_value.$value_postfix;
			$radio_value = esc_attr($radio_value).'+'.esc_attr($radio_top_value);
			
			$checked = "";
			if($radio_value == $default)
			{
				$checked = ' checked="checked"';
			}

			$input_id = SF_INPUT_ID_PRE.sanitize_html_class($name."_".$radio_value);

			$returnvar .= '<li><input class="'.$input_class.'" id="'.$input_id.'" type="radio" name="'.$field.'[]" value="'.$radio_value.'"'.$checked.'><label for="'.$input_id.'">'.esc_html($radio_label).'</label></li>';
		}
		
		
		$returnvar .= '</ul>';
		
		return $returnvar;
	}
	public function generate_range_select($field, $min, $max, $step, $default, $value_prefix = "", $value_postfix = "")
	{
		
		
		$startval = $min;
		$endval = $max;
		$diff = $endval - $startval;
		$istep = ceil($diff/$step);
		
		$input_class = SF_CLASS_PRE."input-select";
		

		$returnvar = "";
		
		$returnvar .= '<select class="'.$input_class.'" name="'.$field.'[]">';
		if(isset($all_items_label))
		{
			if($all_items_label!="")
			{//check to see if all items has been registered in field then use this label
				$returnvar .= '<option class="level-0" value="">'.esc_html($all_items_label).'</option>';
			}
		}
		
		
		for($i=0; $i<($istep); $i++)
		{
			$radio_value = $startval + ($i * $step);
			$radio_top_value = ($radio_value + $step - 1);
			
			if($radio_top_value>$endval)
			{
				$radio_top_value = $endval;
			}
			
			$radio_label = $value_prefix.$radio_value.$value_postfix." - ".$value_prefix.$radio_top_value.$value_postfix;
			$radio_value = esc_attr($radio_value).'+'.esc_attr($radio_top_value);
			
			$selected = "";
			if($radio_value == $default)
			{
				$selected = ' selected="selected"';
			}
			$returnvar .= '<option class="level-0" value="'.esc_attr($radio_value).'"'.$selected.'>'.esc_html($radio_label).'</option>';
		}
		
		
		$returnvar .= "</select>";
		
		return $returnvar;
	}
	
	public function generate_range_checkbox($field, $min, $max, $step, $smin, $smax, $value_prefix = "", $value_postfix = "")
	{
		$returnvar = '<ul>';
		$input_class = SF_CLASS_PRE."input-checkbox";
		
		if(isset($this->defaults[SF_FPRE.'meta_'.$field]))
		{
			$defaults = $this->defaults[SF_FPRE.'meta_'.$field];
		}
		
		if(isset($defaults[0]))
		{
			$smin = intval($defaults[0]);
		}
		
		if(isset($defaults[1]))
		{
			$smax = intval($defaults[1]);
		}
		
		$startval = $min;
		$endval = $max;
		$diff = $endval - $startval;
		$istep = ceil($diff/$step);
		
		
		for($i=0; $i<($istep); $i++)
		{
			$radio_value = $startval + ($i * $step);
			$radio_top_value = ($radio_value + $step - 1);
			
			if($radio_top_value>$endval)
			{
				$radio_top_value = $endval;
			}

			$input_id = SF_INPUT_ID_PRE.sanitize_html_class($name."_".$radio_value);

			$radio_label = $value_prefix.$radio_value.$value_postfix." - ".$value_prefix.$radio_top_value.$value_postfix;
			$returnvar .= '<li><input class="'.$input_class.'" id="'.$input_id.'" type="checkbox" name="'.SF_FPRE.'meta_'.$field.'[]" value="'.esc_attr($radio_value).'"><label for="'.$input_id.'">'.esc_html($radio_label).'</label></li>';
		}
		
		
		$returnvar .= '</ul>';
		
		return $returnvar;
	}
	
	public function is_meta_value($key)
	{
		if(substr( $key, 0, 5 )===SF_META_PRE)
		{
			return true;
		}
		return false;
	}
	
	public function is_taxonomy_key($key)
	{
		if(substr( $key, 0, 5 )===SF_TAX_PRE)
		{
			return true;
		}
		return false;
	}
}

if ( ! class_exists( 'Search_Filter_Taxonomy_Walker' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'class-search-filter-taxonomy-walker.php' );
}

if ( ! class_exists( 'Search_Filter_Author_Walker' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'class-search-filter-author-walker.php' );
}

