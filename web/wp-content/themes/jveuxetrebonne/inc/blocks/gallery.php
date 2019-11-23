<?php

use Timber\{ Timber };

function gallery_render( array $attributes, $content ) {
	if ( is_admin() ) {
		return false;
	}

	$context = Timber::get_context();

	$context['post']            = array();
	$context['post']['gallery'] = array();
	$context['id']              = uniqid();

	foreach ( $attributes['ids'] as $id ) {
		array_push( $context['post']['gallery'], $id );
	}

	$count_ids = count( $attributes['ids'] );

	if ( $count_ids > 1 ) {
		$context['post']['count_attachments'] = $count_ids;
	}

	return Timber::compile( 'partials/slider-post.html.twig', $context );
}

function register_gallery() {
	register_block_type( 'core/gallery', array(
		'render_callback' => 'gallery_render',
	) );
}

add_action( 'init', 'register_gallery' );
