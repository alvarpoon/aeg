<?
	if($post->ID == 27){
		$terms_id = 6; //on-going
	}
	
	if($post->ID == 29){
		$terms_id = 7; //past
	}
	
	if($post->ID == 31){
		$terms_id = 8; //published
	}

    $user_id = get_current_user_id();

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	if( isset($_GET['sorting']) ){
		$sort_order = $_GET['sorting'];
	}else{
		$sort_order = '';
	}
	
	switch ($sort_order) {
		case "title_asc":
			$args= array(
				'post_type'			=> 'research',
				'post_status' 		=> 'publish',
				'orderby'			=> 'title',
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'tax_query' => array(
					array(
						'taxonomy' => 'status',
						'field' => 'id',
						'terms' => $terms_id
					)
				)
			);
			break;
		case "title_desc":	
			$args= array(
				'post_type'			=> 'research',
				'post_status' 		=> 'publish',
				'orderby'			=> 'title',
				'order' 			=> 'DESC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'tax_query' => array(
					array(
						'taxonomy' => 'status',
						'field' => 'id',
						'terms' => $terms_id
					)
				)
			);
			break;
		case "speaker_asc":
			$args= array(
				'post_type'			=> 'research',
				'post_status' 		=> 'publish',
				'meta_key' 			=> 'researcher',
				'orderby' 			=> 'meta_value',
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'tax_query' => array(
					array(
						'taxonomy' => 'status',
						'field' => 'id',
						'terms' => $terms_id
					)
				)
			);
			break;
		case "speaker_desc":
			$args= array(
				'post_type'			=> 'research',
				'post_status' 		=> 'publish',
				'meta_key' 			=> 'researcher',
				'orderby' 			=> 'meta_value',
				'order' 			=> 'DESC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'tax_query' => array(
					array(
						'taxonomy' => 'status',
						'field' => 'id',
						'terms' => $terms_id
					)
				)
			);
			break;
		default:
			$args= array(
				'post_type'			=> 'research',
				'post_status' 		=> 'publish',
				'orderby'			=> 'date',
				'order' 			=> 'DESC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 16,
				'paged' 			=> $paged,
				'tax_query' => array(
					array(
						'taxonomy' => 'status',
						'field' => 'id',
						'terms' => $terms_id
					)
				)
			);
	}

    //for on-going and past research, get only the research user is involved in
    if($post->ID == 27 || $post->ID == 29){
        $args['meta_query'] = array(
              array(
                'key' => 'researcher',
                'value' => '"' . $user_id . '"',
                'compare' => 'LIKE'
            )
        );
    }

	$results = new WP_Query( $args );

