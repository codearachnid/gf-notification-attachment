Gravity Forms: Notifications Attachment
==========================
*This plugin requires Gravity Forms by RocketGenius to be active.*

Download from [WordPress.org plugin repo](http://wordpress.org/plugins/gravity-forms-notification-attachments/) or the [plugin homepage](http://codearachnid.github.io/gf-notification-attachment/).

	A WordPress addon for Gravity Forms to add attachments to notification emails. 

An active form with notifications must be available to  In order to add attachments to notification you must select them through the media modal, once listed save the notification to attach during notification triggers. This plugin uses the core WordPress and Gravity Forms methods to attach and send notification emails. You are responsible for ensuring your server environment can successfully send.

![A WordPress addon for Gravity Forms to add attachments to notification emails.](https://raw.github.com/codearachnid/gf-notification-attachment/master/screenshot.png)

	Note: When WP_DEBUG is active the script will use the unminified version of the script.

### Credits:

Thanks to the following users for making the plugin better!

* @saxonycreative
* @mathijsbok

### Changelog:
#### = 1.4 =

* fixed Gravity Forms from being greedy to remove the js script for plugin no conflict mode (thanks to @saxonycreative)
* tested compatible to WordPress v3.9

#### = 1.3 =

* fixed filter handling for no attachment emails (thanks to @mathijsbok)

#### = 1.2 =

* refactored `gf_notification_attachment_send` to filter the attachment id array through wp_get_attachment_url for server compatibility
[More Technical Detail](https://gist.github.com/codearachnid/9537604)

#### = 1.1hf = 

* fixed issue with referencing empty array on blank meta

#### = 1.0 =

* Initial release.

#### Known Issues:
* GoDaddy and other shared server environments may block wp_mail from sending attachments.
