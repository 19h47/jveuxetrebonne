<?php
/**
 *
 * Display last sticky post
 *
 * @file        component-sticky-post
 * @package     jveb
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\{ Timber, Helper };

$context = Timber::get_context();


// Add socials to context.
$socials      = array();

// @TODO: get socials from functions.php, and remove duplicate.
foreach ( array( 'YouTube', 'Facebook', 'Twitter', 'Instagram' ) as $social ) {
	${ strtolower( $social ) } = array(
		'slug' => strtolower( $social ),
		'name' => $social,
		'url'  => get_option( strtolower( $social ) ),
	);

	$socials[ strtolower( $social ) ] = ${ strtolower( $social ) };
}

$context['contact']['socials'] = $socials;

$context['posts'] = Helper::transient(
	'jveb_sticky_posts_' . pll_current_language( 'slug' ),
	function() {
		$sticky_posts = Timber::get_posts(
			array(
				'order'          => 'DESC',
				'orderby'        => 'date',
				'post_type'      => 'post',
				'post__in'       => get_option( 'sticky_posts' ),
				'posts_per_page' => 1,
			)
		);

		return $sticky_posts;
	},
);


Timber::render( 'components/sticky-post.html.twig', $context );
