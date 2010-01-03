<?php
// set up occurs in the config.php
include_once(WP_PLUGIN_DIR . '/wpbook/theme/config.php');
  if(isset($_GET['is_invite'])) { // this is the invite page
		if(isset($_POST["ids"])) { // this means we've already added some stuff
			echo "<center>Thank you for inviting ".sizeof($_POST["ids"])
        ." of your friends to ". $app_name .". <br><br>\n"; 
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
        . $app_name ."</a> and thought you should try it out!\n"
        ."<fb:req-choice url=\"".$facebook->get_add_url()
      ."\" label=\"Add ". $app_name ." to your profile\"/>"; 
	?>
<fb:fbml>
<fb:title>Invite Friends</fb:title>
      <fb:request-form action="http://apps.facebook.com/<?php echo $app_url ?>" 
        method="post" type="<? echo $app_name; ?>" 
        content="<? echo htmlentities($content); ?>" 
        image="<? echo $app_image; ?>"> 
			<fb:multi-friend-selector actiontext="Here are your friends who don't 
have <? echo $app_name; ?> yet. Invite all you want - it's free!" 
        exclude_ids="<? echo $friends; ?>" bypass="cancel" />
			</fb:request-form> 
</fb:fbml>
	<?php
		}
	} 
	else {  // this is the regular blog page
    // if this is the frontpage, but it is not the blog list page
    if(is_front_page() && (!is_home())) {
      echo '<!-- this is a static homepage -->'; // how can I redirect to home page here?
      // what can I do here? if we're not listing pages, the user will have
      // no way to get to the list of blog posts . . . 
      // this template by default will load their designated static page
      // but will also then load a recent posts list underneath that
    }
    $receiver_url = WP_PLUGIN_URL . '/wpbook/theme/default/xd_receiver.html';
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" 
  xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title><?php bloginfo('name'); ?> :: Facebook Blog Application</title>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL ?>/wpbook/theme/default/style.css" 
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
<script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
<div class="wpbook_header">
  <?php if($invite_friends == "true"){
	$invite_link = '<a href="http://apps.facebook.com/' . $app_url 
  .'/index.php?is_invite=true&fb_force_mode=fbml" class="share"> Invite Friends</a>';
  ?>
		<div style="float:right;"><span class="wpbook_invite_button"><?php echo("$invite_link") ?></span> </div>	
<?php } ?>
  <?php if($enable_profile_link == "true"){ ?>
<div> <div id="addProfileButton" style="float:right;"></div></div>
  <?php }?>

	<h3><a href="http://apps.facebook.com/<?php echo $app_url; ?>/" 
    target="_top"><?php bloginfo('name'); ?></a></h3>
	
</div>
<?php if(is_page()){ // is a page ?>
<div id="content">
				<div class="box_head clearfix"
				<h3 class="wpbook_box_header"><?php the_title(); ?></a></h3>
				<?php if (have_posts()) : while (have_posts()) : the_post();
					the_content();
				endwhile; else: ?>
					<p>
					<?php _e('Sorry, page does not exist.'); ?>
					</p>
			<?php endif; ?> 
</div>	
	<?php } 
else{
if(is_archive()){ //is an archive page  ?>
<div class="acomment">
 <?php /* If this is a category archive */ if (is_category()) { ?>
			<p><b><?php printf( __('You are currently browsing the %1$s archives for the \'%2$s\' category.'), $app_name, single_cat_title('', false) ) ?></b></p>

					
			<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
			<p><b>You are currently browsing the <?php $app_name; ?> archives for the day <?php the_time('l, F jS, Y'); ?>.</b></p>
			
			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<p><b>You are currently browsing the <?php $app_name; ?> archives 	for <?php the_time('F, Y'); ?>.</b></p>

      <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<p><b>You are currently browsing the <?php $app_name; ?> archives  for the year <?php the_time('Y'); ?>.</b></p>
			
		 <?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
			<p><b>You have searched the <?php $app_name; ?> archives for <strong>'<?php echo wp_specialchars($s); ?>'</strong>. </b></p>

			<?php /* If this is a monthly archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<p><b>You are currently browsing the <?php $app_name; ?> archives.</b></p>

			<?php } }?>
			</div>
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
				<div class="box_head clearfix" id="post-<?php the_ID(); ?>">
				<h3 class="wpbook_box_header">
					<?php if($show_date_title == "true"){the_time($timestamp_date_format); echo(" - ");}?> <a href="<?php the_permalink(); ?>" target="_top">
            <?php the_title(); ?></a></h3>
			<?php if(($show_custom_header_footer == "header") || ($show_custom_header_footer == "both")){
        echo( '<div id="custom_header">'.custom_header($custom_header,$timestamp_date_format,$timestamp_time_format) .'</div>');
      } // end if for showing customer header
      ?>

<?php if(($enable_share == "true" || $enable_external_link == "true") && ($links_position == "top")) { 
  echo '<p>';
  if($enable_share == "true"){?>
    <span class="wpbook_share_button">
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
  </span>
<?php } // end if for enable_share
  if($enable_external_link == "true"){ ?>
  <span class="wpbook_external_post"><a href="<?php echo get_external_post_url(get_permalink()); ?>" title="View this post outside Facebok at <?php bloginfo('name'); ?>">View post on <?php bloginfo('name'); ?></a></span>
<?php } // end if for enable external_link
  echo '</p>';
} ?>
			
	<?php the_content(); ?>	
			
					<?php // echo custom footer
					if(($show_custom_header_footer == "footer") || ($show_custom_header_footer == "both")){	
            echo('<div id="custom_footer">'.custom_header($custom_footer,$timestamp_date_format,$timestamp_time_format) .'</div>');
          } // endif for header or footer 
          ?>

					<?php // get share link 
if(($enable_share == "true" || $enable_external_link == "true") && ($links_position == "bottom")) { 
  echo '<p>';
if($enable_share == "true"){?>
<span class="wpbook_share_button">
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
</span>
<?php } 
if($enable_external_link == "true"){?>
<span class="wpbook_external_post"><a href="<?php echo get_exteral_post_url(get_permalink()); ?>" title="View this post outside Facebook at <?php bloginfo('name'); ?>">View post on <?php bloginfo('name'); ?></a></span>
<?php }
  echo '</p>';
} ?>
	</div>	
				<?php
				comments_template(); 
			endwhile; // while have posts
		endif; // if have posts	
?>
</div>
<?php 	 
} //end if blog or arvhive 

if($show_pages == "true"){?>
<div class="box_head clearfix">
				<h3 class="wpbook_box_header">
					<?php _e('Pages'); ?></h3>
				<ul>
					    <?php wp_list_pages('sort_column=menu_order&title_li='); ?>
				</ul>
</div>
<?php }?>

<div class="box_head clearfix">
				<h3 class="wpbook_box_header">
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
  <?php if($enable_profile_link == "true" ){ ?>
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
  } // end john's catch test
?>
</html>
<?php
  } // end if not invite condition 
?>