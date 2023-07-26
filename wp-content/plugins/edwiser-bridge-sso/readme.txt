=== Single Sign On for Edwiser Bridge ===
Contributors: WisdmLabs
Tags: Single Sign On, SSO, WordPress Moodle SSO, WordPress, Moodle, Courses, Users, Synchronization, Sell Courses, Learning Management System, LMS, LMS Integration, Moodle WordPress, WordPress Moodle, WP Moodle, Single Sign On, SSO
Requires at least: 4.0
Tested up to: 4.6.1
Stable tag: trunk
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html


The Single Sign On extension for Edwiser Bridge facilitates simultaneous login to WordPress and Moodle by entering login credentials only once. 

== Description ==

The Single Sign On extension provides end users with an option to enter a single username and password on the WordPress as well as Moodle website and gain access on both the websites simultaneously.

= Simultaneous Login =
The Single Sign On Extension for Edwiser Bridge provides users with an option to enter the login credentials once in WordPress as well as on Moodle website and to be logged in to both the websites simultaneously.

= Simultaneous Logout =
Just like simultaneous login the Single Sign On Extension provides users with an option to be logged out of WordPress & Moodle simultaneously from the WordPress as well as from the Moodle website.

= Shortcode for Login =
The Single Sign On extension for Edwiser Bridge provides users with a shortcode that can be used to provide an automated login link on the WordPress website.


== Installation ==

= Minimum Requirements = 
* WordPress 4.0 or higher
* Edwiser Bridge 1.1
* Moodle extension wdmwpmoodle 1.2 or higher

= Installation on WordPress =

* Upon purchasing the Single Sign On Extension for Edwiser Bridge, an email containing the purchase receipt, download link and license key will be sent to your registered email id. You can download the extension using the download link provided.
* The downloaded file contains two zip files – 'edwiser-bridge-sso.zip'  and 'edwiser-bridge-sso-moodle'. The 'edwiser-bridge-sso.zip' is the plugin file that has to be installed on the WordPress website.
* To install this plugin, go to the 'Plugins' menu from the dashboard.
* Click on the 'Add New' button on this page.
* Now click on the 'Upload Plugin' button and upload the 'edwiser-bridge-sso.zip' file.
* Click on 'Install Now' button once you have located and uploaded the plugin.
* On successful installation click the 'Activate Plugin' link to activate the plugin.
* Alternatively, you can unzip and upload the Single Sign On Extension plugin folder using the FTP application of your choice.
* Once you have activated the plugin a new option labeled as 'Single Sign On License' will be created under the plugins menu in the website backend.
* The following screen will be available on clicking on this option. Enter the license key provided in the purchase email in the 'License Key' field and click the 'Activate License' button.

=  Installation on Moodle  = 
* The 'edwiser-bridge-sso-moodle.zip' file will have to be installed on the Moodle website.
* To do so you will have to login to the Moodle website and navigate to 'Site administration' -> 'Plugins' -> 'Install plugins'.
* Once here upload the 'edwiser-bridge-sso-moodle.zip' file using the 'Choose a file' button alongside the 'Zip package' field.
* Choose 'Authentication method (auth)' from the drop down list provided against the 'Plugin type' field. The 'Rename the root directory' field can be left blank.
* Now click the ‘Install plugin from the ZIP file’ button.

=  Secret Key Settings  =
Setting a secret key on WordPress and Moodle is an important part of the set up process. Please refer to the <a href = "https://edwiser.org/bridge/extensions/single-sign-on/#tab-1438003330466-2-6">Documentation</a> and follow the steps provided to define the secret keys.

== Frequently Asked Questions ==

= Which version of WordPress does the Single Sign extension work with? =
The Single Sign On Extension extension requires at least WordPress version 4.0 and has been tested up to version 4.6.1.

= Are there any prerequisites for the installation of the extension? =
Single Sign On is an extension of the Edwiser Bridge plugin. Hence, the Edwiser Bridge plugin will have to be installed on your website before you get started. You can download your free copy of Edwiser Bridge from <a href = "https://wordpress.org/plugins/edwiser-bridge/">wordpress.org</a>.

= The single sign on functionality is not working on my website. What should I do? =
The feature is probably not working because you have not set the secret key on WordPress and Moodle. If this is the case then refer to the <a href = "https://edwiser.org/bridge/extensions/single-sign-on/#Documentation">Documentation</a> to learn how to set the secret keys on both websites.

Take a look at the link below to see the full list of questions which will help you around the Single Sign On Extension.
<a href = "https://edwiser.org/bridge/extensions/single-sign-on/#FAQ">Frequently Asked Questions</a>

== Changelog ==
= 1.3.2 =
* Feature - Added functionality to show social logins on different pages separately.
* Tweak - Split the setting into three parts (General/Redirection/Social Login) settings.
* Tweak - Added shortcode description in the shortcodes tab.
* Fix - Resolved the Facebook issue.


= 1.3.1 =
* Fix -Fixed the settings save issue.

= 1.3.0 =
* Feature - Functionality for user role based redirection.
* Feature - Settings for user role based redirection enable/disable.
* Feature - Setting for social login enable disable.
* Feature - Functionality for register and login user using Google plus.
* Feature - Functionality for register and login user using Facebook.
* Feature - Added Shortcode [eb_sso_social_login] to add the social login buttons on any page.
* Tweak - Split the setting into tow parts (Redirection/General) settings.
* Tweak - Split the settings code into tow parts.
* Fix - Reduced the number of request set for the secrete key check.
* Fix - Various performance improvement.

= 1.2.1 =
* Tweak - Added moodle 3.3 compatibility.
* Tweak - Added separate setting for the SSO in Edwiser Bridge settings menu.
* Fix - Secret token verification fix.
 
= 1.2 =
* Feature - Trigger wordpress login when users logs into moodle.
* Feature - Trigger wordpress logout when users logs out from moodle.
* Feature - Moodle dashboard setting to redirect on a specific page after logout.
* Feature - Wordpress dashboard setting to redirect on a specific page after login.
* Feature - Translation ready.
* Feature - Added a button in dashboard to verify WordPress Secret Key with Moodle Secret Key.
* Tweak - Optimized plugin.
* Tweak - Updated licensing code.
* Tweak - If query argument `redirect_to` or `redirect` is set in login url, then redirect users to that specified URL.
* Tweak - WooCommerce MyAccount page login - redirect to the same page itself i.e. MyAccount page.
* Fix - Users redirecting to homepage when they logs in on WooCommerce's checkout page.
* Fix - Notice "undefined constant HTTP_REFERER"
* Fix - Even after saving Secret Key at moodle end, it does not show the entered secret key.


= 1.1 =
* Tweak - Single Sign On license key presented inside the "licenses" tab in Edwiser Bridge
* Tweak - Refactored & optimized whole plugin codebase using tools like PHPCS, PHPCBF & PHPMD.

= 1.0.0 =
* Plugin Launched
