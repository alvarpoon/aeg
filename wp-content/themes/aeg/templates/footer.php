<?
	$footer_post_id = 4;
	$footer_address = get_field("address",$footer_post_id);
	$footer_telephone = get_field("telephone",$footer_post_id);
	$footer_fax = get_field("fax",$footer_post_id);
	$footer_email = get_field("email",$footer_post_id);
	$facebook_link = get_field("facebook_link",$footer_post_id);
?>
<footer class="content-info footer" role="contentinfo">
  <div class="container">
    <?php dynamic_sidebar('sidebar-footer'); ?>
  </div>
  <div class="container footer-container">
  	<div class="row">
    	<div class="col-sm-4 social-media">
        	<a href="<?=$facebook_link;?>" class="btn_facebook"></a>
            <a href="#" class="btn_google"></a>
            <a href="#" class="btn_youtube"></a>
            <a href="#" class="btn_twitter"></a>
            <a href="#" class="btn_rss"></a>
        </div>
        <div class="col-sm-8 contact-info">
        	<div class="col-sm-5 noPadding">
            	<?=$footer_address;?>
            </div>
            <div class="col-sm-3 noPadding">
            	Tel:  	<?=$footer_telephone;?><br />Fax:  	<?=$footer_fax;?>
            </div>
            <div class="col-sm-4 noPadding">
            	Email:  	<a href="mailto:<?=$footer_email;?>"><?=$footer_email;?></a>
            </div>
        </div>
    </div>
  </div>
</footer>
