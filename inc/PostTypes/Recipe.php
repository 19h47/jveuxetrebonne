<?php // phpcs:ignore
/**
 * Class Recipe
 *
 * PHP version 7.3.8
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 * @package JVEB
 */

namespace JVEB\PostTypes;

use Timber\{ Timber };
use JVEB\{ Transients };

/**
 * Recipe class
 *
 * @file   inc/PostTypes/Recipe.php
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 */
class Recipe {

	/**
	 * The version of the theme.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this theme.
	 */
	private $theme_version;


	/**
	 * Construct function
	 *
	 * @param string $theme_version The theme version.
	 * @access public
	 */
	public function __construct( string $theme_version ) {
		$this->theme_version = $theme_version;

		$this->register_post_type();

		add_action( 'init', array( $this, 'register_post_type' ) );

		add_action( 'wp_ajax_nopriv_ajax_load_recipes', array( $this, 'ajax_load_recipes' ) );
		add_action( 'wp_ajax_ajax_load_recipes', array( $this, 'ajax_load_recipes' ) );

		add_filter( 'manage_recipe_posts_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_recipe_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );
	}


	/**
	* Add custom columns
	*
	* @param array $columns Array of columns.
	* @link https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
	 */
	public function add_custom_columns( array $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {

			$new_columns[ $key ] = $value;

			if ( 'title' === $key ) {
				$new_columns['relation'] = pll__( 'Relation' );
			}
		}

		return $new_columns;
	}


	/**
	 * Render custom columns
	 *
	 * @param string $column_name The column name.
	 * @param int    $post_id The ID of the post.
	 */
	public function render_custom_columns( string $column_name, int $post_id ) {

		switch ( $column_name ) {
			case 'relation':
				$posts = Transients::posts();
				$html  = array();

				foreach ( $posts as $post ) {
					$recipes = get_field( 'recipes', $post->ID );

					if ( null === $recipes ) {
						continue;
					}

					if ( ! is_array( $recipes ) ) {
						continue;
					}

					foreach ( $recipes as $recipe ) {
						if ( $recipe->ID !== $post_id ) {
							continue;
						}

						array_push(
							$html,
							'<a href="' . get_edit_post_link( $post->ID ) . '">' . $post->post_title . '</a>'
						);
					}
				}

				if ( empty( $html ) ) {
					echo '—';
				} else {
					echo join( ', ', $html );
				}

				break;
		}
	}


	/**
	 * Register Custom Post Type
	 *
	 * @access public
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Recettes', 'Post Type General Name', 'JVEB' ),
			'singular_name'         => _x( 'Recette', 'Post Type Singular Name', 'JVEB' ),
			'menu_name'             => __( 'Recettes', 'JVEB' ),
			'name_admin_bar'        => __( 'Recette', 'JVEB' ),
			'archives'              => __( 'Archive des recettes', 'JVEB' ),
			'attributes'            => __( 'Item Attributes', 'JVEB' ),
			'parent_item_colon'     => __( 'Parent Item:', 'JVEB' ),
			'all_items'             => __( 'Toutes les recettes', 'JVEB' ),
			'add_new_item'          => __( 'Ajouter une nouvelle recette', 'JVEB' ),
			'add_new'               => __( 'Ajouter', 'JVEB' ),
			'new_item'              => __( 'Nouvelle recette', 'JVEB' ),
			'edit_item'             => __( 'Éditer la recette', 'JVEB' ),
			'update_item'           => __( 'Update Item', 'JVEB' ),
			'view_item'             => __( 'Voir la recette', 'JVEB' ),
			'view_items'            => __( 'Voir les recettes', 'JVEB' ),
			'search_items'          => __( 'Rechercher dans les recettes', 'JVEB' ),
			'not_found'             => __( 'Aucune publication trouvée.', 'JVEB' ),
			'not_found_in_trash'    => __( 'Aucune recette n\'a été trouvée dans la Corbeille', 'JVEB' ),
			'featured_image'        => __( 'Image à la une', 'JVEB' ),
			'set_featured_image'    => __( 'Set featured image', 'JVEB' ),
			'remove_featured_image' => __( 'Remove featured image', 'JVEB' ),
			'use_featured_image'    => __( 'Use as featured image', 'JVEB' ),
			'insert_into_item'      => __( 'Insert into item', 'JVEB' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'JVEB' ),
			'items_list'            => __( 'Items list', 'JVEB' ),
			'items_list_navigation' => __( 'Items list navigation', 'JVEB' ),
			'filter_items_list'     => __( 'Filter items list', 'JVEB' ),
		);

		$rewrite = array(
			'slug'       => __( 'recettes', 'JVEB' ),
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);

		$args = array(
			'label'               => __( 'Recette', 'JVEB' ),
			'description'         => __( 'Description de la recette', 'JVEB' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail', 'custom-fields', 'author' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-carrot',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'rewrite'             => $rewrite,
			'show_in_rest'        => true,
		);
		register_post_type( 'recipe', $args );
	}


	/**
	 * Load recipes with AJAX request.
	 */
	public function ajax_load_recipes() {
		if ( ! isset( $_GET['nonce'] ) && ! wp_verify_nonce( sanitize_key( $_GET['nonce'] ), 'security' ) ) {
			return false;
		}

		$id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : 0;

		if ( 0 === $id ) {
			return;
		}

		$args = array(
			'post_type' => 'recipe',
			'p'         => $id,
		);

		$context         = Timber::get_context();
		$context['post'] = Timber::get_post( $args );

		Timber::render( 'partials/recipe.html.twig', $context );

		wp_die();
	}
}
