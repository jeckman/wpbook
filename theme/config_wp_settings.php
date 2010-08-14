<?php
  
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
if ($app_name == '' || (empty($app_name)) ) {
  $app_name = 'Blog Title';
}
$use_gravatar = $wpbookAdminOptions['use_gravatar'];
$gravatar_rating = $wpbookAdminOptions['gravatar_rating'];
$gravatar_default = $wpbookAdminOptions['gravatar_default'];
$show_pages = $wpbookAdminOptions['show_pages'];
$exclude_pages_true = $wpbookAdminOptions['exclude_true'];
$exclude_pages_list = $wpbookAdminOptions['exclude_pages'];
$show_pages_menu = $wpbookAdminOptions['show_pages_menu'];
$show_page_list = $wpbookAdminOptions['show_pages_list'];
$show_post_list = $wpbookAdminOptions['show_recent_post_list'];
$recent_post_list_amount= $wpbookAdminOptions['recent_post_amount'];
$wpbook_show_errors = $wpbookAdminOptions['show_errors'];
?>



