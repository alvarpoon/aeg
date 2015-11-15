<?	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args= array(
		'post_type'			=> 'member',
		'post_status' 		=> 'publish',
		'orderby'			=> 'menu_order',
		'order' 			=> 'ASC',
		'numberposts' 		=> -1,
		'posts_per_page' 	=> 3,
		'paged' 			=> $paged,
	);
	$results = query_posts( $args );
?>
<div class="container">
	<div class="row">
    	<div class="page-banner-container">
	    	<img src="<?=get_stylesheet_directory_uri()?>/assets/img/committees/img_committees_banner.png" alt="" />
            <h1><?=the_title();?></h1>
        </div>
        <div class="members-container clearfix">
        <?
			foreach ($results as $result){ 
				$member_image = wp_get_attachment_image_src( get_post_thumbnail_id( $result->ID ), 'full' );				
				$member_countries = wp_get_object_terms( $result->ID, 'country');
		?>
            	<div class="col-sm-4 committee-item">
                    <img src="<?=$member_image[0]?>" alt="" class="img-responsive" />
                    <div class="committee-content">
                        <p class="title"><?=$result->post_title; ?></p>
						<p class="position"><?=$member_countries[0]->name;?></p>
                    </div>
                </div>
		<? } ?>        
        </div>
        <div class="pagination_bar_container">
        	<div class="pagination_bar clearfix">
				<?php pagination_bar(); ?>
            </div>
        </div>
    </div>
</div>