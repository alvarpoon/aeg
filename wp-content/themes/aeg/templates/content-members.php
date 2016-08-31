<?	
	
	$countries = array("AF"=>"Afghanistan","AX"=>"Aland Islands","AL"=>"Albania","DZ"=>"Algeria","AS"=>"American Samoa","AD"=>"Andorra","AO"=>"Angola","AI"=>"Anguilla","AQ"=>"Antarctica","AG"=>"Antigua and Barbuda","AR"=>"Argentina","AM"=>"Armenia","AW"=>"Aruba","AU"=>"Australia","AT"=>"Austria","AZ"=>"Azerbaijan","BS"=>"Bahamas","BH"=>"Bahrain","BD"=>"Bangladesh","BB"=>"Barbados","BY"=>"Belarus","BE"=>"Belgium","BZ"=>"Belize","BJ"=>"Benin","BM"=>"Bermuda","BT"=>"Bhutan","BO"=>"Bolivia","BQ"=>"Bonaire, Saint Eustatius and Saba","BA"=>"Bosnia and Herzegovina","BW"=>"Botswana","BV"=>"Bouvet Island","BR"=>"Brazil","IO"=>"British Indian Ocean Territory","VG"=>"British Virgin Islands","BN"=>"Brunei","BG"=>"Bulgaria","BF"=>"Burkina Faso","BI"=>"Burundi","KH"=>"Cambodia","CM"=>"Cameroon","CA"=>"Canada","CV"=>"Cape Verde","KY"=>"Cayman Islands","CF"=>"Central African Republic","TD"=>"Chad","CL"=>"Chile","CN"=>"China","CX"=>"Christmas Island","CC"=>"Cocos Islands","CO"=>"Colombia","KM"=>"Comoros","CK"=>"Cook Islands","CR"=>"Costa Rica","HR"=>"Croatia","CU"=>"Cuba","CW"=>"Curacao","CY"=>"Cyprus","CZ"=>"Czech Republic","CD"=>"Democratic Republic of the Congo","DK"=>"Denmark","DJ"=>"Djibouti","DM"=>"Dominica","DO"=>"Dominican Republic","TL"=>"East Timor","EC"=>"Ecuador","EG"=>"Egypt","SV"=>"El Salvador","GQ"=>"Equatorial Guinea","ER"=>"Eritrea","EE"=>"Estonia","ET"=>"Ethiopia","FK"=>"Falkland Islands","FO"=>"Faroe Islands","FJ"=>"Fiji","FI"=>"Finland","FR"=>"France","GF"=>"French Guiana","PF"=>"French Polynesia","TF"=>"French Southern Territories","GA"=>"Gabon","GM"=>"Gambia","GE"=>"Georgia","DE"=>"Germany","GH"=>"Ghana","GI"=>"Gibraltar","GR"=>"Greece","GL"=>"Greenland","GD"=>"Grenada","GP"=>"Guadeloupe","GU"=>"Guam","GT"=>"Guatemala","GG"=>"Guernsey","GN"=>"Guinea","GW"=>"Guinea-Bissau","GY"=>"Guyana","HT"=>"Haiti","HM"=>"Heard Island and McDonald Islands","HN"=>"Honduras","HK"=>"Hong Kong","HU"=>"Hungary","IS"=>"Iceland","IN"=>"India","ID"=>"Indonesia","IR"=>"Iran","IQ"=>"Iraq","IE"=>"Ireland","IM"=>"Isle of Man","IL"=>"Israel","IT"=>"Italy","CI"=>"Ivory Coast","JM"=>"Jamaica","JP"=>"Japan","JE"=>"Jersey","JO"=>"Jordan","KZ"=>"Kazakhstan","KE"=>"Kenya","KI"=>"Kiribati","XK"=>"Kosovo","KW"=>"Kuwait","KG"=>"Kyrgyzstan","LA"=>"Laos","LV"=>"Latvia","LB"=>"Lebanon","LS"=>"Lesotho","LR"=>"Liberia","LY"=>"Libya","LI"=>"Liechtenstein","LT"=>"Lithuania","LU"=>"Luxembourg","MO"=>"Macao","MK"=>"Macedonia","MG"=>"Madagascar","MW"=>"Malawi","MY"=>"Malaysia","MV"=>"Maldives","ML"=>"Mali","MT"=>"Malta","MH"=>"Marshall Islands","MQ"=>"Martinique","MR"=>"Mauritania","MU"=>"Mauritius","YT"=>"Mayotte","MX"=>"Mexico","FM"=>"Micronesia","MD"=>"Moldova","MC"=>"Monaco","MN"=>"Mongolia","ME"=>"Montenegro","MS"=>"Montserrat","MA"=>"Morocco","MZ"=>"Mozambique","MM"=>"Myanmar","NA"=>"Namibia","NR"=>"Nauru","NP"=>"Nepal","NL"=>"Netherlands","NC"=>"New Caledonia","NZ"=>"New Zealand","NI"=>"Nicaragua","NE"=>"Niger","NG"=>"Nigeria","NU"=>"Niue","NF"=>"Norfolk Island","KP"=>"North Korea","MP"=>"Northern Mariana Islands","NO"=>"Norway","OM"=>"Oman","PK"=>"Pakistan","PW"=>"Palau","PS"=>"Palestinian Territory","PA"=>"Panama","PG"=>"Papua New Guinea","PY"=>"Paraguay","PE"=>"Peru","PH"=>"Philippines","PN"=>"Pitcairn","PL"=>"Poland","PT"=>"Portugal","PR"=>"Puerto Rico","QA"=>"Qatar","CG"=>"Republic of the Congo","RE"=>"Reunion","RO"=>"Romania","RU"=>"Russia","RW"=>"Rwanda","BL"=>"Saint Barthelemy","SH"=>"Saint Helena","KN"=>"Saint Kitts and Nevis","LC"=>"Saint Lucia","MF"=>"Saint Martin","PM"=>"Saint Pierre and Miquelon","VC"=>"Saint Vincent and the Grenadines","WS"=>"Samoa","SM"=>"San Marino","ST"=>"Sao Tome and Principe","SA"=>"Saudi Arabia","SN"=>"Senegal","RS"=>"Serbia","SC"=>"Seychelles","SL"=>"Sierra Leone","SG"=>"Singapore","SX"=>"Sint Maarten","SK"=>"Slovakia","SI"=>"Slovenia","SB"=>"Solomon Islands","SO"=>"Somalia","ZA"=>"South Africa","GS"=>"South Georgia and the South Sandwich Islands","KR"=>"South Korea","SS"=>"South Sudan","ES"=>"Spain","LK"=>"Sri Lanka","SD"=>"Sudan","SR"=>"Suriname","SJ"=>"Svalbard and Jan Mayen","SZ"=>"Swaziland","SE"=>"Sweden","CH"=>"Switzerland","SY"=>"Syria","TW"=>"Taiwan","TJ"=>"Tajikistan","TZ"=>"Tanzania","TH"=>"Thailand","TG"=>"Togo","TK"=>"Tokelau","TO"=>"Tonga","TT"=>"Trinidad and Tobago","TN"=>"Tunisia","TR"=>"Turkey","TM"=>"Turkmenistan","TC"=>"Turks and Caicos Islands","TV"=>"Tuvalu","VI"=>"U.S. Virgin Islands","UG"=>"Uganda","UA"=>"Ukraine","AE"=>"United Arab Emirates","GB"=>"United Kingdom","US"=>"United States","UM"=>"United States Minor Outlying Islands","UY"=>"Uruguay","UZ"=>"Uzbekistan","VU"=>"Vanuatu","VA"=>"Vatican","VE"=>"Venezuela","VN"=>"Vietnam","WF"=>"Wallis and Futuna","EH"=>"Western Sahara","YE"=>"Yemen","ZM"=>"Zambia","ZW"=>"Zimbabwe");

	if( isset($_GET['sorting']) ){
		$sort_order = $_GET['sorting'];
	}else{
		$sort_order = '';
	}
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	switch ($sort_order) {
<<<<<<< HEAD
		/*case "title_asc":
			$args= array(
				'post_type'			=> 'member',
				'post_status' 		=> 'publish',
				'orderby'			=> 'title',
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 30,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
=======
		case "title_asc":
			$args = array(
				'orderby' => 'display_name',
				'order' => 'ASC',
	            'meta_query' => array(
	            'relation' => 'OR',
	                array(
	                    'key' => 'category',
	                    'value' => "Member",
	                    'compare' => 'LIKE'
	                )
	            )
	        );
>>>>>>> 701f04be6ea7e90a9497a736c5e0cbd7c3196a40
			break;
		case "title_desc":
			$args = array(
				'orderby' => 'display_name',
				'order' => 'DESC',
	            'meta_query' => array(
	            'relation' => 'OR',
	                array(
	                    'key' => 'category',
	                    'value' => "Member",
	                    'compare' => 'LIKE'
	                )
	            )
	        );
			break;
		/*case "country_asc":
			$args= array(
				'post_type'			=> 'member',
				'post_status' 		=> 'publish',
				//'orderby'			=> 'menu_order',
				'meta_key' 			=> 'country',
				'orderby' 			=> 'meta_value', 
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 30,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;
		case "country_desc":
			$args= array(
				'post_type'			=> 'member',
				'post_status' 		=> 'publish',
				//'orderby'			=> 'menu_order',
				'meta_key' 			=> 'country',
				'orderby' 			=> 'meta_value', 
				'order' 			=> 'DESC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 30,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);
			break;*/
		default:
			/*$args= array(
				'post_type'			=> 'member',
				'post_status' 		=> 'publish',
				'orderby'			=> 'title',
				'order' 			=> 'ASC',
				'numberposts' 		=> -1,
				'posts_per_page' 	=> 30,
				'paged' 			=> $paged,
				'suppress_filters' => false
			);*/
			/*$args = array(
				'meta_key'     => 'category',
				'meta_value'   => 'member',
				'orderby'      => 'title',
				'order'        => 'ASC'
			 ); */
<<<<<<< HEAD
			$args = array(						
=======
			/*$args = array(						
>>>>>>> 701f04be6ea7e90a9497a736c5e0cbd7c3196a40
				'meta_query' => array(
				'relation' => 'OR',
					array(
					'key' => 'category',
					'value' => 'member',
					'compare' => 'LIKE'
					)
				),
				'orderby'      => 'title',
				'order'        => 'ASC'
<<<<<<< HEAD
			);
	}
	/*$results = new WP_Query( $args );*/
	$results = new WP_User_Query( $args );
	$members = $results->get_results();
=======
			);*/
			$args = array(
				'orderby' => 'display_name',
				'order' => 'ASC',
	            'meta_query' => array(
	            'relation' => 'OR',
	                array(
	                    'key' => 'category',
	                    'value' => "Member",
	                    'compare' => 'LIKE'
	                )
	            )
	        );
	}
	$user_query = new WP_User_Query( $args );
	$members = $user_query->results;
