<?php //the_search_query(); ?>

<?
	//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
<div class="container">
	<div class="row">
        <div class="clearfix">
            <div class="lecture_nav_container clearfix">
                <div class="lecture_nav_item">
                    <a href="/education/lecture/">LECTURE</a>
                </div>
                <div class="lecture_nav_item">
                    <a href="/education/videos/">VIDEO</a>
                </div>
                <div class="lecture_nav_item active">
                    <a href="#">IMAGES</a>
                </div>
            </div>
            <a href="<?=site_url().'/education/image-upload/';?>" class="btn_yellow">UPLOAD</a>
        </div>
        <div class="clearfix lecture-search-container">
            <div class="col-sm-5 noPadding">
                <!--<select>
                    <option>Sort by Title (Ascending)</option>
                </select>-->
            </div>
            <div class="col-sm-7 noPadding align-right">
                <!--Search bar-->
				<?=do_shortcode('[searchandfilter id="1698"]');?>
            </div>
        </div>
        <div class="table-responsive lecture-table">
        	<table class="table">
            	<col width="45%">
                <col width="20%">
                <col width="15%">
                <col width="20%">
            	<tr class="header">                    
                    <td><a href="" class="title_sort">IMAGE TITLE<i class="fa fa-caret-down"></i></a></td>
                    <td><a href="" class="speaker_sort">CONTRIBUTOR<i class="fa fa-caret-down"></i></a></td>
                    <td><a href="" class="date_sort">DATE<i class="fa fa-caret-down"></i></a></td>
                    <td></td>
                </tr>
                <? while ( have_posts() ) : the_post(); 
					$author_id=$post->post_author;
                    $id=get_the_ID();
					/*$postImage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );*/
				?>
                <tr>
                	<td><a href="#lecture_content" class="btn_lecture_detail various" data-code="<?=$post->ID;?>"><? the_title(); ?></a></td>
                    <td><a href="#author_content" class="btn_author_detail various" data-code="<?=$author_id;?>"><?=the_author_meta( 'display_name' , $author_id ); ?></a></td>
                    <td><?php echo get_the_time('F j, Y', $id); ?><br /><?=get_the_time('g:ia', $id);?></td>
                    <td align="center">
                        <!-- <a href="<?=$postImage[0];?>" target="_blank"><span class="logo_image"></span></a> -->
                     <?  if(is_user_logged_in()){
                            //echo usp_get_meta(false, 'usp-file-single');
                            echo usp_get_image(false, "image-thumbnail", false, false, "file", '', '', '', '', false, false, false, false, false);
                          }
                    ?>
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

<? 
	$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
?>
<script>
	
	var full_url = 'http://<?=$_SERVER['HTTP_HOST']; ?>'+'<?=$uri_parts[0];?>';
</script>