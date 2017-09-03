<?php


add_action( 'init', 'image_content_shortcode_init' );


/**
 * Rich content shortcode init
 */
function image_content_shortcode_init() {
	add_shortcode( 'image_content', 'image_content_shortcode_handler' );
}


/**
 * Image content shortcode handler
 */
function image_content_shortcode_handler( $atts ) {

	// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array)$atts, CASE_LOWER );

	extract( 
		shortcode_atts(
			array(
				'content'	=> '',
				'url'		=> ''
			),
			$atts,
			'image_content'
		) 
	);

	$letters = str_split_unicode( $content );
	$i = 0;
	for ( $i; $i < count( $letters ); $i++) { 

		if ( $letters[$i] === ' ' ) {
			$letters[$i] = '&nbsp;';
		}
		
		$letters[$i] = '<i style="animation-delay:' . $i / count( $letters ) . 's;">' . $letters[$i] . '</i>';
	}


	$output  = "<span class=\"image-content-link js-image-content-link\">";
	$output .= "<span class=\"image js-image-content-link-image\">";
	$output .= "<img src=\"{$url}\"></span>";
	$output .= "<span class=\"letters\">" . implode( $letters ) . "</span>";
	$output .= "</span>";

	return $output;
}