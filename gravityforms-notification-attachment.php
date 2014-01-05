<?php
/* 

Plugin Name: Gravity Forms: Notification Attachments
Plugin URI: 
Description: An addon for Gravity Forms to add attachments to notification emails
Version: 1.0
Author: Timothy Wood (@codearachnid)
Author URI: http://codearachnid.com
Text Domain: gf_notification_attachment

*/


add_filter( 'gform_notification', 'gf_notification_attachment_send', 20, 3 );
add_filter( 'gform_pre_notification_save', 'gf_notification_attachment_save', 20, 2 );
add_filter( 'gform_notification_ui_settings', 'gf_notification_attachment_editor', 20, 3 );
add_action( 'admin_enqueue_scripts', 'gf_notification_attachment_attach_script');

/**
 * [gf_notification_attachment_send description]
 * @param  array $notification
 * @param  array $form
 * @param  array $lead
 * @return array
 */
function gf_notification_attachment_send( $notification, $form, $lead ){
	$attachment = wp_get_attachment_metadata( $notification['attachment_id'] );
	$wp_upload_dir = wp_upload_dir();
	$notification['attachments'][] = trailingslashit( $wp_upload_dir['basedir'] ) . $attachment['file'];
	return $notification;
}

/**
 * [gf_notification_attachment_save description]
 * @param  array $notification
 * @param  array $form
 * @return array
 */
function gf_notification_attachment_save( $notification, $form ){
	$notification["attachment_id"] = rgpost("gform_notification_attachment_id");
	return $notification;
}

/**
 * [gf_notification_attachment_editor description]
 * @param  array $ui_settings
 * @param  array $notification
 * @param  array $form
 * @return array
 */
function gf_notification_attachment_editor( $ui_settings, $notification, $form ){
	if( !empty($ui_settings['notification_message']) ){
		ob_start();
		?>
		<tr valign="top">
            <th scope="row">
                <label for="gform_notification_attachment">
                    <?php _e("Attachments", "gf_notification_attachment"); ?>
                </label>
            </th>
            <td>
            	<?php $attachment_id = esc_attr(rgar($notification,"attachment_id")); if( !empty($attachment_id) ) : $attachment_image = wp_get_attachment_image( $attachment_id, array(100,100), true ); ?>
            	<div id="gform_notification_attachment-details">
                	<?php echo $attachment_image; ?>
                	<span class="title"></span>
                	<span class="type"></span>
                	<input type="hidden" name="gform_notification_attachment_url" id="gform_notification_attachment_url" value="<?php echo esc_attr(rgar($notification,"attachments")); ?>" />
                	<input type="hidden" name="gform_notification_attachment_id" id="gform_notification_attachment_id" value="<?php echo $attachment_id; ?>" />
            	</div>
	                <?php else : ?>
				<div id="gform_notification_attachment-details" style="display:none;">
                	<img src="" />
                	<span class="title"></span>
                	<span class="type"></span>
                	<input type="hidden" name="gform_notification_attachment_id" id="gform_notification_attachment_id" />		            
                </div>
                <?php endif; ?>
            	<a href="#" id="gform_notification_attachment-button" class="button gform_notification_attachment" data-editor="gform_notification_attachment" title="<?php _e('Add Attachment', 'gf_notification_attachment'); ?>">
            		<?php _e('Add Attachment', 'gf_notification_attachment'); ?>
            	</a>
            </td>
        </tr> <!-- / notification attachment -->
        <?php
		$ui_settings['notification_message'] .= ob_get_clean();
	}
	return $ui_settings;
}

/**
 * [gf_notification_attachment_attach_script description]
 * @return null
 */
function gf_notification_attachment_attach_script(){
	if( GFForms::get_page() == 'notification_edit'){
		wp_enqueue_script( 'gravityforms-notification-attachment', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'gravityforms-notification-attachment.js', array('gform_gravityforms'), '1.0', true );
	}
}
