<?php // USP Pro - Custom Login & Register

/* 
	Function: display a form that lets users login, register, and reset their password
	Usage: <?php if (function_exists('display_usp_login')) display_usp_login(); ?>
	Ref: https://digwp.com/2010/12/login-register-password-code/
*/
if (!function_exists('display_usp_login')) : 
function display_usp_login() { 
	global $current_user;
	if (isset($current_user)) {
		$user = $current_user->user_login;
		$id =  $current_user->ID;
	}
	$reset = false;
	$register = false;
	if (isset($_GET['reset']))    $reset    = (bool) sanitize_text_field($_GET['reset']);
	if (isset($_GET['register'])) $register = (bool) sanitize_text_field($_GET['register']); ?>
	
	<style>
		.usp-login #tab2_login, .usp-login #tab3_login { display: none; }
		
		.usp-login { width: 370px; overflow: hidden; margin: 50px auto; padding: 10px; color: #333; font-size: 12px; line-height: 16px; text-shadow: 0 0 1px #fff; }
		.usp-login h1 { margin: 20px 0 10px 20px; font: 400 32px/32px sans-serif; -webkit-font-smoothing: antialiased; }
		.usp-login h3 { margin: 0; border: 0 none; font-size: 14px; line-height: 14px; }
		.usp-login p { margin: 10px 0 20px 0; color: #333; }
		
		.usp-login .tabs_login { float: left; margin: 0; padding: 0; list-style-type: none; }
		.usp-login .tabs_login li { float: left; overflow: hidden; margin: 0 5px 0 0; padding: 0; }
		.usp-login .tabs_login li a { height: 30px; display: block; padding: 0 15px; line-height: 30px; text-decoration: none; border: none; color: #777; background: url(<?php echo plugins_url(); ?>/usp-pro/img/usp-login-bg-alt.png); }
		.usp-login .tabs_login li a:hover { color: #555; }
		.usp-login .tabs_login li.active_tab a { font-weight: bold; color: #333; background: url(<?php echo plugins_url(); ?>/usp-pro/img/usp-login-bg.png); }
		.usp-login .tab_container { width: 100%; float: left; margin: 0 0 20px 0; background-color: #fff; border: none; }
		.usp-login .tab_content { padding: 20px; background: url(<?php echo plugins_url(); ?>/usp-pro/img/usp-login-bg.png); }
		
		.usp-login .username, .usp-login .password, .usp-login .login_fields { width: 100%; overflow: hidden; margin: 7px 0 0 0; padding: 3px 0; }
		.usp-login .username label, .usp-login .password label { float: left; width: 80px; margin: 0; padding: 6px 0; color: #777; }
		.usp-login .username input, .usp-login .password input { float: left; width: 240px; margin: 0 0 0 3px; padding: 6px 8px; color: #777; font: 12px "Lucida Grande", "Lucida Sans Unicode", Verdana, sans-serif; }
		
		.usp-login .rememberme { margin: 0 0 15px 0; color: #777; font-size: 11px; }
		.usp-login .rememberme input { float: left; margin: 2px 7px 0 0; }
		.usp-login .user-submit { float: left; margin: 5px 0 0 0; }
		
		.usp-login .user-logged-in { width: 330px; overflow: hidden; padding: 20px; color: #333; background: url(<?php echo plugins_url(); ?>/usp-pro/img/usp-login-bg.png); }
		.usp-login .user-icon { float: left; width: 80px; padding: 20px 0 0 0; }
		.usp-login .user-icon img { width: 60px; height: 60px; box-shadow: 1px 1px 3px 0 rgba(0,0,0,0.7); }
		.usp-login .user-info { float: left; width: 240px; padding: 20px 0 0 0; }
		.usp-login .user-info p { margin: 10px 0; }
		.usp-login .user-info p:last-child { color: #777; }
		.usp-login .user-info a, .usp-login .user-info a:hover { text-decoration: none; }
		.usp-login .usp-sep { padding: 0 3px; }
	</style>
	
	<?php if (!current_user_can('read')) : ?>
	
	<script>
		jQuery(document).ready(function($) {
			$('.tab_content').hide();
			$('.tabs_login li:first').addClass('active_tab').show();
			$('.tab_content:first').show();
			$('ul.tabs_login li').click(function() {
				$('ul.tabs_login li').removeClass('active_tab');
				$(this).addClass('active_tab');
				$('.tab_content').hide();
				var activeTab = $(this).find('a').attr('href');
				$(activeTab).show();
				return false;
			});
		});
	</script>
	<div class="usp-login">
		<ul class="tabs_login">
			<li class="active_tab"><a href="#tab1_login"><?php _e('Login', 'usp'); ?></a></li>
			<li><a href="#tab2_login"><?php _e('Register', 'usp'); ?></a></li>
			<li><a href="#tab3_login"><?php _e('Forgot?', 'usp'); ?></a></li>
		</ul>
		<div class="tab_container">
			<div id="tab1_login" class="tab_content">
				
				<?php if ($register) : ?>
				
				<h3><?php _e('Success!', 'usp'); ?></h3>
				<p><?php _e('Check your email for the password.', 'usp'); ?></p>
				
				<?php elseif ($reset) : ?>
				
				<h3><?php _e('Success!', 'usp'); ?></h3>
				<p><?php _e('Check your email to reset your password.', 'usp'); ?></p>
				
				<?php else : ?>
				
				<h3><?php _e('Have an account?', 'usp'); ?></h3>
				<p><?php _e('Log in or sign up! It&rsquo;s fast &amp; free!', 'usp'); ?></p>
				
				<?php endif; ?>
				
				<form method="post" action="<?php echo home_url(); ?>/wp-login.php">
					<div class="username">
						<label for="user_login"><?php _e('Username', 'usp'); ?>: </label>
						<input type="text" name="log" value="">
					</div>
					<div class="password">
						<label for="user_pass"><?php _e('Password', 'usp'); ?>: </label>
						<input type="password" name="pwd" value="" autocomplete="off">
					</div>
					<div class="login_fields">
						<div class="rememberme">
							<label for="rememberme">
								<input type="checkbox" name="rememberme" value="forever" checked="checked" id="rememberme"> <?php _e('Remember me', 'usp'); ?>
							</label>
						</div>
						<input type="submit" name="user-submit" value="<?php _e('Login', 'usp'); ?>" class="user-submit">
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr(get_permalink()); ?>">
						<input type="hidden" name="user-cookie" value="1">
						<?php do_action('login_form'); ?>
					</div>
				</form>
			</div>
			<div id="tab2_login" class="tab_content">
				<h3><?php _e('Register for this site!', 'usp'); ?></h3>
				<p><?php _e('Sign up now for the good stuff.', 'usp'); ?></p>
				<form method="post" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>">
					<div class="username">
						<label for="user_login"><?php _e('Username', 'usp'); ?>: </label>
						<input type="text" name="user_login" value="">
					</div>
					<div class="password">
						<label for="user_email"><?php _e('Your Email', 'usp'); ?>: </label>
						<input type="text" name="user_email" value="">
					</div>
					<div class="login_fields">
						<input type="submit" name="user-submit" value="<?php _e('Sign up!', 'usp'); ?>" class="user-submit">
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr(get_permalink()); ?>?register=true">
						<input type="hidden" name="user-cookie" value="1">
						<?php do_action('register_form'); ?>
					</div>
				</form>
			</div>
			<div id="tab3_login" class="tab_content">
				<h3><?php _e('Lose something?', 'usp'); ?></h3>
				<p><?php _e('Enter your username or email to reset your password.', 'usp'); ?></p>
				<form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>">
					<div class="username">
						<label for="user_login"><?php _e('Username or Email', 'usp'); ?>: </label>
						<input type="text" name="user_login" value="">
					</div>
					<div class="login_fields">
						<input type="submit" name="user-submit" value="<?php _e('Reset my password', 'usp'); ?>" class="user-submit">
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr(get_permalink()); ?>?reset=true">
						<input type="hidden" name="user-cookie" value="1">
						<?php do_action('login_form', 'resetpass'); ?>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<?php else : ?>
	
	<div class="usp-login">
		<div class="user-logged-in">
			<h3><?php _e('Welcome, ', 'usp'); echo $user; ?></h3>
			<div class="user-icon"><?php echo get_avatar($id, 60); ?></div>
			<div class="user-info">
				<p><?php _e('Congratulations, you&rsquo;re logged in.', 'usp'); ?></p>
				<p>
					<a href="<?php echo wp_logout_url(get_permalink()); ?>"><?php _e('Log out', 'usp'); ?></a><span class="usp-sep"> | </span>
					<?php if (current_user_can('manage_options')) echo '<a href="'. admin_url() .'">'. __('Admin', 'usp') .'</a>'; 
					else echo '<a href="'. admin_url() .'profile.php">'. __('Profile', 'usp') .'</a>'; ?>
				</p>
			</div>
		</div>
	</div>
	
	<?php endif; ?>
	
<?php }
endif;



/* 
	Shortcode: shortcode displays a form that lets users login, register, and reset their password
	Usage: [usp_login_form]
	Based on: https://digwp.com/2010/12/login-register-password-code/
*/
if (!function_exists('usp_login_form_shortcode')) :
function usp_login_form_shortcode($attr, $content = null) {
	ob_start();
	display_usp_login();
	$form = ob_get_contents();
	ob_end_clean();
	return $form;
}
add_shortcode('usp_login_form', 'usp_login_form_shortcode');
endif;

