<?
	$post_id = $post->ID;
	$about_us_text = get_field("about_us_text",$post_id);
?>

<div class="container">
	<div class="row">
    	<div class="home-logo-container">
        	<a href="<?php echo home_url(); ?>" class="logo_aeg"><img src="<?=get_stylesheet_directory_uri()?>/assets/img/logo_aeg.png" alt="" /></a>
        </div>
        <div class="home-banner">
            <?
				$args = array( 'numberposts' => -1, 'post_type' => 'mainpage_banner', 'post_status' => 'publish', 'order' => 'ASC', 'orderby' => 'menu_order', 'suppress_filters' => 0);
				$results = get_posts( $args );
				foreach( $results as $result ) :
					$url = wp_get_attachment_image_src( get_post_thumbnail_id($result->ID), 'full');
					$page_url = get_field("link",$result->ID);
			?>
                    <div class="main-banner-item">
                    	<? if($page_url != '') { ?>
                        <a href="<?=$page_url?>"><img src="<?=$url[0]?>" /></a>
                        <? }else { ?>
                        <img src="<?=$url[0]?>" />
                        <? } ?>
                    </div>
			<? endforeach;?>
        </div>
    </div>
</div>

<div class="container">
	<div class="row">
        <div class="col-sm-6 home-left-container">
            <div class="about-us-container">
                <h2>About us</h2>
                <div class="about-us-brief">
                    <?=$about_us_text;?>
                </div>
            </div>
            
            <div class="member-scroller-container">
                <h2><a href="/about-us/our-committees/">Committees</a></h2>
                <div class="member-scroller-wrapper clearfix">
                    <div class="member-scroller committees-home">
                    	<?
							$committees = get_field("committees", $post->ID);
							foreach( $committees as $committee){
								$committee_content = get_post($committee);
								
								$committee_image = wp_get_attachment_image_src( get_post_thumbnail_id( $committee->ID ), 'full' );
						?>
                            <div class="member_item">
                                <img src="<?=$committee_image[0];?>" alt="" class="img-responsive" />
                                <span><?=$committee_content->post_title?></span>
                            </div>
						<? } ?>
                    </div>
                </div>
            </div>
            <div class="member-scroller-container">
                <h2><a href="/about-us/our-members/">Members</a></h2>
                <div class="member-scroller-wrapper clearfix">
                    <div class="member-scroller members-home">
                        <?
							$members = get_field("members", $post->ID);
							foreach( $members as $member){
								$member_content = get_post($member);
								$member_image = wp_get_attachment_image_src( get_post_thumbnail_id( $member->ID ), 'full' );
						?>
                            <div class="member_item">
                                <img src="<?=$member_image[0];?>" alt="" class="img-responsive" />
                                <span><?=$member_content->post_title?></span>
                            </div>
						<? } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <h2>Up-coming Event</h2>
            <div class="up-coming-event-container">
            	<?
					$events = get_field("events", $post->ID);
					foreach( $events as $key => $event){
						$event_content = get_post($event);
						$event_image = get_field("mainpage_banner",$event->ID);
                        $event_pdf = get_field("pdf",$event->ID);
				?>
					<div class="coming-event-item <? //if($key == 0) { echo "major"; } ?>">
                <?
                        if($event_pdf){
                ?>
                        <a href="<?=$event_pdf['url']?>" target="_blank">
                <?
                        }
                ?>   
                		<img src="<?=$event_image['url'];?>" class="hidden-md visible-sm visible-xs hidden-lg img-responsive" />
						<div class="feature-img-bg hidden-xs hidden-sm visible-md visible-lg" style="background:url(<?=$event_image['url'];?>) no-repeat top center;"></div>
						<div class="event-date">
							<span class="month"><?=date("M", (strtotime(get_field("date_from",$event->ID))));?></span>
							<span class="date"><?=date("j", (strtotime(get_field("date_from",$event->ID))));?></span>
						</div>
						<div class="event-title"><?=$event_content->post_title;?></div>
                <?
                        if($event_pdf){
                ?>
                        </a>
                <?
                        }
                ?>   
					</div>
				<? } ?>
            </div>
        </div>
        <div class="col-sm-3">
            <h2>Latest News</h2>
            <div class="lastest-news-container">
            	<div class="lastest-news-inner-container">
					<?
                        query_posts('showposts=3');
                        
                        while (have_posts()) : the_post(); ?>
                        <div class="lastest-news-item">
                            <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                            <?=apply_filters('the_content',get_field("mainpage_excerpt",get_the_ID()));?>
                        </div>
                    <?php endwhile; ?>
                </div>
            	<!--<div class="lastest-news-item">
                	<h3>AEG-KitasatoTTT</h3>
                    <p>AEG-KitasatoTTT Nomination is now open Trainers and potential trainers may be nominated by members of .</p>
                </div>
                <div class="lastest-news-item">
                	<h3>Notice of 2ndAEG Annual General Meeting</h3>
                    <p>We are pleased to inform you that the 2ndAnnual General Meeting of Asian EUS Group will be held on 4thDec 2015...</p>
                </div>
                <div class="lastest-news-item">
                	<h3>Lecture on PsedocystDrainage</h3>
                    <p>The lecture on PsudocystDrainage by A/Prof Anthony Teoh is now published. Please visit the education page</p>
                </div>-->
                <a href="/latest-news/" class="btn-and-more">AND MORE</a>
            </div>
        </div>
	</div>
</div>