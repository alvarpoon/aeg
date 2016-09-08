<?	
	$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
	
	$countries = array("AF"=>"Afghanistan","AX"=>"Aland Islands","AL"=>"Albania","DZ"=>"Algeria","AS"=>"American Samoa","AD"=>"Andorra","AO"=>"Angola","AI"=>"Anguilla","AQ"=>"Antarctica","AG"=>"Antigua and Barbuda","AR"=>"Argentina","AM"=>"Armenia","AW"=>"Aruba","AU"=>"Australia","AT"=>"Austria","AZ"=>"Azerbaijan","BS"=>"Bahamas","BH"=>"Bahrain","BD"=>"Bangladesh","BB"=>"Barbados","BY"=>"Belarus","BE"=>"Belgium","BZ"=>"Belize","BJ"=>"Benin","BM"=>"Bermuda","BT"=>"Bhutan","BO"=>"Bolivia","BQ"=>"Bonaire, Saint Eustatius and Saba","BA"=>"Bosnia and Herzegovina","BW"=>"Botswana","BV"=>"Bouvet Island","BR"=>"Brazil","IO"=>"British Indian Ocean Territory","VG"=>"British Virgin Islands","BN"=>"Brunei","BG"=>"Bulgaria","BF"=>"Burkina Faso","BI"=>"Burundi","KH"=>"Cambodia","CM"=>"Cameroon","CA"=>"Canada","CV"=>"Cape Verde","KY"=>"Cayman Islands","CF"=>"Central African Republic","TD"=>"Chad","CL"=>"Chile","CN"=>"China","CX"=>"Christmas Island","CC"=>"Cocos Islands","CO"=>"Colombia","KM"=>"Comoros","CK"=>"Cook Islands","CR"=>"Costa Rica","HR"=>"Croatia","CU"=>"Cuba","CW"=>"Curacao","CY"=>"Cyprus","CZ"=>"Czech Republic","CD"=>"Democratic Republic of the Congo","DK"=>"Denmark","DJ"=>"Djibouti","DM"=>"Dominica","DO"=>"Dominican Republic","TL"=>"East Timor","EC"=>"Ecuador","EG"=>"Egypt","SV"=>"El Salvador","GQ"=>"Equatorial Guinea","ER"=>"Eritrea","EE"=>"Estonia","ET"=>"Ethiopia","FK"=>"Falkland Islands","FO"=>"Faroe Islands","FJ"=>"Fiji","FI"=>"Finland","FR"=>"France","GF"=>"French Guiana","PF"=>"French Polynesia","TF"=>"French Southern Territories","GA"=>"Gabon","GM"=>"Gambia","GE"=>"Georgia","DE"=>"Germany","GH"=>"Ghana","GI"=>"Gibraltar","GR"=>"Greece","GL"=>"Greenland","GD"=>"Grenada","GP"=>"Guadeloupe","GU"=>"Guam","GT"=>"Guatemala","GG"=>"Guernsey","GN"=>"Guinea","GW"=>"Guinea-Bissau","GY"=>"Guyana","HT"=>"Haiti","HM"=>"Heard Island and McDonald Islands","HN"=>"Honduras","HK"=>"Hong Kong","HU"=>"Hungary","IS"=>"Iceland","IN"=>"India","ID"=>"Indonesia","IR"=>"Iran","IQ"=>"Iraq","IE"=>"Ireland","IM"=>"Isle of Man","IL"=>"Israel","IT"=>"Italy","CI"=>"Ivory Coast","JM"=>"Jamaica","JP"=>"Japan","JE"=>"Jersey","JO"=>"Jordan","KZ"=>"Kazakhstan","KE"=>"Kenya","KI"=>"Kiribati","XK"=>"Kosovo","KW"=>"Kuwait","KG"=>"Kyrgyzstan","LA"=>"Laos","LV"=>"Latvia","LB"=>"Lebanon","LS"=>"Lesotho","LR"=>"Liberia","LY"=>"Libya","LI"=>"Liechtenstein","LT"=>"Lithuania","LU"=>"Luxembourg","MO"=>"Macao","MK"=>"Macedonia","MG"=>"Madagascar","MW"=>"Malawi","MY"=>"Malaysia","MV"=>"Maldives","ML"=>"Mali","MT"=>"Malta","MH"=>"Marshall Islands","MQ"=>"Martinique","MR"=>"Mauritania","MU"=>"Mauritius","YT"=>"Mayotte","MX"=>"Mexico","FM"=>"Micronesia","MD"=>"Moldova","MC"=>"Monaco","MN"=>"Mongolia","ME"=>"Montenegro","MS"=>"Montserrat","MA"=>"Morocco","MZ"=>"Mozambique","MM"=>"Myanmar","NA"=>"Namibia","NR"=>"Nauru","NP"=>"Nepal","NL"=>"Netherlands","NC"=>"New Caledonia","NZ"=>"New Zealand","NI"=>"Nicaragua","NE"=>"Niger","NG"=>"Nigeria","NU"=>"Niue","NF"=>"Norfolk Island","KP"=>"North Korea","MP"=>"Northern Mariana Islands","NO"=>"Norway","OM"=>"Oman","PK"=>"Pakistan","PW"=>"Palau","PS"=>"Palestinian Territory","PA"=>"Panama","PG"=>"Papua New Guinea","PY"=>"Paraguay","PE"=>"Peru","PH"=>"Philippines","PN"=>"Pitcairn","PL"=>"Poland","PT"=>"Portugal","PR"=>"Puerto Rico","QA"=>"Qatar","CG"=>"Republic of the Congo","RE"=>"Reunion","RO"=>"Romania","RU"=>"Russia","RW"=>"Rwanda","BL"=>"Saint Barthelemy","SH"=>"Saint Helena","KN"=>"Saint Kitts and Nevis","LC"=>"Saint Lucia","MF"=>"Saint Martin","PM"=>"Saint Pierre and Miquelon","VC"=>"Saint Vincent and the Grenadines","WS"=>"Samoa","SM"=>"San Marino","ST"=>"Sao Tome and Principe","SA"=>"Saudi Arabia","SN"=>"Senegal","RS"=>"Serbia","SC"=>"Seychelles","SL"=>"Sierra Leone","SG"=>"Singapore","SX"=>"Sint Maarten","SK"=>"Slovakia","SI"=>"Slovenia","SB"=>"Solomon Islands","SO"=>"Somalia","ZA"=>"South Africa","GS"=>"South Georgia and the South Sandwich Islands","KR"=>"South Korea","SS"=>"South Sudan","ES"=>"Spain","LK"=>"Sri Lanka","SD"=>"Sudan","SR"=>"Suriname","SJ"=>"Svalbard and Jan Mayen","SZ"=>"Swaziland","SE"=>"Sweden","CH"=>"Switzerland","SY"=>"Syria","TW"=>"Taiwan","TJ"=>"Tajikistan","TZ"=>"Tanzania","TH"=>"Thailand","TG"=>"Togo","TK"=>"Tokelau","TO"=>"Tonga","TT"=>"Trinidad and Tobago","TN"=>"Tunisia","TR"=>"Turkey","TM"=>"Turkmenistan","TC"=>"Turks and Caicos Islands","TV"=>"Tuvalu","VI"=>"U.S. Virgin Islands","UG"=>"Uganda","UA"=>"Ukraine","AE"=>"United Arab Emirates","GB"=>"United Kingdom","US"=>"United States","UM"=>"United States Minor Outlying Islands","UY"=>"Uruguay","UZ"=>"Uzbekistan","VU"=>"Vanuatu","VA"=>"Vatican","VE"=>"Venezuela","VN"=>"Vietnam","WF"=>"Wallis and Futuna","EH"=>"Western Sahara","YE"=>"Yemen","ZM"=>"Zambia","ZW"=>"Zimbabwe");

	if( isset($_GET['sorting']) ){
		$sort_order = $_GET['sorting'];
	}else{
		$sort_order = '';
	}
	
	if( isset($_GET['search_word']) ){
		$search_string = esc_attr(trim($_GET['search_word']));
	}else{
		$search_string = '';
	}
	
	//echo $search_string;
	//$search_string = esc_attr( trim( get_query_var('s') ) );	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	if($search_string != ''){
		switch ($sort_order) {
			case "title_asc":
				$args = array(
					'orderby' => 'display_name',
					'order' => 'ASC',
					
					'search'         => "*{$search_string}*",
					'search_columns' => array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
						'user_country'
					),
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'relation' => 'OR',
							array(
								'key'     => 'first_name',
								'value'   => $search_string,
								'compare' => 'LIKE'
							),
							array(
								'key'     => 'last_name',
								'value'   => $search_string,
								'compare' => 'LIKE'
							),
							array(
								'key'     => 'country',
								'value'   => $search_string,
								'compare' => 'LIKE'
							)
						),
						array(
							'key' => 'category',
							'value' => "Member",
							'compare' => 'LIKE'
						)
					)
				);
				break;
			case "title_asc":
				$args = array(
					'orderby' => 'display_name',
					'order' => 'DESC',
					'search'         => "*{$search_string}*",
					'search_columns' => array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
						'user_country'
					),
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'relation' => 'OR',
							array(
								'key'     => 'first_name',
								'value'   => $search_string,
								'compare' => 'LIKE'
							),
							array(
								'key'     => 'last_name',
								'value'   => $search_string,
								'compare' => 'LIKE'
							),
							array(
								'key'     => 'country',
								'value'   => $search_string,
								'compare' => 'LIKE'
							)
						),
						array(
							'key' => 'category',
							'value' => "Member",
							'compare' => 'LIKE'
						)
					)
				);
				break;
			default:
				$args = array(
					'orderby' => 'display_name',
					'order' => 'ASC',
					'fields' => 'all',
					'search'         => "*{$search_string}*",
					'search_columns' => array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
						'user_country'
					),
					'meta_query' => array(
						/*'relation' => 'AND',
						array(
							'relation' => 'OR',
							array(
								'key'     => 'first_name',
								'value'   => $search_string,
								'compare' => 'LIKE'
							),
							array(
								'key'     => 'last_name',
								'value'   => $search_string,
								'compare' => 'LIKE'
							)
						),
						array(*/
							'key' => 'category',
							'value' => "Member",
							'compare' => 'LIKE'
						//)
					)
				);
				break;
		}
	}else{
		switch ($sort_order) {
			case "title_asc":
				$args = array(
					'orderby' => 'display_name',
					'order' => 'ASC',
					'meta_query' => array(
						//'relation' => 'OR',
						array(
							'key' => 'category',
							'value' => "Member",
							'compare' => 'LIKE'
						)
					),
					'numberposts' => -1,
				);
				break;
			case "title_desc":
				$args = array(
					'orderby' => 'display_name',
					'order' => 'DESC',
					'meta_query' => array(
						//'relation' => 'OR',
						array(
							'key' => 'category',
							'value' => "Member",
							'compare' => 'LIKE'
						)
					),
					'numberposts' => -1,
				);
				break;
			default:
				$args = array(
					'orderby' => 'display_name',
					'order' => 'ASC',
					'meta_query' => array(
						//'relation' => 'OR',
						array(
							'key' => 'category',
							'value' => "Member",
							'compare' => 'LIKE'
						)
					),
					'numberposts' => -1,
				);
		}
	}
	$user_query = new WP_User_Query( $args );
	//$members = $user_query->results;
	$members = $user_query->get_results();
	//echo count($members);
