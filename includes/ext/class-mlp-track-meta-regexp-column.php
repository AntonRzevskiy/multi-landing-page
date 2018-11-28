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
	public function define_process_meta( $track, $post_id = NULL, $new_data = array() ) {

		// get array metadata
		$old_data = get_metadata( $track->get( 'object_type' ), $post_id, $track->get( 'track_id' ) . '-public', false );

		if ( $new_data && false === is_array( $new_data ) ) {

			$new_data = array( wp_slash( $new_data ) );

		} else {

			$new_data = array();

		}

		if ( count( $old_data ) > count( $new_data ) ) {

			delete_metadata( $track->get( 'object_type' ), $post_id, $track->get( 'track_id' ) . '-public' );

			delete_metadata( $track->get( 'object_type' ), $post_id, $track->get( 'track_id' ) );

		}

		if ( $new_data ) {

			$old_value = ( isset( $old_data[ 0 ] ) ? $old_data[ 0 ] : '' );

			$new_value = $new_data[ 0 ];

			if ( $old_value !== $new_value ) {

				update_metadata( $track->get( 'object_type' ), $post_id, $track->get( 'track_id' ) . '-public', $new_value, $old_value );

				// convert back to regexp
				$new_value = wp_unslash( $new_value );
				$old_value = wp_unslash( $old_value );

				if ( false === $this->is_valid_data( $track, $post_id, $new_value, $old_value ) ) {

					/**
					 * .
					 *
					 * @since      1.0.0
					 *
					 * @param      object         $track         .
					 * @param      int            $post_id       .
					 * @param      string         $new_value     .
					 * @param      string         $old_value     .
					 */
					do_action( 'mlp_regexp_column_metadata_invalid', $track, $post_id, $new_value, $old_value );

					return;
				}

				/**
				 * .
				 *
				 * @since      1.0.0
				 *
				 * @param      string         $new_value     .
				 * @param      object         $track         .
				 * @param      int            $post_id       .
				 */
				$new_value = apply_filters( 'mlp_save_regexp_column_metadata', $new_value, $track, $post_id );

				update_metadata( $track->get( 'object_type' ), $post_id, $track->get( 'track_id' ), $new_value, $old_value );

			}

		}

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function get_metadata( $track, $post_id = NULL ) {

		global $post;

		$data = get_metadata( $track->get( 'object_type' ), $post->ID, $track->get( 'track_id' ) . '-public', false );

		if ( $data ) {

			return wp_unslash( $data );
		}

		return array();
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
