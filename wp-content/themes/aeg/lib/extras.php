<?php
/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');

//hide the Admin Bar
//https://s2member.com/kb-article/how-do-i-hide-the-admin-bar/
if (!current_user_can('manage_options')){
   show_admin_bar(FALSE); 
}

function custom_pagination($numpages = '', $pagerange = '', $paged='') {

  if (empty($pagerange)) {
    $pagerange = 2;
  }

  global $paged;
  if (empty($paged)) {
    $paged = 1;
  }
  if ($numpages == '') {
    global $wp_query;
    $numpages = $wp_query->max_num_pages;
    if(!$numpages) {
        $numpages = 1;
    }
  }
  
  $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
  $split_parameters = explode('&', $uri_parts[1]);
  for($i = 0; $i < count($split_parameters); $i++) {
	  $final_split = explode('=', $split_parameters[$i]);
	  $split_complete[$final_split[0]] = $final_split[1];
  }
  
  $pagination_args = array(
	'base' 			  => preg_replace('/\?.*/', '', get_pagenum_link(1)) . '%_%',
    'format'          => 'page/%#%',
    'total'           => $numpages,
    'current'         => $paged,
    'show_all'        => False,
    'end_size'        => 1,
    'mid_size'        => $pagerange,
    'prev_next'       => True,
    'prev_text'       => __('&laquo;'),
    'next_text'       => __('&raquo;'),
    'type'            => 'plain',
	'add_args'		  => $split_complete,
	'add_fragment'    => '',	
  );

  $paginate_links = paginate_links($pagination_args);

  if ($paginate_links) {
    echo "<div class='pagination_bar_container'>";
		echo "<div class='pagination_bar clearfix'>";
		    echo $paginate_links;
    	echo "</div>";
	echo "</div>";
  }
}