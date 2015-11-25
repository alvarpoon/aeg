<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Third_Party
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2015 Designs & Code
 */
 
class Search_Filter_Third_Party
{
	private $plugin_slug = '';
	private $form_data = '';
	private $count_table;
	private $cache;
	private $query;
	private $sfid = 0;
	
	function __construct($plugin_slug)
	{
		$this->plugin_slug = $plugin_slug;
		
		// -- woocommerce
		
		add_filter('sf_edit_query_args', array($this, 'sf_woocommerce_query_args'), 11, 2); //modify S&F results URL
		add_filter('sf_admin_filter_settings_save', array($this, 'sf_woocommerce_filter_settings_save'), 11, 2); //modify S&F results URL
		
		// -- EDD
		//add_action( 'marketify_entry_before', array($this, 'marketify_entry_before_hook') );
		//add_filter('edd_downloads_query', array($this, 'edd_prep_downloads_sf_query'), 10, 2);
		//$searchform->query()->prep_query();
		
		// -- polylang
		
		//add_action('init', array($this, 'dump_stuff'));
		
		add_filter('pll_get_post_types', array($this, 'my_pll_get_post_types'));
		add_filter('sf_archive_results_url', array($this, 'sf_archive_results_url'), 10, 3); //modify S&F results URL
		//add_filter('sf_pre_get_posts_admin_cache', array($this, 'sf_pre_get_posts_admin_cache'), 10, 3); //modify S&F results URL
		add_filter('sf_edit_cache_query_args', array($this, 'sf_edit_cache_query_args'), 10, 3); //modify S&F results URL
		
		
		// -- relevanssi
		add_filter( 'sf_edit_query_args_after_custom_filter', array( $this, 'relevanssi_filter_query_args' ), 12, 2);
		add_filter( 'sf_apply_custom_filter', array( $this, 'relevanssi_add_custom_filter' ), 10, 3);
		
		$this->init();
	}
	
	public function init()
	{
		
	}
	
	/* WooCommerce integration */
	
	
	public function sf_woocommerce_query_args($query_args,  $sfid)
	{
		global $searchandfilter;
		$sf_inst = $searchandfilter->get($sfid);
		
		//make sure this search form is tyring to use woocommerce
		if($sf_inst->settings("display_results_as")=="custom_woocommerce_store")
		{
			$del_val = "product_variation"; //always remove product variations from main query
			
			if(isset($query_args['post_type']))
			{
				if(is_array($query_args['post_type']))
				{
					if(($key = array_search($del_val, $query_args['post_type'])) !== false) {
						unset($query_args['post_type'][$key]);
					}
				}
			}
			
			return $query_args;
		}
		
		return $query_args;		
	}
	
	public function sf_woocommerce_filter_settings_save($settings,  $sfid)
	{				
		//make sure this search form is tyring to use woocommerce
		if(isset($settings['display_results_as']))
		{
			if($settings["display_results_as"]=="custom_woocommerce_store")
			{	
				$settings['treat_child_posts_as_parent'] = 1;
			}
			else
			{
				$settings['treat_child_posts_as_parent'] = 0;
			}
		}
				
		return $settings;
	}
	
	/* EDD integration */
	
	public function edd_prep_downloads_sf_query($query, $atts) {
		
		return $query;
	}
	
	
	/* pollylang integration */
	
	public function dump_stuff($stuff) {
		global $polylang;
		
		/*if(empty($polylang))
		{
			return;
		}
		else
		{
			
		}
		echo $polylang;
		exit;*/

		/*$langs = array();
		
		foreach ($polylang->model->get_languages_list() as $term)
		{
			array_push($langs, $term->slug);
		}
		
		var_dump($langs);exit;*/
	}
	
	public function my_pll_get_post_types($types) {
		return array_merge($types, array('search-filter-widget' => 'search-filter-widget'));
	}
	
	public function sf_edit_cache_query_args($query_args,  $sfid) {
		
		global $polylang;
		
		if(empty($polylang))
		{
			return $query_args;
		}
		
		$langs = array();
		
		foreach ($polylang->model->get_languages_list() as $term)
		{
			array_push($langs, $term->slug);
		}
		
		$query_args["lang"] = implode(",",$langs);
		
		return $query_args;
	}
	/*
	public function sf_pre_get_posts_admin_cache($query,  $sfid) {
		
		$query->set("lang", "all");
		
		return $query;
	}
	*/
	public function sf_archive_results_url($results_url,  $sfid, $page_slug) {
		
		/*$results_url = add_query_arg(array('sfid' => $sfid), pll_home_url());
		
		if(get_option('permalink_structure'))
		{
			if($page_slug!="")
			{
				$results_url = trailingslashit(pll_home_url()).$page_slug."/";
			}
		}*/
		
		return $results_url;
	}
	
	
	
	
	/* Relevanssi integration */
	
	public function remove_relevanssi_defaults()
	{
		remove_filter('the_posts', 'relevanssi_query');
		remove_filter('posts_request', 'relevanssi_prevent_default_request', 9);
		remove_filter('posts_request', 'relevanssi_prevent_default_request');
		remove_filter('query_vars', 'relevanssi_query_vars');
	}
	
	public function relevanssi_filter_query_args($query_args, $sfid) {
		
		//always remove normal relevanssi behaviour
		$this->remove_relevanssi_defaults();
		
		global $searchandfilter;
		$sf_inst = $searchandfilter->get($sfid);
		
		if($sf_inst->settings("use_relevanssi")==1)
		{//ensure it is enabled in the admin
			
			if(isset($query_args['s']))
			{//only run if a search term has actually been set
				if(trim($query_args['s'])!="")
				{
					
					$search_term = $query_args['s'];
					$query_args['s'] = "";
				}
			}
		}
		
		return $query_args;
	}
	
	public function relevanssi_add_custom_filter($ids_array, $query_args, $sfid) {
		
		global $searchandfilter;
		$sf_inst = $searchandfilter->get($sfid);
		
		$this->remove_relevanssi_defaults();
		
		if($sf_inst->settings("use_relevanssi")==1)
		{//ensure it is enabled in the admin
			
			if(isset($query_args['s']))
			{//only run if a search term has actually been set
				
				if(trim($query_args['s'])!="")
				{
					//$search_term = $query_args['s'];
					
					if (function_exists('relevanssi_do_query'))
					{
						$expand_args = array(
						   'posts_per_page' 			=> -1,
						   'paged' 						=> 1,
						   'fields' 					=> "ids", //relevanssi only implemented support for this in 3.5 - before this, it would return the whole post object
						   
						   'orderby' 					=> "", //remove sorting
						   'meta_key' 					=> "",
						   'order' 						=> "",
						   
						   /* speed improvements */
						   'no_found_rows' 				=> true,
						   'update_post_meta_cache' 	=> false,
						   'update_post_term_cache' 	=> false
						   
						);
						
						$query_args = array_merge($query_args, $expand_args);
											
						// The Query
						$query_arr = new WP_Query( $query_args );
						relevanssi_do_query($query_arr);
						
						$ids_array = array();
						if ( $query_arr->have_posts() ){
							
							foreach($query_arr->posts as $post)
							{
								$postID = 0;
								
								if(is_numeric($post))
								{
									$postID = $post;
								}
								else if(is_object($post))
								{
									if(isset($post->ID))
									{
										$postID = $post->ID;
									}
								}
								
								if($postID!=0)
								{
									array_push($ids_array, $postID);
								}
							}
						}
						
						return $ids_array;
					}
				}
			}
		}
		
		return array(false); //this tells S&F to ignore this custom filter
	}
	
	
}

?>