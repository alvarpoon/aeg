<?php
/* Don't remove this line. */
require('../../../../wp-blog-header.php');
?>

<div class="committee_popup_content author_popup_content">
	<h2><?=get_the_author_meta('display_name',$_POST['postID']) ?></h2>
    <div class="popup_content">
    	<p><?=get_the_author_meta('description',$_POST['postID']) ?></p>
    </div>
</div>