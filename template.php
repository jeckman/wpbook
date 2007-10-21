<?php

// the facebook client library
include_once 'php4client/facebook.php';

// Get these from http://developers.facebook.com
$wpbookOptions = get_option('wpbookAdminOptions');

if (!empty($wpbookOptions)) {
	foreach ($wpbookOptions as $key => $option)
		$wpbookAdminOptions[$key] = $option;
	}

$api_key = $wpbookAdminOptions['fb_api_key'];
$secret  = $wpbookAdminOptions['fb_secret'];

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
/*$user = $facebook->require_install(); */

	$params = $facebook->fb_params;
	$user_id = $params[user];
 ?>

<div style="padding: 10px;">
<h1 style="font-size: 33px; padding: 0 0 5px 0;"><?php bloginfo('name'); ?></h1>

<div id="content">
	
<?php
	global $more;
	// set $more to 0 in order to only get the first part of the post
	$more = 0;

	$lastposts = get_posts('numberposts=10');

	foreach($lastposts as $post):
	  setup_postdata($post);
?>
<div class="box_head clearfix" style="padding: 15px 0 0 0;"id="post-<?php the_ID(); ?>">

		<h3 style="padding: 1px 6px 0px 8px;
		  border-top: solid 1px #3B5998;
		  background: #d8dfea;"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_time('F j, Y') ?> - <?php the_title(); ?></a></h3><!-- by <?php the_author() ?> -->
	<?php the_content(); ?>
</div>
<?php endforeach; ?>

<!-- begin footer -->
</div>

</div>