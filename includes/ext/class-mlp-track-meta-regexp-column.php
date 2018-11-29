<?php

/**
 * The file that defines the class track meta regexp column for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the track type regexp-column.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Track_Meta_Regexp_Column extends MLP_Track_Meta {


	/**
	 * Params for WP_Query filter.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $replace_query .
	 */
	protected $replace_query = array();

	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct( $track ) {

		add_filter( 'posts_where', array( $this, 'replace_posts_where' ), 10, 2 );

		add_filter( 'mlp_query', array( $this, 'mlp_query_config' ) );


		$track = wp_parse_args( $track, array(

			'full_match' => false,

		) );

		// hide meta box
		$track[ 'meta_box' ] = false;

		parent::__construct( $track );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function fill_meta_query( $url, $track, $strict ) {

		if ( '' === $url && false === $track->is_strict() ) {

			return false;
		}

		$this->replace_query[ 'search' ][] = "/([\w.]+?)meta_value REGEXP '{$url}'/um";

		$this->replace_query[ 'replace' ][] = "'{$url}' REGEXP \\1meta_value";

		if ( $url && false === $track->is_strict() ) {

			return array(

				'relation' => 'OR',

				array(

					'key'      => $track->get( 'track_id' ),
					'value'    => $url,
					'compare'  => 'REGEXP',

				),

				array(

					'key'      => $track->get( 'track_id' ),
					'compare'  => 'NOT EXISTS',

				),

			);
		}

		return array(

			'key'      => $track->get( 'track_id' ),
			'value'    => $url,
			'compare'  => 'REGEXP',

		);

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @param      int            $post_id       .
	 */
	public function verify_save( $post_id ) {

		if ( wp_is_post_revision( $post_id ) ) {

			return false;
		}

		if( false === current_user_can( 'edit_post', $post_id ) ) {

			return false;
		}

		return true;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function get_saving_data() {

		if ( isset( $_POST[ $this->track_id . '-admin' ] ) ) {

			return $_POST[ $this->track_id . '-admin' ];
		}

		return '';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function prepare_data( $track, $post_id, $new_data, $old_data ) {

		if ( $new_data && false === is_array( $new_data ) ) {

			$new_data = array( $new_data );

		} else {

			$new_data = array();

		}

		return $new_data;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @param      string         $where         .
	 * @param      WP_Query       $query         .
	 */
	public function replace_posts_where( $where, $query ) {

		if ( false === isset( $query->query[ 'mlp_replace_query_where' ] ) ) {

			return $where;
		}

		$where = preg_replace( $this->replace_query[ 'search' ], $this->replace_query[ 'replace' ], $where );

		return $where;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @param      array          $query_args    .
	 */
	public function mlp_query_config( $query_args ) {

		if ( false === isset( $this->replace_query[ 'search' ] ) || false === isset( $this->replace_query[ 'replace' ] ) ) {

			return $query_args;
		}

		$query_args[ 'mlp_replace_query_where' ] = $this->replace_query;

		return $query_args;
	}


}
