<?php
// the facebook client library
include_once 'config.php';
	if(is_home() && isset($_GET['is_invite'])) { // this is the invite page
		if(isset($_POST["ids"])) { // this means we've already added some stuff
			echo "<center>Thank you for inviting ".sizeof($_POST["ids"])
        ." of your friends on <b><a href=\"http://apps.facebook.com/".$app_url
        ."/\">".$app_name."</a></b>.<br><br>\n"; 
			echo "<h2><a href=\"http://apps.facebook.com/".$app_url
        ."/\">Click here to return to ".$app_name."</a>.</h2></center>"; 
		} 
		else { 
			// Retrieve array of friends who've already added the app. 
			$fql = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend '
        . 'WHERE uid1='.$user.') AND is_app_user = 1'; 
      $_friends = $facebook->api_client->fql_query($fql); 
 
			// Extract the user ID's returned in the FQL request into a new array. 
			$friends = array(); 
      if (is_array($_friends) && count($_friends)) {
        foreach ($_friends as $friend) { 
          $friends[] = $friend['uid']; 
        } 
      } // Convert the array of friends into a comma-delimeted string. 
			$friends = implode(',', $friends); 
			// Prepare the invitation text that all invited users will receive. 
			$content = "<fb:name uid=\"".$user
        ."\" firstnameonly=\"true\" shownetwork=\"false\"/> has started using "
        ."<a href=\"http://apps.facebook.com/".$app_url."/\">"
        . bloginfo('name')."</a> and thought you should try it out!\n"
        ."<fb:req-choice url=\"".$facebook->get_add_url()
        ."\" label=\"Add ".bloginfo('name')." to your profile\"/>"; 
	?>
			<fb:request-form action="http://apps.facebook.com/<?php echo $app_url ?>" 
        method="post" type="<?php bloginfo('name'); ?>" 
        content="<? echo htmlentities($content); ?>" 
        image="<? echo $app_image; ?>"> 
			<fb:multi-friend-selector actiontext="Here are your friends who don't 
        have <? bloginfo('name') ?> yet. Invite whoever you want - it's free!" 
        exclude_ids="<? echo $friends; ?>" bypass="cancel" />
			</fb:request-form> 
	<?php
		}
	} 
	else {  // this is the regular blog page
    $receiver_url = get_bloginfo('wpurl') . '/wp-content/plugins/wpbook/theme/default/xd_receiver.html';
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" 
  xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title><?php bloginfo('name'); ?> :: Facebook Blog Application</title>
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php bloginfo('wpurl'); ?>/wp-content/plugins/wpbook/theme/default/style.css" 
  type="text/css" media="screen" />
<BASE TARGET="_top">	
</head>
<body>
<?php
  if(isset($_GET['fb_page_id'])) { 
  echo " <div><h3>Thank You!</h3> <p>This application has been added to your page's profile.</p>";
  echo "<p>You can return to your page to see the updated information.</p>";
  echo "<p>Thanks!</p></div>";
  echo "</body></html>";
} else {
?>
<script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"
  type="text/javascript"></script>

  
<?php if($invite_friends == "true"){
	$invite_link = 'http://apps.facebook.com/' . $app_url 
  ."/index.php?is_invite=true&fb_force_mode=fbml";
  ?>
<div style="float:right" class="wpbook_invite_button"><a href="<?php echo("$invite_link") ?>" class="share"> Invite Friends </div>				
<?php } ?>
  <?php if($enable_profile_link == "true"){ ?>
<div><div id="addProfileButton" style="float:right"></div></div>
  <?php }?>
<div>
	<h3><a href="http://apps.facebook.com/<?php echo $app_url; ?>/" 
    target="_top"><?php bloginfo('name'); ?></a></h3>
	<div id="content">
<?php 	
	have_posts();
		if (have_posts()) : 
			while (have_posts()) : 
				the_post();
				if (is_single() || $wp_query->is_single || $wp_query->is_singular) {
					previous_post_link('&laquo; Previous Post: %link <br />',
                             '%title',FALSE,'');
					next_post_link('Next Post: %link &raquo;<br />',
                         '%title',FALSE,'');
?>
				<?php } //end if single?> 
				<div class="box_head clearfix" style="padding: 5px 0 0 0;" 
          id="post-<?php the_ID(); ?>">
				<h3 style="padding: 1px 6px 0px 8px; border-top: solid 1px #3B5998; 
          background: #d8dfea;">
					<a href="<?php the_permalink(); ?>" target="_top">
            <?php the_title(); ?></a></h3>


<?php if(($enable_share == "true" || $enable_external_link == "true") && ($links_position == "top")) { 
if($enable_share == "true"){?>
<div class="wpbook_share_button">
<?php
  echo '<a onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=';
  echo urlencode(get_the_title());
  echo '&amp;p[summary]=';
  echo urlencode(get_the_excerpt());
  echo '&amp;p[url]=';
  echo urlencode(get_permalink());
  echo "','sharer','toolbar=0,status=0,width=626,height=436'); return false;\""; 
  echo ' class="share" title="Send this to friends or post it on your profile.">Share This Post</a>';

?>
</div>
<?php } 
if($enable_external_link == "true"){?>

<div class="wpbook_external_post">
	<?php 
// code to get the url of the orginal post for use in the "show external url view"
//get the permalink
$permalink_peices = parse_url(get_permalink());
//get the app_url and the preceeding slash
$permalink_app_url = "/".$app_url;
//remove /appname
$external_post_permalink = str_replace($permalink_app_url,"","$permalink_peices[path]");
//re-write the post url using the site url 
$external_site_url_peices = parse_url(get_bloginfo('wpurl'));

//break apart the external site address and get just the "site.com" part
$external_site_url = $external_site_url_peices[host];
$exteral_post_url = get_bloginfo('wpurl').$external_post_permalink;

//echo external post url
echo "<a href='$exteral_post_url' title='View this post on the web at $external_site_url'>View post on $external_site_url</a>";  ?>
</div>
<?php }
} ?>
			
	<?php the_content(); ?>	
<?php if(($enable_share == "true" || $enable_external_link == "true") && ($links_position == "bottom")) { 
if($enable_share == "true"){?>
<div class="wpbook_share_button">
<?php
  echo '<a onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=';
  echo urlencode(get_the_title());
  echo '&amp;p[summary]=';
  echo urlencode(get_the_excerpt());
  echo '&amp;p[url]=';
  echo urlencode(get_permalink());
  echo "','sharer','toolbar=0,status=0,width=626,height=436'); return false;\""; 
  echo ' class="share" title="Send this to friends or post it on your profile.">Share This Post</a>';

?>
</div>
<?php } 
if($enable_external_link == "true"){?>

<div class="wpbook_external_post">
	<?php 
// code to get the url of the orginal post for use in the "show external url view"
//get the permalink
$permalink_peices = parse_url(get_permalink());
//get the app_url and the preceeding slash
$permalink_app_url = "/".$app_url;
//remove /appname
$external_post_permalink = str_replace($permalink_app_url,"","$permalink_peices[path]");
//re-write the post url using the site url 
$external_site_url_peices = parse_url(get_bloginfo('wpurl'));

//break apart the external site address and get just the "site.com" part
$external_site_url = $external_site_url_peices[host];
$exteral_post_url = get_bloginfo('wpurl').$external_post_permalink;

//echo external post url
echo "<a href='$exteral_post_url' title='View this post on the web at $external_site_url'>View post on $external_site_url</a>";  ?>
</div>
<?php }
} ?>
	</div>	
				<?php
				comments_template(); 
			endwhile; // while have posts
		endif; // if have posts	
?>
</div>
<div class="box_head clearfix" style="padding: 5px 0 0 0;">
				<h3 style="padding: 1px 6px 0px 8px; border-top: solid 1px #3B5998; 
          background: #d8dfea;">
					<?php _e('Recent Posts'); ?></h3>
      <ul>
				<?php wp_recent_posts(10); ?>
      </ul>
</div>
<?php if($give_credit == "true"){ ?>
<div class="box_head clearfix" style="padding: 5px 0 0 0;">
<p><small>
  This Facebook Application powered by 
  <a href="http://www.wordpress.org/extend/plugins/wpbook/">the WPBook plugin</a>
  for <a href="http://www.wordpress.org/">WordPress</a>.
</small></p>
</div>
<?php } ?>
  <?php if($enable_profile_link =="true"){ ?>
<script type="text/javascript">
	FB_RequireFeatures(["XFBML"],function() {
		FB.Facebook.init('<?php echo $api_key; ?>',
                     '<?php echo $receiver_url; ?>',
          null);
		FB.Connect.showAddSectionButton('profile',
        document.getElementById('addProfileButton'));
});   
</script>
	<?php } ?>
<script type="text/javascript">
FB_RequireFeatures(["CanvasUtil"], function() {
                   FB.FBDebug.isEnabled=true;
                   FB.FBDebug.logLevel = 4;                
                   FB.XdComm.Server.init("<?php echo $receiver_url; ?>");
                   FB.CanvasClient.startTimerToSizeToContent();
                   });
</script>	
</body>
<?php	 
}
?>
</html>
<?php
  }
?>