<?php 
/** 
 * Template Name: Page À propos
 * 
 * @package  	WordPress
 * @subpackage  jveb
 * @author   	Jérémy Levron levronjeremy@19h47.fr
 */ 


if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}


$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['links'] = Timber::get_sidebar( 'component-link.php' );

$templates = array( 'pages/about.twig' );


Timber::render( $templates, $context )

?>