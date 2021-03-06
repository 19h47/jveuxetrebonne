<?php
/**
 * Template Name: Page À propos
 *
 * @package JVeuxEtreBonne
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\{ Timber };

$context = Timber::context();

$context['links'] = Timber::get_sidebar( 'component-link.php' );

$context['post']               = Timber::query_post();
$context['post']->color_header = true;


$templates = array( 'pages/about.html.twig' );

Timber::render( $templates, $context );
