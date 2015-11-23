<?php // USP Pro - Form Demos

if (!defined('ABSPATH')) die();

global $usp_advanced;
if (!$usp_advanced['submit_button']) $usp_submit = "\n" . '[usp_submit value=""]' . "\n";
else $usp_submit = '';

$shortcodes = '<a href="https://plugin-planet.com/usp-pro-shortcodes/">Learn more</a>';

$usp_form = '<p>This USP Form Demo enables visitors to submit content. '. $shortcodes .'</p>' . "\n\n" . 
'[usp_name placeholder="" label="" required="" max=""]
[usp_email placeholder="" label="" required="" max=""]
[usp_url placeholder="" label="" required="" max=""]
[usp_title placeholder="" label="" required="" max=""]
[usp_captcha placeholder="" label="" max=""]
[usp_tags placeholder="" label="" required="false" max=""]
[usp_category label="" required=""]
[usp_content placeholder="" label="" required="" max="" richtext=""]
[usp_files placeholder="" label="" required="false" method="" types=""]' . $usp_submit;


$contact_form = '<p>This USP Form Demo enables visitors to contact you via email. '. $shortcodes.'</p>' . "\n\n" . 
'[usp_name placeholder="" label="" required="" max=""]
[usp_email placeholder="" label="" required="" max=""]
[usp_url placeholder="" label="" required="" max=""]
[usp_subject placeholder="" label="" required="" max=""]
[usp_content placeholder="" label="" required="" max="" richtext=""]' . $usp_submit . "\n" . 
'<input name="usp-send-mail" value="1" type="hidden" />';


$register_form = '<p>This USP Form Demo enables visitors to register without submitting content. '. $shortcodes .'</p>' . "\n\n" . 
'[usp_name placeholder="" label="" required="" max=""]
[usp_url placeholder="" label="" required="" max=""]
[usp_captcha placeholder="" label="" max=""]
[usp_email placeholder="" label="" required="" max=""]
[usp_custom_field form="register" id="1"]
[usp_custom_field form="register" id="2"]
[usp_custom_field form="register" id="3"]
[usp_custom_field form="register" id="4"]
[usp_custom_field form="register" id="5"]
[usp_custom_field form="register" id="6"]'. $usp_submit . "\n" . 
'<input name="usp-is-register" value="1" type="hidden" />';


$image_form = '<p>This USP Form Demo demonstrates how to display image previews with file uploads. '. $shortcodes .'</p>' . "\n\n" .
'[usp_title placeholder="" label="" required="" max=""]
[usp_content placeholder="" label="" required="" max="" richtext=""]
[usp_files placeholder="" label="" required="" multiple="yes" method="select" types=""]' . $usp_submit;


$classic_form = '<p>This USP Form Demo is a replica of the form that is included with the free version of the plugin. '. $shortcodes .'</p>' . "\n\n" .
'[usp_name placeholder="" label="" required="" max=""]
[usp_url placeholder="" label="" required="" max=""]
[usp_title placeholder="" label="" required="" max=""]
[usp_tags placeholder="" label="" required="" max="" tags="" size="" multiple=""]
[usp_captcha placeholder="" label="" max=""]
[usp_category label="" required="" cats="" size="" exclude="" combo=""]
[usp_content placeholder="" label="" required="" max="" richtext=""]
[usp_files placeholder="" label="" required="" method="" types=""]' . $usp_submit;
