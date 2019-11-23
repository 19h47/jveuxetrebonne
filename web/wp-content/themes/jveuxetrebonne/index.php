<?php
/**
 * @file index
 * @package jveb
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 */

use Timber\{ Timber, Post };

$context         = Timber::get_context();
$context['post'] = new Post();

$templates = array( 'index.html.twig' );

Timber::render( $templates, $context );
