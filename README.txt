=== WPBook ===
Contributors: davelester, johneckman
Donate link: http://www.davelester.org
Tags: facebook, platform, application, blog, mirror
Stable tag: 0.8.2
Tested up to: 2.7
Requires at least: 2.5

Plugin to embed Wordpress Blog into Facebook Platform.

== Installation ==
1. Copy wpbook.php into your wordpress plugins folder, normally located
   in /wp-content/plugins/

2. Copy the wp-facebook directory into your themes folder, normally
   located in /wp-content/themes/

   Note that if you use the "automated update" of plugins feature, you will 
   need to copy the theme to the appropriate location each time an update 
   is issued.  

3. Set up a New Application at http://www.facebook.com/developers/, obtaining
   a secret and API key.  

   Set the callback url to your blog url, including  a trailing slash. 
       (http://www.yourblogurl.com/)
   For canvas url, you just need something unique, with no spaces, and 
   no trailing slash. Remember it. 
   
   Set the application type to "website"
   
   Set the sidenav url to your canvas url
   
   Set the application to use an iFrame, not fbml, and to "resizable"
   (Using iFrames lets you use javascript, objects, and other tags 
    not allowed in FBML inside blog posts)

4. Login to Wordpress Admin and activate the plugin

5. Using the WPBook menu, (located under the options tag, "WPBook") fill 
   in the appropriate information including Facebook application secret 
   and API keys, as well as your application canvas url. 

   Enabling the "Show Invite Friends Link" will show a link inside 
   Facebook when viewing your application which allows users to send
   invites to their friends. 

NOTE: If you update using the "automatic update" feature, you will
      need to copy the theme files (in the wp-facebook subdirectory) 
      over to wp-content/themes/ for the plugin updates to work. 

== Frequently Asked Questions ==

= How do I edit the way my Facebook Application (mirrored blog) looks? =

In the wp-facebook theme directory, there is an index.php file.  Most of 
what you want is there.  

There's also a style.css which basically mimics Facebook's styles, as well 
as some other files for processing comments and the like.  

== Version History ==

= Version 0.8.2 =
* Added option to require email address of comment author
* Can be set separately only for Facebook comment authors
* Functionality added by Brandon Dukes. 

= Version 0.8.1 =
* Oops. Typo in README.txt - Brandon Dukes.
* Issue with some text not being displayed
  on the invite form
* Tested with Wordpress 2.6.2

= Version 0.8 =
* Thanks to Brandon Dukes for contributing facebook invites - if you
  select 'display invite friends link' checkbox in the wp-admin 
  settings for WPBook, you can invite facebook friends!
* Display email box for commentors (optional)

= Version 0.7.5 = 
* bug fix: style.css is in template directory, not necessarily
  based on /wp-content/themes/wp-facebook - account for subdirs
* Same goes for the FB.XdComm.Server.init call

= Version 0.7.4 =
* bug fix for subdirectory based blogs
* fixed hardcoded offset of permalinks
* added note to readme to update theme when updating plugin
* Updated javascript in theme to reflect "new" facebook js 0.4
  (See http://wiki.developers.facebook.com/index.php/Resizable_IFrame#New_Profile_Update)
* Fixed erroneous link in "theme not installed" check
* Added ABSPATH as appropriate to catch the right includes
* Removed hard dependency on specific Avatars plugin, now uses default gravatar

= Version 0.7.3 =
* bug fix
* adding namespacing to plugin function
* anded min version to readme

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
* Options combined into associative array to speed-up and remove 
  interference w/ other plugins

= Version 0.4 =
* First push to WP-Plugins Directory

== To Do ==

= Ongoing =
* Option for comments or no comments in admin section
* Better accompanying documentation
