<?php

/**
 * The file that defines the abstract class for query results.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the query functionality.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
abstract class MLP_Query {


	/**
	 * The tracks registered via hooks.
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 *
	 * @var        MLP_Registry   $registry      The instance of class.
	 */
	protected $registry;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 */
	protected function prepare_tax_query( $args = array() ) {

		$tax_query = array();

		

		if ( 1 < count( $tax_query ) ) $tax_query[ 'relation' ] = 'AND';

		return $tax_query;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 */
	protected function prepare_meta_query( $args = array() ) {

		$meta_query = array();

		

		if ( 1 < count( $meta_query ) ) $meta_query[ 'relation' ] = 'AND';

		return $meta_query;
	}


}
