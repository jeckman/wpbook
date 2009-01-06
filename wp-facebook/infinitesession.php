<?php
// include wordpress context
include_once('../../../wp-blog-header.php');

// the facebook client library
if (version_compare(PHP_VERSION,'5','>=')) {
	include_once('client/facebook.php');  // php5
} else {
	include_once('php4client/facebook.php');
	include_once('php4client/facebookapi_php4_restlib.php');
}
	
// also need some config here
$wpbookOptions = get_option('wpbookAdminOptions');
	
if (!empty($wpbookOptions)) {
	foreach ($wpbookOptions as $key => $option)
	$wpbookAdminOptions[$key] = $option;
}

$api_key = $wpbookAdminOptions['fb_api_key'];
$secret  = $wpbookAdminOptions['fb_secret'];
$app_url = $wpbookAdminOptions['fb_app_url'];
	
$facebook = new Facebook($api_key, $secret);

// force a login page
$facebook->require_frame();
$user = $facebook->require_login();
		
// Echo the "infinite session key" that everyone keeps talking about.
echo 'Your infinite session key is: ' . $facebook->api_client->session_key;
?>