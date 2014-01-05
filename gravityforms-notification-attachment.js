/**
* Custom media selector for attaching items to notification emails.
*/

// custom obj for the wp.media.frames
var gform_notification_attachment;

jQuery('.button.gform_notification_attachment').live('click', function( event ){
	event.preventDefault();

	// If the media frame already exists, reopen
	if ( gform_notification_attachment ) {
		gform_notification_attachment.open();
		return;
	}

	// Create the media frame
	gform_notification_attachment = wp.media.frames.gform_notification_attachment = wp.media({
		title: jQuery( this ).attr( 'title' ),
		button: {
			text: jQuery( this ).attr( 'title' ),
		},
		multiple: false
	});

	// When an image is selected, run a callback.
	gform_notification_attachment.on( 'select', function() {
		// We set multiple to false so only get one image from the uploader
		attachment = gform_notification_attachment.state().get('selection').first().toJSON();
		// gform_notification_attachment.state().get('selection').map( function( attachment ) {
		// console.log(attachment);
		var details = jQuery('#gform_notification_attachment-details')
		details.find('img').attr( 'src', attachment.icon );
		details.find('.title').text( attachment.title );
		details.find('.type').text( attachment.mime );
		details.show();
		jQuery("#gform_notification_attachment-button").hide();
		// jQuery('#gform_notification_attachment_url').val( attachment.url );
		jQuery('#gform_notification_attachment_id').val( attachment.id );
		// });
	});

	// Finally, open the modal for selecting files
	gform_notification_attachment.open();
});
