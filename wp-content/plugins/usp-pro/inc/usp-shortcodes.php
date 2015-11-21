<?php // USP Pro - Shortcodes

if (!defined('ABSPATH')) die();

if (!isset($_SESSION)) session_start();

/*
	Shortcode: Fieldset
		Displays opening/closing fieldset brackets
		Syntax: [usp_fieldset class="aaa,bbb,ccc"][...][#usp_fieldset]
		Attributes:
			class = classes comma-sep list (displayed as class="aaa bbb ccc") 
*/
if (!function_exists('usp_fieldset_open')) : 
function usp_fieldset_open($args) {
	$class = 'usp-fieldset,' . $args['class'];
	$classes = usp_classes($class);
	return '<fieldset class="'. $classes .'">'. "\n";
}
add_shortcode('usp_fieldset', 'usp_fieldset_open');
function usp_fieldset_close() { return '</fieldset>'. "\n"; }
add_shortcode('#usp_fieldset', 'usp_fieldset_close');
endif;

/*
	Shortcode: Name
	Displays name input field
	Syntax: [usp_name class="aaa,bbb,ccc" placeholder="Your Name" label="Your Name" required="yes" max="99" fieldset="true"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_name')) : 
function usp_input_name($args) {
	global $current_user;
	
	if ($current_user->ID) $value = $current_user->user_login;
	elseif (isset($_SESSION['usp_form_session']['usp-name']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-name'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-name,' . $args['class'];
	else $class = 'usp-input,usp-input-name';
	$classes = usp_classes($class, '1');
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];
	
	$field = 'usp_error_1';
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	
	$max = usp_max_att($args, '99');

	if (empty($label)) $content = '';
	else $content = '<label for="usp-name" class="usp-label usp-label-name">'. $label .'</label>'. "\n";
	
	$content .= '<input name="usp-name" type="text" value="'. $value .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	if ($required == 'true') $content .= '<input name="usp-name-required" value="1" type="hidden" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_name', 'usp_input_name');
endif;

/*
	Shortcode: URL
	Displays URL input field
	Syntax: [usp_url class="aaa,bbb,ccc" placeholder="Your URL" label="Your URL" required="yes" max="99"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_url')) : 
function usp_input_url($args) {
	if (isset($_SESSION['usp_form_session']['usp-url']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-url'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-url,' . $args['class'];
	else $class = 'usp-input,usp-input-url';
	$classes = usp_classes($class, '2');
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	$field = 'usp_error_2';
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '99');

	if (empty($label)) $content = '';
	else $content  = '<label for="usp-url" class="usp-label usp-label-url">'. $label .'</label>'. "\n";
	$content .= '<input name="usp-url" type="text" value="'. $value .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	if ($required == 'true') $content .= '<input name="usp-url-required" value="1" type="hidden" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_url', 'usp_input_url');
endif;

/*
	Shortcode: Title
	Displays title input field
	Syntax: [usp_title class="aaa,bbb,ccc" placeholder="Post Title" label="Post Title" required="yes" max="99"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_title')) : 
function usp_input_title($args) {
	if (isset($_SESSION['usp_form_session']['usp-title']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-title'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-title,' . $args['class'];
	else $class = 'usp-input,usp-input-title';
	$classes = usp_classes($class, '3');
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	$field = 'usp_error_3';
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '99');

	if (empty($label)) $content = '';
	else $content  = '<label for="usp-title" class="usp-label usp-label-title">'. $label .'</label>'. "\n";
	$content .= '<input name="usp-title" type="text" value="'. $value .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	if ($required == 'true') $content .= '<input name="usp-title-required" value="1" type="hidden" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_title', 'usp_input_title');
endif;

/*
	Shortcode: Tags
	Displays tags input field
	Syntax: [usp_tags class="aaa,bbb,ccc" placeholder="Post Tags" label="Post Tags" required="yes" max="99" tags="" size="3" multiple="no"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		tags        = specifies any default tags that should always be include with the form
		size        = specifies value for the size attribute of the select tag when using the select menu
		multiple    = specifies whether users should be allowed to select multiple tags
		exclude     = specifies any tags that should be excluded from the form (comma separated)
		custom      = any attributes or custom code
		default     = specifies the text used for the "Please select.." default option, may by any string or "null" to exclude
		type        = specifies the type of field to use for displaying the tags (dropdown, checkbox, input) (default = General > Tag settings)
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_tags')) : 
function usp_input_tags($args) {
	global $usp_general;
	if (isset($_SESSION['usp_form_session']['usp-tags']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-tags'];
	else $value = '';
	
	$display_tags = $usp_general['tags_menu'];
	if     (isset($args['type']) && $args['type'] === 'checkbox') $display_tags = 'checkbox';
	elseif (isset($args['type']) && $args['type'] === 'dropdown') $display_tags = 'dropdown';
	elseif (isset($args['type']) && $args['type'] === 'input')    $display_tags = 'input';
	
	if (isset($args['custom'])) {
		if ($display_tags == 'checkbox' || $display_tags == 'input') $custom = sanitize_text_field($args['custom']) .' ';
		else $custom = ' '. sanitize_text_field($args['custom']);
	} else {
		$custom = '';
	}
	
	$default = __('Please select..', 'usp');
	if     (isset($args['default']) && !empty($args['default']) && $args['default'] !== 'null') $default = sanitize_text_field($args['default']);
	elseif (isset($args['default']) && !empty($args['default']) && $args['default'] === 'null') $default = false;
	
	$default_html = "\n";
	if ($default) $default_html = '<option value="">'. $default .'</option>'. "\n";
	
	$multiple = ''; $brackets = '';
	
	if (isset($args['multiple']) && !empty($args['multiple'])) {
		if ($args['multiple'] == 'yes' || $args['multiple'] == 'true' || $args['multiple'] == 'on') {
			$multiple = ' multiple="multiple"';
			$brackets = '[]';
		}
	} else {
		if ($usp_general['tags_multiple']) {
			$multiple = ' multiple="multiple"';
			$brackets = '[]';
		}
	}
	
	$size = '';
	if (isset($args['size']) && !empty($args['size']) && $multiple == ' multiple="multiple"') $size = ' size="'. $args['size'] .'"';
	
	$tag_array = array();
	if (isset($usp_general['tags']) && !empty($usp_general['tags'])) $tag_array = $usp_general['tags'];
	if (empty($tag_array)) $tag_array = get_popular_tags(5);
	
	
	if (isset($args['exclude']) && !empty($args['exclude'])) {
		$exclude = trim($args['exclude']);
		$excluded = explode(",", $exclude);
		foreach($excluded as $exclude) {
			$excluded_tags[] = trim($exclude);
		}
		foreach($tag_array as $key => $val) {
			if (in_array($val, $excluded_tags)) unset($tag_array[$key]);
		}
	}
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-tags,' . $args['class'];
	else $class = 'usp-input,usp-input-tags';
	$classes = usp_classes($class, '4');
	
	if (isset($args['tags'])) $tags = usp_tags($args['tags']);
	else $tags = '';
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	$field = 'usp_error_4';
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '99');
	
	if (isset($usp_general['hidden_tags']) && !empty($usp_general['hidden_tags'])) {
		$content = '';
		if (!empty($tags)) $content .= '<input name="usp-tags-default" value="'. $tags .'" type="hidden" />'. "\n";
		return $content;
	} else {
		if ($display_tags == 'checkbox') {
			if (empty($label)) $content = '';
			else $content  = '<label for="usp-tags[]" class="usp-label usp-label-tags">'. $label .'</label>'. "\n";
			foreach ((array) $tag_array as $tag) {
				$the_tag = get_term_by('id', $tag, 'post_tag');
				if (!$the_tag) continue;
				$checked = '';
				if (is_array($value)) {
					if (in_array($tag, $value)) $checked = ' checked';
				}
				$content .= '<span class="usp-checkbox usp-tag"><input type="checkbox" name="usp-tags[]" value="'. $tag .'" data-required="'. $required .'" class="'. $classes .'"'. $checked .' '. $custom .'/> '. sanitize_text_field($the_tag->name) .'</span>' . "\n";
			}
		} elseif ($display_tags == 'input') {
			if (empty($label)) $content = '';
			else $content  = '<label for="usp-tags" class="usp-label usp-label-tags">'. $label .'</label>'. "\n";
			$content .= '<input name="usp-tags" type="text" value="'. $value .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
		} else {
			if (empty($label)) $content = '';
			else $content  = '<label for="usp-tags'. $brackets .'" class="usp-label usp-label-tags">'. $label .'</label>'. "\n";
			$content .= '<select name="usp-tags'. $brackets .'" '. $parsley .'data-required="'. $required .'"'. $size . $multiple .' class="'. $classes .' usp-select"'. $custom .'>'. "\n";
			$content .= $default_html;
			foreach ((array) $tag_array as $tag) {
				$the_tag = get_term_by('id', $tag, 'post_tag');
				if (!$the_tag) continue;
				$selected = '';
				if (is_array($value)) {
					foreach ($value as $val) {
						if (intval($tag) === intval($val)) $selected = ' selected';
					}
				} else {
					if (intval($tag) === intval($value)) $selected = ' selected';
				}
				$content .= '<option value="'. $the_tag->term_id .'"'. $selected .'>'. sanitize_text_field($the_tag->name) .'</option>'. "\n";
			}
			$content .= '</select>'. "\n";
		}
		if ($required == 'true') $content .= '<input name="usp-tags-required" value="1" type="hidden" />'. "\n";
		if (!empty($tags)) $content .= '<input name="usp-tags-default" value="'. $tags .'" type="hidden" />'. "\n";
		return $fieldset_before . $content . $fieldset_after;
	}
}
add_shortcode('usp_tags', 'usp_input_tags');
endif;

/*
	Shortcode: Captcha
	Displays captcha input field
	Syntax: [usp_captcha class="aaa,bbb,ccc" placeholder="Antispam Question" label="Antispam Question" max="99"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_captcha')) : 
function usp_input_captcha($args) {
	global $usp_general;
	$required = 'true'; // always required when included in form
	if (isset($_SESSION['usp_form_session']['usp-captcha']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-captcha'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-captcha,' . $args['class'];
	else $class = 'usp-input,usp-input-captcha';
	$classes = usp_classes($class, '5');

	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	$field = 'captcha_question'; // overrides usp_error_5
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	$max = usp_max_att($args, '99');
	
	$recaptcha_public = $usp_general['recaptcha_public'];
	$recaptcha_private = $usp_general['recaptcha_private'];
	if ((isset($recaptcha_public) && !empty($recaptcha_public)) && (isset($recaptcha_private) && !empty($recaptcha_private))) {
		$captcha = '<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k='. $recaptcha_public .'"></script>
		<noscript>
			<iframe src="http://www.google.com/recaptcha/api/noscript?k='. $recaptcha_public .'" height="300" width="500" frameborder="0"></iframe><br>
			<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
			<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
		</noscript>' . "\n";
	} else {
		$captcha = '<input name="usp-captcha" type="text" value="'. $value .'" data-required="true" required="required" maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	}
	if (empty($label)) $content = '';
	else $content  = '<label for="usp-captcha" class="usp-label usp-label-captcha">'. $label .'</label>'. "\n";
	if ($required == 'true') $required = '<input name="usp-captcha-required" value="1" type="hidden" />'. "\n";
	return $fieldset_before . $content . $captcha . $required . $fieldset_after;
}
add_shortcode('usp_captcha', 'usp_input_captcha');
endif;

/*
	Shortcode: Category
	Displays category input field
	Syntax: [usp_combo id="" class="aaa,bbb,ccc" label="Post Category" required="yes" cats="" size="3" multiple="no" exclude="" custom=""]
	Attributes:
		class       = comma-sep list of classes
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		cats        = specifies any default cats that should always be include with the form (comma separated)
		size        = specifies value for the size attribute of the select tag when using the select menu
		multiple    = specifies whether users should be allowed to select multiple categories
		exclude     = specifies any cats that should be excluded from the form (comma separated)
		custom      = any attributes or custom code
		combo       = a unique id if using chained/combo fields (must be either 1, 2, or 3)
		default     = specifies the text used for the "Please select.." default option, may by any string or "null" to exclude
		type        = specifies the type of field to use for displaying the categories (dropdown or checkbox) (default = General > Category settings)
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_category')) : 
function usp_input_category($args) {
	global $usp_general;
	
	$id = ''; 
	$value = ''; 
	$name = 'usp-category';
	if (isset($args['combo']) && is_numeric($args['combo'])) {
		$id = $args['combo'];
		if (!empty($id)) {
			$name = 'usp-cat-combo-'. $id;
			if (isset($_COOKIE['remember'])) {
				if (isset($_SESSION['usp_form_session']['usp-cat-combo-'. $id])) $value = $_SESSION['usp_form_session']['usp-cat-combo-'. $id];
			}
		}
	} else {
		if (isset($_COOKIE['remember'])) {
			if (isset($_SESSION['usp_form_session']['usp-category'])) $value = $_SESSION['usp_form_session']['usp-category'];
		}
	}
	
	$display_cats = $usp_general['cats_menu'];
	if     (isset($args['type']) && $args['type'] === 'checkbox') $display_cats = 'checkbox';
	elseif (isset($args['type']) && $args['type'] === 'dropdown') $display_cats = 'dropdown';
	
	if (isset($args['custom'])) {
		if ($display_cats === 'checkbox') $custom = sanitize_text_field($args['custom']) .' ';
		else $custom = ' '. sanitize_text_field($args['custom']);
	} else {
		$custom = '';
	}
	
	$multiple = ''; $brackets = '';
	if (isset($args['multiple']) && !empty($args['multiple'])) {
		if ($args['multiple'] == 'yes' || $args['multiple'] == 'true' || $args['multiple'] == 'on') {
			$multiple = ' multiple="multiple"';
			$brackets = '[]';
		}
	} else {
		if ($usp_general['cats_multiple']) {
			$multiple = ' multiple="multiple"';
			$brackets = '[]';
		}
	}
	
	$default = __('Please select..', 'usp');
	if     (isset($args['default']) && !empty($args['default']) && $args['default'] !== 'null') $default = sanitize_text_field($args['default']);
	elseif (isset($args['default']) && !empty($args['default']) && $args['default'] === 'null') $default = false;
	
	$default_html = "\n";
	if ($default) $default_html = '<option value="">'. $default .'</option>'. "\n";
	
	if (isset($args['size']) && !empty($args['size']) && $multiple == ' multiple="multiple"') $size = ' size="'. $args['size'] .'"';
	else $size = '';
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-category,' . $args['class'];
	else $class = 'usp-input,usp-input-category';
	$classes = usp_classes($class, '6');
	
	if (isset($args['cats'])) $cats = usp_cats($args['cats']);
	else $cats = '';
	$categories = usp_get_cats();
	
	if (isset($args['exclude']) && !empty($args['exclude'])) {
		$exclude = trim($args['exclude']);
		$excluded = explode(",", $exclude);
		foreach($excluded as $exclude) {
			$excluded_cats[] = trim($exclude);
		}
		foreach($categories as $key => $val) {
			foreach($val as $k => $v) {
				if (in_array($v, $excluded_cats)) unset($categories[$key]);
			}
		}
	}
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	$field = 'usp_error_6';
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	
	if (isset($usp_general['hidden_cats']) && !empty($usp_general['hidden_cats'])) {
		$content = '';
		if (!empty($cats)) $content .= '<input name="usp-cats-default" value="'. $cats .'" type="hidden" />'. "\n";
		return $content;
	} else {
		if ($display_cats == 'checkbox') {
			if (empty($label)) $content = '';
			else $content  = '<label for="'. $name .'[]" class="usp-label usp-label-category">'. $label .'</label>'. "\n";

			if (isset($usp_general['cats_nested']) && !empty($usp_general['cats_nested'])) {
				$content .= '<style type="text/css">';
				$content .= '.usp-cat { display: block; }';
				$content .= '.usp-cat-0 { margin-left: 0; } .usp-cat-1 { margin-left: 20px; } .usp-cat-2 { margin-left: 40px; } .usp-cat-3 { margin-left: 60px; } .usp-cat-4 { margin-left: 80px; }';
				$content .= '</style>'. "\n";
			}
			foreach ($categories as $cat) {
				$category = get_category($cat['id']);
				if (!$category) continue;
				$checked = '';
				if (is_array($value)) {
					if (in_array($cat['id'], $value)) $checked = ' checked';
				}
				$level = 'na';
				$cat_level = $cat['level'];
				if ($cat_level == 'parent') $level = '0';
				elseif ($cat_level == 'child') $level = '1';
				elseif ($cat_level == 'grandchild') $level = '2';
				elseif ($cat_level == 'great_grandchild') $level = '3';
				elseif ($cat_level == 'great_great_grandchild') $level = '4';
				$content .= '<span class="usp-checkbox usp-cat usp-cat-'. $level .'"><input type="checkbox" name="'. $name .'[]" value="'. $cat['id'] .'" data-required="'. $required .'" class="'. $classes .'"'. $checked .' '. $custom .'/> '. get_cat_name($cat['id']) .'</span>' . "\n";
			}
		} else {
			if (empty($label)) $content = '';
			else $content  = '<label for="'. $name . $brackets .'" class="usp-label usp-label-category">'. $label .'</label>'. "\n";

			$content .= '<select name="'. $name . $brackets .'" '. $parsley .'data-required="'. $required .'"'. $size . $multiple .' class="'. $classes .' usp-select"'. $custom .'>'. "\n";
			$content .= $default_html;
			
			foreach ($categories as $cat) {
				$category = get_category($cat['id']);
				if (!$category) continue;
				$selected = '';
				if (is_array($value)) {
					foreach ($value as $val) {
						if (intval($cat['id']) === intval($val)) $selected = ' selected';
					}
				} else {
					if (intval($cat['id']) === intval($value)) $selected = ' selected';
				}
				$indent = '';
				$cat_level = $cat['level'];
				if (isset($usp_general['cats_nested']) && !empty($usp_general['cats_nested'])) {
					if ($cat_level == 'parent') $indent = '';
					elseif ($cat_level == 'child') $indent = '&emsp;';
					elseif ($cat_level == 'grandchild') $indent = '&emsp;&emsp;';
					elseif ($cat_level == 'great_grandchild') $indent = '&emsp;&emsp;&emsp;';
					elseif ($cat_level == 'great_great_grandchild') $indent = '&emsp;&emsp;&emsp;&emsp;';
				}
				$content .= '<option value="'. $cat['id'] .'"'. $selected .'>'. $indent . get_cat_name($cat['id']) .'</option>'. "\n";
			}
			$content .= '</select>'. "\n";
		}
		if ($required == 'true') $content .= '<input name="'. $name .'-required" value="1" type="hidden" />'. "\n";
		if (!empty($cats)) $content .= '<input name="usp-cats-default" value="'. $cats .'" type="hidden" />'. "\n";
		return $fieldset_before . $content . $fieldset_after;
	}
}
add_shortcode('usp_category', 'usp_input_category');
endif;

/*
	Shortcode: Taxonomy
	Displays taxonomy input field
	Syntax: [usp_taxonomy class="aaa,bbb,ccc" label="Post Taxonomy" required="yes" tax="" size="3" multiple="no" terms="123,456,789" type="checkbox"]
	Attributes:
		class       = comma-sep list of classes
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		tax         = specifies the taxonomy
		size        = specifies value for the size attribute of the select tag when using the select menu
		multiple    = specifies whether users should be allowed to select multiple terms
		terms       = specifies which tax terms to include (comma-separated list of term IDs) OR specifies to include all terms via "all" value
		type        = specifies the type of field to display (checkbox or dropdown)
		exclude     = specifies any cats that should be excluded from the form (comma separated)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
		default     = specifies the text used for the "Please select.." default option, may by any string or "null" to exclude
*/
if (!function_exists('usp_input_taxonomy')) : 
function usp_input_taxonomy($args) {

	$taxonomy = 'undefined';
	if (isset($args['tax'])) $taxonomy = $args['tax'];
	
	$type = 'dropdown';
	if (isset($args['type'])) $type = $args['type'];
	
	if (isset($args['custom'])) {
		if ($type == 'checkbox') $custom = sanitize_text_field($args['custom']) .' ';
		else $custom = ' '. sanitize_text_field($args['custom']);
	} else {
		$custom = '';
	}
	
	$value = '';
	if (isset($_SESSION['usp_form_session']) && isset($_COOKIE['remember'])) {
		foreach($_SESSION['usp_form_session'] as $session_key => $session_value) {
			if (preg_match("/^usp-taxonomy-$taxonomy$/i", $session_key, $match)) {
				$value = $session_value;
			}
		}
	}

	$multiple = ''; $brackets = '';
	if (isset($args['multiple']) && !empty($args['multiple'])) {
		if ($args['multiple'] == 'yes' || $args['multiple'] == 'true' || $args['multiple'] == 'on') {
			$multiple = ' multiple="multiple"';
			$brackets = '[]';
		}
	}
	
	$default = __('Please select..', 'usp');
	if     (isset($args['default']) && !empty($args['default']) && $args['default'] !== 'null') $default = sanitize_text_field($args['default']);
	elseif (isset($args['default']) && !empty($args['default']) && $args['default'] === 'null') $default = false;
	
	$default_html = "\n";
	if ($default) $default_html = '<option value="">'. $default .'</option>'. "\n";

	$size = '';
	if (isset($args['size']) && !empty($args['size']) && $multiple == ' multiple="multiple"') $size = ' size="'. $args['size'] .'"';
	
	$class = 'usp-input,usp-input-taxonomy';
	if (isset($args['class'])) $class = 'usp-input,usp-input-taxonomy,' . $args['class'];
	$classes = usp_classes($class, '14');
	
	if (isset($args['terms'])) {
		if ($args['terms'] === 'all') {
			$terms = get_terms($taxonomy);
			foreach ($terms as $term) {
				$tax_terms[] = (array) $term;
			}
		} elseif ($args['terms'] === 'all_include_empty') {
			$terms = get_terms($taxonomy, array('hide_empty' => false));
			foreach ($terms as $term) {
				$tax_terms[] = (array) $term;
			}
		} else {
			$tax_terms = array();
			$terms = trim($args['terms']);
			$terms = explode(",", $terms);
			foreach($terms as $term) {
				$term = trim($term);
				$get_term = get_term($term, $taxonomy, ARRAY_A);
				if (!is_wp_error($get_term)) {
					$term_exists = term_exists($get_term['term_id'], $taxonomy);
					if ($term_exists !== 0 && $term_exists !== null) $tax_terms[] = $get_term; 
				}
			}
		}
	}
	if (isset($args['exclude']) && !empty($args['exclude'])) {
		$exclude = trim($args['exclude']);
		$excluded = explode(",", $exclude);
		foreach($excluded as $exclude) {
			$excluded_tax[] = trim($exclude);
		}
		foreach($tax_terms as $key => $val) {
			foreach($val as $k => $v) {
				if (in_array($v, $excluded_tax)) unset($tax_terms[$key]);
			}
		}
	}
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	$field = $taxonomy;
	$label = usp_label($args, $field);
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';

	if (!empty($tax_terms)) {
		if ($type == 'checkbox') {
			if (empty($label)) $content = '';
			else $content  = '<label for="usp-taxonomy-'. $taxonomy .'[]" class="usp-label usp-label-taxonomy">'. $label .'</label>'. "\n";
			
			foreach ($tax_terms as $tax) {
				$checked = '';
				if (is_array($value)) {
					if (in_array($tax['term_id'], $value)) $checked = ' checked';
				} else {
					if (intval($tax['term_id']) === intval($value)) $checked = ' checked';
				}
				$content .= '<span class="usp-checkbox usp-tax"><input type="checkbox" name="usp-taxonomy-'. $taxonomy .'[]" value="'. $tax['term_id'] .'" data-required="'. $required .'" class="'. $classes .'"'. $checked .' '. $custom .'/> '. $tax['name'] .'</span>' . "\n";
			}
		} else {
			if (empty($label)) $content = '';
			else $content  = '<label for="usp-taxonomy-'. $taxonomy . $brackets .'" class="usp-label usp-label-taxonomy">'. $label .'</label>'. "\n";
	
			$content .= '<select name="usp-taxonomy-'. $taxonomy . $brackets .'" '. $parsley .'data-required="'. $required .'"'. $size . $multiple .' class="'. $classes .' usp-select"'. $custom .'>'. "\n";
			$content .= $default_html;
			
			foreach ($tax_terms as $tax) {
				$selected = '';
				if (is_array($value)) {
					if (in_array($tax['term_id'], $value)) $selected = ' selected';
				} else {
					if (intval($tax['term_id']) === intval($value)) $selected = ' selected';
				}
				$content .= '<option value="'. $tax['term_id'] .'"'. $selected .'>'. $tax['name'] .'</option>'. "\n";
			}
			$content .= '</select>'. "\n";
		}
		if ($required == 'true') $content .= '<input name="usp-taxonomy-'. $taxonomy .'-required" value="1" type="hidden" />'. "\n";
	} else {
		$content = 'No terms found for '. $taxonomy;
	}
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_taxonomy', 'usp_input_taxonomy');
endif;

/*
	Shortcode: Content
	Displays content textarea
	Syntax: [usp_content class="aaa,bbb,ccc" placeholder="Post Content" label="Post Content" required="yes" max="999" cols="3" rows="30" richtext="off"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		cols        = sets the number of columns for the textarea
		rows        = sets the number of rows for the textarea
		richtext    = specifies whether or not to use WP rich-text editor
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_content')) : 
function usp_input_content($args) {
	if (isset($_SESSION['usp_form_session']['usp-content']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-content'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = ' '. sanitize_text_field($args['custom']);
	else $custom = '';
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-content,' . $args['class'];
	else $class = 'usp-input,usp-input-content';
	$classes = usp_classes($class, '7');
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];
	
	$field = 'usp_error_7';
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '999');
	
	if (isset($args['cols']) && !empty($args['cols'])) $cols = trim(intval($args['cols']));
	else $cols = '30';
	
	if (isset($args['rows']) && !empty($args['rows'])) $rows = trim(intval($args['rows']));
	else $rows = '5';
	
	if (isset($args['richtext']) && !empty($args['richtext']) && ($args['richtext'] == 'on' || $args['richtext'] == 'yes' || $args['richtext'] == 'true')) $richtext = 'on';
	else $richtext = 'off';
	
	if (empty($label)) $content = '';
	else $content = '<label for="usp-content" class="usp-label usp-label-content">'. $label .'</label>'. "\n";
	if ($richtext == 'on') {
		$settings = array(
		    'wpautop'          => true,          // enable rich text editor
		    'media_buttons'    => true,          // enable add media button
		    'textarea_name'    => 'usp-content', // name
		    'textarea_rows'    => $rows,         // number of textarea rows
		    'tabindex'         => '',            // tabindex
		    'editor_css'       => '',            // extra CSS
		    'editor_class'     => $classes,      // class
		    'teeny'            => false,         // output minimal editor config
		    'dfw'              => false,         // replace fullscreen with DFW
		    'tinymce'          => true,          // enable TinyMCE
		    'quicktags'        => true,          // enable quicktags
		    'drag_drop_upload' => true,          // drag-n-drop uploads
		);
		ob_start(); // until get_wp_editor() is available..
		wp_editor($value, 'uspcontent', $settings);
		$get_wp_editor = ob_get_clean();
		$content .= $get_wp_editor;
	} else {
		$content .= '<textarea name="usp-content" rows="'. $rows .'" cols="'. $cols .'" maxlength="'. $max .'" data-required="'. $required .'" '. $parsley .'placeholder="'. $placeholder .'" class="'. $classes .'"'. $custom .'>'. $value .'</textarea>'. "\n";
	}
	if ($required == 'true') $content .= '<input name="usp-content-required" value="1" type="hidden" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_content', 'usp_input_content');
endif;

/*
	Shortcode: Files
	Displays file-upload input field
	Syntax: [usp_files class="aaa,bbb,ccc" placeholder="Upload File" label="Upload File" required="yes" max="99" link="Add another file" multiple="yes" key="single"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		link        = specifies text for the add-another input link (when displayed)
		multiple    = specifies whether to display single or multiple file input fields
		key         = key to use for custom field for this image
		types       = allowed file types (overrides global defaults)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default = true)
		preview_off = disable file preview: true
		files_min   = specifies minimum number of required files (overrides global defaults)
		files_max   = specifies maximum number of allowed files (overrides global defaults)
*/
if (!function_exists('usp_input_files')) : 
function usp_input_files($args) {
	global $usp_uploads;
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['files_min']) && is_numeric($args['files_min'])) $files_min = $args['files_min'];
	else $files_min = $usp_uploads['min_files'];
	
	if (isset($args['files_max']) && is_numeric($args['files_max'])) $files_max = $args['files_max'];
	else $files_max = $usp_uploads['max_files'];
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-files,' . $args['class'];
	else $class = 'usp-input,usp-input-files';
	$classes = usp_classes($class, '8');
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	if (isset($args['preview_off'])) $preview = '<input name="usp-file-preview" value="1" type="hidden" />'. "\n";
	else $preview = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	if (isset($args['types'])) {
		$allow_types = trim($args['types']);
		$types = explode(",", $allow_types);
		$file_types = '';
		foreach ($types as $type) $file_types .= trim($type) . ',';
		$file_types = rtrim(trim($file_types), ',');
	} else {
		$file_types = '';
	}

	$field = 'usp_error_8';
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	$required = usp_required($args);
	$max = usp_max_att($args, '255');
	
	$key = 'single';
	if (isset($args['key']) && is_numeric($args['key'])) $key = $args['key'];
	
	$multiple = true;
	if (isset($args['multiple'])) {
		if ($args['multiple'] == 'no' || $args['multiple'] == 'false' || $args['multiple'] == 'off') $multiple = false;
	}
	$method = '';
	if (isset($args['method'])) {
		if ($args['method'] == 'yes' || $args['method'] == 'true' || $args['method'] == 'on' || $args['method'] == 'select') $method = ' multiple="multiple"';
	}
	if (isset($args['link']) && !empty($args['link'])) $link = trim($args['link']);
	else $link = 'Add another file';

	if (isset($usp_uploads['max_files']) && $usp_uploads['max_files'] !== 0) {
		if ($usp_uploads['min_files'] < 1) $number_files = 1;
		else $number_files = $usp_uploads['min_files'];
		//
		$content = '';
		if ($multiple) {
			
			$content .= '<div id="usp-files" class="usp-files">'. "\n";
			
			if (empty($label)) $content .= '';
			else               $content .= '<label for="usp-files[]" class="usp-label usp-label-files">'. $label .'</label>'. "\n";
			
			$content .= '<div class="usp-input-wrap">'. "\n";
			
			if (empty($method)) {
				for ( $i = 1; $i <= $number_files; $i++ ) {
					$content .= '<input name="usp-files[]" type="file" maxlength="'. $max .'" data-required="'. $required .'" data-file="1" placeholder="'. $placeholder .'" class="'. $classes .' add-another multiple" '. $custom .'/>'. "\n";
				}
				$content .= '<div class="usp-add-another"><a href="#">'. $link .'</a></div>'. "\n";
			} else {
				$content .= '<input name="usp-files[]" type="file" maxlength="'. $max .'" data-required="'. $required .'" placeholder="'. $placeholder .'" class="'. $classes .' select-file multiple"'. $method .' id="usp-multiple-files" '. $custom .'/>'. "\n";
			}
			
			$content .= '</div>'. "\n";
			$content .= '<input name="usp-file-limit" class="usp-file-limit" value="'. $files_max .'" type="hidden" />'. "\n";
			$content .= '<input name="usp-file-count" class="usp-file-count" value="1" type="hidden" />'. "\n";
			
			if (!empty($file_types)) $content .= '<input name="usp-file-types" value="'. $file_types .'" type="hidden" />'. "\n";
			if ($required == 'true') $content .= '<input name="usp-files-required" value="'. $files_min .'" type="hidden" />'. "\n";
			
			$content .= $preview;
			$content .= '</div>'. "\n";
			
		} else {
			
			$content .= '<div id="usp-file" class="usp-file">'. "\n";
			
			if (empty($label))  $content .= '';
			else                $content .= '<label for="usp-file-'. $key .'" class="usp-label usp-label-files usp-label-file usp-label-file-'. $key .'">'. $label .'</label>'. "\n";
			
			if (empty($method)) $method_class = ' add-another single-file';
			else                $method_class = ' select-file single-file';
			
			$content .= '<input name="usp-file-'. $key .'" type="file" maxlength="'. $max .'" data-required="'. $required .'" placeholder="'. $placeholder .'" class="'. $classes .' usp-input-file usp-input-file-'. $key . $method_class .'" '. $custom .'/>'. "\n";
			$content .= '<input name="usp-file-key" value="'. $key .'" type="hidden" />'. "\n";
			
			if (!empty($file_types)) $content .= '<input name="usp-file-types" value="'. $file_types .'" type="hidden" />'. "\n";
			if ($required == 'true') $content .= '<input name="usp-file-required-'. $key .'" value="'. $files_min .'" type="hidden" />'. "\n";
			
			$content .= $preview;
			$content .= '</div>'. "\n";
		}
		
		$content .= '<div class="usp-preview"></div>'. "\n";
		
	} else {
		return __('File uploads not currently allowed. Please check your settings or contact the site administrator.', 'usp');
	}
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_files', 'usp_input_files');
endif;

