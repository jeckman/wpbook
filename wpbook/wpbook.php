<?php
/*
Plugin Name: WPBook
Plugin URI: http://www.scholarpress.net
Date: 2009, January 13
Description: Plugin to embed Wordpress Blog into Facebook Canvas using 
the Facebook Platform. <b>If you update via automatic update, be sure 
to copy theme to appropriate directory!</b> <em>By 
<a href="http://johneckman.com/">John Eckman</a>.</em> 
Author: Dave Lester
Author URI: http://www.davelester.org
Version: 0.9.5b
*/

/*
Note: As od version 0.7, this plugin draws inspiration (and code) from: 
   Alex King's WP-Mobile plugin (http://alexking.org/projects/wordpress ) 
   BraveNewCode's WPTouch (http://www.bravenewcode.com/wptouch/ )
*/

/*  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// this function checks for admin pages
if (!function_exists('is_admin_page')) {
  function is_admin_page() {
    if (function_exists('is_admin')) {
      return is_admin();
    }
		if (function_exists('check_admin_referer')) {
			return true;
		}
		else {
			return false;
		}
  }
}

$_SERVER['REQUEST_URI'] = ( isset($_SERVER['REQUEST_URI']) ? 
  $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME'] 
  . (( isset($_SERVER['QUERY_STRING']) ? '?' 
  . $_SERVER['QUERY_STRING'] : '')));

function is_authorized() {
  global $user_level;
	if (function_exists("current_user_can")) {
		return current_user_can('activate_plugins');
	} else {
		return $user_level > 5;
	}
}

function wpbook_getAdminOptions() {
	$wpbookOptions = get_option('wpbookAdminOptions');
	if (!empty($wpbookOptions)) {
		foreach ($wpbookOptions as $key => $option)
			$wpbookAdminOptions[$key] = $option;
		}
	return $wpbookAdminOptions;
}

function setAdminOptions($wpbook_installation, $fb_api_key, $fb_secret, 
  $fb_app_url, $fb_app_name,$invite_friends,$require_email) {
	$wpbookAdminOptions = array(
		'wpbook_installation' => $wpbook_installation,
		'fb_api_key' => $fb_api_key,
		'fb_secret'  => $fb_secret,
		'fb_app_url' => $fb_app_url,
		'fb_app_name'=> $fb_app_name,
		'invite_friends' => $invite_friends,
		'require_email' => $require_email);
	update_option('wpbookAdminOptions', $wpbookAdminOptions);
}

function wpbook_options_page() {
	if (function_exists('add_options_page')) {
		add_options_page('WPBook', 'WPBook', 8, 
		  basename(__FILE__), 'wpbook_subpanel');
	}
}

function wpbook_subpanel() {
	if (is_authorized()) {
		if (isset($_POST['fb_api_key']) 
		  && isset($_POST['fb_secret'])) { 
			$fb_api_key = $_POST['fb_api_key'];
			$fb_secret = $_POST['fb_secret'];
			$fb_app_url = $_POST['fb_app_url'];
			$fb_app_name = $_POST['fb_app_name'];
			$invite_friends = $_POST['invite_friends'];
			$require_email = $_POST['require_email'];
			setAdminOptions(1, $fb_api_key, $fb_secret, $fb_app_url, $fb_app_name,
        $invite_friends,$require_email);
			$flash = "Your settings have been saved. ";
		} else {
			$flash = "Please complete all necessary fields";
		}
	} else {
		$flash = "You don't have enough access rights.";
	}
	
	if (is_authorized()) {
		$wpbookAdminOptions = wpbook_getAdminOptions();
		if ($wpbookAdminOptions['wpbook_installation'] != 1) {  
			setAdminOptions(1, null,null,null);
		}
		
		if ($flash != '') echo '<div id="message"class="updated fade">'
			. '<p>' . $flash . '</p></div>';
		echo '<div class="wrap">';
		echo '<h2>Set Up Your Facebook Application</h2><p>';
		echo 'This plugin allows you to embed your blog into the Facebook canvas';
    echo ', and in future versions - users who have installed the app will be ';
    echo 'able to receive notifications upon new blog posts. </p>';
		echo '<form action="'. $_SERVER["REQUEST_URI"] .'" method="post">';
		echo '<ol>';
		echo '<li>To use this app, you must register for an API key at ';
    echo '<a href="http://www.facebook.com/developers/">';
    echo 'http://www.facebook.com/developers/</a>.  Follow the link and click ';
    echo '"set up a new application."  After you\'ve obtained the necessary ';
    echo 'info, fill in both your application\'s API and Secret keys.</li>';
		echo '<li>Enter Your Facebook Application\'s API Key:';
    echo '<br /><input type="text" name="fb_api_key" value="';
    echo htmlentities($wpbookAdminOptions['fb_api_key']) .'" size="45" /></li>';
		echo '<li>Enter Your Facebook Application\'s Secret:<br />';
    echo '<input type="text" name="fb_secret" value="';
    echo htmlentities($wpbookAdminOptions['fb_secret']) .'" size="45" /></li>';
		echo '<li>Enter Your Facebook Application\'s Canvas Page URL, ';
    echo '<strong>NOT</strong> INCLUDING "http://apps.facebook.com/"<br />';
    echo '<input type="text" name="fb_app_url" value="';
    echo htmlentities($wpbookAdminOptions['fb_app_url']) .'" size="45" /></li>';
		echo '<li><input type="checkbox" name="invite_friends"';
    echo 'onclick="document.getElementById(\'invite_options\').style.';
    echo 'display=(document.getElementById(\'invite_options\').style.';
    echo 'display== \'none\')?\'block\':\'none\';" value = "true"';
    if( htmlentities($wpbookAdminOptions['invite_friends']) == "true"){ 
      echo("checked");
    }
    echo '> Show Invite Friends Link <div id="invite_options" style="display:';
		if( htmlentities($wpbookAdminOptions['invite_friends']) == "true"){ 
      echo("block");}else{ echo("none");
    }
		echo ';margin-top:15px;">Enter Your Application\'s Name:<br />';
    echo '<input type="text" name="fb_app_name" value="';
    echo htmlentities($wpbookAdminOptions['fb_app_name']);
    echo '" size="45" /> (no trailing spaces) </div> </li>';
		echo'<li><input type="checkbox" name="require_email" value = "true"';
    if( htmlentities($wpbookAdminOptions['require_email']) == "true"){ 
      echo("checked");
    }
    echo '> Require Comment Authors E-mail Address</li>';
		echo '</ol>';
    echo '<p>If you\'d like to enable users to add your application to ';
    echo 'Facebook <strong>Pages</strong> as well as individual user ';
    echo 'profiles, you\'ll need to enable &quot;add to pages&quot; in ';
    echo 'your application settings in Facebook, and set the ';
    echo '&quot;default FBML&quot; to the following: <br /><code>';
    echo '&lt;fb:ref url="' . get_bloginfo('wpurl');
    echo '/wp-content/themes/wp-facebook/recent_posts.php?fb_sig_in_iframe"';
    echo '/&gt;</code>';		

    echo '<p><input type="submit" value="Save" class="button"';
    echo 'name="wpbook_save_button" /></p></form>';
		echo '</div>';
	} else {
		echo '<div class="wrap"><p>Sorry, you are not allowed to access ';
    echo 'this page.</p></div>';
	}
}


if (!function_exists('wp_recent_posts')) {
// this is based almost entirely on: Recent Posts
// http://mtdewvirus.com/code/wordpress-plugins/ v. 1.07
// by Nick Momrik, http://mtdewvirus.com/
	function wp_recent_posts($count = 5, $before = '<li>', $after = '</li>',
      $hide_pass_post = true, $skip_posts = 0, $show_excerpts = false, 
      $where = '', $join = '', $groupby = '') {
		global $wpdb;
		$time_difference = get_settings('gmt_offset');
		$now = gmdate("Y-m-d H:i:s",time());
	
		$join = apply_filters('posts_join', $join);
		$where = apply_filters('posts_where', $where);
		$groupby = apply_filters('posts_groupby', $groupby);
		if (!empty($groupby)) { $groupby = ' GROUP BY '.$groupby; }
	
		$request = "SELECT ID, post_title, post_excerpt FROM $wpdb->posts "
      . "$join WHERE post_status = 'publish' AND post_type != 'page' ";
		if ($hide_pass_post) $request .= "AND post_password ='' ";
		$request .= "AND post_date_gmt < '$now' $where $groupby ORDER BY "
      . "post_date DESC LIMIT $skip_posts, $count";
		$posts = $wpdb->get_results($request);
		$output = '';
		if ($posts) {
			foreach ($posts as $post) {
				$post_title = stripslashes($post->post_title);
				$permalink = get_permalink($post->ID);
				$output .= $before . '<a href="' . $permalink . '" rel="bookmark" '
          . 'title="Permanent Link: ' 
          . htmlspecialchars($post_title, ENT_COMPAT) . '">'
          . htmlspecialchars($post_title) . '</a>';
				if($show_excerpts) {
					$post_excerpt = stripslashes($post->post_excerpt);
					$output.= '<br />' . $post_excerpt;
				}
				$output .= $after;
			}
		} else {
			$output .= $before . "None found" . $after;
		}
		echo $output;
	}
}


// this checks to see if we are in facebook
function check_facebook() {
	if (!isset($_SERVER["HTTP_USER_AGENT"])) {
		return false;
	}
	if (isset($_GET['fb_sig_in_iframe']) || isset($_GET['fb_force_mode'])) {  
		return true;
	}
	return false;
}

// Sets wp-facebook as the theme name
function wpbook_template($theme) {
		return apply_filters('wpbook_template', 'wp-facebook');
}

// this just checks whether the theme is installed
function wpbook_installed() {
	return is_dir(ABSPATH.'wp-content/themes/wp-facebook');
}

function wpbook_theme_root($path) {
	$theme_root = dirname(__FILE__);
	if (check_facebook()) {
		return $theme_root . '/theme'; 
	} else {
		return $path;
	}
}	

function wpbook_theme_root_uri($url) {
	if (check_facebook()) {
		$dir = get_bloginfo('wpurl') . "/wp-content/plugins/wpbook/theme";
		return $dir;
	} else {
		return $url;
	}
}
	
// this is the function which adds to the template and stylesheet hooks
// the call to wpbook_template
if (check_facebook()) {
	add_filter('theme_root', 'wpbook_theme_root');
  add_filter('theme_root_uri', 'wpbook_theme_root_uri');
  //add_action('template', 'wpbook_template');
	//add_action('option_template', 'wpbook_template');
	//add_action('option_stylesheet', 'wpbook_template');
}
             
// also have to change permalinks and next/prev links and more links
function fb_filter_postlink($postlink) {
	if (check_facebook()) {
		$my_offset = strlen(get_option('home'));
		$my_options = wpbook_getAdminOptions();
		$app_url = $my_options['fb_app_url'];
		$my_link = 'http://apps.facebook.com/' . $app_url 
      . substr($postlink,$my_offset); 
		return $my_link;
	} else {
		return $postlink; 
	}
}
	
function wp_update_profile_boxes() {
  if(!class_exists('FacebookRestClient')) {
    if (version_compare(PHP_VERSION,'5','>=')) {
      include_once(ABSPATH.'wp-content/plugins/wpbook/client/facebook.php');
	  } else {
		  include_once(ABSPATH.'wp-content/plugins/wpbook/php4client/'
        . 'facebook.php');
		  include_once(ABSPATH.'wp-content/plugins/wpbook/php4client/'
        . 'facebookapi_php4_restlib.php');
	  }
  }           
	$wpbookOptions = get_option('wpbookAdminOptions');
	
	if (!empty($wpbookOptions)) {
		foreach ($wpbookOptions as $key => $option)
		$wpbookAdminOptions[$key] = $option;
	}
	
	$api_key = $wpbookAdminOptions['fb_api_key'];
	$secret  = $wpbookAdminOptions['fb_secret'];
	
	$facebook = new Facebook($api_key, $secret);
	
	$url = 	get_bloginfo('wpurl')
    . "/wp-content/plugins/wpbook/theme/recent_posts.php?fb_sig_in_iframe";
	// Now you can update FBML pages, update your fb:ref tags, etc.
	$facebook->api_client->fbml_refreshRefUrl($url);	
}
	
//add_filter('comments_template','fb_comments_template',1,1);
add_filter('post_link','fb_filter_postlink',1,1);
add_action('admin_menu', 'wpbook_options_page');
	
// these capture new posts, not edits of previous posts	
add_action('future_to_publish','wp_update_profile_boxes');	
add_action('new_to_publish','wp_update_profile_boxes');
add_action('draft_to_publish','wp_update_profile_boxes');  
?>
