=== Streamline Core ===
Contributors: msoria, transom, snewby
Requires at least: 4.2
Tested up to: 4.4.2
Stable tag: master
License: GPL 2.0

Connects WordPress to Streamline Vacation Rental Software servers

== Description ==
Your visitors can view and book your properties directly from your website. Inventory and availability are pulled directly from your Streamline server account. Visitors can complete their entire booking directly from your web site.

== Installation ==

1. Upload the entire `streamline-core` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Update/Review the settings for Streamline at Settings -> Streamline Core
4. It is always a good idea to reset your permalinks - Settings -> Permalinks, just hit Save

== Changelog ==
== 2.2.0 ==
* Date
###New Features:###

###Fixes:###

###General Maintenance:###

###Developer Notes###

== 2.1.5 ==
* May 19, 2016
###New Features:###
* Inquiry Forms are pre-populated based on the current query
* Min Stay option added to filter widget to have default stay on checkout
* Browse template includes Property Inquery
* When using map for filter, AutoZoom can be enabled/disabled
* Search Template 3 added link from large image, review stars added
* Property Inquiry must include either phone or email
* New Search - Property Type
* Optionally skip amenities on search (improves performance)
* New Option: Restrict Checkins for next (n) days (prevent next-day bookings)
* Featured Property widget - supports room-type logic

###Fixes:###
* Amenity filter correctly filters by multiple selections (must match all)
* Search and Filter widgets are pre-populated from the query
* Correctly display custom quote for 1 property
* Sorting by bedroom/bathroom provided by server (speeding performance)
* Fixed issue when search returned only one property and random search
* Checkout validation better indicates errors
* Fixed incorrect link in Featured Properties widget
* Improved stability of calendar popups in listing detail pages
* Fixed "Share with Friends" in Custom Quote, links on other than 1st propery failed

###General Maintenance###
* Removed backup folder from includes/templates
* Improvements to listing detail and checkout for mobile and desktop (props Rusu)
* Improved Property details using WordPress hooks to include sub-templates
* Clean-up software error on checkout page
* Improved Security in Custom Quote (share with friends)
* Fixed security issue by removing all non-standard AJAX code, re-factored to use WordPress admin-ajax.php

###Developer Notes:###
* Abstracted CSS into SCSS for cleaner markup, placing .scss files in assets/src/css/scss will be processed, minimized and concatonated into streamline-core.min.css
* Added plugin version tracking in database, new class for version upgrades (fixes issue with custom-quote page)
* Refactored all AJAX calls through new StreamlineCore_AJAX class and wp-admin/admin-ajax.php
== 2.1.4 ==
* Apr 21, 2016
###New Features:###
* Max Occupancy is enforced when adult guests + children exceeds maximum occupancy for a property
* Supports Google Analytics eCommerce tracking. When order is completed, GA is notified of the value of the booking.
* Now support security deposits during checkout
* Better checking of availability and pricing when coming from a quote link
* Featured Property widget now filterable by pets
* Control of visibility of property and Book Now button are set within Streamline (not the plugin)
* Supports display of 1/2 stars on reviews - better reflecting the calculated average
* Quotes are displayed within the context of the website, formatting is controlled in /includes/templates/quote.php

###Fixes:###
* Breadcrumbs (if present) use the correct area id for the related seach query
* Addressed occasional issue with date selector
* Sorting in Search Results more reliable
* Booking error messages persist until new request is made
* Fixed conflict with WordPress Screen Options and Help tabs in Dashboard
* Better performance when loading search results (avoids "jumping")
* Amenities in Search Widget now passes request correctly
* During checkout, discount is shown if coupon is applied
* Shows default image if gallery is missing
* Searching for pets = 1, returns all properties allowing 1 or more pets (uses min_pets)
* Searching by Neighborhood returns correct results
* Map Search no longer resets bounds and zoom on page load
* Solved Search Results error under certain page caching circumstances
* Changing Bedroom count in Filter widget correctly filters results
* Fixed missing amenity when there was only one amenity

###General Maintenance###
* Updated Angular dependency to 1.5.3
* Markup cleanup in Search Widget
* Better support of version 5.2 of PHP
* Improvement to presentation of "No Inventory" message
* Improved security checking of requests
* Removed all PHP short tabs, improving compatibility with latest PHP releases

###Developer Notes:###
* Templates now starting to include WordPress "apply_filters" to templates. Use functions.php to override text instead of creating custom templates
* Feature Properties widget has override-able template
== 2.1.3 ==
* Mar 21, 2016
= Bug Fix =
* Update Search Results page for Widget Name Change
* Fix javascript bug that was ignoring skipped units from wp-admin settings
== 2.1.2 ==
* Mar 21, 2016
= Bug Fix =
* Update Search Results page for Widget Name Change
== 2.1.1 ==
* Mar 21, 2016
= Bug Fix =
* Restore Widget Area name for Property Search Page
= 2.1 =
* Mar 21, 2016
= New Features: =
* Added security deposits to checkout
* Removed adult word from dropdowns
* Improvements to filter widget to better handle labels from widget instance
* Improvements to search widget to use labels from widget instance and to sort units
* Removed 'Company Name' text from widgets
* Added sticky options to unit page settings
* Map widget logic to pull lat/long if passed via query string
* Added link to map info window to be able to click and go to unit details
* Order reviews from newest to oldest
* Added room type logic
* Added extra charges to pricing
* Bedroom sorting in bedroom type dropdown
* Added title and metatag keywords to property pages.
* Changed amenity filtering from serverside to client side
* Added testimonials widget
* Removed yoast cannonical from property pages
* Added Added pre_get_document_title filter to ensure property titles show on page
* Changed pagination on search results to show a Show More button
* Added toggle to display/hide sorting dropdown
* Added sorting dropdown for listing template
* Added discount row to step 3 in checkout page
* Improved calendar speed on availability calendar by splitting methods into two
* Ability to go back to previous steps in checkout
* Display expected charges
* Select US as default country
* Change wording on warning message about ssl in checkout page
* Added ssl option on settings to force https on checkout
* Changed rates from three years to two years to improve speed

=Bug Fixes: -
* Fixed calendar width issue on property page tab layout and bug when building links in shortcodes
* Bug fixes in amenities for property layout
* Fixed responsive issue on template details 2
* Viewname fixes with commas
* Added pet.png to assets/images
* Fixed pets and children logic
* Changed href to ng-href on links on search page to avoid 404 from google bots
* Improved Compatibility with Yoast SEO plugin
* Fixed sticky checkout widget issues on mobile

= Housekeeping/Maintenance: =
* Changed visible names from ResortPro to Streamline
* Added additional variables to responses to grab additional fields from units
* Performance Improvements in wp-admin (eliminate conflicts preventing access to Screen Options)
* Remove debugging code that slowed activation of plugin as well as removed unneeded database table