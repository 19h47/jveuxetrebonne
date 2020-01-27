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

$context['post']->recipes = Timber::get_posts( get_field( 'recipes' ), 'JVEB\Lib\RecipePost' );

$templates = array( 'pages/single.html.twig' );

Timber::render( $templates, $context );
