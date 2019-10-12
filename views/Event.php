<?php // phpcs:ignore
/**
 * Class Event
 *
 * PHP version 7
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 * @package VacancesPourSeniors
 */

namespace VacancesPourSeniors\PostTypes;

use WP_Post;
use Timber;

/**
 * Event class
 *
 * @file   inc/post-types/class-event.php
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 */
class Event {

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
		add_action( 'admin_head', array( $this, 'css' ) );

		add_action( 'wp_ajax_nopriv_ajax_load_events', array( $this, 'ajax_load_events' ) );
		add_action( 'wp_ajax_ajax_load_events', array( $this, 'ajax_load_events' ) );

		add_action( 'save_post_event', array( $this, 'save' ), 10, 3 );
	}


	/**
	 * Save
	 *
	 * Fires once an event has been saved.
	 *
	 * @param  int     $post_ID Post ID.
	 * @param  WP_Post $post    Post object.
	 * @param  bool    $update  Whether this is an existing post being updated or not.
	 * @link https://developer.wordpress.org/reference/hooks/save_post_post-post_type/
	 * @access public
	 */
	public function save( int $post_ID, WP_Post $post, bool $update ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		delete_transient( 'vps_events' );
	}


	/**
	 * Register Custom Post Type
	 *
	 * @access public
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Évènements', 'Post Type General Name', 'VacancesPourSeniors' ),
			'singular_name'         => _x( 'Évènement', 'Post Type Singular Name', 'VacancesPourSeniors' ),
			'menu_name'             => __( 'Évènements', 'VacancesPourSeniors' ),
			'name_admin_bar'        => __( 'Évènement', 'VacancesPourSeniors' ),
			'archives'              => __( 'Archive des évènements', 'VacancesPourSeniors' ),
			'attributes'            => __( 'Item Attributes', 'VacancesPourSeniors' ),
			'parent_item_colon'     => __( 'Parent Item:', 'VacancesPourSeniors' ),
			'all_items'             => __( 'Tous les évènements', 'VacancesPourSeniors' ),
			'add_new_item'          => __( 'Ajouter un nouvel évènement', 'VacancesPourSeniors' ),
			'add_new'               => __( 'Ajouter', 'VacancesPourSeniors' ),
			'new_item'              => __( 'Nouvel évènement', 'VacancesPourSeniors' ),
			'edit_item'             => __( 'Éditer l\'évènement', 'VacancesPourSeniors' ),
			'update_item'           => __( 'Update Item', 'VacancesPourSeniors' ),
			'view_item'             => __( 'Voir l\'évènement', 'VacancesPourSeniors' ),
			'view_items'            => __( 'Voir les évènements', 'VacancesPourSeniors' ),
			'search_items'          => __( 'Rechercher dans les évènements', 'VacancesPourSeniors' ),
			'not_found'             => __( 'Aucune publication trouvée.', 'VacancesPourSeniors' ),
			'not_found_in_trash'    => __( 'Aucun évènement n\'a été trouvé dans la Corbeille', 'VacancesPourSeniors' ),
			'featured_image'        => __( 'Image à la une', 'VacancesPourSeniors' ),
			'set_featured_image'    => __( 'Set featured image', 'VacancesPourSeniors' ),
			'remove_featured_image' => __( 'Remove featured image', 'VacancesPourSeniors' ),
			'use_featured_image'    => __( 'Use as featured image', 'VacancesPourSeniors' ),
			'insert_into_item'      => __( 'Insert into item', 'VacancesPourSeniors' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'VacancesPourSeniors' ),
			'items_list'            => __( 'Items list', 'VacancesPourSeniors' ),
			'items_list_navigation' => __( 'Items list navigation', 'VacancesPourSeniors' ),
			'filter_items_list'     => __( 'Filter items list', 'VacancesPourSeniors' ),
		);

		$rewrite = array(
			'slug'       => __( 'evenements', 'VacancesPourSeniors' ),
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);

		$args = array(
			'label'               => __( 'Évènement', 'VacancesPourSeniors' ),
			'description'         => __( 'Description de l\'évènement', 'VacancesPourSeniors' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'          => array( 'event_category' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-megaphone',
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
		register_post_type( 'event', $args );
	}


	/**
	 * CSS
	 *
	 * @access public
	 */
	public function css() {
		global $typenow;

		if ( 'event' !== $typenow ) {
			return false;
		}

		?>
		<style></style>
		<?php

		return true;
	}


	/**
	 * Load posts with AJAX request.
	 *
	 * @access public
	 */
	public function ajax_load_events() {
		if ( ! isset( $_GET['nonce'] ) && ! wp_verify_nonce( sanitize_key( $_GET['nonce'] ), 'security' ) ) {
			return false;
		}

		$offset         = 0;
		$posts_per_page = 3;

		if ( isset( $_GET['offset'] ) ) {
			$offset = sanitize_text_field( wp_unslash( $_GET['offset'] ) );
		}

		if ( isset( $_GET['posts_per_page'] ) ) {
			$posts_per_page = sanitize_text_field( wp_unslash( $_GET['posts_per_page'] ) );
		}

		$args = array(
			'post_type'      => 'event',
			'posts_per_page' => $posts_per_page,
			'offset'         => $offset,
			'post_status'    => 'publish',
		);

		// Timber.
		$context          = Timber::get_context();
		$context['posts'] = Timber::get_posts( $args );

		Timber::render( 'partials/archive-listing.html.twig', $context );

		wp_die();
	}
}
