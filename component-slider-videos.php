<?php
/**
 * /component-slider-videos
 *
 * Display the last 5 recent post in the video category
 *
 * @package JVEB
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 */

use Timber\{ Timber, Helper };

$context = Timber::get_context();

global $post;

$context['posts'] = Helper::transient(
	'jveb_slider_videos',
	function() {
		$videos = Timber::get_posts(
			array(
				'post_type'           => 'post',
				'category__in'        => 1411,
				'posts_per_page'      => 5,
				'ignore_sticky_posts' => 1,
			),
			// 'VideoPost'
		);

		return $videos;
	},
);

Timber::render( 'blocks/slider-videos.html.twig', $context );
