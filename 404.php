<?php

/**
 * @file 		404
 * @package  	jveb
 * @author   	Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */


if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::get_context();
$post = new TimberPost();

$templates = array( 'pages/404.twig' );


Timber::render( $templates, $context );
