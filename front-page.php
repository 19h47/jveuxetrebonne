<?php
/**
 *
 * @file front-page
 * @package JVEB
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 */

use Timber\{ Timber, Helper };

$post_per_page = 5;
$context       = Timber::get_context();

$context['posts'] = Helper::transient(
	'jveb_front_page_posts',
	function () use ( $post_per_page ) {
		return Timber::get_posts(
			array(
				'post_type'        => 'post',
				'category__not_in' => array( 1335 ),
				'posts_per_page'   => $post_per_page,
				'post__not_in'     => get_option( 'sticky_posts' ),
				'post_status'      => 'publish',
				'meta_query'       => array( // phpcs:ignore
					'relation' => 'OR',
					array(
						'key'   => 'exclude_from_loop',
						'value' => 0,
						'type'  => 'BOOLEAN',
					),
					array(
						'key'     => 'exclude_from_loop',
						'compare' => 'NOT EXISTS',
						'type'    => 'BOOLEAN',
					),
				),
			)
		);
	},
	3600
);

$count_query = new WP_Query(
	array(
		'post_type' 		=> 'post',
		'category__not_in'	=> array( 1335 ),
		'posts_per_page'	=> -1,
		// 'ignore_sticky_posts' 	=> 1,
		'post__not_in' 		=>	get_option( 'sticky_posts' ),
		'post_status'		=> 'publish',
		'meta_query'		=> array(
			'relation' 		=> 'OR',
			array(
				'key'		=> 'exclude_from_loop',
				'value'		=> 0,
				'type' 		=> 'BOOLEAN'
			),
			array(
				'key' 		=> 'exclude_from_loop',
				'compare' 	=> 'NOT EXISTS',
				'type' 		=> 'BOOLEAN'
			)
		)
	)
);


$context['post_per_page'] = $post_per_page;
$context['found_posts'] = $count_query->found_posts;
$context['exclude'] = 1;


$templates = array( 'pages/front-page.html.twig' );

Timber::render( $templates, $context );
