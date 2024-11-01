=== TMB App.net Embed and Widget ===
Contributors: th0masmb
Tags: social, adn, app.net, widget, sidebar, embed
Author URI: http://thomas.bensmann.no
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 0.1.4.1

The TMB ADN plugin adds App.net post embedding and a widget to WordPress

== Description ==
This is a plugin that will allow you to embed ADN posts easily, and add a App.net widget to your sidebar in WordPress. [Check here for a full overview](http://www.bensmann.no/downloads/wordpress-adn-plugin/ "TMB App.net Embed and Widget")

= Embed Posts =
To embed a post from App.net, just paste the url to the post in the content, and it will be displayed. Just like you would expect from an oEmbed solution.

`https://alpha.app.net/thomasmb/post/978899`

If you want to embed this with a shortcode instead, there is a “tmb_adn_post” shortcode available, which for now only takes the post ID value.

`[tmb_adn_post id="927284"]`

= Settings =
With this plugin there are some setting included. You can find these by going to ‘Settings -> TMB ADN’.

= Widget =
I have included a simple widget for displaying App.net posts. With this widget, you can choose to show either posts by a user, or posts that have been tagged.

== Installation ==
1. Download and unzip plugin
2. Upload the 'tmb-adn' folder to the '/wp-content/plugins/' directory,
3. Activate the plugin through the 'Plugins' menu in WordPress.


== Changelog ==

= Version 0.1.4 =
* Updated stylesheet to match new App.net design
* Minor code changes

= Version 0.1.3.* =
* Minor bug fixing

= Version 0.1.3 =
* Released 2012-10-25
* Added option for opening links in new tab/window

= Version 0.1.2 =
* Released 2012-10-24
* Small fix for themes that don't include the widget class

= Version 0.1.1 =
* Released 2012-10-23
* Fixes the plugin after App.net API changes

= Version 0.1 =
* Released 2012-10-14
* It works - for me at least
