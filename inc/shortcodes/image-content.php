<?php

use Timber\{ Timber };

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
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );

	extract(
		shortcode_atts(
			array(
				'content' => '',
				'url'     => '',
			),
			$atts,
			'image_content'
		)
	);

	$letters = str_split_unicode( $content );
	$i       = 0;
	$count   = count( $letters );

	for ( $i; $i < $count; $i++ ) {
		if ( ' ' === $letters[ $i ] ) {
			$letters[ $i ] = '&nbsp;';
		}

		$letters[ $i ] = '<i style="animation-delay:' . $i / count( $letters ) . 's;">' . $letters[ $i ] . '</i>';
	}

	$context = Timber::get_context();

	$context['url']     = $url;
	$context['letters'] = $letters;
	$context['id']      = uniqid();

	return Timber::compile( 'partials/image-content.html.twig', $context );
}
