/**
 * Taxonomy Image Uploader
 * Handles the media uploader for category images
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        var mediaUploader;
        
        // Upload button click
        $('#haupt_upload_image_button').on('click', function(e) {
            e.preventDefault();
            
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Select Sector Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            // When an image is selected, run a callback
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                
                // Set the hidden input value
                $('#haupt_category_image_id').val(attachment.id);
                
                // Show preview
                var previewHtml = '<img src="' + attachment.url + '" style="max-width: 300px; height: auto; border-radius: 4px;">';
                $('#haupt_category_image_preview').html(previewHtml);
                
                // Show remove button, change upload button text
                $('#haupt_remove_image_button').show();
                $('#haupt_upload_image_button').text('Change Image');
            });
            
            // Open the uploader dialog
            mediaUploader.open();
        });
        
        // Remove button click
        $('#haupt_remove_image_button').on('click', function(e) {
            e.preventDefault();
            
            // Clear the hidden input
            $('#haupt_category_image_id').val('');
            
            // Clear preview
            $('#haupt_category_image_preview').html('');
            
            // Hide remove button, reset upload button text
            $('#haupt_remove_image_button').hide();
            $('#haupt_upload_image_button').text('Upload Image');
        });
    });
    
})(jQuery);
