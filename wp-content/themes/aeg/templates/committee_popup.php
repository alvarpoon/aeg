<?php
/* Don't remove this line. */
require('../../../../wp-blog-header.php');
//global $wpdb;

$postContent = get_post($_POST['postID']);

$postImage = wp_get_attachment_image_src( get_post_thumbnail_id( $_POST['postID'] ), 'full' );
?>

<div class="committee_popup_content">
	<div class="popup_info clearfix">
    	<div class="popup_image">
	    	<img src="<?=$postImage[0];?>" class="img-responsive">
        </div>
        <div class="popup_title">
        	<p class="title"><?=$postContent->post_title; ?></p>
            <p class="position"><?=get_field("position",$_POST['postID'])?></p>
        </div>
    </div>
    <div class="popup_content">
    	<span>DESIGNATION</span>
        <p><?=get_field("designation",$_POST['postID'])?></p>
        
        <span>INSTITUTION</span>
        <p><?=get_field("institution",$_POST['postID'])?></p>
        
        <span>QUALIFICATIONS</span>
        <p><?=get_field("qualifications",$_POST['postID'])?></p>
        
        <span>BIO-SKETCH</span>
        <p><?=get_field("bio-sketch",$_POST['postID'])?></p>
    </div>
</div>