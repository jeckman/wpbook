<?php
// the facebook client library
if(!class_exists('FacebookRestClient')) {
  include_once(WP_PLUGIN_DIR . '/wpbook/client/facebook.php');
}

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

try{
  $facebook->api_client->call_method('facebook.Fbml.setRefHandle',array(
                                         'handle' => 'recent_posts',
                                         'fbml' => $ProfileContent, // wide box
                                    ) );
} catch (Exception $e) {
  // couldn't set refhandle to fbml
}
try{  
  $facebook->api_client->call_method('facebook.profile.setFBML',
                                    array(
                                          'uid' => $user_id,
                                          'profile' => '<fb:wide><fb:ref handle="recent_posts" /></fb:wide><fb:narrow><fb:ref handle="recent_posts" /></fb:narrow>',
                                          'profile_main' => '<fb:ref handle="recent_posts" />'
                                           )
                                    );
} catch (Exception $e) {
  // failed to setFBML for profile boxes
}
// utility functions after here

    
 ?>



