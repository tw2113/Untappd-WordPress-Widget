=== Untappd WordPress Widget ===
Contributors: tw2113
Tags: untappd, widgets, beer, social drinking
Requires at least: 3.5
Tested up to: 5.4.2
Stable tag: 1.3.3
Requires PHP: 5.3
License: WTFPL
License URI: http://www.wtfpl.net/

Display recent Untappd Checkins via widget

== Description ==

This plugin creates a widget that you can use to display recent checkins on Untappd.

**NOTE** You will need to register for an api key from Untappd to use this widget. More information and application can be found at [Untappd API Docs](https://untappd.com/api/)

See full documentation at http://trexthepirate.com/untappd/

== Installation ==

1. Unzip and upload extracted folder to your wp-content/plugins/ folder OR upload the zip file via the installer in the WP-Admin Plugin screen
1. Activate through the 'Plugins' menu in WordPress
1. Drag instance of new widget into a widget sidebar on your Widgets screen
1. Drink.
1. Check in with what you drink.

== Frequently Asked Questions ==

= How Do I find the Brewery or Venue ID? =

You should be able to find the Brewery ID by checking the URL for the brewery on a specific checkin. Hover over or copy the URL for the BREWERY in a checkin like in the following example: "PERSON is drinking a BEER by BREWERY at VENUE" and look for a numeral ID at the end of the URL

You should be able to do the same for a venue ID. Hover over the VENUE in the checkin URL and look for the numeral ID at the end.

== Screenshots ==

None

== Changelog ==

= 1.3.3 - 2020-07-08 =
* Fixed: fatal error regarding failing API requests and error retrieving.

= 1.3.2 - 2018-01-22 =
* Updated: Changed default transient name values to include username/venue/brewery information. This allows for multiple instances of each widget type.

= 1.3.1 =
* Fixed: Mistakenly had the same filter name for multiple widget.

= 1.3.0 =
* Added: widgets for "Latest Badge", "Venue checkins", and user profile data.
* Added: Settings page for Untappd API client credentials.
* Updated: Conditionally show widget fields for API credentials if not saved on settings page.
* Updated: Revised much of the underlaying code around API requests.

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

= 1.3.1 =
* Fixed: Mistakenly had the same filter name for multiple widget.

= 1.3.0 =
* Added: widgets for "Latest Badge", "Venue checkins", and user profile data.
* Added: Settings page for Untappd API client credentials.
* Updated: Conditionally show widget fields for API credentials if not saved on settings page.
* Updated: Revised much of the underlaying code around API requests.

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
