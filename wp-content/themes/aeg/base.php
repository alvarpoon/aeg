<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

  <!--[if lt IE 8]>
    <div class="alert alert-warning">
      <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
    </div>
  <![endif]-->

  <?php
    do_action('get_header');
    get_template_part('templates/header-top-navbar');
  ?>

      <main class="main" role="main">
      	<!--<div class="container contact_wrapper">
			<div class="row">
                <div class="page-banner-container">
                    <img src="<?=get_stylesheet_directory_uri()?>/assets/img/banner_contact.png" alt="" />
                    <h1><?=the_title();?></h1>
                </div>
            </div>
        </div>-->
        <?php include roots_template_path(); ?>
      </main>

  <?php get_template_part('templates/footer'); ?>

</body>
</html>