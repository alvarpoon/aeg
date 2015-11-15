<?	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args= array(
		'post_type'			=> 'member',
		'post_status' 		=> 'publish',
		'orderby'			=> 'id',
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
        <?php while (have_posts()) : the_post(); 
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
        <div class="pagination_bar_container">
        	<div class="pagination_bar clearfix">
				<?php pagination_bar(); ?>
            </div>
        </div>
    </div>
</div>