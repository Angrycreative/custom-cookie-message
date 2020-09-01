=== Custom Cookie Message ===
Contributors: johansylvan, angrycreative, kylegard, killua99, melindrea, victorcamnerin, eliaschalhoub, johannaelmesioo, hosam87
Tags:  custom, cookie, message, consent, cookie bar, cookie compliance, cookie law, cookie notice, cookie notification, cookie notification bar, cookie notify, cookies, eu, eu cookie, eu cookie law, notice, notification, notify, custom cookie message, WPML, Polylang, Multisite, multisites, local storage
Requires at least: 4.9
Tested up to: 5.5
Stable tag: 2.4.10
Requires PHP: 5.6+

License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Custom Cookie Message compatible with multilanguages and multisites.

== Description ==
Installing and activating the plugin will automatically make it appear with the default content and styling.

Custom cookie message is compatible with multilanguages where all you have to do is translate the strings of the cookie message as you would with any other strings. In plugins such as WPML or Polylang the cookie message strings show up just as other strings and are ready to be translated.
Custom cookie message is also compatible with multisites. The location of the message is customizable as well as the content and styling. The settings can be found under settings -> cookies.

= Features =
We have a list of cookies by default so the customers can delete them if they want to, and you can expand this list using this filter:
`
add_filter( 'default_advertising_list_filters', 'add_som_cookies_name_to_default_list' );

/**
 * To expand advertising cookies list
 * @param $default_cookies
 *
 * @return array
 */
function add_som_cookies_name_to_default_list( $default_cookies ){
	$default_cookies[] = 'cookie_name';

	return $default_cookies;
}
`
Default cookies list:
`
_ga,_gid,_hjIncludedInSample,_hjid,1P_JAR,APISID,CONSENT,HSID,NID,SAPISID,SEARCH_SAMESITE,SID,SIDCC,SSID,UULE
`

== Installation ==

1. Add the plugin custom cookie message to your plugins map
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Locate the options in settings -> Cookies. Default location, content and styling is set but can be customized.

== Changelog ==
= 2.4.10 =
* Update - updated package.json file.
* Fix 	 - Fix npm build job.
* Fix    - Fix the Javascript error when using srcElement.
* Adding - A default period (a week) has been added to keep cookies stored in case the user does not enter a value.
* Adding - Added a special field for the word 'OR' to make it translatable.


= 2.4.8 =
* Update - updated webpack package

= 2.4.7 =
* Fix - new version added.

= 2.4.6 =
* Fix - Fixed the empty array in class-removecookie.php

= 2.4.5 =
* Fix - Fixed a js bug with duracelltomi-google-tag-manager plugin

= 2.4.4 =
* Fix - Fixed shortcodes functionality in Content Options.
* Fix - Fixed ["opt_in_opt_out"] warning empty value.

= 2.4.1 =
* Support for a opt-in method, instead of the previous opt-out method. Use with caution and make sure to test the site functionality, before activating on a live environment.

= 2.4.0 =
* Changed checkbox design.

= 2.3.9 =
* Adding default cookies list.

= 2.3.8 =
* Adding ability to remove the cookies.

= 2.3.7 =
* Removed lang parameter from ajax request.

= 2.3.6 =
* Fix the translation.
* Renamed and explained some options and removed som unnecessarily code.

= 2.3.5 =
* Fix for pll_current_language fatal error.

= 2.3.4 =
* Removed API request when closing off cookie banner.
* Fix for saving settings

= 2.2.9 =
* Improved polylang translations.

= 2.2.8 =
* Fixed error 401 when using mode rewrite.
* Added option to enable/disable mode rewrite for the plugin rest-api endpoints.

= 2.2.7 =
* Added polylang translation support for modal content.
* Fixed display on IE11.

= 2.2.4 =
* Only use the translation function if it exists.

= 2.2.2 =
* Fixed missing options page error.

= 2.2.1 =
* Only set the options on the option page.
* Add default values in general array.

= 2.2.0 =
* Changed banner layout for better user experience.
* Option to choose type of close button (close or accept).
* More options for better button styling.
* Option to select internal page for (about cookies page).
* Internal page is ready for multilingual sites.
* Internal about cookies page can be overwritten by external link field.
* Fixed error 500 bug.
* Added select2 for admin forms select tags.

= 2.1.8 =
* Added more support to Polylang (strings translations).

= 2.1.7 =
* Banner animation options.
* Custom CSS field for buttons and other elements.
* Scroll content container down/up (optional).

= 2.1.6 =
* Export and import settings.

= 2.1.4 =
* Solved bug with wp_nonce
* Warn if the options are not set

= 2.1.3 =
* Translations folder path updated to load the correct translations
* Responsive styling updates

= 2.1.2 =
* Z-index updated
* Button hover text color settings added
* Responsive styling updates
* Minor styling fixes

= 2.1.1 =
* Undefined variables fixes

= 2.1.0 =
* Updated styling to better work on older browsers.
* Improve default styling settings.
* Improve user styling options for buttons.

= 2.0.3 =
* Swedish translation.
* Improve default settings values.

= 2.0.0 =
* New Cookie structure
* Support for Polylang
* Support for GDPR
* Better cookie life time spam (LTS)

= 1.6.4 =
* Changed on scroll behavior and added roles restriction to change styles in settings

= 1.6.3 =
* Fixed margin bug

= 1.6.2 =
* Update readme

= 1.6.1 =
* Add check to see if opacity is set.

= 1.6 =
* Add more styling options.

= 1.5.3 =
* Small css change to link.

= 1.5.2 =
* Look over code to work for older versions of php.

= 1.5.1 =
* Add minor styling fix.

= 1.5 =
* Improved the functionality of adding styling from theme.

= 1.4 =
* Redefine styling.

= 1.3 =
* Add possibility to use styling from theme.

= 1.2 =
* Add localstorage.

= 1.1 =
* Add debounce for scroll events.

= 1.0 =
* Initial commit.
