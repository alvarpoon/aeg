<?php
/**
* Plugin Name: Profile Builder - List user meta with a shortcode 
* Plugin URI: http://www.cozmsolabs.com
* Description: List a user meta using a shortcode. Usage: [user_meta user_id=1 key="custom_field_12"]. Other paramenters: wpautop="on" size="50" (size is for the key="avatar", wpautop is for textareas or wysiwyg fields)
* Version: 1.2
* Author: Cozmoslabs
* Author URI: http:/www.cozmoslabs.com
* License: GPL2
*/
/* Copyright Cozmoslabs.com 
 
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

/*
 * List user meta using a shortcode
 */
add_shortcode('user_meta', 'user_meta_shortcode_handler');
/**
 * User Meta Shortcode handler
 * Retrieve the value of a property or meta key from the users and usermeta tables.
 * usage: [user_meta user_id=1 key="first_name" size="50" wpautop="on" pre="Pre Label " post="Post Label "]
 * @param  array $atts
 * @param  string $content
 * @return stirng
 */
function user_meta_shortcode_handler($atts,$content=null){

	if ( !isset( $atts['user_id'] ) ){
		$user = wp_get_current_user();
		$atts['user_id'] = $user->ID;
	}
	if ( !isset( $atts['size'] ) ){
		$atts['size'] = '50';
	}


	$user = new WP_User($atts['user_id']);

	if ( !$user->exists() ) return;

	if( $atts['key'] == 'avatar'){
		return $atts['pre'] . get_avatar( $user->ID, $atts['size']) . $atts['post'] ;
	}
	if ( $user->has_prop( $atts['key'] ) ){
		if ($atts['wpautop'] == 'on'){
			$value = wpautop( $user->get( $atts['key'] ) );
		} else {
			$value = $user->get( $atts['key'] );
		}
	}
	
	if (!empty( $value )){
		return $atts['pre'] . $value . $atts['post'] ;
	}

	return;
}