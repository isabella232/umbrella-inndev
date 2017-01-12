<?php
/**
 * Formats an attachment link for inclusion on Guide pages, with icon, filesize & type, etc.
 *
 * @access public
 * @param integer $attachment_id
 * @return void
 */
function cjet_format_attachment_link( $attachment_id ) {

	$extras = cjet_attachment_extras( $attachment_id );

	switch ( $extras['human_type'] ) {
		case "Image":
			$human_type_css_class = "picture";
			break;
		case "Video":
			$human_type_css_class = "play";
			break;
		case "Excel":
			$human_type_css_class = "table";
			break;
		case "Word":
			$human_type_css_class = "doc-text";
			break;
		case "PDF":
			$human_type_css_class = "doc-text-inv";
			break;
		default:
			$human_type_css_class = "download";
	}

	$details = array();
	if ( !empty($extras['human_type'] )) $details[] = $extras['human_type'];
	if ( !empty($extras['filesize'] )) $details[] = size_format($extras['filesize']);

	$output = sprintf(
		'<a href="%s" title="%s"><i class="%s"></i> %s <span class="attachment-meta">(%s)</span></a>',
		wp_get_attachment_url( $attachment_id ),
		__('Permalink to', 'cjet') . ' ' . esc_attr( get_the_title( $attachment_id ) ),
		'icon-' . $human_type_css_class,
		get_the_title( $attachment_id ),
		implode(", ", $details)
	);
	return $output;
}


/**
 * Gets some extra information (simplified filetype and file size) about the provided attachment ID and returns it as an array.
 *
 * @access public
 * @param integer $attachment_id
 * @return array
 */
function cjet_attachment_extras( $attachment_id ) {

	$human_type = "";
	$mime = get_post_mime_type( $attachment_id );
	switch( $mime ) {
		case "image/jpeg":
		case "image/jpg":
		case "image/png":
		case "image/gif":
			$human_type = "Image";
			break;
		case "video/mpeg":
		case "video/mp4":
		case "video/quicktime":
			$human_type = "Video";
			break;
		case "application/vnd.ms-excel":
		case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
		case "text/csv":
			$human_type = "Excel";
			break;
		case "application/msword":
		case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
			$human_type = "Word";
			break;
		case "application/pdf":
			$human_type = "PDF";
			break;
		//case "application/vnd.ms-powerpoint"
	}

	return array(
		'human_type' => $human_type,
		'filesize' => @filesize( get_attached_file( $attachment_id )),
	);

}
