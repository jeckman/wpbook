=== WPBook ===
Contributors: davelester
Donate link: http://www.davelester.org
Tags: facebook, platform, application, blog, mirror
Requires at least: 2.0.2
Tested up to: 2.3
Stable tag: trunk

Plugin to embed Wordpress Blog into Facebook Canvas using the Facebook Platform.

== Installation ==
1. Copy the WPBook directory into your wordpress plugins folder, normally located
   in /wp-content/plugins/

2. Set up a New Application at http://www.facebook.com/developers/, obtaining
   a secret and API key

3. Login to Wordpress Admin and activate the plugin

4. Using the WPBook menu, (located under the options tag, "WPBook") fill in the appropriate information including Facebook
   application secret and API keys

== Frequently Asked Questions ==
= How do I edit the way my Facebook Application (mirrored blog) looks? =

In the wpbook plugin directory, there is a template.php file.  Edit this the same way you'd edit any Wordpress theme.

By default, only inline styles are allowed in the Facebook canvas.  To use an external style, change your Facebook Application's settings by going to the developers application.  Set your application to be in an iframe; that should do the trick.

= Once I install WPBook, where can I access it? =

If you have properly installed WPBook, you can access it at http://www.yourblog.com/wpbook/

The /wpbook/ directory is an automatically generated page the plugin generates
