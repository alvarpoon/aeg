<?php //the_search_query(); ?>

<?
	//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
<div class="container">
	<div class="row">
        <div class="clearfix">
            <div class="lecture_nav_container clearfix">
                <div class="lecture_nav_item active">
                    <a href="/education/lecture/">LECTURE</a>
                </div>
                <div class="lecture_nav_item">
                    <a href="/education/videos/">VIDEO</a>
                </div>
                <div class="lecture_nav_item">
                    <a href="/education/image/">IMAGES</a>
                </div>
            </div>
            <a href="<?=site_url().'/education/lecture-upload/';?>" class="btn_yellow">UPLOAD</a>
        </div>
        <div class="clearfix lecture-search-container">
            <div class="col-sm-5 noPadding">
                <!--<select>
                    <option>Sort by Title (Ascending)</option>
                </select>-->
            </div>
            <div class="col-sm-7 noPadding align-right">
                <!--Search bar-->
                <?=do_shortcode('[searchandfilter id="1126"]');?>
            </div>
        </div>
        <div class="table-responsive lecture-table search-result">
        	<table class="table">
            	<col width="50%">
                <col width="15%">
                <col width="15%">
                <col width="20%">
            	<tr class="header">
                	<td><a href="" class="title_sort">LECTURE TITLE<i class="fa fa-caret-down"></i></a></td>
                    <td><a href="" class="speaker_sort">CONTRIBUTOR<i class="fa fa-caret-down"></i></a></td>
                    <td><a href="" class="date_sort">DATE<i class="fa fa-caret-down"></i></a></td>
                    <td>
                    	<span class="logo_pdf"></span>
                        <span class="logo_video"></span>
                        <span class="logo_audio"></span>
                    </td>
                </tr>
                <? while ( have_posts() ) : the_post(); 
					$author_id=$post->post_author;
					/*$pdf = get_field("pdf", $post->ID);
					$video_file = get_field("video_file", $post->ID);
                    $audio_file = get_field("audio_file", $post->ID);*/
                    $pdf = usp_get_meta(false, 'usp-file-1');
                    $video_file = usp_get_meta(false, 'usp-file-2');
                    $audio_file = usp_get_meta(false, 'usp-file-3');
				?>
                <tr>
                	<td><a href="#lecture_content" class="btn_lecture_detail various" data-code="<?=$post->ID;?>"><? the_title(); ?></a></td>
                    <td><a href="#author_content" class="btn_author_detail various" data-code="<?=$author_id;?>"><?=the_author_meta( 'display_name' , $author_id ); ?></a></td>
                    <td><?php echo get_the_time('F j, Y', $post->ID); ?><br /><?=get_the_time('g:ia', $post->ID);?></td>
                    <td>
                    	<? //if(count($pdf) > 1) { 
                            if($pdf!="") {
                                 if(is_user_logged_in()){?>
	                    	<a href="<?=$pdf?>" target="_blank">
                                <? } ?>
                                <span class="logo_pdf enable"></span>
                                <? if(is_user_logged_in()){?>
                            </a>
                                <? } ?>
                        <? }else{ ?>
                        	<span class="logo_pdf"></span>
                        <? } ?>
                        
                        <? //if(count($media_file) > 1 && $media_file['mime_type'] == 'video/mp4') {
                        if($video_file!=""){
                                if(is_user_logged_in()){?>
	                    	<a href="<?=$video_file?>" target="_blank">
                                <? } ?>
                                <span class="logo_video enable"></span>
                                <? if(is_user_logged_in()){?>
                            </a>
                                <? } ?>
                        <? }else{ ?>
                        	<span class="logo_video"></span>
                        <? } ?>
                        
                        <? //if(count($media_file) > 1 && $media_file['mime_type'] == 'audio/mpeg') {
                          if($audio_file!=""){
                                if(is_user_logged_in()){?>
	                    	<a href="<?=$audio_file?>" target="_blank">
                                <? } ?>
                                <span class="logo_audio enable"></span>
                                <? if(is_user_logged_in()){?>
                            </a>
                                <? } ?>
                        <? }else{ ?>
                        	<span class="logo_audio"></span>
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

<? 
	$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
?>
<script>
	
	var full_url = 'http://<?=$_SERVER['HTTP_HOST']; ?>'+'<?=$uri_parts[0];?>';
</script>