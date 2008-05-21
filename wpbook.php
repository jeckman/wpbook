<?php
/*
Plugin Name: WPBook
Plugin URI: http://www.scholarpress.net
Description: Plugin to embed Wordpress Blog into Facebook Canvas using the Facebook Platform.
Date: 2008, May 14
Author: Dave Lester, John Eckman
Author URI: http://www.davelester.org
Author URI: http://www.johneckman.com/
Version: 0.7
*/

/*
	Note: As od version 0.7, this plugin draws inspiration (and code) from Alex King's
	WP-Mobile plugin (http://alexking.org/projects/wordpress) adapted by John Eckman for Facebook context.
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

$_SERVER['REQUEST_URI'] = ( isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME'] . (( isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')));

function is_authorized() {
	global $user_level;
	if (function_exists("current_user_can")) {
		return current_user_can('activate_plugins');
	} else {
		return $user_level > 5;
	}
}

function getAdminOptions() {
	$wpbookOptions = get_option('wpbookAdminOptions');
	if (!empty($wpbookOptions)) {
		foreach ($wpbookOptions as $key => $option)
			$wpbookAdminOptions[$key] = $option;
		}
	return $wpbookAdminOptions;
}

function setAdminOptions($wpbook_installation, $fb_api_key, $fb_secret, $fb_app_url) {
	$wpbookAdminOptions = array('wpbook_installation' => $wpbook_installation,
		'fb_api_key' => $fb_api_key,
		'fb_secret' => $fb_secret,
		'fb_app_url' => $fb_app_url);
	update_option('wpbookAdminOptions', $wpbookAdminOptions);
	
}


function wpbook_options_page() {
	if (function_exists('add_options_page')) {
		add_options_page('WPBook', 'WPBook', 8, basename(__FILE__), 'wpbook_subpanel');
	}
}

function wpbook_subpanel() {
	if (is_authorized()) {
		if (isset($_POST['fb_api_key']) && isset($_POST['fb_secret'])) { 
			$fb_api_key = $_POST['fb_api_key'];
			$fb_secret = $_POST['fb_secret'];
			$fb_app_url = $_POST['fb_app_url'];
			setAdminOptions(1, $fb_api_key, $fb_secret, $fb_app_url);
			$flash = "Your settings have been saved. ";
		} else {
			$flash = "You must complete all fields completely";
		}
	} else {
		$flash = "You don't have enough access rights.";
	}
	
	if (is_authorized()) {
		$wpbookAdminOptions = getAdminOptions();
		if ($wpbookAdminOptions['wpbook_installation'] != 1) {   // fires every time, since get fails. 
			setAdminOptions(1, null,null,null);
		}
		
		if ($flash != '') echo '<div id="message"class="updated fade"><p>' . $flash . '</p></div>';
		echo '<div class="wrap">';
		echo '<h2>Set Up Your Facebook Application</h2>';
		echo '<p>This plugin allows you to embed your blog into the Facebook canvas, and in future versions - users who have installed the app will be able to receive notifications upon new blog posts.  Note that this is the pre-beta version, so there are bound to be some quirks.</p>';
		echo '<form action="';
		echo $_SERVER["REQUEST_URI"];
		echo '" method="post">';
		echo '<ol>';
		echo '<li>To use this app, you must register for an API key at <a href="http://www.facebook.com/developers/">http://www.facebook.com/developers/</a>.  Follow the link and click "set up a new application."  After you\'ve obtained the necessary info, fill in both your application\'s API and Secret keys.</li>';
		echo '<li>Enter Your Facebook Application\'s API Key:<br /><input type="text" name="fb_api_key" value="' . htmlentities($wpbookAdminOptions['fb_api_key']) . '" size="45" /></li>';
		echo '<li>Enter Your Facebook Application\'s Secret:<br /><input type="text" name="fb_secret" value="' . htmlentities($wpbookAdminOptions['fb_secret']) . '" size="45" /></li>';
		echo '<li>Enter Your Facebook Application\'s Canvas Page URL: ( http://apps.facebook.com/<just this bit> )<br /><input type="text" name="fb_app_url" value="' . htmlentities($wpbookAdminOptions['fb_app_url']) . '" size="45" /></li>';		
		echo '</ol>';
		echo '<p><input type="submit" value="Save" /></p></form>';
		echo '</div>';
	} else {
		echo '<div class="wrap"><p>Sorry, you are not allowed to access this page.</p></div>';
	}
}


if (!function_exists('wp_recent_posts')) {
// this is based almost entirely on: Recent Posts http://mtdewvirus.com/code/wordpress-plugins/ v. 1.07
// by Nick Momrik, http://mtdewvirus.com/
	function wp_recent_posts($count = 5, $before = '<li>', $after = '</li>', $hide_pass_post = true, $skip_posts = 0, $show_excerpts = false, $where = '', $join = '', $groupby = '') {
		global $wpdb;
		$time_difference = get_settings('gmt_offset');
		$now = gmdate("Y-m-d H:i:s",time());
	
		$join = apply_filters('posts_join', $join);
		$where = apply_filters('posts_where', $where);
		$groupby = apply_filters('posts_groupby', $groupby);
		if (!empty($groupby)) { $groupby = ' GROUP BY '.$groupby; }
	
		$request = "SELECT ID, post_title, post_excerpt FROM $wpdb->posts $join WHERE post_status = 'publish' AND post_type != 'page' ";
		if ($hide_pass_post) $request .= "AND post_password ='' ";
		$request .= "AND post_date_gmt < '$now' $where $groupby ORDER BY post_date DESC LIMIT $skip_posts, $count";
		$posts = $wpdb->get_results($request);
		$output = '';
		if ($posts) {
			foreach ($posts as $post) {
				$post_title = stripslashes($post->post_title);
				$permalink = get_permalink($post->ID);
				$output .= $before . '<a href="' . $permalink . '" rel="bookmark" title="Permanent Link: ' . htmlspecialchars($post_title, ENT_COMPAT) . '">' . htmlspecialchars($post_title) . '</a>';
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
	if (isset($_GET['fb_sig_in_iframe'])) {  // this just checks a simple thing
		return true;
	}
	return false;
}

// this checks if wpbook is installed and if so returns that as the theme name
function wpbook_template($theme) {
	if (wpbook_installed()) {
		return apply_filters('wpbook_template', 'wp-facebook');
	}
	else {
		return $theme;
	}
}

// this just checks whether the theme is installed
function wpbook_installed() {
	return is_dir(ABSPATH.'/wp-content/themes/wp-facebook');
}

// this alerts user if the theme is deleted
if (is_admin_page() && !wpbook_installed()) {
	global $wp_version;
	if (isset($wp_version) && version_compare($wp_version, '2.5', '>=')) {
		add_action('admin_notices', create_function( '', "echo '<div class=\"error\">WPBook is incorrectly installed. Please check the <a href=\"http://alexking.org/projects/wordpress/readme?project=wordpress-mobile-edition\">README</a>.</div>';" ) );
	}
}

// this is the function which adds to the template and stylesheet hooks the call to wpbook_template∑
if (check_facebook()) {
	add_action('template', 'wpbook_template');
	add_action('option_template', 'wpbook_template');
	add_action('option_stylesheet', 'wpbook_template');
}

// also have to change permalinks and next/prev links and more links
function fb_filter_postlink($postlink) {
	if (check_facebook()) {
		$my_fullurl = get_option('home');
		$my_offset = 30;
		$my_options = getAdminOptions();
		$app_url = $my_options['fb_app_url'];
		$my_link = 'http://apps.facebook.com/' . $app_url . substr($postlink,30); 
		return $my_link;
	} else {
		return $postlink; 
	}
}

add_filter('post_link','fb_filter_postlink',1,1);
add_action('admin_menu', 'wpbook_options_page');

?>