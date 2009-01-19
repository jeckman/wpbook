<?php
// the facebook client library
if(!class_exists('FacebookRestClient')) {
  if (version_compare(PHP_VERSION,'5','>=')) {
    include_once(ABSPATH . 'wp-content/plugins/wpbook/client/facebook.php');
  } else {
    include_once(ABSPATH . 'wp-content/plugins/wpbook/php4client/facebook.php');
    include_once(ABSPATH . 'wp-content/plugins/wpbook/php4client/facebookapi_php4_restlib.php');
  }
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
$app_name = $wpbookAdminOptions['fb_app_name']; 
$invite_friends = $wpbookAdminOptions['invite_friends']; 
$require_email = $wpbookAdminOptions['require_email']; 
$allow_comments = $wpbookAdminOptions['allow_comments'];
$give_credit = $wpbookAdminOptions['give_credit'];
$enable_share = $wpbookAdminOptions['enable_share'];

$facebook = new Facebook($api_key, $secret);
$user = $facebook->require_login(); 

$params = $facebook->fb_params;
$user_id = $params[user];
  
$url = 	get_bloginfo('wpurl') 
  . "/wp-content/plugins/wpbook/theme/recent_posts.php?fb_sig_in_iframe";

// This sets the default FBML for the users profile, so that it is 
// available for the "add to profile" button

$facebook->api_client->call_method('facebook.profile.setFBML',
            array(
                'uid' => $user_id,
                'profile' => '<fb:wide><fb:ref url="'. $url .'"/></fb:wide><fb:narrow><fb:ref url="'. $url .'"/></fb:narrow>',
                'profile_main' => '<fb:ref url="'. $url .'"/>'
											 )
								   );  
$facebook->api_client->fbml_refreshRefUrl($url);	

?>
