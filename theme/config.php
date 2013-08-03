<?php
if(!class_exists('Facebook')) {  
  include_once(WP_PLUGIN_DIR . '/wpbook/includes/client/facebook.php');  
}
if($wpbookOptions['wpbook_enable_debug'] == "true") {
  define ('WPBOOKDEBUG',true);
} else {
  define ('WPBOOKDEBUG',false);
}
$debug_file= WP_PLUGIN_DIR .'/wpbook/wpbook_debug.txt';
$canvas_page = $proto . "://apps.facebook.com/" . $app_url . "/";

if($wpbookOptions['wpbook_disable_sslverify'] == "true") {
  Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
  Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;
}
$facebook = new Facebook(array(
                              'appId'  => $api_key,
                              'secret' => $secret,
                              'fileUpload' => true,
                              )
                         );
/* First, do we have a stored access_token we can use */ 
$access_token = get_option('wpbook_user_access_token','');
if (($access_token != '') && ($access_token != 'invalid')) {
	try {
		$facebook->setAccessToken($access_token); 
	} catch (FacebookApiException $e) {
		if(WPBOOKDEBUG) {
			$wpbook_message = 'Caught exception setting access token to stored: ' .  $e->getMessage() .'Error code: '. $e->getCode();  
			$fp = @fopen($debug_file, 'a');
			$debug_string=date("Y-m-d H:i:s",time())." :". $wpbook_message  ."\n";
  			fwrite($fp, $debug_string);
		} // end if debug
	} // end try catch
} // end if stored_access_token valid

/* now, was that token successfully set? */ 
try {
	$result = $facebook->api('/me');
} catch (FacebookApiException $e) {
	if(WPBOOKDEBUG) {
		$wpbook_message = 'Caught exception testing access_token: ' .  $e->getMessage() .'Error code: '. $e->getCode();  
		$fp = @fopen($debug_file, 'a');
		$debug_string=date("Y-m-d H:i:s",time())." :". $wpbook_message  ."\n";
		fwrite($fp, $debug_string);
	} // end if debug
}

$access_token = $facebook->getAccessToken(); // did we get a new access token?

// now let's go find out when that our access token expires
try {
	$token_debug = $facebook->api('/debug_token?input_token='. $access_token .'&access_token='. $access_token,'GET');
} catch (FacebookApiException $e) {
	if(WPBOOKDEBUG) {
		$wpbook_message = 'Caught exception with access token: ' .  $e->getMessage() .'Error code: '. $e->getCode();  
		$fp = @fopen($debug_file, 'a');
		$debug_string=date("Y-m-d H:i:s",time())." :". $wpbook_message  ."\n";
		fwrite($fp, $debug_string);
	} // end if debug
}
	
// see if token expiration is within a day 
$my_now = time();  
if (($token_debug['data']['expires_at'] - $my_now) < 86400) {
	try {
		$graph_url = "https://graph.facebook.com/oauth/access_token?client_id=" .$api_key."&client_secret="
			.$secret."&grant_type=fb_exchange_token&fb_exchange_token=".$access_token;
		$response = @file_get_contents($graph_url);
		parse_str($response,$output);
		$new_access_token = $output['access_token'];	   
		update_option('wpbook_user_access_token',$new_access_token);
		$facebook->setAccessToken($new_access_token);
	} catch (FacebookApiException $e) {
		if(WPBOOKDEBUG) {
			$wpbook_message = 'Caught exception extending access token: ' .  $e->getMessage() .'Error code: '. $e->getCode();  
			$fp = @fopen($debug_file, 'a');
			$debug_string=date("Y-m-d H:i:s",time())." :". $wpbook_message  ."\n";
			fwrite($fp, $debug_string);
		} // end if debug
	} // end try catch
}	


/* should not store in user_meta - need to store as an option 
 * If a wp_user id was passed in, that lets us know they came from wp
 * And they are the $target_admin of the FB app, so we should store their ID
 */   
if ((isset($_REQUEST["wp_user"])) && ($data["user_id"] == $target_admin)) {
  $access_token = $facebook->getAccessToken();
  update_option('wpbook_user_access_token',$access_token);
}
?>
