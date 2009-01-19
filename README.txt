=== WPBook ===
Contributors: johneckman, davelester, bandonrandon
Tags: facebook, platform, application, blog, mirror
Stable tag: 1.0
Tested up to: 2.7
Requires at least: 2.5

Plugin to embed Wordpress Blog into Facebook Platform.

== Overview ==

WPBook enables users to add your (self-hosted, not wordpress.com) wordpress 
blog as a Facebook application. Facebook users will see your posts in a 
Facebook look and feel, and can leave comments with their Facebook identity. 

Comments are shared - meaning comments made by users on your blog at its 
regular domain and comments made by users inside Facebook are all shown to 
users of either "view" of your content. 

Facebook users can also - at their option - add a profile box to their profile,
using the "add to profile" button at the top of the default canvas page. 

That profile box shows the 5 most recent posts from your blog, as links. 

== Installation ==

1. Copy the entire wpbook directory into your wordpress plugins folder,
   /wp-content/plugins/

   You should have a directory structure like this:
   /wp-content/plugins/wpbook/wpbook.php
   /wp-content/plugins/wpbook/theme/
   /wp-content/plugins/wpbook/client/
   /wp-content/plugins/wpbook/php4client/

   NOTE: If you've used previous versions, you no longer need to copy
   the wp-facebook folder (which no longer exists) to your themes directory. 
   
   If you've used versions prior to 0.9.5, you can DELETE the following 
   directory and all it's contents: 
		/wp-content/themes/wp-facebook/ 

2. Set up a New Application at http://www.facebook.com/developers/, obtaining
   a secret and API key.  

   Set the callback url to your blog url, including  a trailing slash. 
       (http://www.yourblogurl.com/)
   For canvas url, you just need something all lower case, unique, with
   no spaces, and no trailing slash. Remember it. 
   
   Set the application type to "website"
      
   Set the application to use an iFrame, not fbml, and to "resizable"
   (Using iFrames lets you use javascript, objects, and other tags 
    not allowed in FBML inside blog posts)

3. Login to Wordpress Admin and activate the plugin

4. Using the WPBook menu, (Dashboard->Settings->WPBook) fill 
   in the appropriate information including Facebook application secret 
   and API keys, as well as your application canvas url. 

   Enabling the "Show Invite Friends Link" will show a link inside 
   Facebook when viewing your application which allows users to send
   invites to their friends. 

   Comments inside Facebook can be enabled or disabled without any impact
   on comments when your blog is viewed outside Facebook. If you have comments
   enabled, you can optionally require users to provide their email address. 
   (Facebook does not allow access to the user's email address, so you can 
   really only ask users to provide one, not prefill it automatically). 

   The "Share this post" links can also be enabled or disabled. If they are
   enabled, they will allow the user to "share" your blog posts using the 
   built in Facebook Share mechanism, including sending a message to friends
   or posting in their profile. 

   The "Give WPBook credit" option adds a line at the bottom of your Facebook
   application pages which says "This Facebook application powered by the 
   WPBook plugin for Wordpress" - I'd love it if you would leave this enabled
   but it is not required.  

5. If you wish to enable users to add your blog application to Facebook
   Pages, not just individual user profiles, get the default FBML by 
   visiting the settings page inside Wordpress (Dashboard->Settings->WPBook)
   and enter the provided FBML in the appropriate place (default FBML) in
   the Facebook Application Setting ppage, using the Facebook Developer Application. 

   Note that this currently has to be entered in two places: in the
   "User Profiles" tab AND in the "Pages" tab. Not sure why. 

== Frequently Asked Questions ==

= How do I edit the way my Facebook Application (mirrored blog) looks? =

In the wpbook/theme directory, there is an index.php file.  Most of 
what you want is there.  

There's also a default/style.css which basically mimics Facebook's styles, 
as well as some other files for processing comments and the like.  

== Version History ==

= Version 1.0 =
* Added simplexml44 library (BSD Licensed) for php4client
* Added option for "Give Credit" 
* Added option for "Enable Share"
* Added option for "Allow Comments"
* Moved "Invite Friends" to top of page
* Cleaned up CSS for "recent posts" in main page
* Added fix to facebookapi_php5_restlib.php which affected hosts where
  curl libraries were not present or enabled
* Jumped version to 1.0 - functionally complete

= Version 0.9.7 =
* template_directory deprecated in 2.7, use bloginfo('wpurl') instead

= Version 0.9.6 = 
* Clean up from moving plugin in to directory
* Added Share button to share posts on FB
* Added fix for conflict with other Facebook-based plugins

= Version 0.9.5 = 
* Moved plugin into wpbook dir in subversion
* Moved theme subdirectory inside plugin subdir
*   Required several function changes
* Added check for existing FacebookRestClient


= Version 0.9.4 =
* Bug in javascript (NULL isn't the same as null) for profile

= Version 0.9.3 =
* Bug in commenting inside Facebook due to $facebook->redirect
* Now redirects to the post on which the user commented
* Added instruction for adding to FB Pages to settings page in WordPress

= Version 0.9.2 =
* Didn't realize I had set default FBML inside Facebook, masked a bug
* Should now set profile FBML before calling add profile box

= Version 0.9.1 =
* Fixed xd_reciever.html versus xd_receiver.html issue
* (You'd think a guy with a PhD in English would know how to spell.) 

= Version 0.9  = 
* Added profile boxes
* Shows 5 most recent posts in profile box
* Also sets FBML for "pages" profile boxes

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
* Capture Facebook Profile picture of user commenting in
  Facebook, display instead of Gravatar. (I think this 
  will require actually fetching and storing the picture,
  not just its url, as it may not be available outside
  Facebook, and may change). 
* Deal with non-standard front pages (where user has set
  a static page in WordPress options)

= Ongoing =
* Better accompanying documentation
  (Screenshots of examples, clarity on what's required
   where, all the facebook option pages, etc)
