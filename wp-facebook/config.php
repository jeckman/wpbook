<?php
// the facebook client library

if (version_compare(PHP_VERSION,'5','>=')) {
	include_once('client/facebook.php');  // php5
} else {
 include_once('php4client/facebook.php');
 include_once('php4client/facebookapi_php4_restlib.php');
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

//catch the exception that gets thrown if the cookie has an invalid session_key in it
try
{
  if (!$facebook->api_client->users_isAppAdded())
  {
    $facebook->redirect($facebook->get_add_url());
  }
}
catch (Exception $ex)
{
  //this will clear cookies for your application and redirect them to a login prompt
  $facebook->set_user(null, null);
  $facebook->redirect($appcallbackurl);
}

$params = $facebook->fb_params;
$user_id = $params[user];
?>