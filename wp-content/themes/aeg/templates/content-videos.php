<?

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args= array(
		'post_type'			=> 'video',
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
        <div class="clearfix">
            <div class="lecture_nav_container clearfix">
                <div class="lecture_nav_item">
                    <a href="/education/lecture/">LECTURE<span>24</span></a>
                </div>
                <div class="lecture_nav_item active">
                    <a href="#">VIDEO</a>
                </div>
                <div class="lecture_nav_item">
                    <a href="/education/image/">IMAGES<span>24</span></a>
                </div>
            </div>
            <a href="<?=site_url().'/education/video-upload/';?>" class="btn_yellow">UPLOAD</a>
        </div>
        <div class="clearfix lecture-search-container">
            <div class="col-sm-5 noPadding">
                <select>
                    <option>Sort by Title (Ascending)</option>
                </select>
            </div>
            <div class="col-sm-7 noPadding">
                <!--Search bar-->
            </div>
        </div>
        <div class="table-responsive lecture-table">
        	<table class="table">
            	<col width="50%">
                <col width="15%">
                <col width="15%">
                <col width="20%">
            	<tr class="header">
                	<td>VIDEO TITLE</td>
                    <td>SPEAKER</td>
                    <td>DATE</td>
                    <td>&nbsp;</td>
                </tr>
                <? while ( $results->have_posts() ) : $results->the_post(); 
					$author_id=$post->post_author;
                    $id=get_the_ID();
					/*$media_file = get_field("file", $id);*/

				?>
                <tr>
                	<td><a href="#lecture_content" class="btn_lecture_detail various" data-code="<?=$post->ID;?>"><? the_title(); ?></a></td>
                    <td><a href="#author_content" class="btn_author_detail various" data-code="<?=$author_id;?>"><?=the_author_meta( 'display_name' , $author_id ); ?></a></td>
                    <td><?php echo get_the_time('F j, Y', $post->ID); ?><br /><?=get_the_time('g:ia', $id);?></td>
                    <td align="center">
                    <?  if(is_user_logged_in()){?>
	                    <a href="<?=usp_get_meta(false, 'usp-file-single')?>" target="_blank"><span class="logo_video enable"></span></a>
                    <? }else{ ?>
                        <span class="logo_video enable"></span>
                    <? } ?>
                    </td>
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