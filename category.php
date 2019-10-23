<?php
/**
 * Category
 *
 * @package JVEB
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 */

use Timber\{ Timber, Term };

global $wp_query;

$context                  = Timber::get_context();
$context['posts']         = Timber::get_posts();
$context['category']      = new Term( get_queried_object_id() );
$context['post_per_page'] = 9;
$context['found_posts']   = $wp_query->found_posts;

$templates = array( 'pages/category.html.twig' );

Timber::render( $templates, $context );
