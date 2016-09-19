<?
	$today = date('Ymd');
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	if( isset($_GET['sorting']) ){
		$sort_order = $_GET['sorting'];
	}else{
		$sort_order = '';
	}
	
	if($post->ID == 37){ //Asian EUS Congress
		switch ($sort_order) {
			case "date_asc":
				$current_args= array(
					'post_type'			=> 'event',
					'post_status' 		=> 'publish',
					'meta_key' 			=> 'date_from',
					//'orderby' 			=> 'date_from',
					'orderby'   		=> 'meta_value',
					'order' 			=> 'ASC',
					'numberposts' 		=> -1,
					'posts_per_page' 	=> 10,
					'category_name'		=> 'asian-eus-congress',
					'paged' 			=> $paged,
				);
				break;
			case "date_desc":
				$current_args= array(
					'post_type'			=> 'event',
					'post_status' 		=> 'publish',
					'meta_key' 			=> 'date_from',
					//'orderby' 			=> 'date_from',
					'orderby'   		=> 'meta_value',
					'order' 			=> 'DESC',
					'numberposts' 		=> -1,
					'posts_per_page' 	=> 10,
					'category_name'		=> 'asian-eus-congress',
					'paged' 			=> $paged,
				);
				break;
			case "venue_asc":
				$current_args= array(
					'post_type'			=> 'event',
					'post_status' 		=> 'publish',
					'meta_key' 			=> 'venue',
					'orderby'    		=> 'meta_value',
					'order' 			=> 'ASC',
					'numberposts' 		=> -1,
					'posts_per_page' 	=> 10,
					'category_name'		=> 'asian-eus-congress',
					'paged' 			=> $paged,
				);
				break;
			case "venue_desc":
				$current_args= array(
					'post_type'			=> 'event',
					'post_status' 		=> 'publish',
					'meta_key' 			=> 'venue',
					'orderby'    		=> 'meta_value',
					'order' 			=> 'DESC',
					'numberposts' 		=> -1,
					'posts_per_page' 	=> 10,
					'category_name'		=> 'asian-eus-congress',
					'paged' 			=> $paged,
				);
				break;
			case "country_asc":
				$current_args= array(
					'post_type'			=> 'event',
					'post_status' 		=> 'publish',
					'meta_key' 			=> 'country',
					'orderby'    		=> 'meta_value',
					'order' 			=> 'ASC',
					'numberposts' 		=> -1,
					'posts_per_page' 	=> 10,
					'category_name'		=> 'asian-eus-congress',
					'paged' 			=> $paged,
				);
				break;
			case "country_desc":
				$current_args= array(
					'post_type'			=> 'event',
					'post_status' 		=> 'publish',
					'meta_key' 			=> 'country',
					'orderby'    		=> 'meta_value',
					'order' 			=> 'DESC',
					'numberposts' 		=> -1,
					'posts_per_page' 	=> 10,
					'category_name'		=> 'asian-eus-congress',
					'paged' 			=> $paged,
				);
				break;
			default:
				$current_args= array(
					'post_type'			=> 'event',
					'post_status' 		=> 'publish',
					'order' 			=> 'DESC',
					'numberposts' 		=> -1,
					'posts_per_page' 	=> 10,
					'category_name'		=> 'asian-eus-congress',
					'orderby' 			=> 'date_from',
					'paged' 			=> $paged,
				);
		}
	
		/*$current_args= array(
			'post_type'			=> 'event',
			'post_status' 		=> 'publish',
			'order' 			=> 'DESC',
			'numberposts' 		=> -1,
			'posts_per_page' 	=> 10,
			'category_name'		=> 'asian-eus-congress',
			//'meta_key' 			=> 'date_to',
			//'meta_compare'		=> '>',
			//'meta_value' 		=> $today,
			//'category__in'		=> 13,
			'orderby' 			=> 'date_from',
			'paged' 			=> $paged,
		);*/
		
	} else if($post->ID == 39){//up-coming
		$current_args= array(
			'post_type'			=> 'event',
			'post_status' 		=> 'publish',
			'order' 			=> 'DESC',
			'numberposts' 		=> -1,
			'posts_per_page' 	=> 10,
			'category__not_in'  => 13,
			'meta_key' 			=> 'date_to',
			'meta_compare'		=> '>',
			'meta_value' 		=> $today,
			'orderby' 			=> 'date_from',
			'paged' 			=> $paged,
		);
		
		$past_args= array(
			'post_type'			=> 'event',
			'post_status' 		=> 'publish',
			'order' 			=> 'DESC',
			'numberposts' 		=> -1,
			'posts_per_page' 	=> 10,
			'category__not_in'  => 13,
			'meta_key' 			=> 'date_to',
			'meta_compare'		=> '<',
			'meta_value' 		=> $today,
			'orderby' 			=> 'date_from',
			'paged' 			=> $paged,
		);
	} else if($post->ID == 41){ //past
		$current_args= array(
			'post_type'			=> 'event',
			'post_status' 		=> 'publish',
			'order' 			=> 'DESC',
			'numberposts' 		=> -1,
			'posts_per_page' 	=> 10,
			'category__not_in'  => 13,
			'meta_key' 			=> 'date_to',
			'meta_compare'		=> '<',
			'meta_value' 		=> $today,
			'orderby' 			=> 'date_from',
			'paged' 			=> $paged,
		);
		
		$past_args= array(
			'post_type'			=> 'event',
			'post_status' 		=> 'publish',
			'order' 			=> 'DESC',
			'numberposts' 		=> -1,
			'posts_per_page' 	=> 10,
			'category__not_in'  => 13,
			'meta_key' 			=> 'date_to',
			'meta_compare'		=> '>',
			'meta_value' 		=> $today,
			'orderby' 			=> 'date_from',
			'paged' 			=> $paged,
		);
	}
		
	$results = new WP_Query( $current_args );
	if($post->ID != 37){
		$past_result = new WP_Query ($past_args);
	}
