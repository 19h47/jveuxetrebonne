<?php
/**
 *
 * @file page.php
 * @package JVEB
 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\{ Timber, Post };

$context = Timber::get_context();
$context['post'] = new Post();

$context['post']->color_header = true;

$templates = array( 'pages/page.html.twig' );

Timber::render( $templates, $context );
