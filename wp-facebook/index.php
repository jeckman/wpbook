<?php
// the facebook client library
include_once 'config.php';
	if(is_home() && isset($_GET['is_invite'])) { // this is the invite page
		if(isset($_POST["ids"])) { // this means we've already added some stuff
			echo "<center>Thank you for inviting ".sizeof($_POST["ids"])." of your friends on <b><a href=\"http://apps.facebook.com/".$app_url."/\">".$app_name."</a></b>.<br><br>\n"; 
			echo "<h2><a href=\"http://apps.facebook.com/".$app_url."/\">Click here to return to ".$app_name."</a>.</h2></center>"; 
		} 
		else { 
			// Retrieve array of friends who've already added the app. 
			$fql = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1='.$user.') AND is_app_user = 1'; $_friends = $facebook->api_client->fql_query($fql); 
 
			// Extract the user ID's returned in the FQL request into a new array. 
			$friends = array(); if (is_array($_friends) && count($_friends)) { foreach ($_friends as $friend) { $friends[] = $friend['uid']; } } // Convert the array of friends into a comma-delimeted string. 
			$friends = implode(',', $friends); 
			// Prepare the invitation text that all invited users will receive. 
			$content = "<fb:name uid=\"".$user."\" firstnameonly=\"true\" shownetwork=\"false\"/> has started using <a href=\"http://apps.facebook.com/".$app_url."/\">".$app_name."</a> and thought you should try it out!\n". "<fb:req-choice url=\"".$facebook->get_add_url()."\" label=\"Add ".$app_name." to your profile\"/>"; 
	?>
			<fb:request-form action="http://apps.facebook.com/<?php echo $app_url ?>" method="post" type="<? echo $app_name; ?>" content="<? echo htmlentities($content); ?>" image="<? echo $app_image; ?>"> 
			<fb:multi-friend-selector actiontext="Here are your friends who don't have <? echo $app_name; ?> yet. Invite whoever you want -it's free!" exclude_ids="<? echo $friends; ?>" bypass="cancel" />
			</fb:request-form> 
	<?php
		}
	} 
	else {  // this is the regular blog page
	?>
		<html>
		<head>
		<title>Facebook Blog Application</title>
			<?php wp_head(); // in case any plugins rely on this hook ?>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<BASE TARGET="_top">
		</head>
		<body>
<script src="<?= $_REQUEST['fb_sig_in_new_facebook'] ? 'http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php' : 'http://static.ak.facebook.com/js/api_lib/v0.3/FeatureLoader.js' ?> " type="text/javascript"> </script>
<script type="text/javascript">
	FB_RequireFeatures(["CanvasUtil"], function() {
		FB.FBDebug.isEnabled=true;
		FB.FBDebug.logLevel = 4;
		FB.XdComm.Server.init("<?php echo get_bloginfo('template_directory');?>/xd_reciever.html");
		FB.CanvasClient.startTimerToSizeToContent();
		});
</script>	

<div>
	<h3><a href="http://apps.facebook.com/<?php echo $app_url; ?>/" target="_top"><?php bloginfo('name'); ?></a></h3>
	<div id="content">
<?php 	
	have_posts();
		if (have_posts()) : 
			while (have_posts()) : 
				the_post();
				if (is_single() || $wp_query->is_single || $wp_query->is_singular) {
					previous_post_link('&laquo; Previous Post: %link <br />','%title',FALSE,'');
					next_post_link('Next Post: %link &raquo;<br />','%title',FALSE,'');
				} ?>
				<div class="box_head clearfix" style="padding: 5px 0 0 0;"id="post-<?php the_ID(); ?>">
				<h3 style="padding: 1px 6px 0px 8px; border-top: solid 1px #3B5998; background: #d8dfea;">
					<a href="<?php the_permalink(); ?>" target="_top"><?php the_title(); ?></a></h3>
				<?php the_content(); ?>	
				</div>
				<?php
				comments_template('comments_facebook.php'); 
			endwhile; // while have posts
		endif; // if have posts	
?>
</div>
<div class="box_head clearfix" style="padding: 5px 0 0 0;">
				<h3 style="padding: 1px 6px 0px 8px; border-top: solid 1px #3B5998; background: #d8dfea;">
					<?php _e('Recent Posts'); ?></h3>
				<?php wp_recent_posts(10); ?>
				</div>

<?php if($invite_friends == "true"){
	$invite_link = 'http://apps.facebook.com/' . $app_url ."/index.php?is_invite=true&fb_force_mode=fbml";

 ?>

<div class="box_head clearfix" style="padding: 5px 0 0 0;">
				<h3 style="padding: 1px 6px 0px 8px; border-top: solid 1px #3B5998; background: #d8dfea;">
					<a href="<?php echo("$invite_link") ?>">Invite Friends</a></h3>
				<a href="<?php echo("$invite_link") ?>">Invite friends to <?php echo("$app_name") ?>! </a>
				</div>				
				<?php 
	}
?>
</div>
</body>
<?php	 
}
?>
</html>


