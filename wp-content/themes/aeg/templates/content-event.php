<?
	$today = date('Ymd');
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	if($post->ID == 37){ //Asian EUS Congress
		$current_args= array(
			'post_type'			=> 'event',
			'post_status' 		=> 'publish',
			'order' 			=> 'DESC',
			'numberposts' 		=> -1,
			'posts_per_page' 	=> 10,
			'category__in'		=> 11,
/*			'meta_key' 			=> 'date_to',
			'meta_compare'		=> '>',
			'meta_value' 		=> $today,*/
			'orderby' 			=> 'date_from',
			'paged' 			=> $paged,
		);
		
		/*$past_args= array(
			'post_type'			=> 'event',
			'post_status' 		=> 'publish',
			'order' 			=> 'DESC',
			'numberposts' 		=> -1,
			'posts_per_page' 	=> 10,
			'meta_key' 			=> 'date_to',
			'meta_compare'		=> '<',
			'meta_value' 		=> $today,
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
			'category__not_in'  => 11,
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
			'category__not_in'  => 11,
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
			'category__not_in'  => 11,
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
			'category__not_in'  => 11,
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
    	<? $feature_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
    	<img src="<?=$feature_image[0];?>" alt="" />
    </div>
</div>
<div class="container">
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
                	<td>DATE</td>
                    <td>VENUE</td>
                    <td>COUNTRY</td>
                    <td></td>
                </tr>
                <? while ( $results->have_posts() ) : $results->the_post(); 
					//$pdf = get_field("pdf", $post->ID);
					
					$event_image = get_field("mainpage_banner",$results->ID);
				?>
                <tr>
                	<td>
                    	<?=date('F j, Y', strtotime(get_field('date_from', $post->ID)));?><br/><?=date('g:ia', strtotime(get_field('date_from', $post->ID)));?>
                    </td>
                    <td>
                    	<?=get_field('venue', $post->ID);?>
                    </td>
                    <td>
	                    <?=get_field('country', $post->ID);?>
                    </td>
                    <td>
                    	<img src="<?=$event_image['url'];?>" class="img-respsonive" />
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
                	<td>EVENT TITLE</td>
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