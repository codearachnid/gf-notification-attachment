/**
 * Custom media selector for attaching items to notification emails.
 * An addon for Gravity Forms to add attachments to notification emails
 * @author Timothy Wood (@codearachnid)
 * @version 1.0
 */
// custom obj for the wp.media.frames
var gform_notification_attachment;
jQuery( document ).ready(function(){
	var element = jQuery('#gform_notification_attachment');
	var attachment_ids = jQuery.parseJSON( element.find('.attachment_ids').val() );

	// convert the attachment_ids from objects to arrays
	if( typeof attachment_ids == 'object' )
		attachment_ids = attachment_ids != null ? 
			jQuery.map( attachment_ids, function(value, index){ 
				return value;
			}) : [];

	if( element.find('.details li').length > 0 )
		element.show();
	
	// add remove events
	element.find('.remove').on('click',function( event ){
		event.preventDefault();
		var attachment = jQuery(this).parent();
		var index = attachment_ids.indexOf( attachment.data('id') );
		if ( index > -1 ) {
		    attachment_ids.splice( index, 1 );
		    element.find('.attachment_ids').val( JSON.stringify( attachment_ids ) );
		}
		// remove the attachment from the DOM list
		attachment.remove();
	});

	// add attachment event
	element.find('.add').on('click', function( event ){
		event.preventDefault();

		// if the media frame already exists, reopen
		if ( gform_notification_attachment ) {
			gform_notification_attachment.open();
			return;
		}

		// create the media frame
		gform_notification_attachment = wp.media.frames.gform_notification_attachment = wp.media({
			title: jQuery( this ).attr( 'title' ),
			button: {
				text: jQuery( this ).attr( 'title' ),
			},
			multiple: false // set multiple to false so only get one file from the uploader
		});

		// run a callback when file is selected
		gform_notification_attachment.on( 'select', function() {

			// if multiple == false get the first file from the uploader
			attachment = gform_notification_attachment.state().get('selection').first().toJSON();

			var request = {
				action: 'gf_notification_attachment',
				attachment_id: attachment.id
			};

			// lookup media assets for insert
			jQuery.post( ajaxurl, request, function ( response ) {
				response = jQuery.parseJSON( response );
				// if response is successful then attach it to the DOM
				if ( response.success ) {

					// create attachment node
					var node = jQuery( '<li/>' ).data( 'id', attachment.id );

					// build the substructure
					var nodes = [
						jQuery( '<div/>' ).addClass( 'remove dashicons dashicons-dismiss' ), // add delete icon
						jQuery( '<img/>' ).addClass( 'fl' ).attr( 'src', response.data.mime_file ), // add mime icon
						jQuery( '<div/>').addClass('fl file-details').append(
							jQuery( '<span/>').text( response.data.title ).addClass('title'),
							jQuery( '<span/>').text( "[" + response.data.mime + "]").addClass('mime')
						), // add the file details
						jQuery( '<br/>' ).addClass( 'clear' ) // add clear
					];

					// add internal nodes
					node.append( nodes );

					// add node to the detail list
					element.find('.details').append( node );

					// store the attachment id 
					attachment_ids.push( attachment.id );

					// store attachment ids back to the hidden field
					element.find('.attachment_ids').val( JSON.stringify( attachment_ids ) );

				} else {
					alert('The system could not attach the selected file to the notification. Please try again.');
				}
			} );
			
			// });
		});

		// Finally, open the modal for selecting files
		gform_notification_attachment.open();
	});
});