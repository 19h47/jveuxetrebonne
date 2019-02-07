<?php

/**
 *
 * Display last recent post
 *
 * @file        component-recent-post
 * @package     jveb
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

$context = Timber::get_context();


// get last post
$context['posts'] = Timber::get_posts(
    array(
        'order'             	=> 'DESC',
        'orderby'           	=> 'date',
        'post_type'         	=> 'post',
        'posts_per_page'    	=> 1
    )
);


Timber::render( 'components/recent-post.twig', $context );
