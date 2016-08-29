<?

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	if( isset($_GET['sorting']) ){
		$sort_order = $_GET['sorting'];
	}else{
		$sort_order = '';
	}
		
	switch ($sort_order) {
		case "title_asc":
			$video_args= array(
				'post_type'			=> 'video',
				'post_status' 		=> 'publish',
				'orderby'			=> 'title',
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		case "title_desc":	
			$video_args= array(
				'post_type'			=> 'video',
				'post_status' 		=> 'publish',
				'orderby'			=> 'title',
				'order' 			=> 'DESC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		case "speaker_asc":
			$video_args= array(
				'post_type'			=> 'video',
				'post_status' 		=> 'publish',
				'orderby'			=> 'author date',
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		case "speaker_desc":
			$video_args= array(
				'post_type'			=> 'video',
				'post_status' 		=> 'publish',
				'orderby'			=> 'author date',
				'order' 			=> 'DESC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		case "date_asc":
			$video_args= array(
				'post_type'			=> 'video',
				'post_status' 		=> 'publish',
				'orderby'			=> 'date',
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		case "date_desc":
			$video_args= array(
				'post_type'			=> 'video',
				'post_status' 		=> 'publish',
				'orderby'			=> 'date',
				'order' 			=> 'DESC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		default:
			$video_args= array(
				'post_type'			=> 'video',
				'post_status' 		=> 'publish',
				'orderby'			=> 'title',
				'order' 			=> 'DESC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
	}
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
                <!--<select>
                    <option>Sort by Title (Ascending)</option>
                </select>-->
            </div>
            <div class="col-sm-7 noPadding align-right">
                <!--Search bar-->
				<?=do_shortcode('[searchandfilter id="1126"]');?>
            </div>
        </div>
        <div class="table-responsive lecture-table">
        	<table class="table">
            	<col width="45%">
                <col width="20%">
                <col width="15%">
                <col width="20%">
            	<tr class="header">
                    <td><a href="" class="title_sort">VIDEO TITLE<i class="fa fa-caret-down"></i></a></td>
                    <td><a href="" class="speaker_sort">CONTRIBUTOR<i class="fa fa-caret-down"></i></a></td>
                    <td><a href="" class="date_sort">DATE<i class="fa fa-caret-down"></i></a></td>
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

<? 
	$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
?>
<script>
	
	var full_url = 'http://<?=$_SERVER['HTTP_HOST']; ?>'+'<?=$uri_parts[0];?>';
</script>