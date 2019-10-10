<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @file 404.php
 * @package JVEB
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\{ Timber };

$context = Timber::context();

Timber::render( 'pages/404.html.twig', $context );
