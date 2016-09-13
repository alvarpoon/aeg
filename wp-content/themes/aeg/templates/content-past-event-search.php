<div class="container event_container">
	<div class="row">
    	
        <div class="clearfix">
            <div class="lecture_nav_container clearfix">
                <div class="lecture_nav_item">
                    <a href="/events/up-coming-events/">Up-Coming Events</a>
                </div>
                <div class="lecture_nav_item active">
                    <a href="/events/past-events/">Past Event</a>
                </div>
            </div>
            <div class="clearfix lecture-search-container">
                <div class="align-right">
                    <!--Search bar-->
                    <?=do_shortcode('[searchandfilter id="1143"]');?>
                </div>
            </div>
        </div>
        <div>
        	<p>Search Results for <span class="italic"><?=$_GET['_sf_s']?></span></p>
        </div>
        <div class="event-grid">
            <div class="gutter-sizer"></div>
            <div class="event-sizer"></div>
            <? 
				$post_count = 1;
				while ( have_posts() ) : the_post(); 
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