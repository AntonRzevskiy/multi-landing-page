<?php

/**
 * The file that defines the class track taxonomy for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the track taxonomy.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Track_Tax extends MLP_Track_Base {


	/**
	 * Params for register_taxonomy WP function.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $taxonomy_args
	 */
	protected $taxonomy_args = array();

	/**
	 * Params for WP_Query.
	 *
	 * @since      1.0.0
	 *
	 * @var        function       $fill_tax_query
	 */
	protected $fill_tax_query;

	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 *
	 * @param      array          $args          Arguments when creating.
	 */
	public function __construct( $args ) {

		$args = wp_parse_args( $args, array(

			'object_type'    => 'post',
			'track_id'       => '',
			'post_type'      => array(),
			'taxonomy_args'  => array(),
			'fill_tax_query' => array( $this, 'fill_tax_query' ),

		) );

		$this->args = $args;

		$this->type = 'taxonomy';

		$this->object_type = $args[ 'object_type' ];

		$this->track_id = $args[ 'track_id' ];

		$this->full_match = true;

		$this->post_type = is_array( $args[ 'post_type' ] ) ? $args[ 'post_type' ] : array( $args[ 'post_type' ] );

		$this->taxonomy_args = wp_parse_args( $args[ 'taxonomy_args' ], array(

			'hierarchical'       => false,
			'rewrite'            => true,
			'publicly_queryable' => false,
			'query_var'          => true,
			'show_in_rest'       => true, // for Gutenberg editor

		) );

		$this->fill_tax_query = $args[ 'fill_tax_query' ];

		// Init
		$this->init();

	}

	/**
	 * Fill part of taxonomy query.
	 *
	 * @since      1.0.0
	 *
	 * @param      string         $url           URL value.
	 * @param      object         $track         Current instance.
	 * @param      bool           $strict        Is the query strict.
	 *
	 * @return     array          Part of taxonomy query.
	 */
	public function fill_tax_query( $url, $track, $strict ) {

		return array(

			'taxonomy' => $this->track_id,
			'field'    => 'name',
			'terms'    => array( $url ),
			'operator' => 'IN',

		);

	}


}