>>>>>>> 701f04be6ea7e90a9497a736c5e0cbd7c3196a40
?>
<div class="container">
	<div class="row">
    	<div class="page-banner-container">
	    	<img src="<?=get_stylesheet_directory_uri()?>/assets/img/committees/img_committees_banner.png" alt="" />
            <h1><?=the_title();?></h1>
        </div>
        <div class="member-search-container">
        	<?php
$blogusers = get_users( 'blog_id=1&orderby=nicename&role=subscriber' );
// Array of WP_User objects.
foreach ( $blogusers as $user ) {
	//print_r($user);
	//echo '<p>' . esc_html( $user->display_name ) . '</p>';
	
	$first_name = get_field( 'country', $user->ID );
	
}?>
        
        	<div class="clearfix">
		        <? //=do_shortcode('[acps id="156"]');?>
				
				<?//=do_shortcode('[searchandfilter id="349"]'); ?>
            </div>
            <!-- <select id="sorting_control">
            	<option value="title_asc">Sort by Title (Ascending)</option>
                <option value="title_desc">Sort by Title (Descending)</option>
                <option value="country_asc">Sort by Country (Ascending)</option>
                <option value="country_desc">Sort by Country (Descending)</option>
            </select>    -->         
        </div>
        <?//=do_shortcode('[ULWPQSF id=465]');?>
        <div class="members-container clearfix">
        <?php 
			$i = 0;
			$separater = 3;
			foreach ( $members as $member ) {
<<<<<<< HEAD
		?>
				<div class="col-sm-4 committee-item">
					<p class="title"><?=$member->display_name; ?></p>
				</div>
		<?
				if( ($i+1)%$separater == 0){
					echo '<div class="clearfix"></div>';
				}
				$i++;
			}
			/*while ( $results->have_posts() ) : $results->the_post(); 
				$member_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );				
				//$member_countries = wp_get_object_terms( $post->ID, 'country');
				//$member_countries = get_field('country', $post->ID);
				$member_countries = get_the_terms($post->ID, 'country');*/
		?>
            	<!-- <div class="col-sm-4 committee-item">
                    <img src="<?=($member_image?$member_image[0]:get_template_directory().'/assets/img/profile-dummy.jpg')?>" alt="" class="img-responsive" />
