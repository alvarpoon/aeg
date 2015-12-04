<header class="banner navbar navbar-default navbar-fixed-top" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="javascript:;" class="menu-label hidden-xs hidden-sm hidden-md hidden-lg" data-toggle="collapse" data-target=".navbar-collapse">menu</a>
      <!--<a class="navbar-brand" href="<?php echo home_url(); ?>/"><img src="<?=get_stylesheet_directory_uri()?>/assets/img/logo-top.png"></a>-->
    </div>
  </div>
  <div class="container">
  	<div class="row">
      <div class="nav-container">
        <nav class="collapse navbar-collapse main-menu" role="navigation">
            <?php
                //Main menu
                if (has_nav_menu('primary_navigation')) :
                  wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav', 'depth' => 0));
                endif;
    
            ?>
        </nav>
        <a href="<?=(is_user_logged_in()?wp_logout_url(get_permalink($post->ID)):wp_login_url(get_permalink($post->ID)))?>" class="login-btn"><?=(is_user_logged_in()?"logout":"login")?></a>
      </div>
  	</div>
  </div>
</header>
