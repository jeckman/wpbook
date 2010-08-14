<?php
// set up occurs in the config.php
if(isset($_GET['app_tab'])) { // this is an app tab
  // output tab
  ?>
  <fb:fbml>
  <div id="content">
  <?php 	
  have_posts();
  if (have_posts()) : 
    while (have_posts()) : 
      the_post(); 
      echo '<div class="box_head clearfix" id="post-'.  get_the_ID() .'">';
      echo  '<h3 class="wpbook_box_header">';
      if($show_date_title == "true"){
        the_time($timestamp_date_format); 
        echo(" - ");
      }
      echo '<a href="'. get_permalink() .'" target="_top">'. get_the_title() .'</a></h3>';
      if(($show_custom_header_footer == "header") || ($show_custom_header_footer == "both")){
        echo( '<div id="custom_header">'.custom_header_footer($custom_header,$timestamp_date_format,$timestamp_time_format) .'</div>');
      } // end if for showing customer header
  
      if(($enable_share == "true" || $enable_external_link == "true") && ($links_position == "top")) { 
        echo '<p>';
        if($enable_share == "true"){
          echo '<span class="wpbook_share_button">';
          echo '<a onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=';
          echo urlencode(get_the_title());
          echo '&amp;p[summary]=';
          echo urlencode(get_the_excerpt());
          echo '&amp;p[url]=';
          echo urlencode(get_permalink());
          echo "','sharer','toolbar=0,status=0,width=626,height=436'); return false;\""; 
          echo ' class="share" title="Send this to friends or post it on your profile.">Share This Post</a>';
          echo '</span>';
        } // end if for enable_share
        if($enable_external_link == "true"){ 
          echo '<span class="wpbook_external_post"><a href="'. get_external_post_url(get_permalink()) .'" title="View this post outside Facebook at '. get_bloginfo('name') .'">View post on '. get_bloginfo('name') .'</a></span>';
        } // end if for enable external_link
          echo '</p>';
      } // end links_position _top
            
      the_content();
            
      // echo custom footer
      if(($show_custom_header_footer == "footer") || ($show_custom_header_footer == "both")){	
        echo('<div id="custom_footer">'.custom_header_footer($custom_footer,$timestamp_date_format,$timestamp_time_format) .'</div>');
      } // endif for footer 
            
      // get share link 
      if(($enable_share == "true" || $enable_external_link == "true") && ($links_position == "bottom")) { 
        echo '<p>';
        if($enable_share == "true"){
          echo '<span class="wpbook_share_button">';
          echo '<a onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=';
          echo urlencode(get_the_title());
          echo '&amp;p[summary]=';
          echo urlencode(get_the_excerpt());
          echo '&amp;p[url]=';
          echo urlencode(get_permalink());
          echo "','sharer','toolbar=0,status=0,width=626,height=436'); return false;\""; 
          echo ' class="share" title="Send this to friends or post it on your profile.">Share This Post</a>';
          echo '</span>';
        } // end enable_share = true 
        if($enable_external_link == "true"){
          ?><span class="wpbook_external_post"><a href="<?php echo get_external_post_url(get_permalink()); ?>" title="View this post outside Facebook at <?php bloginfo('name'); ?>">View post on <?php bloginfo('name'); ?></a></span><?php
        }
        echo '</p>';
      } // end if for enable share, external, bottom
      echo '</div>';	
    endwhile; // while have posts
  endif; // if have posts	
  echo '</div>';
  echo '</fb:fbml>';  
} else { // not the tab page
  
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
  }  // end of the else for $_POST["ids"]
} // end of the if for $_GET['is_invite']

