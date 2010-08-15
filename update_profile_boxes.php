<?php
  /*
   * in an include to avoid errors thrown when installed 
   * on PHP 4 based hosts - conditionally included into
   * wpbook.php if PHP 5 is present due to try/catch blocks
   */
$ProfileContent = '<h3>Recent posts</h3><div class="wpbook_recent_posts">'
  . '<ul>' . wpbook_profile_recent_posts(5) . '</ul></div>';
// this call just updates the RefHandle, already set for the user profile
$api_key = $wpbookAdminOptions['fb_api_key'];
$secret  = $wpbookAdminOptions['fb_secret'];
$facebook = new Facebook($api_key, $secret);
try {
  $facebook->api_client->call_method('facebook.Fbml.setRefHandle',
                                     array('handle' => 'recent_posts',
                                           'fbml' => $ProfileContent,
                                           ) 
                                     );
} catch (Exception $e) {
  if($wpbook_show_errors) {
    $wpbook_message = 'Caught exception: ' .  $e->getMessage() .' Error code: '. $e->getCode(); 
    wp_die($wpbook_message,'WPBook Error');
  } // end if for show errors
} // end try catch
?>

