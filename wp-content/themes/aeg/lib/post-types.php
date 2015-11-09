<?php

add_post_type_support('page', 'excerpt');

// mainpaage banner
add_action('init', 'mainpage_banner_register');
function mainpage_banner_register() {
  $labels = array(
      'name' => _x('Mainpage banner', 'post type general name'),
      'singular_name' => _x('Mainpage banner', 'post type singular name'),
      'add_new' => _x('Add mainpage banner', 'rep'),
      'add_new_item' => __('Add mainpage banner'),
      'edit_item' => __('Edit mainpage banner'),
      'new_item' => __('New mainpage banner'),
      'view_item' => __('View mainpage banner'),
      'search_items' => __('Search mainpage banner'),
      'not_found' =>  __('Nothing found'),
      'not_found_in_trash' => __('Nothing found in Trash'),
      'parent_item_colon' => ''
  );
  $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => true,
      'menu_position' => 4,
      'supports'      => array( 'title', 'thumbnail'),
  );
  register_post_type( 'mainpage_banner' , $args );
}

//   Committee
add_action('init', 'committee_register');
function committee_register() {
  $labels = array(
      'name' => _x('Committee', 'post type general name'),
      'singular_name' => _x('Committee', 'post type singular name'),
      'add_new' => _x('Add Committee', 'rep'),
      'add_new_item' => __('Add Committee'),
      'edit_item' => __('Edit Committee'),
      'new_item' => __('New Committee'),
      'view_item' => __('View Committee'),
      'search_items' => __('Search Committee'),
      'not_found' =>  __('Nothing found'),
      'not_found_in_trash' => __('Nothing found in Trash'),
      'parent_item_colon' => ''
  );
  $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => true,
      'menu_position' => 5,
      'supports'      => array('title', 'thumbnail'),
  );
  register_post_type( 'committee' , $args );
}

//   Member
add_action('init', 'member_register');
function member_register() {
  $labels = array(
      'name' => _x('Member', 'post type general name'),
      'singular_name' => _x('Member', 'post type singular name'),
      'add_new' => _x('Add Member', 'rep'),
      'add_new_item' => __('Add Member'),
      'edit_item' => __('Edit Member'),
      'new_item' => __('New Member'),
      'view_item' => __('View Member'),
      'search_items' => __('Search Member'),
      'not_found' =>  __('Nothing found'),
      'not_found_in_trash' => __('Nothing found in Trash'),
      'parent_item_colon' => ''
  );
  $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => true,
      'menu_position' => 6,
      'supports'      => array('title', 'editor', 'thumbnail'),
  );
  register_post_type( 'member' , $args );
}

//   Lecture
add_action('init', 'lecture_register');
function lecture_register() {
  $labels = array(
      'name' => _x('Lecture', 'post type general name'),
      'singular_name' => _x('Lecture', 'post type singular name'),
      'add_new' => _x('Add Lecture', 'rep'),
      'add_new_item' => __('Add Lecture'),
      'edit_item' => __('Edit Lecture'),
      'new_item' => __('New Lecture'),
      'view_item' => __('View Lecture'),
      'search_items' => __('Search Lecture'),
      'not_found' =>  __('Nothing found'),
      'not_found_in_trash' => __('Nothing found in Trash'),
      'parent_item_colon' => ''
  );
  $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => true,
      'menu_position' => 7,
      'supports'      => array('title', 'editor'),
  );
  register_post_type( 'lecture' , $args );
}

//    Video
add_action('init', 'video_register');
function video_register() {
  $labels = array(
      'name' => _x('Video', 'post type general name'),
      'singular_name' => _x('Video', 'post type singular name'),
      'add_new' => _x('Add Video', 'rep'),
      'add_new_item' => __('Add Video'),
      'edit_item' => __('Edit Video'),
      'new_item' => __('New Video'),
      'view_item' => __('View Video'),
      'search_items' => __('Search Video'),
      'not_found' =>  __('Nothing found'),
      'not_found_in_trash' => __('Nothing found in Trash'),
      'parent_item_colon' => ''
  );
  $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => true,
      'menu_position' => 8,
      'supports'      => array('title', 'editor'),
  );
  register_post_type( 'video' , $args );
}

//    Image
add_action('init', 'image_register');
function image_register() {
  $labels = array(
      'name' => _x('Image', 'post type general name'),
      'singular_name' => _x('Image', 'post type singular name'),
      'add_new' => _x('Add Image', 'rep'),
      'add_new_item' => __('Add Image'),
      'edit_item' => __('Edit Image'),
      'new_item' => __('New Image'),
      'view_item' => __('View Image'),
      'search_items' => __('Search Image'),
      'not_found' =>  __('Nothing found'),
      'not_found_in_trash' => __('Nothing found in Trash'),
      'parent_item_colon' => ''
  );
  $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => true,
      'menu_position' => 9,
      'supports'      => array('title', 'thumbnail'),
  );
  register_post_type( 'image' , $args );
}

//    Research
add_action('init', 'research_register');
function research_register() {
  $labels = array(
      'name' => _x('Research', 'post type general name'),
      'singular_name' => _x('Research', 'post type singular name'),
      'add_new' => _x('Add Research', 'rep'),
      'add_new_item' => __('Add Research'),
      'edit_item' => __('Edit Research'),
      'new_item' => __('New Research'),
      'view_item' => __('View Research'),
      'search_items' => __('Search Research'),
      'not_found' =>  __('Nothing found'),
      'not_found_in_trash' => __('Nothing found in Trash'),
      'parent_item_colon' => ''
  );
  $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => true,
      'menu_position' => 10,
      'supports'      => array('title', 'editor'),
  );
  register_post_type( 'research' , $args );
}

//    Event
add_action('init', 'event_register');
function event_register() {
  $labels = array(
      'name' => _x('Event', 'post type general name'),
      'singular_name' => _x('Event', 'post type singular name'),
      'add_new' => _x('Add Event', 'rep'),
      'add_new_item' => __('Add Event'),
      'edit_item' => __('Edit Event'),
      'new_item' => __('New Event'),
      'view_item' => __('View Event'),
      'search_items' => __('Search Event'),
      'not_found' =>  __('Nothing found'),
      'not_found_in_trash' => __('Nothing found in Trash'),
      'parent_item_colon' => ''
  );
  $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => true,
      'menu_position' => 11,
      'supports'      => array('title', 'editor', 'thumbnail', 'excerpt','revisions'),
  );
  register_post_type( 'Event' , $args );
}
?>
