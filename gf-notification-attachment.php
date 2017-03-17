<?php
/* 

Plugin Name: Gravity Forms: Notification Attachments
Plugin URI: http://codearachnid.github.io/gf-notification-attachment/
Description: An addon for Gravity Forms to add attachments to notification emails
Version: 1.3
Author: Timothy Wood (@codearachnid)
Author URI: http://codearachnid.com
Text Domain: gravity-forms-notification-attachments

*/

global $gf_notification_attachment;


//Loads translation
load_plugin_textdomain('gravity-forms-notification-attachments', false, dirname( plugin_basename( __FILE__ ) ). '/languages/');

add_action( 'init', 'gf_notification_attachment_init' );
add_action( 'wp_ajax_gf_notification_attachment', 'gf_notification_attachment_ajax' );
add_filter( 'gform_notification', 'gf_notification_attachment_send', 20, 3 );
add_filter( 'gform_pre_notification_save', 'gf_notification_attachment_save', 20, 2 );
add_filter( 'gform_notification_ui_settings', 'gf_notification_attachment_editor', 20, 3 );
add_action( 'admin_enqueue_scripts', 'gf_notification_attachment_attach_script');

/**
 * [gf_notification_attachment_init description]
 * @return object
 */
function gf_notification_attachment_init(){
	global $gf_notification_attachment;
	$gf_notification_attachment = (object) array(
		'text_domain' => 'gf-notification-attachment',
		'version' => '1.0',
		'plugin_url' => trailingslashit( plugin_dir_url( __FILE__ ) )
		);
	return $gf_notification_attachment;
}

function gf_notification_attachment_ajax(){
	$attachment_id = !empty( $_REQUEST['attachment_id'] ) ? $_REQUEST['attachment_id'] : 0;
	$attachment = gf_notification_attachment_get_meta( $attachment_id );
	$response = array(
		'attachment_id' => $attachment_id,
		'success' => empty($attachment) ? false : true,
		'data' => $attachment
		);
	echo json_encode( $response );
	die;
}

/**
 * [gf_notification_attachment_send description]
 * @param  array $notification
 * @param  array $form
 * @param  array $lead
 * @return array
 */
function gf_notification_attachment_send( $notification, $form, $lead ){
	$attachment_id_raw = esc_attr( rgar( $notification, "attachment_id" ) );
	$attachment_ids = (array) json_decode( $attachment_id_raw );
	$wp_upload_dir = wp_upload_dir();
	if( !empty( $attachment_ids ) ) {
		foreach( $attachment_ids as $attachment_id ){
			$attachment = wp_get_attachment_url( $attachment_id );
			if( !empty( $attachment ) ){
				$notification['attachments'][] = str_replace( $wp_upload_dir['baseurl'], $wp_upload_dir['basedir'], $attachment );
			}
		}
	}
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
		
		$attachment_id_raw = esc_attr( rgar( $notification, "attachment_id" ) );
		$attachment_ids = (array) json_decode( $attachment_id_raw );

		ob_start();
		?>
		<tr valign="top">
            <th scope="row">
                <label for="gform_notification_attachment">
                    <?php _e("Attachments", "gravity-forms-notification-attachments"); ?>
                </label>
            </th>
            <td id="gform_notification_attachment">
				<ul class="details">
					<?php foreach( $attachment_ids as $attachment_id ) : $attachment = gf_notification_attachment_get_meta( $attachment_id ); ?>
					<li data-id="<?php echo $attachment_id; ?>">
						<div class="remove dashicons dashicons-dismiss"></div>
	                	<img src="<?php echo $attachment->mime_file; ?>" class="fl" />
	                	<div class="fl file-details">
		                	<span class="title"><?php echo $attachment->title; ?></span>
		                	<span class="mime">[<?php echo $attachment->mime; ?>]</span>
		                </div>
		                <br class="clear">
	                </li>
	                <?php endforeach; ?>           	
                </ul>
                <input type="hidden" name="gform_notification_attachment_id" id="gform_notification_attachment_id" class="attachment_ids" value="<?php echo $attachment_id_raw; ?>" />
            	<a href="#" class="button add gform_notification_attachment" title="<?php _e('Add Attachment', 'gravity-forms-notification-attachments'); ?>">
            		<?php _e('Add Attachment', 'gravity-forms-notification-attachments'); ?>
            	</a>
            </td>
        </tr> <!-- / notification attachment -->
        <?php
		$ui_settings['notification_message'] .= ob_get_clean();
	}
	return $ui_settings;
}

/**
 * [gf_notification_attachment_get_meta description]
 * @param  int $attachment_id
 * @return obj
 */
function gf_notification_attachment_get_meta( $attachment_id  ) {

	$attachment = get_post( $attachment_id );
	$image = wp_get_attachment_image_src( $attachment_id, array(100,100), true ); 
	$image = !empty( $image ) ? $image[0] : null;

	if( is_null( $image ) )
		$image = wp_mime_type_icon( $attachment->post_mime_type );

	return (object) apply_filters( 'gf_notification_attachment_get_meta', array(
			'id' => $attachment_id,
			'mime_file' => $image,
			'mime' => $attachment->post_mime_type,
			'title' => $attachment->post_title
		), $attachment_id, $attachment );
}

/**
 * [gf_notification_attachment_attach_script description]
 * @return null
 */
function gf_notification_attachment_attach_script(){
	global $gf_notification_attachment;
	$plugin = $gf_notification_attachment;
	if( GFForms::get_page() == 'notification_edit'){
		$script = $plugin->plugin_url . 'script';
		$script .= ( WP_DEBUG ) ? '.js' : '.min.js';
		wp_enqueue_script( $plugin->text_domain, $script, array('gform_gravityforms'), $plugin->version, true );
		wp_enqueue_style( $plugin->text_domain, $plugin->plugin_url . 'style.css', array(), $plugin->version );			
	}
}
