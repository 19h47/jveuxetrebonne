<?php
/**
 *
 * Display lastest post
 *
 * @file        component-recent-post
 * @package     jveb
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\{ Timber };

$context = Timber::get_context();


// Get lastest post.
$context['posts'] = Timber::get_posts(
	array(
		'order'          => 'DESC',
		'orderby'        => 'date',
		'post_type'      => 'post',
		'posts_per_page' => 1,
	)
);


Timber::render( 'components/recent-post.twig', $context );
