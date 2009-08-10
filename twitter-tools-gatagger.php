<?php
/*
Plugin Name: Twitter Tools - Google Analytics Tagger
Plugin URI: http://www.farre.cat/blog/
Description: Tag all your URL's posted to twitter with Google Analytics tags
Version: 1.0
Author: Oriol Farre
Author URI: http://www.farre.cat/blog/
*/

// ini_set('display_errors', '1'); ini_set('error_reporting', E_ALL);

if (!defined('PLUGINDIR')) {
	define('PLUGINDIR','wp-content/plugins');
}

load_plugin_textdomain('twitter-tools-gatagger');

function aktt_utm_tagging($url) {


	$utm_source = get_option('aktt_gatagger_utm_source');
	$utm_medium = get_option('aktt_gatagger_utm_medium');
	$utm_term = get_option('aktt_gatagger_utm_term');
	$utm_content = get_option('aktt_gatagger_utm_content');
	$utm_campaign = get_option('aktt_gatagger_utm_campaign');


	//Si s'han informat els 3 camps obligatoris
	if( !empty($utm_source) && !empty($utm_medium) && !empty($utm_campaign) ){


		$tag = "?";
		$tag .= "utm_source=".$utm_source;
		$tag .= "&utm_medium=".$utm_medium;
		if (!empty($utm_term)) $tag .= "&utm_term=".$utm_term;
		if (!empty($utm_content)) $tag .= "&utm_content=".$utm_content;
		$tag .= "&utm_campaign=".$utm_campaign;

		//$url .= "?utm_source=twitter&utm_medium=post&utm_campaign=social";
		$url .= $tag;
	}
	return $url;
}
add_filter('tweet_blog_post_url', 'aktt_utm_tagging');


function aktt_gatagger_request_handler() {
	if (!empty($_GET['of_action'])) {
		switch ($_GET['of_action']) {

		}
	}
	if (!empty($_POST['of_action'])) {
		switch ($_POST['of_action']) {

			case 'aktt_gatagger_update_settings':
				aktt_gatagger_save_settings();
				wp_redirect(trailingslashit(get_bloginfo('wpurl')).'wp-admin/options-general.php?page=twitter-tools.php&updated=true');
				die();
				break;
		}
	}
}
add_action('init', 'aktt_gatagger_request_handler');

$aktt_gatagger_settings = array(
	'aktt_gatagger_utm_source' => array(
		'type' => 'string',
		'label' => __('Campaign Source *:', 'twitter-tools-gatagger'),
		'default' => '',
		'help' => 'Required. Use utm_source to identify a search engine, newsletter name, or other source.<br />(referrer: google, citysearch, newsletter4)',
	),
	'aktt_gatagger_utm_medium' => array(
		'type' => 'string',
		'label' => __('Campaign Medium *:', 'twitter-tools-gatagger'),
		'default' => '',
		'help' => 'Required. Use utm_medium to identify a medium such as email or cost-per- click.<br />(marketing medium: cpc, banner, email)',
	),
	'aktt_gatagger_utm_term' => array(
		'type' => 'string',
		'label' => __('Campaign Term:', 'twitter-tools-gatagger'),
		'default' => '',
		'help' => 'Used for paid search. Use utm_term to note the keywords for this ad.<br />(identify the paid keywords)',
	),
	'aktt_gatagger_utm_content' => array(
		'type' => 'string',
		'label' => __('Campaign Content:', 'twitter-tools-gatagger'),
		'default' => '',
		'help' => 'Used for A/B testing and content-targeted ads. Use utm_content to differentiate ads or links that point to the same URL.<br />(use to differentiate ads)',
	),
	'aktt_gatagger_utm_campaign' => array(
		'type' => 'string',
		'label' => __('Campaign Name *:', 'twitter-tools-gatagger'),
		'default' => '',
		'help' => 'Used for keyword analysis. Use utm_campaign to identify a specific product promotion or strategic campaign.<br />(product, promo code, or slogan)',
	),
);


function aktt_gatagger_setting($option) {
	$value = get_option($option);
	if (empty($value)) {
		global $aktt_gatagger_settings;
		$value = $aktt_gatagger_settings[$option]['default'];
	}
	return $value;
}

if (!function_exists('of_settings_field')) {
	function of_settings_field($key, $config) {
		$option = get_option($key);
		if (empty($option) && !empty($config['default'])) {
			$option = $config['default'];
		}
		$label = '<label for="'.$key.'">'.$config['label'].'</label>';
		$help = '<span class="help">'.$config['help'].'</span>';
		switch ($config['type']) {
			case 'select':
				$output = $label.'<select name="'.$key.'" id="'.$key.'">';
				foreach ($config['options'] as $val => $display) {
					$option == $val ? $sel = ' selected="selected"' : $sel = '';
					$output .= '<option value="'.$val.'"'.$sel.'>'.htmlspecialchars($display).'</option>';
				}
				$output .= '</select>'.$help;
				break;
			case 'textarea':
				$output = $label.'<textarea name="'.$key.'" id="'.$key.'">'.htmlspecialchars($option).'</textarea>'.$help;
				break;
			case 'string':
			case 'int':
			default:
				$output = $label.'<input name="'.$key.'" id="'.$key.'" value="'.htmlspecialchars($option).'" type="text" />'.$help;
				break;
		}
		return '<div class="option">'.$output.'</div>';
	}
}

function aktt_gatagger_settings_form() {
	global $aktt_gatagger_settings;

	print('
<div class="wrap">
	<h2>'.__('Google Analytics Campaign Tagging', 'twitter-tools-utm').'</h2>
	<form id="ak_twittertools" name="aktt_gatagger_settings_form" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php" method="post">
		<input type="hidden" name="of_action" value="aktt_gatagger_update_settings" />
		<fieldset class="options">
	');
	foreach ($aktt_gatagger_settings as $key => $config) {
		echo of_settings_field($key, $config);
	}
	print('
		</fieldset>
		<p class="submit">
			<input type="submit" name="submit" class="button-primary" value="'.__('Save Settings', 'twitter-tools-gatagger').'" />
		</p>
	</form>
</div>
	');
}
add_action('aktt_options_form', 'aktt_gatagger_settings_form');

function aktt_gatagger_save_settings() {
	if (!current_user_can('manage_options')) {
		return;
	}
	global $aktt_gatagger_settings;
	foreach ($aktt_gatagger_settings as $key => $option) {
		$value = '';
		switch ($option['type']) {
			case 'int':
				$value = intval($_POST[$key]);
				break;
			case 'select':
				$test = stripslashes($_POST[$key]);
				if (isset($option['options'][$test])) {
					$value = $test;
				}
				break;
			case 'string':
			case 'textarea':
			default:
				$value = stripslashes($_POST[$key]);
				break;
		}
		update_option($key, $value);
	}
}

?>