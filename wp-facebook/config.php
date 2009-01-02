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
$app_name = $wpbookAdminOptions['fb_app_name']; // get the application name from the wpbook settings. 
$invite_friends = $wpbookAdminOptions['invite_friends']; // see if invite friends is set to true. 
$require_email = $wpbookAdminOptions['require_email']; // see if require comment author e-mail is set to true. 

$facebook = new Facebook($api_key, $secret);
$user = $facebook->require_login(); 

$params = $facebook->fb_params;
$user_id = $params[user];
?>
