=== Redirect 404 to parent ===
Contributors: mooveagency, gaspar.nemes
Tags: redirect 404, 404 to parent, redirect
Requires at least: 4.0 or higher
Tested up to: 4.9.6
Stable tag: trunk
Requires PHP: 5.6
License: GPLv2

This plugin helps you define redirect rules that will redirect any 404 request under a defined URL base to the parent URL base.

== Description ==

This plugin helps you define redirect rules that will redirect any 404 request under a defined URL base to the parent URL base.

Simply put, it does this: "When there is a 404 found for an URL like /about/dummy-url => redirect the visitor to /about/."

* Note: This plugin is not intended to offer redirect rule creation features for any kind of redirection within the WordPress system. It simply resolves a problem via Plugin not custom functions or template code.*

**Features**

The plugin adds an option/settings page where you can set up these redirects easily.


The following features are included in this plugin:

* You can defined the BASE URL - this is the URL that will be served as a starting point.
* You can define the type of the redirection done by WordPress (302, 301, etc.).
* You can add as many rules you want and easily delete them if you don't need them anymore
* The plugin checks if you already added a rule based on the slug, so you won't add the same rule twice.
* The plugin checks if the URL you are setting up as a BASE exists in WordPress as a post or page, so you're not creating an erroneous URL base.
* You can see a log of the 404 redirected by the plugin (if the plugin registers any 404 errors), so you can easily identify what URLs are generating the 404 errors.
* If there are more then 10 rows in the 404 log/statistics, you can download the whole log for an URL in CSV format.

**Example Use Case**

* Base URL (set up in this plugin as a rule): http://yourdomain.com/sample-page/
* Target URL: http://yourdomain.com/sample-page/non-existing-page

In this case if a visitor try to access the TARGET URL, WordPress returns a 404 error/page by default because the page/post doesn't exist.

This plugin will automatically redirect the visitor to http://yourdomain.com/sample-page/ instead of letting the visitor end up on a 404 page.

== Installation ==
1. Download the .zip archive of this plugin.
2. Upload the plugin files to the plugins directory, or install the plugin through the WordPress plugins screen directly by searching for the plugin by name.
2. Activate the plugin through the \'Plugins\' screen in WordPress.
3. Use the Settings -> **Moove redirect 404** page to configure the plugin and add rules.

== Screenshots ==

1. Adding redirect rules.
2. View / Remove redirect rules & statistics

== Changelog ==

= 1.0.0. =
* Initial release of the plugin.

= 1.0.1. =
* Fixed PHP warnings