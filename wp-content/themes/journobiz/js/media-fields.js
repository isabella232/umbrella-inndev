/*
 * Attaches the image uploader to the input field
 * See https://github.com/thomasgriffin/New-Media-Image-Uploader
 */
jQuery(document).ready(function($){

  // Instantiates the variable that holds the media library frame.
  var grantee_media_frame;

  // Runs when the image button is clicked.
  $('#meta-budget-button, #meta-proposal-button').click(function(e){

		window.field_prefix = this.id.replace('-button', '');

    // Prevents the default action from occuring.
    e.preventDefault();

    // If the frame already exists, re-open it.
    if ( grantee_media_frame ) {
      grantee_media_frame.open();
      return;
    }

    // Sets up the media library frame
    grantee_media_frame = wp.media.frames.grantee_media_frame = wp.media({
  		className: "media-frame my-media-frame",
      title: meta_file.title,
      multiple: false,
      button: { text:  meta_file.button },
      library: { type: 'application' }
    });

    // Runs when an image is selected.
    grantee_media_frame.on('select', function(){

      // Grabs the attachment selection and creates a JSON representation of the model.
      var media_attachment = grantee_media_frame.state().get('selection').first().toJSON();

      // Sends the attachment URL to our custom image input field.
      // See http://kingpro.me/article/tutorials/custom-image-uploader-for-plugins-using-the-wordpress-native-functions/ for object specs
      $('#' + window.field_prefix + '-filename').val(media_attachment.filename);
      $('#' + window.field_prefix + '-id').val(media_attachment.id);
    });

    // Opens the media library frame.
    grantee_media_frame.open();
  });
});