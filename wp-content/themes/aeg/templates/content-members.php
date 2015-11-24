<?	
	if( isset($_GET['sorting']) ){
		$sort_order = $_GET['sorting'];
	}else{
		$sort_order = '';
	}
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	switch ($sort_order) {
		case "title_asc":
			$args= array(
				'post_type'			=> 'member',
				'post_status' 		=> 'publish',
				'orderby'			=> 'title',
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 30,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		case "title_desc":
			$args= array(
				'post_type'			=> 'member',
				'post_status' 		=> 'publish',
				'orderby'			=> 'title',
				'order' 			=> 'DESC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 30,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		case "country_asc":
			$args= array(
				'post_type'			=> 'member',
				'post_status' 		=> 'publish',
				//'orderby'			=> 'menu_order',
				'meta_key' 			=> 'country',
				'orderby' 			=> 'meta_value', 
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 30,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		case "country_desc":
			$args= array(
				'post_type'			=> 'member',
				'post_status' 		=> 'publish',
				//'orderby'			=> 'menu_order',
				'meta_key' 			=> 'country',
				'orderby' 			=> 'meta_value', 
				'order' 			=> 'DESC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 30,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		default:
			$args= array(
				'post_type'			=> 'member',
				'post_status' 		=> 'publish',
				'orderby'			=> 'title',
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 30,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
	}
	$results = new WP_Query( $args );
?>
<div class="container">
	<div class="row">
    	<div class="page-banner-container">
	    	<img src="<?=get_stylesheet_directory_uri()?>/assets/img/committees/img_committees_banner.png" alt="" />
            <h1><?=the_title();?></h1>
        </div>
        <div class="member-search-container">
        	<div class="clearfix">
		        <?=do_shortcode('[acps id="156"]');?>
            </div>
            <select id="sorting_control">
            	<option value="title_asc">Sort by Title (Ascending)</option>
                <option value="title_desc">Sort by Title (Descending)</option>
                <option value="country_asc">Sort by Country (Ascending)</option>
                <option value="country_desc">Sort by Country (Descending)</option>
            </select>            
        </div>
        <div class="members-container clearfix">
        <?php 
			$i = 0;
			$separater = 3;
			while ( $results->have_posts() ) : $results->the_post(); 
				$member_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );				
				//$member_countries = wp_get_object_terms( $post->ID, 'country');
				$member_countries = get_field('country', $post->ID);
		?>
            	<div class="col-sm-4 committee-item">
                    <img src="<?=$member_image[0]?>" alt="" class="img-responsive" />
                    <div class="committee-content">
                        <p class="title"><?=$post->post_title; ?></p>
						<p class="position"><?=$member_countries;?></p>
                    </div>
                </div>
		<?php 
			if( ($i+1)%$separater == 0){
				echo '<div class="clearfix"></div>';
			}
			
			$i++;
			endwhile; ?>        
        </div>
        <?php
		  if (function_exists(custom_pagination)) {
			custom_pagination($results->max_num_pages,"",$paged);
		  }
		?>
    </div>
</div>

<? 
	$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
?>
<script>
	
	var full_url = 'http://<?=$_SERVER['HTTP_HOST']; ?>'+'<?=$uri_parts[0];?>';
</script>