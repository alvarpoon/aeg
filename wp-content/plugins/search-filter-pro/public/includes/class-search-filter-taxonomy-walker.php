<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Taxonomy_Walker
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2015 Designs & Code
 */
 
class Search_Filter_Taxonomy_Walker extends Walker_Category {

	
	private $type = '';
	private $auto_count = 0;
	private $defaults = array();
	private $multidepth = 0; //manually calculate depth on multiselects
	private $multilastid = 0; //manually calculate depth on multiselects
	private $multilastdepthchange = 0; //manually calculate depth on multiselects
	private $elementno = 0; //internal counter of which element we are on

	function __construct($type = 'checkbox', $defaults = array())  {
		// fetch the list of term ids for the given post
		//$this->term_ids = wp_get_post_terms( $post_id, $taxonomy, 'fields=ids' );
		$this->type = $type;
	}
	
	function init($type = 'checkbox', $defaults = array())
	{
		$this->type = $type;
		
	}

	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		/*$display = false;
		
		$id = $element->term_id;

		$display = true;
		if ( isset( $children_elements[ $id ] ) ) {
			// the current term has children
			foreach ( $children_elements[ $id ] as $child ) {
				if ( in_array( $child->term_id, $this->term_ids ) ) {
					// one of the term's children is in the list
					$display = true;
					// can stop searching now
					break;
				}
			}
		}

		if ( $display )*/
			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
	
	
	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 )
	{
		global $searchandfilter;
		
		extract($args);
		
		$this->auto_count = $searchandfilter->get($sfid)->settings("enable_auto_count");
		$this->auto_count_deselect_emtpy = $searchandfilter->get($sfid)->settings("auto_count_deselect_emtpy");
		
		$input_class = SF_CLASS_PRE.'input-'.$this->type;

		if($this->type=="list")
		{
			$cat_name = esc_attr( $category->name );
			$cat_name = apply_filters( 'list_cats', $cat_name, $category );
			$link = '<a href="' . esc_url( get_term_link($category) ) . '" ';
			if ( $use_desc_for_title == 0 || empty($category->description) )
				$link .= 'title="' . esc_attr( sprintf(__( 'View all posts filed under %s' ), $cat_name) ) . '"';
			else
				$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
			$link .= '>';
			$link .= $cat_name . '</a>';

			if ( !empty($feed_image) || !empty($feed) ) {
				$link .= ' ';

				if ( empty($feed_image) )
					$link .= '(';

				$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $feed_type ) ) . '"';

				if ( empty($feed) ) {
					$alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
				} else {
					$title = ' title="' . $feed . '"';
					$alt = ' alt="' . $feed . '"';
					$name = $feed;
					$link .= $title;
				}

				$link .= '>';

				if ( empty($feed_image) )
					$link .= $name;
				else
					$link .= "<img src='$feed_image'$alt$title" . ' />';

				$link .= '</a>';

				if ( empty($feed_image) )
					$link .= ')';
			}
			
			if($this->auto_count==1)
			{
				
				$term_count = $searchandfilter->get($sfid)->get_count_var(SF_TAX_PRE.$category->taxonomy, $category->slug);
			}
			else
			{
				$term_count = intval($category->count);
			}
			
			if((intval($hide_empty)!=1)||($term_count!=0))
			{
				if ( !empty($show_count) )
					$link .= ' <span class="'.SF_CLASS_PRE.'count">(' . number_format_i18n($term_count) . ')</span>';

				if ( 'list' == $args['style'] ) {
					$output .= "\t<li";
					$class = SF_ITEM_CLASS_PRE . $category->term_id;
					if ( !empty($current_category) ) {
						$_current_category = get_term( $current_category, $category->taxonomy );
						if ( $category->term_id == $current_category )
							$class .=  ' current-cat';
						elseif ( $category->term_id == $_current_category->parent )
							$class .=  ' current-cat-parent';
					}
					$output .=  ' class="' . $class . '"';
					$output .= ">$link\n";
				} else {
					$output .= "\t$link<br />\n";
				}
			}
		}
		else if(($this->type=="checkbox")||($this->type=="radio"))
		{
			if($this->type=="radio")
			{
				if($this->elementno==0)
				{//we are on the first element, so insert a default element first
					if(isset($show_option_all_sf))
					{
						$checked = "";
						
						if(isset($defaults))
						{
							if(is_array($defaults))
							{
								
								if(count($defaults)==1)
								{
									if(($defaults[0]=="")||($defaults[0]=="0"))
									{
										$checked = ' checked="checked"';
									}
								}
							}
							else
							{
								$checked = ' checked="checked"';
							}
						}

						$input_id = SF_INPUT_ID_PRE.sanitize_html_class($sf_name."_0_all_items");
						
						$output .= "<li class='".SF_ITEM_CLASS_PRE."0'><input type='".$this->type."' name='".$sf_name."[]' value='0'".$checked." class='".$input_class."' id='".$input_id."' /><label for='".$input_id."'>".$show_option_all_sf."</label></li>";
					}
				}
			}

			$cat_name = esc_attr( $category->name );
			$cat_id = esc_attr( $category->term_id );
			$cat_name = apply_filters( 'list_cats', $cat_name, $category );
			$cat_slug =  esc_attr( $category->slug );
			
			//check a default has been set
			$checked = "";
			
			
			if(isset($defaults))
			{
				$noselected = count($defaults);

				if(($noselected>0)&&(is_array($defaults)))
				{
					if(in_array($cat_id, $defaults))
					{
						$checked = ' checked="checked"';
					}
				}
			}
			
				
				$hidden = "";
				$disabled = "";
				
				if($this->auto_count==1)
				{
				//	$term_count = $searchandfilter->get_count_var("taxonomy", $category->taxonomy, $category->term_id);
					
					$term_count = $searchandfilter->get($sfid)->get_count_var(SF_TAX_PRE.$category->taxonomy, $category->slug);
				}
				else
				{
					$term_count = intval($category->count);
				}
				
				
				if($term_count==0)
				{
					//$disabled = ' disabled="disabled"';
					
					if($this->auto_count_deselect_emtpy==1)
					{
						$checked = '';
					}
					
					if($hide_empty==true)
					{
						$hidden = ' hide';
					}
					else
					{
						//$hidden = ' disabled';
					}
				
				}
				
				
				if((intval($hide_empty)!=1)||($term_count!=0))
				{
					$link  = "";
					
					$input_id = SF_INPUT_ID_PRE.sanitize_html_class($sf_name."_".$cat_slug);
					
					//intval($hide_empty)

					$link .= "<input type='".$this->type."' name='".$sf_name."[]' value='".$cat_slug."' class='".$input_class."'".$checked.$disabled." id='".$input_id."' /><label for='".$input_id."'>".$cat_name;
				

					
					if ( !empty($show_count) )
					{
						$link .= ' <span class="'.SF_CLASS_PRE.'count">(' . number_format_i18n($term_count) . ')</span>';
					}
						
				
					$link .= "</label>";
				
					
					if ( 'list' == $args['style'] ) {
						
						$output .= "\t<li";
						$class = SF_ITEM_CLASS_PRE . $category->term_id.$hidden;
						if ( !empty($current_category) ) {
							$_current_category = get_term( $current_category, $category->taxonomy );
							if ( $category->term_id == $current_category )
								$class .=  ' current-cat';
							elseif ( $category->term_id == $_current_category->parent )
								$class .=  ' current-cat-parent';
						}
						$output .=  ' class="' . $class . '"';
						$output .= ">$link\n";
					
					} else {
						$output .= "\t$link<br />\n";
					}
				}
			
		}
		else if(($this->type=="multiselect")||($this->type=="select"))
		{

			if($this->type=="select")
			{
				if($this->elementno==0)
				{//we are on the first element, so insert a default element first
					if(isset($show_option_all_sf))
					{
						$output .= "<option class='level-0 ".SF_ITEM_CLASS_PRE."0' value='0'> ".$show_option_all_sf."</option>";
					}
				}
			}
			
			$cat_name = esc_attr( $category->name );
			$cat_id = esc_attr( $category->term_id );
			$cat_slug = esc_attr( $category->slug );
			$cat_name = apply_filters( 'list_cats', $cat_name, $category );
			
			//check a default has been set
			$selected = "";
			if($defaults)
			{
				$noselected = count($defaults);

				if(($noselected>0)&&(is_array($defaults)))
				{
					if(in_array($cat_id, $defaults))
					{
						$selected = ' selected="selected"';
					}
				}
			}
			
			/* Custom  depth calculations! :/ */
			if($category->parent == 0)
			{//then this has no parent so reset depth
				$this->multidepth = 0;
			}
			else if($category->parent == $this->multilastid)
			{
				$this->multidepth++;
				$this->multilastdepthchange = $this->multilastid;
			}
			else if($category->parent == $this->multilastdepthchange)
			{//then this is also a child with the same parent so don't change depth
				
			}
			else
			{//then this has a different parent so must be lower depth
				if($this->multidepth>0)
				{
					$this->multidepth--;
				}
			}
			
			
			$hidden = "";
			$disabled = "";
			if($this->auto_count==1)
			{
				$term_count = $searchandfilter->get($sfid)->get_count_var(SF_TAX_PRE.$category->taxonomy, $category->slug);
			}
			else
			{
				$term_count = intval($category->count);
			}
			
			if($term_count==0)
			{
				$disabled = '';
				//$disabled = ' disabled="disabled"';
				//$selected = '';
				
				if($this->type=="select")
				{
					if($hide_empty==true)
					{
						$hidden = ' hide"';
					}
					else
					{
						//$hidden = ' disabled"';
					}
				}
			}
			
			if((intval($hide_empty)!=1)||($term_count!=0))
			{
				$pad = str_repeat('&nbsp;', $this->multidepth * 3);
				$link = "<option class=\"level-".$this->multidepth." ".SF_ITEM_CLASS_PRE.$cat_id.$hidden."\" value='".$cat_slug."'".$selected.$disabled." data-sf-cr='".SF_TAX_PRE.$cat_id."' data-sf-hide-empty='".intval($hide_empty)."'>".$pad.$cat_name;

				if ( !empty($show_count) )
				{
					$link .= '&nbsp;&nbsp;(' . number_format_i18n($term_count) . ')';
					//$link .= '&nbsp;&nbsp;(' . intval($category->count) . ')';
				}
					
				
				$link .= "</option>";
				$output .= "\t$link\n";
				
			}
			$this->multilastid = $cat_id;
		}
		
		$this->elementno++;
	}
	
	function end_el( &$output, $page, $depth = 0, $args = array() )
	{
		if($this->type=="list")
		{
			if ( 'list' != $args['style'] )
				return;

			$output .= "</li>\n";
		}
		else if(($this->type=="checkbox")||($this->type=="radio"))
		{
			if ( 'list' != $args['style'] )
				return;

			$output .= "</li>\n";
		}
			
	}
	
	function start_lvl( &$output, $depth = 0, $args = array() )
	{
		if($this->type=="list")
		{
			if ( 'list' != $args['style'] )
				return;

			$indent = str_repeat("\t", $depth);
			$output .= "$indent<ul class='children'>\n";
		}
		else if(($this->type=="checkbox")||($this->type=="radio"))
		{
			if ( 'list' != $args['style'] )
				return;

			$indent = str_repeat("\t", $depth);
			$output .= "$indent<ul class='children'>\n";
		}
		else if($this->type=="multiselect")
		{
			/*if ( 'list' != $args['style'] )
				return;

			$indent = str_repeat("\t", $depth);
			$output .= "$indent<ul class='children'>\n";*/
		}
	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		if($this->type=="list")
		{
			if ( 'list' != $args['style'] )
				return;

			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul>\n";
		}
		else if(($this->type=="checkbox")||($this->type=="radio"))
		{
			if ( 'list' != $args['style'] )
				return;

			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul>\n";
		}
		else if($this->type=="multiselect")
		{
			
		}
	}
}

?>