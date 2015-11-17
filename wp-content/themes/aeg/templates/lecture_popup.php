<?php
/* Don't remove this line. */
require('../../../../wp-blog-header.php');
//global $wpdb;
$postContent = get_post($_POST['postID']);
//$postImage = wp_get_attachment_image_src( get_post_thumbnail_id( $_POST['postID'] ), 'full' );
?>

<div class="committee_popup_content lecture_popup_content">
    <h2><?=$postContent->post_title; ?></h2>
    <div class="popup_content">
    	<?=apply_filters('the_content',$postContent->post_content);?>
    </div>
</div>