/*
	Shortcode: Remember
	Displays "remember me" button
	Syntax: [usp_remember class="aaa,bbb,ccc" label="Remember me"]
	Attributes:
		class       = comma-sep list of classes
		label       = text for input label (set checked/unchecked in USP Settings)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_remember')) : 
function usp_remember($args) {
	global $usp_general;
	if ($usp_general['sessions_default']) $checked = ' checked';
	else $checked = '';
	
	if (isset($_COOKIE['remember'])) $checked = ' checked';
	elseif (isset($_COOKIE['forget'])) $checked = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['class'])) $class = 'usp-remember,usp-input,usp-input-remember,' . $args['class'];
	else $class = 'usp-remember,usp-input,usp-input-remember';
	$classes = usp_classes($class);
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	if (isset($args['label']) && !empty($args['label'])) $label_text = trim($args['label']);
	else $label_text = __('Remember me', 'usp');
	$label = '<label for="usp-remember" class="usp-label usp-label-remember">'. $label_text .'</label>'. "\n";
	
	$content = '';
	$content .= '<input name="usp-remember" id="usp-remember" type="checkbox" data-required="true" class="'. $classes .'" value="1"'. $checked .' '. $custom .'/> '. "\n". $label;
	
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_remember', 'usp_remember');
endif;

/*
	Shortcode: Submit
	Displays submit button
	Syntax: [usp_submit class="aaa,bbb,ccc" value="Submit Post"]
	Attributes:
		class       = comma-sep list of classes
		value       = text to display on submit button
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_submit_button')) : 
function usp_submit_button($args) {
	if (isset($args['class'])) $class = 'usp-submit,' . $args['class'];
	else $class = 'usp-submit';
	$classes = usp_classes($class, 'submit');
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	if (isset($args['value']) && !empty($args['value'])) $value = trim($args['value']);
	else $value = __('Submit Post', 'usp');

	return $fieldset_before . '<input type="submit" class="'. $classes .'" value="'. $value .'" '. $custom .'/>'. "\n" . $fieldset_after;
}
add_shortcode('usp_submit', 'usp_submit_button');
endif;

/*
	Shortcode: Email
	Displays email input field
	Syntax: [usp_email class="aaa,bbb,ccc" placeholder="Your Email" label="Your Email" required="yes" max="99"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_email')) : 
function usp_input_email($args) {
	global $current_user;
	if ($current_user->user_email) $value = $current_user->user_email;
	elseif (isset($_SESSION['usp_form_session']['usp-email']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-email'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-email,' . $args['class'];
	else $class = 'usp-input,usp-input-email';
	$classes = usp_classes($class, '9');
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	$field = 'usp_error_9';
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '99');

	if (empty($label)) $content = '';
	else $content  = '<label for="usp-email" class="usp-label usp-label-email">'. $label .'</label>'. "\n";
	$content .= '<input name="usp-email" type="text" value="'. $value .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	if ($required == 'true') $content .= '<input name="usp-email-required" value="1" type="hidden" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_email', 'usp_input_email');
endif;

/*
	Shortcode: Email Subject
	Displays email subject input field
	Syntax: [usp_subject class="aaa,bbb,ccc" placeholder="Email Subject" label="Email Subject" required="yes" max="99"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_subject')) : 
function usp_input_subject($args) {
	if (isset($_SESSION['usp_form_session']['usp-subject']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-subject'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-subject,'. $args['class'];
	else $class = 'usp-input,usp-input-subject';
	$classes = usp_classes($class, '10');
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	$field = 'usp_error_10';
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '99');

	if (empty($label)) $content = '';
	else $content  = '<label for="usp-subject" class="usp-label usp-label-subject">'. $label .'</label>'. "\n";
	$content .= '<input name="usp-subject" type="text" value="'. $value .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	if ($required == 'true') $content .= '<input name="usp-subject-required" value="1" type="hidden" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_subject', 'usp_input_subject');
endif;

/*
	Shortcode: Reset form button
	Returns the markup for a reset-form button
	Syntax: [usp_reset_button class="aaa,bbb,ccc" value="Reset form" url="http://example.com/usp-pro/submit/"]
	Attributes:
		class  = comma-sep list of classes
		value  = text for input placeholder
		url    = full URL that the form is displayed on (not the form URL, unless you want to redirect there)
		custom = any attributes or custom code
*/
if (!function_exists('usp_reset_button')) : 
function usp_reset_button($args) {
	if (isset($args['class'])) $class = 'usp-reset-button,' . $args['class'];
	else $class = 'usp-reset-button';
	$classes = usp_classes($class);
	
	if (isset($args['custom'])) $custom = ' '. sanitize_text_field($args['custom']);
	else $custom = '';
	
	if (isset($args['value']) && !empty($args['value'])) $value = trim($args['value']);
	else $value = 'Reset form';
	
	if (isset($args['url']) && !empty($args['url'])) $url = trim($args['url']);
	else $url = '#please-check-shortcode';

	$content = '<div class="'. $classes .'"><a href="'. $url .'?usp_reset_form=true"'. $custom .'>'. $value .'</a></div>'. "\n";
	return $content;
}
add_shortcode('usp_reset_button', 'usp_reset_button');
endif;