?>
<div class="container">
	<div class="row">
    	<div class="page-banner-container">
	    	<img src="<?=get_stylesheet_directory_uri()?>/assets/img/committees/img_committees_banner.png" alt="" />
            <h1><?=the_title();?></h1>
        </div>
        <div class="member-search-container clearfix">
            <div class="col-sm-6 noPadding">
                <select id="sorting_control">
                    <option value="title_asc">Sort by Title (Ascending)</option>
                    <option value="title_desc">Sort by Title (Descending)</option>
                </select>
            </div>
            <div class="col-sm-6 noPadding" style="text-align:right;">
                <form action="http://<?=$_SERVER['HTTP_HOST']; ?><?=$uri_parts[0];?>" method="GET">
                  <input type="text" name="search_word" value="" class="member_search_input" placeholder="Search …" />
                  <input type="submit" value="Submit" class="btn_member_search"/> 
                </form>
            </div>
        </div>
        <div class="members-container clearfix">
        <?php 
			$i = 0;
			$separater = 3;
			if(!empty($members)){
				if($search_string != ''){
					echo '<p>Search Results for <span class="italic">'.$search_string.'</span></p>';
				}
				
				foreach ( $members as $member ) {
					$member_countries = get_the_terms($post->ID, 'country');
					$member_image = wp_get_attachment_image_src(do_shortcode('[user_meta user_id='.$member->ID.' key="user_image"]'), 'full' );
			?>
					<div class="col-sm-4 committee-item">
						<img src="<?=($member_image?$member_image[0]:get_template_directory_uri().'/assets/img/profile-dummy.jpg')?>" alt="" class="img-responsive" />
						<div class="committee-content">
							<p class="title"><?=$member->display_name; ?></p>
							<p class="position"><?=$countries[do_shortcode('[user_meta user_id='.$member->ID.' key="user_country"]')]?></p>
						</div>
					</div>
			<?
					if( ($i+1)%$separater == 0){
						echo '<div class="clearfix"></div>';
					}
					$i++;
				}
			}else{
				echo '<p>No member found.</p>';
			}
		?>      
        </div>
        <?php
		  if (function_exists(custom_pagination)) {
			custom_pagination($results->max_num_pages,"",$paged);
		  }
		?>
    </div>
</div>

<script>
	
	var full_url = 'http://<?=$_SERVER['HTTP_HOST']; ?>'+'<?=$uri_parts[0];?>';
</script>