// Done with potential invite page, now do permissions
if(isset($_GET['is_permissions'])) { // we're looking for extended permissions
  $receiver_url = WP_PLUGIN_URL . '/wpbook/theme/default/xd_receiver.html';
  ?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
  <html xmlns="http://www.w3.org/1999/xhtml" 
        xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
  <title><?php bloginfo('name'); ?> :: Facebook Blog Application</title>
  <link rel="stylesheet" href="<?php echo WP_PLUGIN_URL ?>/wpbook/theme/default/style.css" 
      type="text/css" media="screen" />
  <BASE TARGET="_top">	
  </head>
  <body>
  <script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
  <p>This page is where you can check and grant extended permissions, which enable WPBook to 
   publish to your personal wall and/or to the walls of fan pages.</p>
  <p>Your userid is <?php echo $user; ?> </p>
  <p><strong>You will need to enter that number into the WPBook settings page on your WordPress install.</strong></p>
  <p><a href="
<?php 
  $my_permissions_url = 'http://www.facebook.com/connect/prompt_permissions.php?api_key='.
                        $api_key .'&display=popup&ext_perm=read_stream,publish_stream,offline_access&extern=1'.
                        '&next=http://apps.facebook.com/' . htmlentities($wpbookAdminOptions['fb_app_url']) 
                        .'/?wpbook=catch_permissions';

  echo $my_permissions_url;
?>
" target="_top">Grant permissions for your userid.</a> (This is required if you intend to publish to your personal wall OR any fan pages.)</p>
<p>You are also listed as the admin of these pages:
  <ul>
  <?php 
  $query = "SELECT name, page_id, has_added_app FROM page WHERE page_id IN (SELECT name, page_id FROM page WHERE page_id IN (SELECT page_id FROM page_admin WHERE uid = $user))";
  try {
    $second_result = $facebook->api_client->fql_query($query);
  } catch (Exception $e) {
    if ($wpbook_show_errors) {
      $wpbook_message = 'Caught exception in second_result query: ' . $e->getMessage();
      wp_die($wpbook_message,'WPBook Error');
    }
  }
  if((!empty($second_result))) {
    foreach ($second_result as $page) {
      if($page['has_added_app']) {
        echo '<li>'. $page['name'] .' ('. $page['page_id'] .'), ';
        // Using FQL based lookup, per http://forum.developers.facebook.com/viewtopic.php?pid=213979
        $perm = '';
        $permissions_fql = 'SELECT publish_stream FROM permissions WHERE uid = '.$page['page_id'].' ';
        //echo '<!-- permissions_fql was ' . $permissions_fql . ' -->'; 
        try {
          $perm = $facebook->api_client->fql_query($permissions_fql);
        } catch (Exception $e) {
          if ($wpbook_show_errors) {
            $wpbook_message = 'Caught exception in fql_query: ' . $e->getMessage();
            wp_die($wpbook_message,'WPBook Error');
          }
        }
        //This query will return an array as follows if the permission was found.
        //Array ( [0] => Array ( [publish_stream] => 1 ) )
        //If there was no permission set it will return an empty string. 
        //echo '<!-- perm was ' . print_r($perm) . ' -->'; 

        if (($perm == '') || ($perm[0]['publish_stream'] != 1)) { 
          echo 'This page has NOT granted stream.publish permissions to this app. ';
          echo '<a href="http://www.facebook.com/connect/prompt_permissions.php?api_key=';
          echo $api_key;
          echo '&v=1.0&next=';
          echo 'http://apps.facebook.com/'. urlencode($wpbookAdminOptions['fb_app_url']);
          echo '/?wpbook=catch_perms&extern=1&display=popup&ext_perm=publish_stream&enable_profile_selector=1&profile_selector_ids=';
          echo $page['page_id'];
          echo '" target="_top">Grant stream.publish for this page</a>. ';          
        } else { 
          echo 'This page has granted stream.publish permissions to this app. ';
        }  
      echo '</li>'; 
      } // end if has_added_app
    } // end for each
  } // end if !empty
  ?>
  </ul></p>
  <p>You can use the page IDs of any of these pages in the WPBook settings to publish to that page's wall.</p>

  <p>If you are the administrator of pages which do not show up in this list, 
    you need to ensure you have added the application to the pages first.</p>
  <p>Follow the <a href="<?php echo WP_PLUGIN_URL ?>/wpbook/install_instructions.html" target="_new">detailed directions</a> included with the plugin.</p>
  <script type="text/javascript">
    FB_RequireFeatures(["XFBML"],function() {
      FB.Facebook.init('<?php echo $api_key; ?>',
                      '<?php echo $receiver_url; ?>',
                        null);
    }); 
  </script></body>
  </html>
  <?php 
} // end of the permissions page, now regular themed page

