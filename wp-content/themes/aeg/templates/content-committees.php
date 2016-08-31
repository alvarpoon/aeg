<?
	/*$args = array(
		'type'                     => 'post',
		'orderby'                  => 'id',
		'order'                    => 'asc',
		'hide_empty'               => 1,
		'hierarchical'             => 0,
		'taxonomy'                 => 'type'
	);
	$types = get_categories( $args );*/
	
    //get the options of the user field 'category'
	$field_key = "field_56efbbce6d36e";
    $types = get_field_object($field_key);
?>
<div class="container">
	<div class="row">
    	<div class="page-banner-container">
	    	<img src="<?=get_stylesheet_directory_uri()?>/assets/img/committees/img_committees_banner.png" alt="" />
            <h1><?=the_title();?></h1>
        </div>
        <?//=do_shortcode('[acps id="156"]');?>
        <?
			foreach ($types['choices'] as $type => $type_name){
                if($type_name != "Member"){
                    $args = array(                      
                        'meta_query' => array(
                        'relation' => 'OR',
                            array(
                                'key' => 'category',
                                'value' => $type_name,
                                'compare' => 'LIKE'
                            )
                        )
                    );
                    $user_query = new WP_User_Query( $args );
                    if ( ! empty( $user_query->results ) ) {
        ?>
                <div class="expandable-container <? if($type_name == "Steering Committee") {echo "steering-committee";} ?> clearfix">
                    <div class="expandable-header <? if($type_name == "Steering Committee") {echo "open";}?>">
                        <?=$type_name?>
                        <i class="fa fa-chevron-up"></i>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <div class="expandable-content ">
                    	<div class="expandable-content-wrapper clearfix">
						<?	
                            /*$args= array(
                                'post_type' => 'committee',
                                'tax_query' => array(
                                                  array(
                                                    'taxonomy' => 'type',
                                                    'field'    => 'slug',
                                                    'terms'    => $type->slug,
                                                    'include_children' => false
                                                  )
                                                ),
                                'post_status' 		=> 'publish',
                                'orderby'			=> 'menu_order',
                                'order' 			=> 'ASC',
                                'numberposts' 		=> -1
                            );
                            $results = get_posts( $args );
                            $size = sizeof($results); */

                        ?>
                            <?
                                $keys = 0;
                                foreach ( $user_query->results as $user ) {
                                //$member_image = wp_get_attachment_image_src( get_post_thumbnail_id( $result->ID ), 'full' );
                                $member_image = wp_get_attachment_image_src(do_shortcode('[user_meta user_id='.$user->ID.' key="user_image"]'), 'full' );
                            ?>
<<<<<<< HEAD
                                <div class="<? if($type->cat_ID == 3) { echo "col-sm-6"; }else{ echo "col-sm-4"; } ?>  committee-item">
                                    <img src="<?=($member_image?$member_image[0]:get_template_directory().'/assets/img/profile-dummy.jpg')?>" alt="" class="img-responsive" />
=======
                                <div class="<? if($type_name == "Steering Committee") { echo "col-sm-6"; }else{ echo "col-sm-4"; } ?>  committee-item">
                                    <img src="<?=($member_image?$member_image[0]:get_template_directory_uri().'/assets/img/profile-dummy.jpg')?>" alt="" class="img-responsive" />
>>>>>>> 701f04be6ea7e90a9497a736c5e0cbd7c3196a40
                                    <div class="committee-content">
                                        <p class="title"><?=$user->display_name; ?></p>
                                        <p class="position"><?=do_shortcode('[user_meta user_id='.$user->ID.' key="user_position"]')?></p>
                                        <a href="#committee_popup" class="btn-detail various" data-code="<?=$user->ID;?>">Details</a>
                                    </div>
                                </div>
                                
                            <? 
                                if($type_name == "Steering Committee"){
                                    $separater = 2;
                                }else{
                                    $separater = 3;
                                }
                                if( ($keys+1)%$separater == 0){
                                    echo '<div class="clearfix"></div>';
                                }
                                $keys++;
                            } ?>
                    	</div>
                	</div>
                </div>
		    <? 
                    }
                }
            } 
            ?>
    </div>
</div>

<div id="committee_popup"></div>
