<?php
/* Don't remove this line. */
require('../../../../wp-blog-header.php');
//global $wpdb;
$postContent = get_post($_POST['postID']);
$userID = $_POST['postID'];
$user_info = get_userdata($userID);
//$postImage = wp_get_attachment_image_src( get_post_thumbnail_id( $_POST['postID'] ), 'full' );
$postImage = wp_get_attachment_image_src(do_shortcode('[user_meta user_id='.$userID.' key="user_image"]'), 'full' );
?>

<div class="committee_popup_content">
	<div class="popup_info clearfix">
    	<div class="popup_image">
            <img src="<?=($postImage?$postImage[0]:get_template_directory_uri().'/assets/img/profile-dummy.jpg')?>" alt="" class="img-responsive" />
        </div>
        <div class="popup_title">
        	<p class="title"><?=$user_info->display_name; ?></p>
            <p class="position"><?=do_shortcode('[user_meta user_id='.$userID.' key="user_position"]')?></p>
        </div>
    </div>
    <div class="popup_content">
    	<span>DESIGNATION</span>
        <p><?=do_shortcode('[user_meta user_id='.$userID.' key="user_designation"]')?></p>
        <span>INSTITUTION</span>
        <p><?=do_shortcode('[user_meta user_id='.$userID.' key="user_institution"]')?></p>
        <span>QUALIFICATIONS</span>
        <p><?=do_shortcode('[user_meta user_id='.$userID.' key="user_qualification"]')?></p>
        <span>BIO-SKETCH</span>
        <div><?=wpautop(do_shortcode('[user_meta user_id='.$userID.' key="user_biosketch"]'))?></div>
    </div>
</div>