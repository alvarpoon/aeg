<div class="container">
	<div class="row">
        <div class="clearfix">
            <div class="lecture_nav_container clearfix">
                <div class="lecture_nav_item">
                    <a href="/events/up-coming-events/">Up-Coming Events</a>
                </div>
                <div class="lecture_nav_item">
                    <a href="/events/past-events/">Past Event</a>
                </div>
            </div>
            <div class="clearfix lecture-search-container">
                <div class="align-right">
                    <!--Search bar-->
                    <?=do_shortcode('[searchandfilter id="1127"]');?>
                </div>
            </div>
        </div>
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
                <? while ( have_posts() ) : the_post(); 
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
        <?php
		  if (function_exists(custom_pagination)) {
			custom_pagination($results->max_num_pages,"",$paged);
		  }
		?>
	</div>
</div>

<div id="lecture_content"></div>
<div id="author_content"></div>