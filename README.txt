=== WPBook ===
Contributors: davelester
Donate link: http://www.davelester.org
Tags: facebook, platform, application, blog, mirror
Stable tag: 0.5

Plugin to embed Wordpress Blog into Facebook Canvas using the Facebook Platform.

== Installation ==
1. Copy the WPBook directory into your wordpress plugins folder, normally located
   in /wp-content/plugins/

2. Set up a New Application at http://www.facebook.com/developers/, obtaining
   a secret and API key.  Set the callback url to the location of WPBook on your server

3. Login to Wordpress Admin and activate the plugin

4. Using the WPBook menu, (located under the options tag, "WPBook") fill in the appropriate information including Facebook
   application secret and API keys

== Frequently Asked Questions ==
= How do I edit the way my Facebook Application (mirrored blog) looks? =

In the wpbook plugin directory, there is a template.php file.  Edit this the same way you'd edit any Wordpress theme.

By default, only inline styles are allowed in the Facebook canvas.  To use an external style, change your Facebook Application's settings by going to the developers application.  Set your application to be in an iframe; that should do the trick.

= Once I install WPBook, where can I access it? =

If you have properly installed WPBook, you can access it at http://www.yourblog.com/wpbook/.

The /wpbook/ directory is an automatically generated page the plugin generates (this is if you have name-based permalinks turned on, otherwise the URL will be different)

== Version History ==

= Version 0.5 =
* Added support for PHP4 Facebook Client Library
* Options combined into associative array to speed-up and remove interference w/ other plugins

= Version 0.4 =
* First push to WP-Plugins Directory

== To Do ==

= Version 0.6 =
* Templating customization via Admin panel
* Better accompanying documentation