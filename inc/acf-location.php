<?php

/**
 * ACF location
 *
 * @see  https://github.com/elliotcondon/acf/edit/master/core/controllers/location.php
 */
class acf_location_rule {

	/**
	 * Name of rule
	 *
	 * @access public
	 */
	public $rule_name;


	/**
	 * __construct
	 *
	 * @param  String 	$rule_name
	 * @access public
	 */
	public function __construct( $rule_name ) {

		$this->rule_name = $rule_name;

		add_filter( 'acf/location/rule_types', array( $this, 'types' ) );
		add_filter( 'acf/location/rule_values/' . $this->rule_name, array( $this, 'values' ) );
		add_filter( 'acf/location/rule_match/' . $this->rule_name,  array( $this, 'match' ), 10, 3 );
	}


	/**
	 * Add rule to section
	 *
	 * @param  	Array 	$choices
	 * @return 	array 	$choices 	Populate choice
	 */
	function types( $choices ) {

	    // Add choice
	    // @TODO set in english then later translate to french
	    $choices['Catégorie'][$this->rule_name] = __( 'Catégorie parente' );

		return $choices;
	}


	/**
	 * Populate list with options
	 *
	 * @param  Array $choices
	 */
	function values( $choices ) {

		// Retrieve all categories
		$categories = get_categories();


		foreach( $categories as $category ) {

			if ( $category->category_parent != 0 ) {
				continue;
			}

			$choices[$category->cat_ID] = $category->cat_name;

		}


	    return $choices;
	}


	/**
	 * Match the rule
	 *
	 * @param  	$match
	 * @param  	$rule
	 * @param  	$options
	 * @return  $match
	 * @see https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-post-category-ancestor-location-rule.php
	 */
	function match( $match, $rule, $options ) {
		if ( ! isset( $options['post_taxonomy'] ) ) {
			return false;
		}

		// most of this copied directly from acf post category rule
		$terms = $options['post_taxonomy'];
		$data = acf_decode_taxonomy_term( $rule['value'] );

		$term = get_term_by( 'slug', $data['term'], $data['taxonomy'] );

		if ( ! $term && is_numeric( $data['term'] ) ) {
			$term = get_term_by('id', $data['term'], $data['taxonomy']);
		}

		// this is where it's different than ACf
		// get terms so we can look at the parents
		if ( is_array( $terms ) ) {
			foreach ( $terms as $index => $term_id ) {
				$terms[$index] = get_term_by('id', intval( $term_id ), $term->taxonomy );
			}
		}

		if (! is_array( $terms ) && $options['post_id'] ) {
			$terms = wp_get_post_terms( intval( $options['post_id'] ), $term->taxonomy );
		}

		if ( ! is_array( $terms ) ) {
			$terms = array($terms);
		}
		$terms = array_filter( $terms );
		$match = false;


		// collect a list of ancestors
		$ancestors = array();
		if ( count( $terms ) ) {
			foreach ( $terms as $term_to_check ) {
				$ancestors = array_merge( get_ancestors( $term_to_check->term_id, $term->taxonomy ) );
			}
		}


		// see if the rule matches any term ancetor
		if ( $term && in_array( $term->term_id, $ancestors ) ) {
			$match = true;
		}

		if ( $rule['operator'] == '!=' ) {
			// reverse the result
			$match = !$match;
		}

		return $match;

	}
}
