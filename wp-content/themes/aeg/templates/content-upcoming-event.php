<?

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args= array(
		'post_type'			=> 'event',
		'post_status' 		=> 'publish',
		'orderby'			=> 'date',
		'order' 			=> 'DESC',
		'numberposts' 		=> -1,
		'posts_per_page' 	=> 16,
		'paged' 			=> $paged,
	);
	$results = new WP_Query( $args );

?>
<div class="container">
	<div class="row">
    	<? $feature_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
    	<img src="<?=$feature_image[0];?>" alt="" />
    </div>
</div>
<div class="container">
	<div class="row">
        <div class="clearfix">
            <div class="lecture_nav_container clearfix">
                <div class="lecture_nav_item active">
                    <a href="#">Up-Coming Events</a>
                </div>
                <div class="lecture_nav_item">
                    <a href="#">Past Event<span>24</span></a>
                </div>
            </div>
        </div>
        <div class="table-responsive lecture-table">
        	<table class="table">
            	<col width="60%">
                <col width="20%">
                <col width="20%">
            	<tr class="header">
                	<td>EVENT TITLE</td>
                    <td>VENUE</td>
                    <td>DATE</td>
                </tr>
                <? while ( $results->have_posts() ) : $results->the_post(); 
					$pdf = get_field("pdf", $post->ID);
				?>
                <tr>
                	<td><a href="#lecture_content" class="btn_lecture_detail various" data-code="<?=$post->ID;?>"><? the_title(); ?></a></td>
                    <td><a href="#author_content" class="btn_author_detail various" data-code="<?=$author_id;?>"><?=the_author_meta( 'user_nicename' , $author_id ); ?></a></td>
                    <td><?php echo get_the_time('F j, Y', $post->ID); ?><br /><?=get_the_time('g:ia', $post->ID);?></td>
                </tr>
                <? endwhile; ?>				
            </table>
        </div>        
        <?php
		  if (function_exists(custom_pagination)) {
			custom_pagination($results->max_num_pages,"",$paged);
		  }
		?>
	</div>
</div>

<div id="lecture_content"></div>
<div id="author_content"></div>