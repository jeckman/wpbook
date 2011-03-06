<?php
if(!class_exists('Facebook')) {  
  include_once(WP_PLUGIN_DIR . '/wpbook/includes/client/facebook.php');  
}
  
$canvas_page = "http://apps.facebook.com/" . $app_url . "/";
  
$auth_url = "http://www.facebook.com/dialog/oauth?client_id=" 
  . $api_key . "&redirect_uri=" . urlencode($canvas_page);
  
$signed_request = $_REQUEST["signed_request"];
  
list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
  
$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
  
if (empty($data["user_id"])) {
  echo("<script> top.location.href='" . $auth_url . "'</script>");
} else {
  // need to store this somewhere, if user_id = admin
  // and they've just granted permissions
  $access_token = $data["oauth_token"];   
} 
  
if ((isset($_GET["wp_user"])) && ($data["user_id"] == $target_admin)) {
  if(version_compare($wp_version, '3.0', '<')) {
    update_usermeta($_GET["wp_user"],'wpbook_access_token', $data["oauth_token"]);
  } else {
    update_user_meta($_GET["wp_user"], 'wpbook_access_token',$data["oauth_token"]);
  }
}
?>