=======
				$member_countries = get_the_terms($post->ID, 'country');
				$member_image = wp_get_attachment_image_src(do_shortcode('[user_meta user_id='.$member->ID.' key="user_image"]'), 'full' );
		?>
				<div class="col-sm-4 committee-item">
                    <img src="<?=($member_image?$member_image[0]:get_template_directory_uri().'/assets/img/profile-dummy.jpg')?>" alt="" class="img-responsive" />
>>>>>>> 701f04be6ea7e90a9497a736c5e0cbd7c3196a40
                    <div class="committee-content">
                        <p class="title"><?=$member->display_name; ?></p>
						<p class="position"><?=$countries[do_shortcode('[user_meta user_id='.$member->ID.' key="user_country"]')]?></p>
                    </div>
<<<<<<< HEAD
                </div> -->
		<?php 
			/*if( ($i+1)%$separater == 0){
				echo '<div class="clearfix"></div>';
			}
			
			$i++;
			endwhile; */?>        
=======
                </div>
		<?
				if( ($i+1)%$separater == 0){
					echo '<div class="clearfix"></div>';
				}
				$i++;
			}
		?>      
>>>>>>> 701f04be6ea7e90a9497a736c5e0cbd7c3196a40
        </div>
        <?php
		  if (function_exists(custom_pagination)) {
			custom_pagination($results->max_num_pages,"",$paged);
		  }
		?>
    </div>
</div>

<? 
	$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
?>
<script>
	
	var full_url = 'http://<?=$_SERVER['HTTP_HOST']; ?>'+'<?=$uri_parts[0];?>';
</script>