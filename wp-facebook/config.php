<?php
// the facebook client library

if (version_compare(PHP_VERSION,'5','>=')) {
	include_once(ABSPATH. 'wp-content/themes/wp-facebook/client/facebook.php');  // php5
} else {
 include_once(ABSPATH . 'wp-content/themes/wp-facebook/php4client/facebook.php');
 include_once(ABSPATH . 'wp-content/themes/wp-facebook/php4client/facebookapi_php4_restlib.php');
}

// Get these from http://developers.facebook.com
$wpbookOptions = get_option('wpbookAdminOptions');

if (!empty($wpbookOptions)) {
	foreach ($wpbookOptions as $key => $option)
		$wpbookAdminOptions[$key] = $option;
	}

$api_key = $wpbookAdminOptions['fb_api_key'];
$secret  = $wpbookAdminOptions['fb_secret'];
$app_url = $wpbookAdminOptions['fb_app_url'];

$facebook = new Facebook($api_key, $secret);
$user = $facebook->require_login(); 

$params = $facebook->fb_params;
$user_id = $params[user];
?>
