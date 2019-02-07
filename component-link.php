<?php
/**
 * Display link from Link plugin
 *
 * @file        component-link
 * @package     jveb
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

$context = Timber::get_context();


// get last post
$context['links'] = Timber::get_posts(
    array(
        'post_type'         	=> 'link',
        'posts_per_page'    	=> -1,
        'orderby' 				=> 'menu_order',
        'order' 				=> 'ASC'
    )
);


Timber::render( 'components/links.twig', $context );
