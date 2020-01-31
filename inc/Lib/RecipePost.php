<?php // phpcs:ignore
/**
 * Recipe post
 *
 * PHP version 7.3.8
 *
 * @package jveb
 */

namespace JVEB\Lib;

use Timber\{ Post };

/**
 * Recipe post
 */
class RecipePost extends Post {

	/**
	 * Preparation time
	 *
	 * @return array
	 */
	public function preparation_time() : ?array {
		$time = (int) get_field( 'preparation_time', $this->ID );

		if ( ! $time ) {
			return null;
		}

		$days    = (int) floor( $time / 1440 );
		$hours   = (int) floor( ( $time - $days * 1440 ) / 60 );
		$minutes = (int) $time - ( $days * 1440 ) - ( $hours * 60 );

		return array(
			'time'   => $time,
			'output' => join( $this->output_time( $days, $hours, $minutes ), ' ' ),
		);
	}


	/**
	 * Cooking time
	 *
	 * @return array
	 */
	public function cooking_time() : ?array {
		$time = (int) get_field( 'cooking_time', $this->ID );

		if ( ! $time ) {
			return null;
		}

		$days    = (int) floor( $time / 1440 );
		$hours   = (int) floor( ( $time - $days * 1440 ) / 60 );
		$minutes = (int) $time - ( $days * 1440 ) - ( $hours * 60 );

		return array(
			'time'   => $time,
			'output' => join( $this->output_time( $days, $hours, $minutes ), ' ' ),
		);
	}


	/**
	 * Output time
	 *
	 * @param int $days
	 * @param int $hours
	 * @param int $minutes
	 * @return array
	 */
	public function output_time( int $days, int $hours, int $minutes ) : array {
		$putput = array();

		if ( 0 < $days ) {
			// $output[] = $this->pluralize( $days, pll__( 'jour' ), pll__( 'jours' ) );
			$output[] = $days . ' ' . pll__( 'j' );
		}

		if ( 0 < $hours ) {
			// $output[] = $this->pluralize( $hours, pll__( 'heure' ), pll__( 'heures' ) );
			$output[] = $hours . ' ' . pll__( 'h' );
		}

		if ( 0 < $minutes ) {
			// $output[] = $this->pluralize( $minutes, pll__( 'minute' ), pll__( 'minutes' ) );
			$output[] = $minutes . ' ' . pll__( 'min' );
		}

		return $output;
	}

	/**
	 * Pluralize
	 *
	 * @see https://stackoverflow.com/a/46597432/5091221
	 */
	public function pluralize( $quantity, $singular, $plural, $format = '%d %s' ) {
		if ( 1 === $quantity ) {
			return sprintf( $format, $quantity, $singular );
		}

		return sprintf( $format, $quantity, $plural );
	}
}
