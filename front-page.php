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
		'post_type'      => 'post',
		'posts_per_page' => 5,
		'post__not_in'   => get_option( 'sticky_posts' ),
		'post_status'    => 'publish',
		'meta_query'  => array( // phpcs:ignore
			'relation' => 'OR',
			array(
				'key'     => 'exclude_from_front_page',
				'value'   => '',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'exclude_from_front_page',
				'value'   => '0',
				'compare' => '==',
			),
		),
	)
);

$query = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => -1,
		'post__not_in'   => get_option( 'sticky_posts' ),
		'post_status'    => 'publish',
		'meta_query'  => array( // phpcs:ignore
			'relation' => 'OR',
			array(
				'key'     => 'exclude_from_front_page',
				'value'   => '',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'exclude_from_front_page',
				'value'   => '0',
				'compare' => '==',
			),
		),
	)
);

$context['per_page']            = 5;
$context['found_posts']         = $query->found_posts;
$context['posts_on_front_page'] = get_option( 'posts_on_front_page' );

$templates = array( 'pages/front-page.html.twig' );

Timber::render( $templates, $context );