?>
<div class="container">
	<div class="row">
        <div class="clearfix">
            <div class="lecture_nav_container clearfix">
                <div class="lecture_nav_item <? if($post->ID == 27){ echo "active";} ?>">
                    <a href="/research-page/on-going-research/">On-going</a>
                </div>
                <div class="lecture_nav_item <? if($post->ID == 29){ echo "active past-research";} ?>">
                    <a href="/research-page/past-research/">Past Research</a>
                </div>
                <div class="lecture_nav_item <? if($post->ID == 31){ echo "active";} ?>">
                    <a href="/research-page/research-published/">Published</a>
                </div>                
            </div>
        </div>
        <div class="clearfix lecture-search-container">
            <div class="col-sm-5 noPadding">
                <!--<select>
                    <option>Sort by Title (Ascending)</option>
                </select>-->
            </div>
            <div class="col-sm-7 noPadding">
                <!--Search bar-->
            </div>
        </div>
        <? if($post->ID == 27) { //on-going
            $researcher_code = get_field('researcher_code', 'user_'.$user_id);
            $centre = get_field('research_center', 'user_'.$user_id);
            $centre_code = get_field('centre_code',$centre->ID);
        ?>
        <div class="table-responsive lecture-table">
        	<table class="table">
            	<tr class="header">
                    <td><a href="" class="title_sort">RESEARCH TITLE<i class="fa fa-caret-down"></i></a></td>
                </tr>
                <? while ( $results->have_posts() ) : $results->the_post(); ?>
                <tr>
                	<td>
                		<? if(get_field('external_research_page')){ ?>
                		<a target="_blank" href="http://<?=get_field('external_research_page')?>"><? the_title(); ?></a>
                		<? }else{ ?>
                		<a target="_blank" href="<?=get_field('research_page')?>?researcher_code=<?=$researcher_code?>&centre_code=<?=$centre_code?>"><? the_title(); ?></a>
                		<? } ?>
                	</td>
                </tr>
                <? endwhile; ?>				
            </table>
        </div>
		<? } else if($post->ID == 31) { //published?>
        <div class="table-responsive lecture-table">
        	<table class="table">
            	<col width="60%">
                <col width="20%">
                <col width="20%">
            	<tr class="header">
                	<!--<td>RESEARCH TITLE</td>
                    <td>RESEARCHER</td>-->
                    
                    <td><a href="" class="title_sort">RESEARCH TITLE<i class="fa fa-caret-down"></i></a></td>
                    <td><a href="" class="speaker_sort">RESEARCHER<i class="fa fa-caret-down"></i></a></td>
                    <td>
                    	<span class="logo_pdf"></span>
                        <span class="logo_video"></span>
                        <span class="logo_audio"></span>
                    </td>
                </tr>
                <? while ( $results->have_posts() ) : $results->the_post();
                    $id = get_the_ID();
					$pdf = get_field("pdf", $id);
					$media_file = get_field("video_audio_file", $id);
					$researchers = get_field("researcher", $id);
				?>
                <tr>
                	<td>
                    <? if($pdf) { ?>
                        <a target="_blank" href="<?=$pdf['url']?>"><? the_title(); ?></a></td>
                    <? }else{?>
                        <? the_title(); ?></td>
                    <? } ?>
                    <td>
                    	<? foreach($researchers as $key => $researcher) { 
                        	echo $researcher['display_name'];
							if($key < sizeOf($researchers)-1){
								echo ',<br/>';
							}
                        } ?>
                    </td>
                    <td>
                    	<? if($pdf) { ?>
	                    	<a href="<?=$pdf['url']?>" target="_blank"><span class="logo_pdf enable"></span></a>
                        <? }else{ ?>
                        	<span class="logo_pdf"></span>
                        <? } ?>
                        
                        <? if(count($media_file) > 1 && $media_file['mime_type'] == 'video/mp4') { ?>
	                    	<a href="<?=$media_file['url']?>" target="_blank"><span class="logo_video enable"></span></a>
                        <? }else{ ?>
                        	<span class="logo_video"></span>
                        <? } ?>
                        
                        <? if(count($media_file) > 1 && $media_file['mime_type'] == 'audio/mpeg') { ?>
	                    	<a href="<?=$media_file['url']?>" target="_blank"><span class="logo_audio enable"></span></a>
                        <? }else{ ?>
                        	<span class="logo_audio"></span>
                        <? } ?>
                    </td>
                </tr>
                <? endwhile; ?>				
            </table>
        </div>
        <? } else if($post->ID == 29) { //Past?> 
        <div class="table-responsive lecture-table">
        	<table class="table">
            	<col width="70%">
                <col width="30%">
            	<tr class="header">
                	<!--<td>RESEARCH TITLE</td>
                    <td>RESEARCHER</td>-->
                    
                    <td><a href="" class="title_sort">RESEARCH TITLE<i class="fa fa-caret-down"></i></a></td>
                    <td><a href="" class="speaker_sort">RESEARCHER<i class="fa fa-caret-down"></i></a></td>
                </tr>
                <? while ( $results->have_posts() ) : $results->the_post(); 
                    $id = get_the_ID();
					$researchers = get_field("researcher", $id);
				?>
                <tr>
                	<td><? the_title(); ?></td>
                    <td>
                    	<? foreach($researchers as $key => $researcher) { 
                        	echo $researcher['display_name'];
							if($key < sizeOf($researchers)-1){
								echo ',<br/>';
							}
                        } ?>
                    </td>
                </tr>
                <? endwhile; ?>				
            </table>
        </div>        
        <? } ?>
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