WPBook Plugin v.0.4
=========================================================================

Thanks for downloading the WPBook Plugin. This plugin was 
created by Dave Lester and is published under the GNU version 2. 

If a copy of the GNU was not included with this package, please obtain a 
copy from http://www.gnu.org/licenses/gpl.html

This Plugin is Copyright Dave Lester 2007
http://www.davelester.org


Installation
=========================================================================
1. Copy the WPBook directory into your wordpress plugins folder, normally located
   in /wp-content/plugins/

2. Set up a New Application at http://www.facebook.com/developers/, obtaining
   a secret and API key

3. Login to Wordpress Admin and activate the plugin

4. Using the WPBook menu, (located under the options tag, "WPBook") fill in the appropriate information including Facebook
   application secret and API keys


Usage
=========================================================================
If you have properly installed WPBook, you can access it at http://www.yourblog.com/wpbook/

The /wpbook/ directory is an automatically generated page the plugin generates


Controlling the Look and Feel
=========================================================================
In the wpbook plugin directory, there is a template.php file.  Edit this the same way you'd edit any Wordpress theme.

By default, only inline styles are allowed in the Facebook canvas.  To use an external style, change your Facebook Application's settings by going to the developers application.  Set your application to be in an iframe; that should do the trick.