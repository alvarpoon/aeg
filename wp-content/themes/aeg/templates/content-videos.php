<?

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$video_args= array(
		'post_type'			=> 'video',
		'post_status' 		=> 'publish',
		'orderby'			=> 'date',
		'order' 			=> 'DESC',
		'numberposts' 		=> -1,
		'posts_per_page' 	=> 16,
		'paged' 			=> $paged,
	);
	$results = new WP_Query( $video_args );
	
	$image_args= array(
		'post_type'			=> 'image',
		'post_status' 		=> 'publish',
		'orderby'			=> 'date',
		'order' 			=> 'DESC',
		'numberposts' 		=> -1,
		'posts_per_page' 	=> 16,
		'paged' 			=> $paged,
	);
	$image_results = new WP_Query( $image_args );
	
	$lecture_args= array(
		'post_type'			=> 'lecture',
		'post_status' 		=> 'publish',
		'orderby'			=> 'date',
		'order' 			=> 'DESC',
		'numberposts' 		=> -1,
		'posts_per_page' 	=> 16,
		'paged' 			=> $paged,
	);
	$lecture_results = new WP_Query( $lecture_args );

?>
<div class="container">
	<div class="row">
        <div class="clearfix">
            <div class="lecture_nav_container clearfix">
                <div class="lecture_nav_item">
                    <a href="/education/lecture/">LECTURE<span><?=$lecture_results->post_count;?></span></a>
                </div>
                <div class="lecture_nav_item active">
                    <a href="#">VIDEO</a>
                </div>
                <div class="lecture_nav_item">
                    <a href="/education/image/">IMAGES<span><?=$image_results->post_count;?></span></a>
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
                	<td>LECTURE TITLE</td>
                    <td>SPEAKER</td>
                    <td>DATE</td>
                    <td>&nbsp;</td>
                </tr>
                <? while ( $results->have_posts() ) : $results->the_post(); 
					$author_id=$post->post_author;
					$media_file = get_field("file", $post->ID);
				?>
                <tr>
                	<td><a href="#lecture_content" class="btn_lecture_detail various" data-code="<?=$post->ID;?>"><? the_title(); ?></a></td>
                    <td><a href="#author_content" class="btn_author_detail various" data-code="<?=$author_id;?>"><?=the_author_meta( 'display_name' , $author_id ); ?></a></td>
                    <td><?php echo get_the_time('F j, Y', $post->ID); ?><br /><?=get_the_time('g:ia', $post->ID);?></td>
                    <td align="center">
                        <? if(count($media_file) > 1 && $media_file['mime_type'] == 'video/mp4') {
                            $attachments = get_children( array( 'post_parent' => $post->ID ) ); 
                            $count = count( $attachments );
                        ?>
	                    	<?=$count?> <a href="<?=$media_file['url']?>" target="_blank"><span class="logo_video enable"></span></a>
                        <? }else{ ?>
                        	<span class="logo_video"></span>
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