/*
	Shortcode: CC Message
	Returns the CC message
	Syntax: [usp_cc class="aaa,bbb,ccc" text=""]
	Attributes:
		class  = comma-sep list of classes
		text   = custom cc message (overrides default)
		custom = any attributes or custom code
*/
if (!function_exists('usp_cc')) : 
function usp_cc($args) {
	global $usp_admin;
	if (isset($args['class'])) $class = 'usp-contact-cc,' . $args['class'];
	else $class = 'usp-contact-cc';
	$classes = usp_classes($class);
	
	if (isset($args['custom'])) $custom = ' '. sanitize_text_field($args['custom']);
	else $custom = '';
	
	if (isset($usp_admin['contact_cc_note'])) $default = $usp_admin['contact_cc_note'];
	if (isset($args['text']) && !empty($args['text'])) $text = trim($args['text']);
	else $text = $default;
	
	$content = '<div class="'. $classes .'"'. $custom .'>'. $text .'</div>'. "\n";
	return $content;
}
add_shortcode('usp_cc', 'usp_cc');
endif;

/*
	Shortcode: Custom Redirect
	Redirects to specified URL on successful form submission
	Syntax: [usp_redirect url="http://example.com/" custom="class='example classes' data-role='custom'"]
	Attributes:
		url    = any complete/full URL
		custom = any custom attribute(s) using single quotes
*/
if (!function_exists('usp_redirect')) : 
function usp_redirect($args) {
	if (isset($args['custom']) && !empty($args['custom'])) $custom = ' '. stripslashes(trim($args['custom']));
	else $custom = '';

	if (isset($args['url']) && !empty($args['url'])) $url = esc_url(trim($args['url']));
	else $url = '';
	
	if (!empty($url)) return '<input name="usp-redirect" type="hidden" value="'. $url .'"'. $custom .' />'. "\n";
	else return '<!-- please check URL shortcode attribute -->'. "\n";
}
add_shortcode('usp_redirect', 'usp_redirect');
endif;

