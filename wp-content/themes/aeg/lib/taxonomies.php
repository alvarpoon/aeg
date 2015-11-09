<?php

add_action( 'init', 'create_committee_taxonomies', 0 );
function create_committee_taxonomies() {
  register_taxonomy(
      'type',
      'committee',
      array(
          'labels' => array(
              'name' => 'Type',
              'add_new_item' => 'Add Type',
              'new_item_name' => 'New Type'
          ),
          'show_ui' => true,
          'show_tagcloud' => false,
          'hierarchical' => true
      )
  );
}

add_action( 'init', 'create_research_taxonomies', 0 );
function create_research_taxonomies() {
  register_taxonomy(
      'status',
      'research',
      array(
          'labels' => array(
              'name' => 'Status',
              'add_new_item' => 'Add Status',
              'new_item_name' => 'New Status'
          ),
          'show_ui' => true,
          'show_tagcloud' => false,
          'hierarchical' => true
      )
  );
}

add_action( 'init', 'create_member_taxonomies', 0 );
function create_member_taxonomies() {
  register_taxonomy(
      'country',
      'member',
      array(
          'labels' => array(
              'name' => 'Country',
              'add_new_item' => 'Add Country',
              'new_item_name' => 'New Country'
          ),
          'show_ui' => true,
          'show_tagcloud' => false,
          'hierarchical' => true
      )
  );
}

add_action( 'init', 'create_video_taxonomies', 0 );
function create_video_taxonomies() {
  register_taxonomy(
      'topic',
      'video',
      array(
          'labels' => array(
              'name' => 'Topic',
              'add_new_item' => 'Add Topic',
              'new_item_name' => 'New Topic'
          ),
          'show_ui' => true,
          'show_tagcloud' => false,
          'hierarchical' => true
      )
  );
}

add_action( 'init', 'create_image_taxonomies', 0 );
function create_image_taxonomies() {
  register_taxonomy(
      'topic',
      'image',
      array(
          'labels' => array(
              'name' => 'Topic',
              'add_new_item' => 'Add Topic',
              'new_item_name' => 'New Topic'
          ),
          'show_ui' => true,
          'show_tagcloud' => false,
          'hierarchical' => true
      )
  );
}

add_action( 'init', 'create_research_taxonomies_topic', 0 );
function create_research_taxonomies_topic() {
  register_taxonomy(
      'topic',
      'research',
      array(
          'labels' => array(
              'name' => 'Topic',
              'add_new_item' => 'Add Topic',
              'new_item_name' => 'New Topic'
          ),
          'show_ui' => true,
          'show_tagcloud' => false,
          'hierarchical' => true
      )
  );
}

add_action( 'init', 'create_research_taxonomies_category', 0 );
function create_research_taxonomies_category() {
  register_taxonomy(
      'category',
      'research',
      array(
          'labels' => array(
              'name' => 'Category',
              'add_new_item' => 'Add Category',
              'new_item_name' => 'New Category'
          ),
          'show_ui' => true,
          'show_tagcloud' => false,
          'hierarchical' => true
      )
  );
}

add_action( 'init', 'create_event_taxonomies', 0 );
function create_event_taxonomies() {
  register_taxonomy(
      'category',
      'event',
      array(
          'labels' => array(
              'name' => 'Category',
              'add_new_item' => 'Add Category',
              'new_item_name' => 'New Category'
          ),
          'show_ui' => true,
          'show_tagcloud' => false,
          'hierarchical' => true
      )
  );
}
// in case the templates pop out
// global $wp_taxonomies;
// $taxonomy = 'year';
// unset( $wp_taxonomies[$taxonomy]);
// flush_rewrite_rules();
