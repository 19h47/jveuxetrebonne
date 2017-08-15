<?php


add_action( 'init', 'rich_content_shortcode_init' );


/**
 * Rich content shortcode init
 */
function rich_content_shortcode_init() {
	add_shortcode( 'rich_content', 'rich_content_shortcode_handler' );
}


/**
 * Rich content shortcode handler
 * 
 * @param  array 	$atts 		An array of attributes or an empty string
 * @param  string 	$content 	The enclosed content (available for enclosing 
 *                           	shortcodes only)
 * @param  string   $tag 		The name of the shortcode, useful for shared 
 *                         		callback functions.
 */
function rich_content_shortcode_handler( $atts, $content = null, $tag = '' ) {

	// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array)$atts, CASE_LOWER );

	extract( 
		shortcode_atts(
			array(
				'id'		=> ''
			),
			$atts,
			'rich_content'
		) 
	);

	$output = "<span class=\"rich-content-link js-rich-content-link\" data-id=\"{$id}\">";
	$output .= "<span></span>";
	$output .= "<i>" . html_entity_decode( $content ) . "</i>";
	$output .= "</span>";

	return $output;
}