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
$invite_friends = $wpbookAdminOptions['invite_friends']; 
$require_email = $wpbookAdminOptions['require_email']; 
$allow_comments = $wpbookAdminOptions['allow_comments'];
$give_credit = $wpbookAdminOptions['give_credit'];
$enable_share = $wpbookAdminOptions['enable_share'];
$links_position = $wpbookAdminOptions['links_position'];
$enable_external_link = $wpbookAdminOptions['enable_external_link'];
$enable_profile_link = $wpbookAdminOptions['enable_profile_link'];


$facebook = new Facebook($api_key, $secret);
$user = $facebook->require_login(); 

$params = $facebook->fb_params;
$user_id = $params[user];
  
// This sets the default FBML for the users profile, so that it is 
// available for the "add to profile" button
  
// problem is that this doesn't set anything for 'pages' - if there is an
// fb_page_id then we should try to set profile for the page, and then
// redirect back to the page they came from?  
if (isset($_GET['fb_page_id'])) {
  $user_id = $_GET['fb_page_id'];
}
  
$ProfileContent = '<h3>Recent posts</h3><div class="wpbook_recent_posts">'
                . '<ul>' . wpbook_profile_recent_posts(5) . '</ul></div>';

$facebook->api_client->call_method('facebook.Fbml.setRefHandle',array(
                                         'handle' => 'recent_posts',
                                         'fbml' => $ProfileContent, // wide box
                                    ) );
$facebook->api_client->call_method('facebook.profile.setFBML',
                                    array(
                                          'uid' => $user_id,
                                          'profile' => '<fb:wide><fb:ref handle="recent_posts" /></fb:wide><fb:narrow><fb:ref handle="recent_posts" /></fb:narrow>',
                                          'profile_main' => '<fb:ref handle="recent_posts" />'
                                           )
                                    );
?>