/*
	Shortcode: Custom Fields
	Displays custom input and textarea fields
	Syntax: [usp_custom_field form="x" id="y"]
	Template tag: usp_custom_field(array('form'=>'y', 'id'=>'x'));
	Attributes:
		id   = id of custom field (1-9)
		form = id of custom post type (usp_form)
	Notes:
		shortcode must be used within USP custom post type
		template tag may be used anywhere in the theme template
*/
if (!class_exists('USP_Custom_Fields')) {
	class USP_Custom_Fields {
		function __construct() { 
			add_shortcode('usp_custom_field', array(&$this, 'usp_custom_field')); 
		}
		function usp_custom_field($args) {
			global $usp_advanced;
			
			if (isset($args['id']) && !empty($args['id'])) $id = $args['id'];
			else return __('error:usp_custom_field:1:', 'usp') . $args['id'];
			
			if (isset($args['form']) && !empty($args['form'])) $form = usp_get_form_id($args['form']);
			else return __('error:usp_custom_field:2:', 'usp') . $args['form'];
			
			$custom_fields = get_post_custom($form);
			if (is_null($custom_fields) || empty($custom_fields)) return __('error:usp_custom_field:3:', 'usp') . $custom_fields;
			
			$custom_merged = usp_merge_custom_fields();
			$custom_arrays = usp_custom_field_string_to_array();
			$custom_prefix = $usp_advanced['custom_prefix'];
			
			if (empty($custom_prefix)) $custom_prefix = 'prefix_';
			
			foreach ($custom_fields as $key => $value) {
				
				$key = trim($key);
				if ('_' == $key{0}) continue;
				if ($key !== '[usp_custom_field form="'. $args['form'] .'" id="'. $id .'"]') continue;
				
				if (preg_match("/usp_custom_field/i", $key)) {
					
					$atts = explode("|", $value[0]);
					
					$get_value    = $this->usp_custom_field_cookies($id, $value);
					$default_atts = $this->usp_custom_field_defaults($id, $get_value);
					$field_atts   = $this->usp_custom_field_atts($atts, $default_atts);
					
					if (empty($field_atts)) return __('error:usp_custom_field:4:', 'usp') . $field_atts;
						
					$fieldset = usp_fieldset_custom($field_atts['fieldset'], $field_atts['field_class']);
					
					if (in_array($field_atts['name'], $custom_merged) || preg_match("/^$custom_prefix/i", $field_atts['name'])) $prefix = '';
					else $prefix = 'usp-custom-';
					
					$checked = ''; $selected = ''; $class = ''; $class_label = ''; $label_custom = '';
					
					if (!empty($field_atts['checked']))      $checked      = ' checked="checked"';
					if (!empty($field_atts['selected']))     $selected     = ' selected="selected"';
					if (!empty($field_atts['class']))        $class        = $field_atts['class'] .' ';
					if (!empty($field_atts['label_class']))  $class_label  = $field_atts['label_class'] .' ';
					if (!empty($field_atts['label_custom'])) $label_custom = ' '. $field_atts['label_custom'];
					
					$multiple = ''; $select_array = ''; $multiple_enable = array('multiple','true','yes','on');
					
					if (in_array($field_atts['multiple'], $multiple_enable)) {
						$multiple = ' multiple="multiple"';
						$select_array = '[]';
					}
					
					if     (in_array($field_atts['name'], $custom_arrays['required'])) $field_atts['data-required'] = 'true';
					elseif (in_array($field_atts['name'], $custom_arrays['optional'])) $field_atts['data-required'] = 'false';
					
					$field_hidden = ''; $parsley = '';
					
					if ($field_atts['data-required'] == 'true') {
						if (!empty($field_atts['checkboxes']) && empty($multiple)) {
							unset($field_atts['data-required']);
						} else {
							if ($field_atts['field'] !== 'input_file') {
								$field_hidden = '<input name="'. $prefix . $field_atts['name'] .'-required" value="1" type="hidden" />'. "\n";
							}
						}
						$parsley = ' required="required"'; 
					} else {
						if ($field_atts['data-required'] == 'null') {
							unset($field_atts['data-required']);
						}
					}
					
					$get_wp_editor = $this->usp_custom_field_wp_editor($field_atts);
					if (!empty($get_wp_editor)) return $fieldset['fieldset_before'] . $get_wp_editor . $field_hidden . $fieldset['fieldset_after'];
					
					
					
					
					$error      = $this->usp_custom_field_errors($id, $field_atts, $custom_prefix);
					$checkboxes = $this->usp_custom_fields_checkboxes($field_atts, $prefix, $select_array);
					$radio      = $this->usp_custom_fields_radio($field_atts, $prefix);
					$options    = $this->usp_custom_fields_select($field_atts);
					$files      = $this->usp_custom_fields_files($field_atts, $prefix, $class_label, $label_custom);
					
					
					
					//
					switch ($field_atts['field']) {
						case 'input':
							$field_start = '<input name="'. $prefix . $field_atts['name'] .'" ';
							$field_end   = 'class="'. $error . $class .'usp-input usp-input-custom usp-form-'. $form .'"'. $checked . $selected . $parsley .' />';
							$label_class = 'class="'. $class_label .'usp-label usp-label-input usp-label-custom usp-form-'. $form;
						break;
						case 'textarea':
							$field_start = '<textarea name="'. $prefix . $field_atts['name'] .'" ';
							$field_end   = 'class="'. $error . $class .'usp-input usp-textarea usp-form-'. $form .'" rows="'. $field_atts['rows'] .'" cols="'. $field_atts['cols'] .'"'. $parsley .'>'. $field_atts['value'] .'</textarea>';
							$label_class = 'class="'. $class_label .'usp-label usp-label-textarea usp-label-custom usp-form-'. $form;
							unset($field_atts['type']);
						break;
						case 'select':
							$field_start = '<select name="'. $prefix . $field_atts['name'] . $select_array .'" ';
							$field_end   = 'class="'. $error . $class .'usp-input usp-select usp-form-'. $form .'"'. $parsley . $multiple .'>'. $options .'</select>';
							$label_class = 'class="'. $class_label .'usp-label usp-label-select usp-label-custom usp-form-'. $form;
							unset($field_atts['type'], $field_atts['value'], $field_atts['multiple'], $field_atts['placeholder']);
						break;
						
						case 'input_checkbox':
							$field_start = '<div class="'. $error . $class .'usp-input usp-checkboxes usp-form-'. $form .'">';
							$field_end   = $checkboxes .'</div>';
							$label_class = '';
							unset($field_atts['type'], $field_atts['value'], $field_atts['multiple'], $field_atts['placeholder'], $field_atts['data-required']);
						break;
						case 'input_radio':
							$field_start = '<div class="'. $error . $class .'usp-input usp-radio usp-form-'. $form .'">';
							$field_end   = $radio .'</div>';
							$label_class = '';
							unset($field_atts['type'], $field_atts['value'], $field_atts['placeholder'], $field_atts['data-required']);
						break;
						case 'input_file': 
							$field_start = '<div id="'. $prefix . $field_atts['name'] .'-files" class="'. $error . $class .'usp-files">'. $files['start'];
							$field_end   = $files['end'] .'</div>'. "\n". '<div class="usp-preview"></div>';
							$label_class = '';
							unset($field_atts['type'], $field_atts['value']);
						break;
						default:
							return __('error:usp_custom_field:5:', 'usp') . $field_atts['field'];
						break;
					}
					//
					
					if ($field_atts['field'] == 'input_checkbox' || $field_atts['field'] == 'input_radio' || $field_atts['field'] == 'input_file') $label = '';
					else $label = '<label for="'. $prefix . $field_atts['for'] . $select_array .'" '. $label_class .'"'. $label_custom . '>'. $field_atts['label'] .'</label>' . "\n";
					
					if (isset($field_atts['label']) && $field_atts['label'] == 'null') $label = '';
					if (isset($field_atts['placeholder']) && $field_atts['placeholder'] == 'null') unset($field_atts['placeholder']);
					
					$field_atts = $this->usp_custom_field_unset($field_atts);
					
					$attributes = '';
					foreach ($field_atts as $att_name => $att_value) $attributes .= $att_name .'="'. $att_value .'" ';
					
					$content = $label . $field_start . $attributes . $field_end . "\n" . $field_hidden;
					
					$return = $fieldset['fieldset_before'] . $content . $fieldset['fieldset_after'];
					return apply_filters('usp_custom_field_data', $return);
				}
			}
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		function usp_custom_field_cookies($id, $value) {
			$get_value = '';
			if (isset($_COOKIE['remember'])) {
				preg_match("/name#([0-9a-z_-]+)/i",        $value[0], $name);
				preg_match("/checkboxes#([0-9a-z:_-]+)/i", $value[0], $checkbox);
				
				if (!empty($name[1])) {
					if (isset($_SESSION['usp_form_session']['usp-custom-'. $name[1]])) {
						$get_value = $_SESSION['usp_form_session']['usp-custom-'. $name[1]];
					
					} elseif (isset($_SESSION['usp_form_session'][$name[1]])) {
						$get_value = $_SESSION['usp_form_session'][$name[1]];
					}
				} else {
					if (isset($_SESSION['usp_form_session']['usp-custom-'. $id])) {
						$get_value = $_SESSION['usp_form_session']['usp-custom-'. $id];
					
					} elseif (!empty($checkbox[1])) {
						$get_value = array();
						$checkbox_array = explode(":", $checkbox[1]);
						
						foreach ($checkbox_array as $key => $value) {
							$value = trim($value);
							$checkbox_value = strtolower(trim(str_replace(' ', '_', $value)));
							
							if (isset($_SESSION['usp_form_session']['usp-custom-'. $checkbox_value])) {
								$get_value[] = $_SESSION['usp_form_session']['usp-custom-'. $checkbox_value];
							}
						}
					}
				}
			}
			return apply_filters('usp_custom_field_cookies', $get_value);
		}
		function usp_custom_field_defaults($id, $get_value) {
			global $usp_uploads;
			/*
				Notes:
					form fields:   input, textarea, select, input_checkbox, input_radio, input_file
					form atts:     autocomplete, novalidate, 
					
					input types:   text, checkbox, password, radio, file, url, search, email, tel, month, week, time, datetime, datetime-local, color, date, range, number
					
					input atts:    autocomplete, autofocus, form, formaction, formenctype, formmethod, formnovalidate, formtarget, height, width, list, min, max, multiple, 
					               pattern, placeholder, required, step, value, type, src, size, readonly, name, maxlength, disabled, checked, alt, align, accept
					
					textarea atts: autofocus, cols, disabled, form, name, placeholder, readonly, required, rows, wrap
					select atts:   autofocus, disabled, form, multiple, name, required, size
					option atts:   value, label, selected, disabled
					checkbox atts: name, disabled, form, type, checked, value, autofocus, required
					radio atts:    name, disabled, form, type, checked, value, required 
					
					mime types:    audio/*, video/*, image/*
					
				Infos:
					https://plugin-planet.com/usp-pro-shortcodes/#custom-fields
					http://iana.org/assignments/media-types
			*/
			$default_atts = array(
				// general atts
				'field'              => 'input',    // type of field
				'type'               => 'text',     // type of input, valid when field = input
				'name'               => $id,        // field name, should equal for attribute
				'value'              => $get_value, // field value
				'data-required'      => 'true',     // required + data-required atts
				'placeholder'        => __('Example Input ', 'usp') . $id, // placeholder
				'class'              => '',         // field class
				'checked'            => '',         // checked attribute
				'multiple'           => '',         // enable multiple selections
				
				// fieldset atts
				'field_class'        => '', // custom field class
				'fieldset'           => '', // use null to disable fieldset
				
				// label atts
				'label'              => __('Example Label ', 'usp') . $id, // label text
				'for'                => $id, // for att should equal name att
				'label_class'        => '',  // custom label class
				'label_custom'       => '',  // custom attribute(s)
				
				// custom atts
				'custom_1'           => '', // custom_1#attribute:value
				'custom_2'           => '', // custom_2#attribute:value
				'custom_3'           => '', // custom_3#attribute:value
				'custom_4'           => '', // custom_4#attribute:value
				'custom_5'           => '', // custom_5#attribute:value
				
				// textarea atts
				'rows'               => '3',  // number of rows
				'cols'               => '30', // number of columns
				
				// select atts
				'options'            => '', // list of select options
				'option_select'      => '', // list of selected options
				'option_default'     => __('Please select', 'usp'), // default option
				'selected'           => '', // general selected attribute
				
				// checkbox atts
				'checkboxes'         => '', // list of checkbox values
				'checkboxes_checked' => '', // list of selected checkboxes
				'checkboxes_req'     => '', // list of required checkboxes
				
				// radio atts
				'radio_inputs'       => '', // list of radio inputs
				'radio_checked'      => '', // the selected input
				
				// files atts
				'accept'             => '', // mime types
				'types'              => '', // accepted file types
				'method'             => '', // blank = "Add another", select = dropdown menu
				'link'               =>  __('Add another file', 'usp'), // link text
				'files_min'          => $usp_uploads['min_files'],      // min number of files
				'files_max'          => $usp_uploads['max_files'],      // max number of files
				'preview_off'        => '',
				'max'                => '', // max length of files value
			);
			return apply_filters('usp_custom_field_atts_default', $default_atts);
		}
		function usp_custom_field_atts($atts, $default_atts) {
			foreach ($atts as $att) {
				$a = explode("#", $att); // eg: $a[0] = field, $a[1] = input
				if ($a[0] == 'atts' && $a[1] == 'defaults') continue; // use defaults
				if (isset($a[0])) $user_att_names[]  = $a[0];
				if (isset($a[1])) $user_att_values[] = $a[1];
			}
			if (!empty($user_att_names) && !empty($user_att_values)) $user_atts = array_combine($user_att_names, $user_att_values);
			else $user_atts = $default_atts;
			
			$field_atts = wp_parse_args($user_atts, $default_atts);
			
			if (isset($user_att_names)) unset($user_att_names);
			if (isset($user_att_values)) unset($user_att_values);
			
			$custom_att_names = array();
			$custom_att_values = array();
			foreach ($field_atts as $key => $value) {
				if (preg_match("/^custom_/i", $key)) {
					$b = explode(":", $value);
					if (isset($b[0])) $custom_att_names[]  = $b[0];
					if (isset($b[1])) $custom_att_values[] = $b[1];
					if (isset($field_atts[$key])) unset($field_atts[$key]);
				}
			}
			
			foreach ($custom_att_names as $key => $value) {
				if (is_null($value) || empty($value)) unset($custom_att_names[$key]);
			}
			foreach ($custom_att_values as $key => $value) {
				if (is_null($value) || empty($value)) unset($custom_att_values[$key]);
			}
			
			if (!empty($custom_att_names) && !empty($custom_att_values)) $custom_atts = array_combine($custom_att_names, $custom_att_values);
			else $custom_atts = array();
			
			$field_atts = wp_parse_args($custom_atts, $field_atts);
			if (isset($custom_att_names)) unset($custom_att_names);
			if (isset($custom_att_values)) unset($custom_att_values);
			
			return apply_filters('usp_custom_field_atts', $field_atts);
		}
		function usp_custom_field_wp_editor($field_atts) {
			$get_wp_editor = '';
			if (isset($field_atts['data-richtext']) && $field_atts['data-richtext'] == 'true') {
				$settings = array(
				    'wpautop'       => true,                              // enable rich text editor
				    'media_buttons' => true,                              // enable add media button
				    'textarea_name' => 'usp-custom-'.$field_atts['name'], // name
				    'textarea_rows' => $field_atts['rows'],               // number of textarea rows
				    'tabindex'      => '',                                // tabindex
				    'editor_css'    => '',                                // extra CSS
				    'editor_class'  => $field_atts['class'],              // class
				    'teeny'         => false,                             // output minimal editor config
				    'dfw'           => false,                             // replace fullscreen with DFW
				    'tinymce'       => true,                              // enable TinyMCE
				    'quicktags'     => true,                              // enable quicktags
				);
				ob_start(); // until get_wp_editor() is available..
				wp_editor($field_atts['value'], 'uspcustom', $settings);
				$get_wp_editor = ob_get_clean();
				return apply_filters('usp_custom_field_wp_editor', $get_wp_editor);
			}
		}
		function usp_custom_field_errors($id, $field_atts, $custom_prefix) {
			$error = '';
			wp_parse_str(wp_strip_all_tags($_SERVER['QUERY_STRING']), $vars);
			if ($vars) {
				foreach ($vars as $key => $value) {
					if (preg_match("/^usp_error_custom_([0-9a-z_-]+)$/i", $key, $match)) {
						if (($match[1] == $id) || ($match[1] == $field_atts['name'])) $error = 'usp-error-field usp-error-custom ';
						
					} elseif (preg_match("/^usp_error_([a-g]+)$/i", $key, $match)) {
						$user_fields = array('a' => 'nicename', 'b' => 'displayname', 'c' => 'nickname', 'd' => 'firstname', 'e' => 'lastname', 'f' => 'description', 'g' => 'password');
						foreach ($user_fields as $k => $v) {
							if (($field_atts['name'] == $v) && ($match[1] == $k)) $error = 'usp-error-field usp-error-register ';
						}
					} elseif (preg_match("/^usp_error_(11|12|13)$/i", $key, $match)) {
						$user_fields = array('11' => 'alt', '12' => 'caption', '13' => 'desc');
						foreach ($user_fields as $k => $v) {
							if ((strpos($field_atts['name'], $v) !== false) && (strpos($k, $match[1]) !== false)) $error = 'usp-error-field usp-error-meta ';
						}
					} elseif (preg_match("/^usp_ccf_error_([0-9a-z_-]+)$/i", $key, $match)) {
						if ($match[1] == $field_atts['name']) $error = 'usp-error-field usp-error-custom-custom ';
						
					} elseif (preg_match("/^usp_error_$custom_prefix([0-9a-z_-]+)?$/i", $key, $match)) {
						if ($custom_prefix . $match[1] == $field_atts['name']) $error = 'usp-error-field usp-error-custom-prefix ';
					}
				}
			}
			return apply_filters('usp_custom_field_errors', $error);
		}
		function usp_custom_field_unset($field_atts) {
			if (!empty($field_atts)) {
				unset(
					$field_atts['field'], 
					$field_atts['accept'], 
					$field_atts['name'], 
					$field_atts['checked'], 
					$field_atts['selected'], 
					$field_atts['class'], 
					$field_atts['label_class'], 
					$field_atts['rows'], 
					$field_atts['cols'],
					$field_atts['for'], 
					$field_atts['label_custom'], 
					$field_atts['label'], 
					$field_atts['field_class'],
					$field_atts['options'],
					$field_atts['option_default'], 
					$field_atts['option_select'],
					$field_atts['checkboxes'],
					$field_atts['checkboxes_checked'],
					$field_atts['checkboxes_req'],
					$field_atts['radio_inputs'],
					$field_atts['radio_checked'],
					$field_atts['fieldset'],
					$field_atts['types'],
					$field_atts['method'],
					$field_atts['link'],
					$field_atts['files_min'],
					$field_atts['files_max'],
					$field_atts['multiple'],
					$field_atts['preview_off'],
					$field_atts['max']
				);
			}
			return apply_filters('usp_custom_field_unset', $field_atts);
		}
		function usp_custom_fields_select($field_atts) {
			$options = '';
			if (!empty($field_atts['options']) && $field_atts['field'] == 'select') {
				$options_array = explode(":", $field_atts['options']);
				foreach ($options_array as $option) {
					$option = trim($option);
					$option_value = strtolower(trim(str_replace(' ', '_', $option)));
					$selected = false;
					$option_selected = '';
					if (isset($field_atts['option_select']) && strtolower($option) == strtolower($field_atts['option_select'])) $selected = true;
					if (isset($field_atts['value'])) {
						$value = $field_atts['value'];
						if (is_array($value)) {
							foreach ($value as $att) {
								if ($att == $option_value) $selected = true;
							}
						} else {
							if ($value == $option_value) $selected = true;
						}
					}
					if ($selected) $option_selected = ' selected="selected"';
					if ($option == 'null' && isset($field_atts['option_default'])) {
						$option = $field_atts['option_default'];
						$option_value = '';
					}
					$options .= '<option value="'. $option_value .'"'. $option_selected .'>'. $option .'</option>' . "\n";
				}
				$options = "\n" . $options;
			}
			return apply_filters('usp_custom_fields_select', $options);
		}
		function usp_custom_fields_checkboxes($field_atts, $prefix, $select_array) {
			$checkboxes = '';
			
			if ($field_atts['field'] == 'input_checkbox') {
				
				$checkboxes_array = array(); $required_single = array(); $checked_array = array(); $required = array();
				
				if (!empty($field_atts['checkboxes']))         $checkboxes_array = explode(":", $field_atts['checkboxes']);
				if (!empty($field_atts['checkboxes_req']))     $required_single  = explode(":", $field_atts['checkboxes_req']);
				if (!empty($field_atts['checkboxes_checked'])) $checked_array    = explode(":", $field_atts['checkboxes_checked']);
				
				foreach ($checkboxes_array as $checkbox) {
					$checkbox = trim($checkbox);
					$checkbox_value = strtolower(trim(str_replace(' ', '_', $checkbox)));
					
					if (!empty($select_array)) {
						if (!empty($field_atts['name'])) $name = $field_atts['name'];
						else $name = __('undefined', 'usp');
						$suffix = '['. $checkbox_value .']';
					} else {
						$name = $checkbox_value;
						$suffix = '';
					}
					
					$check = false; $checked = '';
					
					if (!empty($field_atts['value'])) {
						
						$value = $field_atts['value'];
						if (is_array($value)) {
							$value = array_map('strtolower', $value);
							if (in_array(strtolower($checkbox_value), $value)) $check = true;
						} else {
							if (strtolower($checkbox_value) == $value) $check = true;
						}
					} elseif (!empty($checked_array)) {
						$checked_array = array_map('strtolower', $checked_array);
						if (in_array(strtolower($checkbox), $checked_array)) $check = true;
					}
					
					if ($check) $checked = ' checked="checked"';
					
					if (!empty($required_single)) {
						$required_single = array_map('strtolower', $required_single);
						if (in_array(strtolower($checkbox), $required_single)) {
							$required[] = '<input name="'. $prefix . $name .'-required" value="1" type="hidden" />' . "\n";
						}
					}
					$checkboxes .= '<label><input name="'. $prefix . $name . $suffix .'" type="checkbox" value="'. $checkbox_value .'"'. $checked .' /> '. $checkbox .'</label>' . "\n";
				}
				$checkboxes = "\n" . $checkboxes;
				foreach ($required as $require) $checkboxes .=  $require;
			}
			return apply_filters('usp_custom_fields_checkbox', $checkboxes);
		}
		function usp_custom_fields_radio($field_atts, $prefix) {
			$radios = '';
			if ($field_atts['field'] == 'input_radio') {
				$checked = array();
				$radio_array = array();
				if (isset($field_atts['radio_checked']) && !empty($field_atts['radio_checked'])) $radio_checked = strtolower(trim(str_replace(' ', '_', $field_atts['radio_checked'])));
				if (isset($field_atts['radio_inputs'])  && !empty($field_atts['radio_inputs']))  $radio_array   = explode(":", $field_atts['radio_inputs']);
				
				foreach ($radio_array as $radio) {
					$radio = trim($radio);
					$radio_value = strtolower(trim(str_replace(' ', '_', $radio)));
					
					if (!empty($field_atts['name'])) $name = $field_atts['name'];
					else $name = __('undefined', 'usp');
					
					$checked = '';
					if (!empty($field_atts['value'])) {
						if ($radio_value == strtolower($field_atts['value'])) $checked = ' checked="checked"';
						
					} elseif (!empty($radio_checked)) {
						if ($radio_value == $radio_checked) $checked = ' checked="checked"';
					}
					$radios .= '<label><input name="'. $prefix . $name .'" type="radio" value="'. $radio_value .'"'. $checked .' /> '. $radio .'</label>' . "\n";
				}
				$radios = "\n" . $radios;
			}
			return apply_filters('usp_custom_fields_radio', $radios);
		}
		function usp_custom_fields_files($field_atts, $prefix, $class_label, $label_custom) {
			$files = array();
			if ($field_atts['field'] == 'input_file') {
				
				$for      = $field_atts['for'];
				$name     = $field_atts['name'];
				$link     = $field_atts['link'];
				$label    = $field_atts['label'];
				$method   = $field_atts['method'];
				$multiple = $field_atts['multiple'];
				
				if ($prefix == 'usp-custom-') $prefix = 'usp_custom_file_';
				
				if (!empty($field_atts['class'])) $class = $field_atts['class'] .' ';
				else $class = '';
				
				if (!empty($field_atts['max'])) $max = ' maxlength="'. $field_atts['max'] .'"';
				else $max = '';
				
				if (!empty($field_atts['accept'])) $accept = ' accept="'. $field_atts['accept'] .'"';
				else $accept = '';
				
				if (!empty($field_atts['files_max'])) $files_max = "\n" .'<input name="'. $prefix . $name .'-limit" class="usp-file-limit" value="'. $field_atts['files_max'] .'" type="hidden" />';
				else $files_max = '';
				
				if (!empty($field_atts['types'])) $files_type = "\n" .'<input name="'. $prefix . $name .'-types" value="'. $field_atts['types'] .'" type="hidden" />';
				else $files_type = '';
				
				if (!empty($field_atts['preview_off'])) $preview = "\n" .'<input name="'. $prefix . $name .'-preview" value="1" type="hidden" />';
				else $preview = '';
				
				$files_min = '';
				if ($field_atts['data-required'] == 'true') {
					if (empty($field_atts['files_min']) && intval($field_atts['files_min']) < 1) $files_min = '1';
					else $files_min = $field_atts['files_min'];
					$files_min = "\n" .'<input name="'. $prefix . $name .'-required" value="'. $files_min .'" type="hidden" />';
				}
				
				$files_count = "\n" .'<input name="'. $prefix . $name .'-count" class="usp-file-count" value="1" type="hidden" />';
				
				if (empty($method)) {
					$input_id = '';
					$data_file = ' data-file="1"';
					$class_method = ' add-another';
					$add_another = "\n" .'<div class="usp-add-another"><a href="#">'. $link .'</a></div>';
					$is_multiple = false;
				} else {
					$input_id = ' id="'. $prefix . $name .'-multiple-files"';
					$data_file = '';
					$class_method = ' select-file';
					$add_another = '';
					$is_multiple = true;
				}
				
				$multiple_enable = array('multiple', 'true', 'yes', 'on');
				if (empty($multiple) || in_array($multiple, $multiple_enable)) {
					
					$class_label = ' class="'. $class_label .'usp-label usp-label-files usp-label-custom"';
					$class_input = ' class="'. $class       .'usp-input usp-input-files usp-input-custom'. $class_method .' multiple"';
					
					$input_wrap_open = "\n" .'<div class="usp-input-wrap">';
					$input_wrap_close = "\n" .'</div>';
					$select = '[]';
					
					if ($is_multiple) $multiple_att = ' multiple="multiple"';
					else              $multiple_att = '';
				} else {
					$class_label = ' class="'. $class_label .'usp-label usp-label-files usp-label-custom usp-label-file usp-label-file-single"';
					$class_input = ' class="'. $class       .'usp-input usp-input-files usp-input-custom usp-input-file usp-input-file-single'. $class_method .' single-file"';
					
					$input_wrap_open = '';
					$input_wrap_close = '';
					$select = '';
					
					$multiple_att = '';
					if (!$is_multiple) {
						$add_another = '';
						$data_file = '';
					}
					$input_id = '';
					$files_max = '';
					$files_count = '';	
				}
				
				$files['start']  = "\n" .'<label for="'. $prefix . $for . $select .'"'. $class_label . $label_custom .'>'. $label .'</label>';
				$files['start'] .= $input_wrap_open . "\n" .'<input name="'. $prefix . $name . $select .'" type="file"'. $class_input . $input_id . $multiple_att . $accept . $max . $data_file .' ';
				
				$files['end'] = '/>'. $add_another . $input_wrap_close . $files_max . $files_count . $files_type . $files_min . $preview . "\n";
				
				$files['id'] = $prefix . $name;
			}
			return apply_filters('usp_custom_fields_files', $files);
		}
	}
}



/*
	Template Tag: Custom Fields
	Displays custom input and textarea fields
	Syntax: [usp_custom_field id=x]
	Template tag: usp_custom_field(array('id'=>'x', 'form'=>'y'));
	Attributes:
		id   = id of custom field (1-9)
		form = id of custom post type (usp_form)
	Notes:
		shortcode must be used within USP custom post type
		template tag may be used anywhere in the theme template
*/
if (!function_exists('usp_custom_field')) : 
function usp_custom_field($args) {
	$USP_Custom_Fields = new USP_Custom_Fields();
	echo $USP_Custom_Fields->usp_custom_field($args);
}
endif;

/*
	Shortcode: USP Form
		Displays the specified USP Form by id attribute
		Syntax: [usp_form id="1" class="aaa,bbb,ccc"]
		Attributes:
			id    = id of form (post id or slug)
			class = classes comma-sep list (displayed as class="aaa bbb ccc") 
*/
if (!function_exists('usp_form')) : 
function usp_form($args) {
	global $usp_advanced;
	if (isset($args['id']) && !empty($args['id'])) $id = usp_get_form_id($args['id']);
	else return __('error:usp_form:1:', 'usp') . $args['id'];
	
	if (isset($args['class']) && !empty($args['class'])) {
		$class = 'usp-pro,usp-form-'. $id .',' . $args['class'];
		$classes = usp_classes($class);
	} else {
		$classes = '';
	}
	$content = get_post($id, ARRAY_A);
	$args = array('classes' => $classes, 'id' => $id);

	if (isset($_GET['usp_success'])) $success = true;
	else $success = false;
	$form_wrap = usp_form_wrap($args, $success);

	if (get_post_type() !== 'usp_form') {
		if ($success && $usp_advanced['success_form'] == '0') return $form_wrap['form_before'] . $form_wrap['form_after'];
		else return $form_wrap['form_before'] . do_shortcode($content['post_content']) . $form_wrap['form_after'];
	} else {
		return __('error:usp_form:2:', 'usp') . get_post_type();
	}
}
add_shortcode('usp_form', 'usp_form');
endif;


