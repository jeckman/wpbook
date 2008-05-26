=== WPBook ===
Contributors: davelester, johneckman
Donate link: http://www.davelester.org
Tags: facebook, platform, application, blog, mirror
Stable tag: 0.7.2

Plugin to embed Wordpress Blog into Facebook Canvas using the Facebook Platform.

== Installation ==
1. Copy wpbook.php into your wordpress plugins folder, normally located
   in /wp-content/plugins/

2. Copy the wp-facebook.php directory into your themes folder, normally
   located in /wp-content/themes/
 
3. Set up a New Application at http://www.facebook.com/developers/, obtaining
   a secret and API key.  Set the callback url to your blog url. 
	(http://www.yourblogurl.com/)
   For canvas url, you just need something unique, with no spaces - remember it. 
   Set the application type to "website"
   Set the sidenav url to your canvas url
   Set the application to use an iFrame, not fbml, and to "resizable"
   (Using iFrames lets you use javascript, objects, and other tags not allowed in FBML)

4. Login to Wordpress Admin and activate the plugin

5. Using the WPBook menu, (located under the options tag, "WPBook") fill in the appropriate
   information including Facebook application secret and API keys, as well as your application canvas url

== Frequently Asked Questions ==
= How do I edit the way my Facebook Application (mirrored blog) looks? =

In the wp-facebook theme directory, there is an index.php file.  Most of what you want is there.  

There's also a style.css which basically mimics Facebook's styles, as well as some 
other files for processing comments and the like.  

== Version History ==

= Version 0.7.2 =
* bug fix
* no try { } catch {} in PHP4

= Version 0.7.1 =
* bug fix
* comments_facebook.php was not being found
* created fb_comments_template function instead

= Version 0.7 =
* Major architecture changes
* Relies on a theme, not creation of a page
* Inspired by Alex King's mobile plugin (http://alexking.org/projects/wordpress)
* Enables recent posts and post navigation
* Added app canvas url to options for use as redirect post-comment submission

= Version 0.6 =
* Added support for posting comments
* Switched to iFrame to allow more code in blog posts
* Added Facebook javascript for resizing iFrame
* Added style.css for styling
* fixed bug in storing options
* consolidated Facebook client stuff in config.php
* auto detect php version and set client include accordingly

= Version 0.5 =
* Added support for PHP4 Facebook Client Library
* Options combined into associative array to speed-up and remove interference w/ other plugins

= Version 0.4 =
* First push to WP-Plugins Directory

== To Do ==

= Version 0.8 =
* Option for comments or no comments in admin section
* Better accompanying documentation
