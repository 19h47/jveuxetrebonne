<?php
/**
 *
 * Display last sticky post
 *
 * @file        component-sticky-post
 * @package     jveb
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\{ Timber };

$context = Timber::get_context();


// Add socials to context.
$socials      = array();
$socials_name = array( 'YouTube', 'Facebook', 'Twitter', 'Instagram' );

// TODO: get socials from functions.php, and remove duplicate.
foreach ( $socials_name as $name ) {
	${ strtolower( $name ) } = array(
		'slug' => strtolower( $name ),
		'name' => $name,
		'url'  => get_option( strtolower( $name ) ),
	);

	$socials[ strtolower( $name ) ] = ${ strtolower( $name ) };
}

$context['contact']['socials'] = $socials;

$context['posts'] = Timber::get_posts(
	array(
		'ignore_sticky_posts' => 1,
		'order'               => 'DESC',
		'orderby'             => 'date',
		'post_type'           => 'post',
		'post__in'            => get_option( 'sticky_posts' ),
		'posts_per_page'      => 1,
	)
);

Timber::render( 'components/sticky-post.html.twig', $context );
