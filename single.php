<?php

/**
 * The Template for displaying all single posts
 *
 * @file single
 * @package JVEB
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 */

use Timber\{ Timber, Post, User };

$context = Timber::get_context();

$context['post']         = new Post();
$context['current_user'] = new User();

$templates = array( 'singles/index.html.twig' );

Timber::render( $templates, $context );
