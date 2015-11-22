<?	
	if( isset($_GET['sorting']) ){
		$sort_order = $_GET['sorting'];
	}else{
		$sort_order = '';
	}
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args= array(
		'post_type'			=> 'member',
		'post_status' 		=> 'publish',
		'orderby'			=> 'menu_order',
		'order' 			=> 'ASC',
		'numberposts' 		=> -1,
		'posts_per_page' 	=> 30,
		'paged' 			=> $paged,
	);
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
            <select>
            	<option value="title_asc">Sort by Title (Ascending)</option>
                <option value="country_asc">Sort by Country (Ascending)</option>
            </select>            
        </div>
        <div class="members-container clearfix">
        <?php while ( $results->have_posts() ) : $results->the_post(); 
				$member_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );				
				$member_countries = wp_get_object_terms( $post->ID, 'country');
		?>
            	<div class="col-sm-4 committee-item">
                    <img src="<?=$member_image[0]?>" alt="" class="img-responsive" />
                    <div class="committee-content">
                        <p class="title"><?=$post->post_title; ?></p>
						<p class="position"><?=$member_countries[0]->name;?></p>
                    </div>
                </div>
		<?php endwhile; ?>        
        </div>
        <?php
		  if (function_exists(custom_pagination)) {
			custom_pagination($results->max_num_pages,"",$paged);
		  }
		?>
    </div>
</div>