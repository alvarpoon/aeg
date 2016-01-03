<div class="container">
	<div class="row">
    	<div class="page-banner-container">
        	<?php if ( !has_post_thumbnail() ) { ?>
	        	<img src="<?=get_stylesheet_directory_uri()?>/assets/img/banner_contact.png" alt="" />
            <? } ?>
            <h1 <?=(has_post_thumbnail()?"":"class='no-banner'");?>><?=the_title();?></h1>
        </div>
    </div>
    <div class="row">
    	<?php the_content(); ?>
    </div>
</div>
