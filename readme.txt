=== Gravity Forms: Notification Attachments ===
Contributors: codearachnid
Tags: gravityforms, forms, attachment, email, notification
Donate link: http://example.com/
Requires at least: 3.7
Tested up to: 5.6.1
Stable tag: 1.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A WordPress addon for Gravity Forms to add attachments to notification emails.

== Description ==
An active form with notifications must be available to  In order to add attachments to notification you must select them through the media modal, once listed save the notification to attach during notification triggers. This plugin uses the core WordPress and Gravity Forms methods to attach and send notification emails. You are responsible for ensuring your server environment can successfully send.

*This plugin requires Gravity Forms by RocketGenius to be active.*

Tested up to v2.4.22.5 of Gravity Forms.

This plugin is [actively maintained on GitHub](http://codearachnid.github.io/gf-notification-attachment/). I welcome your pull requests, comments and suggestions for improvement

Thanks to the following users for making the plugin better!

* @saxonycreative
* @mathijsbok
* @ollycross
* @davidperezgar
* @hubdotcom

== Installation ==
Installing the plugin is easy. Just follow these steps:

1. From the dashboard of your site, navigate to Plugins --> Add New.
2. Select the Upload option and hit "Choose File."
3. When the popup appears select the gravityforms-notifications-attachment-x.x.zip file from your desktop. (The 'x.x' will change depending on the current version number).
4. Follow the on-screen instructions and wait as the upload completes.
5. When it's finished, activate the plugin via the prompt. A message will show confirming activation was successful.
6. Then navigate to the notification edit screen you wish to add an attachment.


== Frequently Asked Questions ==
= Developers =
When WP_DEBUG is active the script will use the unminified version of the script.

== Screenshots ==
1. The plugin adds logic to the notification edit screen.

== Changelog ==
= 1.6 =
* added translation updates (thanks @davidperezgar)
* fixed js compat for array going forward (thanks @ollycross)
* updated working versions tested (thanks @hubdotcom)

= 1.5 =
* added Gravity Forms requirement check and notification if not found

= 1.4 =
* fixed Gravity Forms from being greedy to remove the js script for plugin in no conflict mode (thanks to @saxonycreative)
* tested compatible to WordPress v3.9

= 1.3 =
* fixed filter handling for no attachment emails (thanks to @mathijsbok)

= 1.2 =
* refactored `gf_notification_attachment_send` to filter the attachment id array through wp_get_attachment_url for server compatibility
(https://gist.github.com/codearachnid/9537604)

= 1.1hf = 
* fixed issue with referencing empty array on blank meta

= 1.0 =
* Initial release.
