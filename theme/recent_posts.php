<?php
// need wordpress context to get 5 recent posts
include_once('../../../../wp-blog-header.php');
// also need some config here
$wpbookOptions = get_option('wpbookAdminOptions');
	
if (!empty($wpbookOptions)) {
	foreach ($wpbookOptions as $key => $option)
	$wpbookAdminOptions[$key] = $option;
}
$app_url = $wpbookAdminOptions['fb_app_url'];
$app_name = $wpbookAdminOptions['fb_app_name']; 
  // get the application name from the wpbook settings. 
	
?>	
<h3>Recent posts</h3>
<div>
<ul>
<?php echo wp_recent_posts(5); ?>
</ul>
</div>