?>
<div class="container">
	<div class="row">
    	<?php
		
			$feature_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); 
		
			if($feature_image != ''){
		?>
        
        <div class="feature_banner_wrapper">
	    	<img src="<?=$feature_image[0];?>" alt="" />
            <div class="banner_content_container">
            	<div class="banner_table_div">
                    <div class="banner_event_heading col-sm-6">
                        <div class="banner_event_content">
                        <?php
                            $banner_event_heading = get_field('banner_event_heading', $post->ID);
                            echo $banner_event_heading;
                        ?>
                        </div>
                    </div>
                    <div class="banner_event_info col-sm-6">
                    	<div class="event_info_content">
						<?php
                            $banner_event_info = get_field('banner_event_info', $post->ID);
                            echo $banner_event_info;
                        ?>
                        </div>
                    </div>
            	</div>
            </div>
            
            <?php
               	$banner_event_apply_link = get_field('banner_event_apply_link', $post->ID);
			?>
            <a href="http://<?=$banner_event_apply_link?>" target="_blank" class="btn_event_apply_link">Apply</a>
        </div>
        
        <?php } ?>
    </div>
</div>
<div class="container event_container">
	<div class="row">
    	<? if($post->ID != 37) { ?>
        <div class="clearfix">
            <div class="lecture_nav_container clearfix">
                <div class="lecture_nav_item <? if($post->ID == 39){ echo "active"; } ?>">
                    <a href="/events/up-coming-events/">Up-Coming Events<? if($post->ID != 39) {echo "<span>".$past_result->post_count."</span>";} ?></a>
                </div>
                <div class="lecture_nav_item <? if($post->ID == 41){ echo "active"; } ?>">
                    <a href="/events/past-events/">Past Event<? if($post->ID != 41) {echo "<span>".$past_result->post_count."</span>";} ?></a>
                </div>
            </div>
            <div class="clearfix lecture-search-container">
                <div class="align-right">
                    <!--Search bar-->
                    
                    <?php
						if($post->ID == 39){
							echo do_shortcode('[searchandfilter id="1127"]');
						}else if($post->ID == 41){
							echo do_shortcode('[searchandfilter id="1143"]');		
						}
					?>
                </div>
            </div>
        </div>
        <? } ?>
        <? if($post->ID == 37) { ?>
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
        <div class="table-responsive lecture-table">
        	<table class="table">
            	<col width="25%">
                <col width="25%">
                <col width="20%">
                <col width="30%">
            	<tr class="header">
                	<td><a href="" class="date_sort">DATE<i class="fa fa-caret-down"></i></a></td>
                    <td><a href="" class="venue_sort">VENUE<i class="fa fa-caret-down"></i></a></td>
                    <td><a href="" class="country_sort">COUNTRY<i class="fa fa-caret-down"></i></a></td>
                    <td></td>
                </tr>
                <? while ( $results->have_posts() ) : $results->the_post(); 
					//$pdf = get_field("pdf", $post->ID);
					
					$event_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
				?>
                <tr>
                	<td>
                    	<?=date('F j, Y', strtotime(get_field('date_from', $post->ID)));?><br/><?=date('g:ia', strtotime(get_field('date_from', $post->ID)));?>
                        <? //=get_field('date_from', $post->ID) ?>
                    </td>
                    <td>
                    	<?=get_field('venue', $post->ID);?>
                    </td>
                    <td>
	                    <?=get_field('country', $post->ID);?>
                    </td>
                    <td class="eus-image">
                    	<img src="<?=$event_image[0];?>" class="img-responive" />
                    </td>
                </tr>
                <? endwhile; ?>				
            </table>
        </div>
        <? } ?>
        <? if($post->ID == 39) { ?>
        <div class="event-grid">
            <div class="gutter-sizer"></div>
            <div class="event-sizer"></div>
            <? 
				$post_count = 1;
				while ( $results->have_posts() ) : $results->the_post(); 
					$event_feature_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
					$pdf = get_field("pdf", $post->ID);
					
			?>
            <div class="event-item color-<?=$post_count%4;?>">
                <div class="event-header-container clearfix">
                	<div class="col-xs-9 noPadding">
		                <h2><? the_title();?></h2>
                    </div>
                    <div class="event-date-container col-xs-3 noPadding">
                    	<div class="event-date">
                            <span class="month"><?=date("M", (strtotime(get_field("date_from",$results->ID))));?></span>
                            <span class="date"><?=date("j", (strtotime(get_field("date_from",$results->ID))));?></span>
                        </div>
                    </div>
                </div>
                <? if($pdf['url'] != '') { ?>
                    <a href="<?=$pdf['url']?>" target="_blank"><img src="<?=$event_feature_image[0];?>" alt="" class="img-responsive" /></a>
                    <a href="<?=$pdf['url']?>" target="_blank" class="btn_readmore">READ MORE ></a>
                <? }else{ ?>
                    <img src="<?=$event_feature_image[0];?>" alt="" class="img-responsive" />
                <? } ?>                
            </div>
            <? 	$post_count++;
				endwhile; ?>
        </div>
        <? } else if($post->ID == 41) { ?>
        <div class="table-responsive lecture-table">
        	<table class="table">
            	<col width="55%">
                <col width="25%">
                <col width="20%">
            	<tr class="header">
                	<td>EVENT</td>
                    <td>VENUE</td>
                    <td>DATE</td>
                </tr>
                <? while ( $results->have_posts() ) : $results->the_post(); 
					$pdf = get_field("pdf", $post->ID);
				?>
                <tr>
                	<td>
                    	<? if($pdf['url'] != '') { ?>
	                    	<a href="<?=$pdf['url']?>" target="_blank"><? the_title(); ?></a>
                        <? }else{ ?>
                        	<? the_title(); ?>
                        <? } ?>
                    </td>
                    <td><?=get_field('venue', $post->ID);?></td>
                    <td>
						<!--<?php echo get_the_time('F j, Y', $post->ID); ?><br /><?=get_the_time('g:ia', $post->ID);?>-->
                        <?=date('F j, Y', strtotime(get_field('date_from', $post->ID)));?><br/><?=date('g:ia', strtotime(get_field('date_from', $post->ID)));?>
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

<?php
	$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
?>
<script>
	
	var full_url = 'http://<?=$_SERVER['HTTP_HOST']; ?>'+'<?=$uri_parts[0];?>';
</script>