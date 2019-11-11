<?php
/**
 *
 * @file front-page
 * @package JVEB
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 */

use Timber\{ Timber, Helper };

$context = Timber::get_context();

$context['posts'] = Timber::get_posts(
	array(
		'post_type'        => 'post',
		'category__not_in' => array( 1411 ),
		'posts_per_page'   => 5,
		'post__not_in'     => get_option( 'sticky_posts' ),
		'post_status'      => 'publish',
	)
);

$query = new WP_Query(
	array(
		'post_type'        => 'post',
		'category__not_in' => array( 1411 ),
		'posts_per_page'   => -1,
		'post__not_in'     => get_option( 'sticky_posts' ),
		'post_status'      => 'publish',
	)
);

$context['per_page']    = 5;
$context['found_posts'] = $query->found_posts;

$templates = array( 'pages/front-page.html.twig' );

Timber::render( $templates, $context );
