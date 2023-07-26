=== Accredible LearnDash Add-on ===
Contributors: accredible
Tags: accredible, learndash, certificate, certificates, digital certificates, online course, lms, learning management system, e-learning, elearning, badges, badge, open badge, mozilla open badge, blockchain, blockchain credential, credential, credentials
Donate link: https://accredible.com/
Requires at least: 5.9
Tested up to: 6.1
Requires PHP: 5.6
Stable tag: 1.0.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Issue credentials, certificates, or badges for your LearnDash courses through Accredible digital credentialing.

== Description ==
The “Accredible LearnDash Add-on” allows you to issue [credentials](https://www.credential.net/10000005 "credentials"), certificates, and badges to your students when they complete your course. [Accredible](http://accredible.com "Accredible") credentials are:

* Easy to design with our drag and drop certificate builder
* Shareable, transferable, verifiable, and OpenBadge compliant
* Blockchain secured
* Easily shared on LinkedIn with just 1 click

Your course content is valuable, and your learners are proud to share their achievements. Make is easy for them.

**Note:** You will need an Accredible account to use this add-on. [Check out our many features](https://www.accredible.com/solutions/more-features "More features") to see if this is right for you. You will also need the [LearnDash plugin](http://www.learndash.com "LearnDash") v3.6 or higher installed.

For instructions to set up this add-on, visit our [Help Center](https://help.accredible.com/how-to-setup-and-use-learndash "Help Center").

== Installation ==

1. Visit https://accredible.com to obtain an API key.
2. Install, activate and configure the [LearnDash plugin](http://www.learndash.com/ "LearnDash") in WordPress.
3. Install and activate the Accredible LearnDash Add-on plugin in WordPress.
4. Go to the plugin settings, input your API key and select the server region of your Accredible account.
5. Ensure if the settings page says “Integraion is up and running“.

Auto issuance configuration:

1. Go to the 'Auto Issuance' page in the Wordpress admin menu.
2. Click the 'New Configuration' button.
3. Select a trigger of credential issuance, a target resource such as a course for course completion, and an Accredible group to issue credentials to.
4. Click the 'Save' button.

== Frequently Asked Questions ==
= How do I get an API key? =

Visit https://accredible.com to obtain a free API key.

= Which server region should I select? =

If the domain of your Accredible account is `eu.dashboard.accredible.com`, you need to select "EU". Otherwise, please select "US".

= Where should I report issues or bugs? =

You can report any issues or bugs on our project [GitHub](https://github.com/accredible/accredible-learndash-add-on/issues "GitHub") site.

== Screenshots ==

1. Digital Certificate
2. Digital Open Badge
3. Auto-issuance page
4. Issuance logs page
5. Responsive certificate designs
6. Marketing click throughs and impressions
7. Example Google certificate.

== Changelog ==

= 1.0.11 =
Bump Tested up to version to 6.1

= 1.0.10 =
Fix course listing being limited to 5

= 1.0.9 =
Add auto issuance for lesson completion.

= 1.0.8 =
Fix pagination for Issuance Logs.

= 1.0.7 =
Improve auto issuance form with sidenav.

= 1.0.6 =
Improve group search logic and auto issuance delete UX.

= 1.0.5 =
Improve auto issuance form UX/UI.

= 1.0.4 =
Add validations to models and improve UI.

= 1.0.3 =
Add a dialog behaviour and Accredible issuer info.

= 1.0.2 =
Add icon and banner images.

= 1.0.1 =
Sanitize all inputs from the request parameters.

= 1.0.0 =
Initial version.
