<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Post_Data
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2015 Designs & Code
 */
 
class Search_Filter_Global
{
	private $plugin_slug = '';
	private $form_data = '';
	private $active_sfid = 0;
	private $count_table;
	private $results_ids = array();
	private $sf_queries;
	private $post_cache;
	
	private $data;
	
	function __construct($plugin_slug)
	{
		$this->plugin_slug = $plugin_slug;
		$this->post_cache = new Search_Filter_Post_Cache();
		
		add_action('search_filter_prep_query', array($this, 'set'), 10);
		add_action('search_filter_archive_query', array($this, 'do_query'), 10);
		add_action('search_filter_setup_pagination', array($this, 'setup_pagination'), 10);
		add_action('search_filter_update_post_cache', array($this, 'update_cache'), 10);
		
		$this->data = new stdClass();
	}
	
	public function set($sfid)
	{
		//$this->active_sfid = $sfid;
		if(!isset($this->data->$sfid))
		{
			$this->data->$sfid = new Search_Filter_Config($this->plugin_slug, $sfid);
		}
	}
	
	
	public function setup_pagination($sfid)
	{
		if(!isset($this->data->$sfid))
		{
			$this->data->$sfid = new Search_Filter_Config($this->plugin_slug, $sfid);
		}
		
		$this->data->$sfid->query->setup_pagination();
	}
	
	public function do_query($sfid)
	{
		//$this->active_sfid = $sfid;
		$this->get($sfid)->query()->do_main_query();
	}
	
	public function get($sfid)
	{
		//$this->active_sfid = $sfid;
		if(!isset($this->data->$sfid))
		{
			$this->data->$sfid = new Search_Filter_Config($this->plugin_slug, $sfid);
		}
		
		return $this->data->$sfid;
	}
	
	public function set_active_sfid($sfid)
	{
		$this->active_sfid = $sfid;
	}
	public function active_sfid()
	{
		return $this->active_sfid;
	}
	
	public function is_search_form($sfid)
	{
		return $this->get($sfid)->is_valid_form();
	}
	
	public function update_cache($postID)
	{		
		$this->post_cache->update_post_cache($postID);
	}
}


?>