if((!isset($_GET['is_invite']))&&(!isset($_GET['is_permissions']))) {  // this is the regular blog page
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
    <?php 
    if($invite_friends == "true"){
      $invite_link = '<a href="http://apps.facebook.com/' . $app_url 
        .'/index.php?is_invite=true&fb_force_mode=fbml" class="share"> Invite Friends</a>';
      ?>
      <div style="float:right;"><span class="wpbook_invite_button"><?php echo("$invite_link") ?></span> </div>	
      <?php 
    } 
    if($enable_profile_link == "true"){ 
      ?>
      <div> <div id="addProfileButton" style="float:right;"></div></div>
      <?php 
    }
    ?>
    <h3><a href="http://apps.facebook.com/<?php echo $app_url; ?>/" 
      target="_top"><?php bloginfo('name'); ?></a></h3>
    <?php 
    if($show_pages_menu == "true"){ ?>
      <div id="underlinemenu" class="clearfix">
        <ul>
    	    <li>Pages:</li>
    	    <?php if ($exclude_pages_true == "true"){wp_list_pages("sort_column=menu_order&depth=1&title_li=&exclude=$exclude_pages_list");}
				else{wp_list_pages("sort_column=menu_order&depth=1&title_li=");} ?>
    	    </ul>
    	    </div>
    <?php 
    } //end show pages menu
    ?>
    </div>
    <?php 
    if(is_page()){ // is a page 
      ?>
      <div id="content">
				<div class="box_head clearfix">
				<h3 class="wpbook_box_header"><?php the_title(); ?></a></h3>
				<?php if (have_posts()) : while (have_posts()) : the_post();
					the_content();
				endwhile; else: ?>
					<p>
					<?php _e('Sorry, page does not exist.'); ?>
					</p>
			<?php endif; ?> 
      </div>	
	    <?php 
    } // end if is_page()
    else{
      if(is_archive()){ //is an archive page  
        ?><div class="archive">
        <?php 			/*If this is a category archive */ 
        if (is_category()) { 
          ?><p><b><?php printf( __('You are currently browsing the %1$s archives for the \'%2$s\' category.'), $app_name, single_cat_title('', false) ) ?></b></p>
          <?php /* If this is a yearly archive */ 
        } elseif (is_day()) { 
          ?><p><b>You are currently browsing the <?php $app_name; ?> archives for the day <?php the_time('l, F jS, Y'); ?>.</b></p>
          <?php /* If this is a monthly archive */ 
        } elseif (is_month()) { 
          ?><p><b>You are currently browsing the <?php $app_name; ?> archives 	for <?php the_time('F, Y'); ?>.</b></p>
          <?php /* If this is a yearly archive */ 
        } elseif (is_year()) { 
          ?><p><b>You are currently browsing the <?php $app_name; ?> archives  for the year <?php the_time('Y'); ?>.</b></p>
          <?php /* If this is a monthly archive */ 
        } elseif (is_search()) { 
          ?><p><b>You have searched the <?php $app_name; ?> archives for <strong>'<?php echo wp_specialchars($s); ?>'</strong>. </b></p>
          <?php /* If this is a monthly archive */ 
        } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { 
          ?><p><b>You are currently browsing the <?php $app_name; ?> archives.</b></p><?php 
        }/* If this is a tag archive */	
        elseif(is_tag()){ 
          ?><p><b><?php printf( __('You are currently browsing the %1$s archives for the \'%2$s\' tag.'), $app_name, single_tag_title('', false) ) ?></b></p>
				<?php 
        } 
      } // end of if is_archive() 
      ?>
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
          } //end if single 
          ?> 
          <div class="box_head clearfix" id="post-<?php the_ID(); ?>">
          <h3 class="wpbook_box_header">
          <?php if($show_date_title == "true"){
            the_time($timestamp_date_format); 
            echo(" - ");
          }
          ?><a href="<?php the_permalink(); ?>" target="_top">
          <?php the_title(); ?></a></h3><?php 
          if(($show_custom_header_footer == "header") || ($show_custom_header_footer == "both")){
            echo( '<div id="custom_header">'.custom_header_footer($custom_header,$timestamp_date_format,$timestamp_time_format) .'</div>');
          } // end if for showing customer header
      
          if(($enable_share == "true" || $enable_external_link == "true") && ($links_position == "top")) { 
            echo '<p>';
            if($enable_share == "true"){
              ?><span class="wpbook_share_button"><?php
              echo '<a onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=';
              echo urlencode(get_the_title());
              echo '&amp;p[summary]=';
              echo urlencode(get_the_excerpt());
              echo '&amp;p[url]=';
              echo urlencode(get_permalink());
              echo "','sharer','toolbar=0,status=0,width=626,height=436'); return false;\""; 
              echo ' class="share" title="Send this to friends or post it on your profile.">Share This Post</a>';
              echo '</span>';
            } // end if for enable_share
            if($enable_external_link == "true"){ 
              ?><span class="wpbook_external_post"><a href="<?php echo get_external_post_url(get_permalink()); ?>" title="View this post outside Facebook at <?php bloginfo('name'); ?>">View post on <?php bloginfo('name'); ?></a></span><?php 
            } // end if for enable external_link
            echo '</p>';
          } // end links_position _top
            
          the_content();
			
          // echo custom footer
          if(($show_custom_header_footer == "footer") || ($show_custom_header_footer == "both")){	
            echo('<div id="custom_footer">'.custom_header_footer($custom_footer,$timestamp_date_format,$timestamp_time_format) .'</div>');
          } // endif for footer 
    
          // get share link 
          if(($enable_share == "true" || $enable_external_link == "true") && ($links_position == "bottom")) { 
            echo '<p>';
            if($enable_share == "true"){
              echo '<span class="wpbook_share_button">';
              echo '<a onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=';
              echo urlencode(get_the_title());
              echo '&amp;p[summary]=';
              echo urlencode(get_the_excerpt());
              echo '&amp;p[url]=';
              echo urlencode(get_permalink());
              echo "','sharer','toolbar=0,status=0,width=626,height=436'); return false;\""; 
              echo ' class="share" title="Send this to friends or post it on your profile.">Share This Post</a>';
              echo '</span>';
            } // end enable_share = true 
            if($enable_external_link == "true"){
              ?><span class="wpbook_external_post"><a href="<?php echo get_external_post_url(get_permalink()); ?>" title="View this post outside Facebook at <?php bloginfo('name'); ?>">View post on <?php bloginfo('name'); ?></a></span><?php
            }
            echo '</p>';
          } // end if for enable share, external, bottom
          echo '</div>';	

          comments_template(); 
  
        endwhile; // while have posts
      endif; // if have posts	

      echo '</div>';
    } //end if else for if_page() - blog or archive 

    if($show_pages == "true" && $show_page_list=="true"){
      ?><div class="box_head clearfix">
      <h3 class="wpbook_box_header">
      <?php _e('Pages'); ?></h3>
      <ul>
      <?php 
      if ($exclude_pages_true == "true"){
        wp_list_pages("sort_column=menu_order&title_li=&exclude=$exclude_pages_list");
      } else {
        wp_list_pages("sort_column=menu_order&title_li=");
      } ?>
      </ul>
      </div>
      <?php 
    }
    
    if($show_post_list == "true"){
      ?><div class="box_head clearfix">
      <h3 class="wpbook_box_header">
      <?php _e('Recent Posts'); ?></h3>
      <ul><?php wp_recent_posts($recent_post_list_amount); ?></ul>
      </div>
      <?php 
    }
    
    if ($give_credit == "true"){ 
      ?><div class="box_head clearfix" style="padding: 5px 0 0 0;">
      <p><small>This Facebook Application powered by <a href="http://www.wordpress.org/extend/plugins/wpbook/">the WPBook plugin</a>
      for <a href="http://www.wordpress.org/">WordPress</a>.</small></p>
      </div><?php 
    } 
    
    if($enable_profile_link == "true" ){ 
      ?>
      <script type="text/javascript">
        FB_RequireFeatures(["XFBML"],function() {
          FB.Facebook.init('<?php echo $api_key; ?>',
                     '<?php echo $receiver_url; ?>',
                      null);
          FB.Connect.showAddSectionButton('profile',
                                          document.getElementById('addProfileButton'));
                           });   
      </script>
      <?php 
    } ?>
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
  } // end else for if (fb_page_id)
  ?>
  </html>
<?php
} // end if not invite, not permissions condition 
  } // end of not tab
?>