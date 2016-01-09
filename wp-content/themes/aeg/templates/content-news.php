<?php 
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$args = array(
		'post_type' 		=> 'post',
		'post_status' 		=> 'publish',
		'posts_per_page' 	=> 10,
		'paged' 			=> $paged,
	);

	query_posts($args);
	
	global $wp_query;
    $page_links_total =  $wp_query->max_num_pages;
?>

<div class="container">
	<div class="row">
    	<div class="page-banner-container">
        	<?php if ( !has_post_thumbnail() ) { ?>
	        	<img src="<?=get_stylesheet_directory_uri()?>/assets/img/banner_contact.png" alt="" />
            <? } ?>
            <h1 <?=(has_post_thumbnail()?"":"class='no-banner'");?>><?=the_title();?></h1>
        </div>
    </div>
</div>

<div class="latest-new-container clearfix">
	<?php if (!have_posts()) : ?>
    <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'roots'); ?>
    </div>
    <?php get_search_form(); ?>
    <?php endif; ?>
    
    <?php while (have_posts()) : the_post(); ?>
    <div class="latest-new-item container">
        <h2><?php the_title(); ?></h2> 
        <?php the_content(); ?>
    </div>
    <?php endwhile; ?>

	<?php
	if (function_exists(custom_pagination)) {
		custom_pagination($page_links_total,"",$paged);
	}
    ?>
</div>