<?php
/**
 * Template Name: Page À propos
 *
 * @package JVeuxEtreBonne
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context          = Timber::context();
$context['post']  = Timber::query_post();
$context['links'] = Timber::get_sidebar( 'component-link.php' );

$templates = array( 'pages/about.html.twig' );

Timber::render( $templates, $context );
