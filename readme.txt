=== Untappd WordPress Widget ===
Contributors: tw2113
Tags: untappd, widgets, beer, social drinking
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 1
License: WTFPL
License URI: http://www.wtfpl.net/

Display recent Untappd Checkins via widget

== Description ==

This plugin creates a widget that you can use to display recent checkins on Untappd.

**NOTE** You will need to register for an api key from Untappd to use this widget. More information and application can be found at [Untappd API Docs](https://untappd.com/api/)

Available settings will be User name, API client ID key, client ID secret key, and the limit of checkins to fetch.

##Developer notes
Two filters available at the moment:

### untappd_checkins_filter
name of the transient to use for the response caching.
	Default: untappd_checkins
### untappd_transient_duration
how long to store the transients, in seconds
	Default: 60*10 (10 minutes)

##General Notes
Translation ready but I still need to add .pot files.


== Installation ==

1. Unzip and upload extracted folder to your wp-content/plugins/ folder OR upload the zip file via the installer in the WP-Admin Plugin screen
1. Activate through the 'Plugins' menu in WordPress
1. Drag instance of new widget into a widget sidebar on your Widgets screen
1. Drink.
1. Check in with what you drink.

== Frequently Asked Questions ==

None yet

== Screenshots ==

None

== Changelog ==

= 1.0 =
* Initial upload

== Upgrade Notice ==

none yet