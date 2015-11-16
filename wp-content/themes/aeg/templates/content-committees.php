<?
	$args = array(
		'type'                     => 'post',
		'orderby'                  => 'id',
		'order'                    => 'asc',
		'hide_empty'               => 1,
		'hierarchical'             => 0,
		'taxonomy'                 => 'type'/*,
		'parent'      		   	   => $post->ID*/
	);
	$types = get_categories( $args );
	
	//print_r($categories);
?>
<div class="container">
	<div class="row">
    	<div class="page-banner-container">
	    	<img src="<?=get_stylesheet_directory_uri()?>/assets/img/committees/img_committees_banner.png" alt="" />
            <h1><?=the_title();?></h1>
        </div>
        <?
			foreach ($types as $type){ ?>
                <div class="expandable-container <? if($type->cat_ID == 3) {echo "steering-committee";} ?> clearfix">
                    <div class="expandable-header open">
                        <?=$type->cat_name?>
                        <i class="fa fa-chevron-up"></i>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <div class="expandable-content ">
                    	<div class="expandable-content-wrapper clearfix">
						<?	$args= array(
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
                            $size = sizeof($results); ?>
                            <? foreach( $results as $keys => $result ){ 
                                $member_image = wp_get_attachment_image_src( get_post_thumbnail_id( $result->ID ), 'full' );
                            ?>
                                <div class="<? if($type->cat_ID == 3) { echo "col-sm-6"; }else{ echo "col-sm-4"; } ?>  committee-item">
                                    <img src="<?=$member_image[0]?>" alt="" class="img-responsive" />
                                    <div class="committee-content">
                                        <p class="title"><?=$result->post_title; ?></p>
                                        <p class="position"><?=get_field("position",$result->ID)?></p>
                                        <a href="#committee_popup" class="btn-detail various" data-code="<?=$result->ID;?>">Details</a>
                                    </div>
                                </div>
                                
                            <? 
                                if($type->cat_ID == 3){
                                    $separater = 2;
                                }else{
                                    $separater = 3;
                                }
                                if( ($keys+1)%$separater == 0){
                                    echo '<div class="clearfix"></div>';
                                }
                            } ?>
                    	</div>
                	</div>
                </div>
		<? } ?>
    </div>
</div>

<div id="committee_popup"></div>