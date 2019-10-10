<?php

namespace JVEB\taxonomies;

/**
 * Project tag class
 */
class ProjectTag {

	/**
     * The unique identifier of this theme.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this theme.
     */
    protected $theme_name;


    /**
     * The version of the theme.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this theme.
     */
    private $theme_version;


	/**
	 * Construct function
	 *
	 * @access public
	 */
	public function __construct( $theme_name, $theme_version ) {
		$this->theme_name = $theme_name;
        $this->theme_version = $theme_version;

        add_action( 'init', array( $this, 'register_taxonomy' ) );
	}


	// Register Custom Taxonomy
	function register_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Catégories', 'Taxonomy General Name', 'JVEB' ),
			'singular_name'              => _x( 'Catégorie', 'Taxonomy Singular Name', 'JVEB' ),
			'menu_name'                  => __( 'Catégories', 'JVEB' ),
			'all_items'                  => __( 'Toutes les Catégories', 'JVEB' ),
			'parent_item'                => __( 'Catégorie parente', 'JVEB' ),
			'parent_item_colon'          => __( 'Catégorie parente :', 'JVEB' ),
			'new_item_name'              => __( 'Nom de la nouvelle catégorie', 'JVEB' ),
			'add_new_item'               => __( 'Ajouter une nouvelle catégorie', 'JVEB' ),
			'edit_item'                  => __( 'Éditer la catégorie', 'JVEB' ),
			'update_item'                => __( 'Mettre à jour la catégorie', 'JVEB' ),
			'view_item'                  => __( 'Voir la catégorie', 'JVEB' ),
			'separate_items_with_commas' => __( 'Séparer les catégories par des virgules', 'JVEB' ),
			'add_or_remove_items'        => __( 'Ajouter ou supprimer une catégorie', 'JVEB' ),
			'choose_from_most_used'      => __( 'Choisir parmi les catégories les plus utilisées', 'JVEB' ),
			'popular_items'              => __( 'Catégorie populaire', 'JVEB' ),
			'search_items'               => __( 'Catégories recherchées', 'JVEB' ),
			'not_found'                  => __( 'Aucune catégorie n\'a été trouvée', 'JVEB' ),
			'no_terms'                   => __( 'Pas de catégorie', 'JVEB' ),
			'items_list'                 => __( 'Liste des catégories', 'JVEB' ),
			'items_list_navigation'      => __( 'Liste de navigation des catégories', 'JVEB' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		register_taxonomy( 'project_tag', array( 'project' ), $args );

	}
}
