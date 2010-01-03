<?php
// the facebook client library
if(!class_exists('FacebookRestClient')) {
  if (version_compare(PHP_VERSION,'5','>=')) {
    include_once(WP_PLUGIN_DIR . '/wpbook/client/facebook.php');
  } else {
    include_once(WP_PLUGIN_DIR . '/wpbook/php4client/facebook.php');
    include_once(WP_PLUGIN_DIR . '/wpbook/php4client/facebookapi_php4_restlib.php');
  }
}

// Get these from http://developers.facebook.com
$wpbookOptions = get_option('wpbookAdminOptions');

if (!empty($wpbookOptions)) {
	foreach ($wpbookOptions as $key => $option)
		$wpbookAdminOptions[$key] = $option;
	}

//get options from wordpress settings
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
$timestamp_date_format = $wpbookAdminOptions['timestamp_date_format'];
$timestamp_time_format = $wpbookAdminOptions['timestamp_time_format'];
$show_date_title = $wpbookAdminOptions['show_date_title'];
$custom_header = $wpbookAdminOptions['custom_header'];
$custom_footer = $wpbookAdminOptions['custom_footer'];
$show_custom_header_footer = $wpbookAdminOptions['show_custom_header_footer'];
$app_name = get_bloginfo('name');
$use_gravatar = $wpbookAdminOptions['use_gravatar'];
$gravatar_rating = $wpbookAdminOptions['gravatar_rating'];
$gravatar_default = $wpbookAdminOptions['gravatar_default'];
$show_pages = $wpbookAdminOptions['show_pages'];


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

// utility functions after here
function get_external_post_url($my_permalink){
  global $app_url;
  // code to get the url of the orginal post for use in the "show external url view"
  $permalink_peices = parse_url($my_permalink);
  //get the app_url and the preceeding slash
  $permalink_app_url = "/". $app_url; 
  //remove /appname
  $external_post_permalink = str_replace_once($permalink_app_url,"",$permalink_peices[path]);
  //re-write the post url using the site url 
  $external_site_url_peices = parse_url(get_bloginfo('wpurl'));
    
  //break apart the external site address and get just the "site.com" part
  $external_site_url = $external_site_url_peices[host];
  $external_post_url = get_bloginfo('siteurl').  $external_post_permalink;
  
  //return "app url is " . $app_url; 
  return $external_post_url; 
}  
  
//write the custom header and footer 
function custom_header($custom_template_header,$date,$time){
  $author = get_the_author();
  $category = get_the_category();
  $date = get_the_time($date);
  $time = get_the_time($time);
  $permalink = '<a href="' . get_permalink() . '">permalink</a>';
  $posttags = get_the_tags();  
  if ($posttags) {
    $tag_count = count($posttags);
    $i = 0;
    foreach($posttags as $tags) {
      $i++;
      $write_tags .= $tags->name ;
      if($i<$tag_count){
        $write_tags .= ', ';
      }
    } 
  }
  else {$write_tags  = "no tags";}
  $postcategory = get_the_category();
  if ($postcategory) {
    $category_count = count($postcategory);
    $i = 0;
    foreach($postcategory as $category) {
      $i++;
      $write_category .= $category->name ;
      if($i<$category_count){
        $write_category .= ', ';
      }
    } 
  }
  else {$write_category  = "no categories";}
  
  $custom_template_header = str_replace("%author%", "$author", "$custom_template_header");
  $custom_template_header = str_replace("%category%", "$write_category", "$custom_template_header");
  $custom_template_header = str_replace("%time%", "$time", "$custom_template_header");
  $custom_template_header = str_replace("%date%", "$date", "$custom_template_header");
  $custom_template_header = str_replace("%tags%", "$write_tags", "$custom_template_header");
  $custom_template_header = str_replace("%permalink%","$permalink","$custom_template_header");
  
  return $custom_template_header;
}  // end function custom_header
  
function custom_footer($custom_template_footer,$date,$time){
  $author = get_the_author();
  $category = get_the_category();
  $date = get_the_time($date);
  $time = get_the_time($time);
  $permalink ='<a href="' . get_permalink() . '">permalink</a>';
  $posttags = get_the_tags();
  if ($posttags) {
    $tag_count = count($posttags);
    $i = 0;
    foreach($posttags as $tags) {
      $i++;
      $write_tags .= $tags->name ;
      if($i<$tag_count){
        $write_tags .= ', ';
      }
    } 
  }
  else {$write_tags  = "no tags";}
  
  $postcategory = get_the_category();
  if ($postcategory) {
    $category_count = count($postcategory);
    $i = 0;
    foreach($postcategory as $category) {
      $i++;
      $write_category .= $category->name ;
      if($i<$category_count){
        $write_category .= ', ';
      }
    } 
  }
  else {$write_category  = "no categories";}
  
  $custom_template_footer = str_replace("%author%", "$author", "$custom_template_footer");
  $custom_template_footer = str_replace("%category%", "$write_category", "$custom_template_footer");
  $custom_template_footer = str_replace("%time%", "$time", "$custom_template_footer");
  $custom_template_footer = str_replace("%date%", "$date", "$custom_template_footer");
  $custom_template_footer = str_replace("%tags%", "$write_tags", "$custom_template_footer");
  $custom_template_footer = str_replace("%permalink%","$permalink","$custom_template_footer");
  
  return $custom_template_footer;
} // end custom footer
  
// check to see if external post link contains the app name, and if it does, 
// only replace the first instance 
function str_replace_once($needle, $replace, $haystack) {
  // Looks for the first occurence of $needle in $haystack
  // and replaces it with $replace.
  $pos = strpos($haystack, $needle);
  if ($pos === false) {
    // Nothing found
    return $haystack;
  }
  return substr_replace($haystack, $replace, $pos, strlen($needle));
}  
?>



