<?php
/*
Plugin Name: WPBook
Plugin URI: http://www.scholarpress.net
Description: Plugin to embed Wordpress Blog into Facebook Canvas using the Facebook Platform.
Date: 2007, October, 21
Author: Dave Lester
Author URI: http://www.davelester.org
Version: 0.5
*/

/*  Copyright 2007  Dave Lester

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

class wpbook_mypost {
	var $post_content;
	var $post_title;    
	var $post_status;    
}

function wpbook_is_authorized() {
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

function wpbook_setAdminOptions($wpbook_installation, $fb_api_key, $fb_secret) {
	$wpbookAdminOptions = array('wpbook_installation' => $wpbook_installation,
		'fb_api_key' => $fb_api_key,
		'fb_secret' => $fb_secret);
		
	update_option('wpbookAdminOptions', $wpbookAdminOptions);
}


function wpbook_options_page() {
	if (function_exists('add_options_page')) {
		add_options_page('WPBook', 'WPBook', 8, basename(__FILE__), 'wpbook_subpanel');
	}
}

function wpbook_subpanel() {
	global $flash, $fb_api_key, $fb_secret, $_POST, $wp_rewrite;
	if (wpbook_is_authorized()) {
		if (isset($_POST['fb_api_key']) && isset($_POST['fb_secret'])) { 
			$fb_api_key = $_POST['fb_api_key'];
			$fb_secret = $_POST['fb_secret'];
			setAdminOptions(null, $fb_api_key, $fb_secret);
			$flash = "Your settings have been saved.";
		} else {
			$flash = "You must complete all fields completely";
		}
	}	else {
		$flash = "You don't have enough access rights.";
	}
	
	$wpbookAdminOptions = wpbook_getAdminOptions();

	;
	
	if (wpbook_is_authorized()) {
		if ($wpbookAdminOptions['wpbook_installation'] != 1) {
			global $wpdb;
		
		$title = "WPBook";
		$content = "WPBook";
		$parent_id = 1; // Uncategorized default
		$post_status = 'publish';  // default
		$post_type = 'page';
		$page_template = '../../plugins/wpbook/template.php';
		$post_author = 1; // Default admin
		$post_category = array( 'post_category' => $parent_id );

		// create the object
		$myobject = new wpbook_mypost();

		// fill object
		$myobject->post_title = $title;
		$myobject->post_content = $content;
		$myobject->post_status = $post_status;
		$myobject->post_type = $post_type;
		$myobject->page_template = $page_template;
		$myobject->post_author = $post_author;
		$myobject->post_category = $post_category;
		
		// feed object to wp_insert_post
		wp_insert_post($myobject);

		wpbook_setAdminOptions(1, null, null);
		}
		
	if ($flash != '') echo '<div id="message"class="updated fade"><p>' . $flash . '</p></div>';
	
		echo '<div class="wrap">';
		echo '<h2>Set Up Your Facebook Application</h2>';
		echo '<p>This plugin allows you to embed your blog into the Facebook canvas, and in future versions - users who have installed the app will be able to receive notifications upon new blog posts.  Note that this is the pre-beta version, so there are bound to be some quirks.</p>
		<form action="" method="post">
		<input type="hidden" name="redirect" value="true" />
		<ol>
		<li>To use this app, you must register for an API key at <a href="http://www.facebook.com/developers/">http://www.facebook.com/developers/</a>.  Follow the link and click "set up a new application."  After you\'ve obtained the necessary info, fill in both your application\'s API and Secret keys.</li>
		<li>Enter Your Facebook Application\'s API Key:<br /><input type="text" name="fb_api_key" value="' . htmlentities($wpbookAdminOptions['fb_api_key']) . '" size="45" /></li>
		<li>Enter Your Facebook Application\'s API Key:<br /><input type="text" name="fb_secret" value="' . htmlentities($wpbookAdminOptions['fb_secret']) . '" size="45" />
		</ol>
		<p><input type="submit" value="Save" /></p></form>';
		echo '</div>';
	} else {
		echo '<div class="wrap"><p>Sorry, you are not allowed to access this page.</p></div>';
	}

}

add_action('admin_menu', 'wpbook_options_page');
?>
