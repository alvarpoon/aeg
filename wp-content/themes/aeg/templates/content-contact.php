<div class="container contact_wrapper">
	<div class="row">
		<div class="page-banner-container">
	    	<img src="<?=get_stylesheet_directory_uri()?>/assets/img/banner_contact.png" alt="" />
            <h1><?=the_title();?></h1>
        </div>
		<div class="clearfix address-container">
			<div id="view1" class="col-sm-6 noPadding">
				<?php
				$location = get_field('map', $post->ID);
				if( ! empty($location) ):
				?>
				<div id="map" style="width: 100%; height: 350px;"></div>
					<script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>
					
					<script type="text/javascript">
					  //<![CDATA[
						function load() {
							var lat = <?php echo $location['lat']; ?>;
							var lng = <?php echo $location['lng']; ?>;
					// coordinates to latLng
							var latlng = new google.maps.LatLng(lat, lng);
					// map Options
							var myOptions = {
							zoom: 15,
							center: latlng,
							mapTypeId: google.maps.MapTypeId.ROADMAP
						  };
					//draw a map
							var map = new google.maps.Map(document.getElementById("map"), myOptions);
							var marker = new google.maps.Marker({
							position: map.getCenter(),
							map: map
						   });
						}
					// call the function
					   load();
					//]]>
					</script>
				<?php endif; ?> 
			</div>
			<div class="col-sm-6 address-content">
				<h2>Address</h2>
				<p>12, West Coast Walk, #02-06<br />West Coast Recreation Centre<br />Singapore 127157</p>
				<p><span>Tel:</span>+65 6774 5201<br /><span>Fax:</span>+65 6774 5203<br /><span>Email:</span><a href="mailto:secretariat@asiaeus.org">secretariat@asiaeus.org</a></p>
			</div>
		</div>
        <div class="col-xs-12 noPadding-sm">
            <h2>ENQUIRIES</h2>
            <?=do_shortcode('[contact-form-7 id="82" title="Contact form 1"]');?>
        </div>
	</div>
</div>
