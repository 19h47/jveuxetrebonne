<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @file 	404.php
 * @package JVeuxEtreBonne
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::context();

Timber::render( 'pages/404.html.twig', $context );
