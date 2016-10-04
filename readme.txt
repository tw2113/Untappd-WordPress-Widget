=== Untappd WordPress Widget ===
Contributors: tw2113
Tags: untappd, widgets, beer, social drinking
Requires at least: 3.5
Tested up to: 4.6.1
Stable tag: 1.2.0
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
### untappd_checkins_brewery_filter
name of the transient to use for the brewery response caching.
	Default: untappd_brewery_checkins
### untappd_transient_duration
how long to store the transients, in seconds
	Default: 60*10 (10 minutes)
### untappd_checkins_list_classes
Classes applied to the `ul` tag
	Default: untappd_checkins
### untappd_user_markup
Allows you to provide your own markup for the user widget.
### untappd_brewery_markup
Allows you to provide your own markup for the brewery widget.

== Installation ==

1. Unzip and upload extracted folder to your wp-content/plugins/ folder OR upload the zip file via the installer in the WP-Admin Plugin screen
1. Activate through the 'Plugins' menu in WordPress
1. Drag instance of new widget into a widget sidebar on your Widgets screen
1. Drink.
1. Check in with what you drink.

== Frequently Asked Questions ==

= How Do I find the Brewery ID? =

You should be able to find the Brewery ID by checking the URL for the brewery on a specific checkin. Hover over or copy the URL for the BREWERY in a checkin like in the following example: "PERSON is drinking a BEER by BREWERY at VENUE" and look for a numeral ID at the end of the URL

== Screenshots ==

None

== Changelog ==

= 1.2.0 =
* Added widget for listing Brewery checkins.
* Fixed transient name issue for user checkins.
* Added parameter to untappd_checkins_list_classes filter to indicate which widget is being rendered at that moment.

= 1.1.2 =
* Change from http to https for Untappd API request

= 1.1.1 =
* Text changes.
* New filter: untappd_user_markup. Used to provide custom widget output if you want to override default.

= 1.1 =
* Fix various lingering issues with labels on the widget.
* Added some missing strings for translations.
* Added pot file for translating.
* Adde filter for the classes applied to the unordered list.
* Hide errors regarding API credentials for all non-admins.
* Saves transient only when a status of 200 is returned by the Untappd API.

= 1.0.1 =
* Wasn't echoing widget labels with translations.

= 1.0 =
* Initial upload

== Upgrade Notice ==

= 1.2.0 =
* Added widget for listing Brewery checkins.
* Fixed transient name issue for user checkins.
* Added parameter to untappd_checkins_list_classes filter to indicate which widget is being rendered at that moment.

= 1.1.2 =
* Change from http to https for Untappd API request

= 1.1.1 =
* Text changes.
* New filter: untappd_user_markup. Used to provide custom widget output if you want to override default.

= 1.0.1 =
* If you were wondering where labels for the widget were, and what to put where, this is a necessary update. Sorry!
