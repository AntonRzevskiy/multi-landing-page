<?php

/**
 * The file that defines the class registry for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the main registry functionality.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Registry extends MLP_Related_Base {


	/**
	 * Register new tracks.
	 *
	 * @since      1.0.0
	 */
	public function add_url_tracks() {

		/**
		 * Fires for add tracks in plugin.
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $tracks        Tracks.
		 */
		$tracks = apply_filters( 'mlp_register_url_tracks', array() );

		foreach ( $tracks as $track ) {

			switch ( $track[ 'type' ] ) {

				case 'taxonomy': $this->tracks[] = new MLP_Track_Tax( $track ); break;

				case 'meta': $this->tracks[] = new MLP_Track_Meta( $track ); break;

				/**
				 * Fires if the default track type is undefined.
				 *
				 * This filter is used to add tracks and their subsequent processing.
				 *
				 * @since      1.0.0
				 *
				 * @param      array          $tracks        Tracks.
				 * @param      array          $track         Current track.
				 */
				default: $this->tracks = apply_filters( 'mlp_add_track', $this->tracks, $track );
			}

		}

	}

	/**
	 * Register new post type.
	 *
	 * @since      1.0.0
	 */
	public function add_post_type() {

		/**
		 * Fires for add post type in plugin.
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $post_types    Post types.
		 */
		$this->post_types = apply_filters( 'mlp_register_post_type', $this->post_types );
	}

	/**
	 * Register new post types in WP.
	 *
	 * @since      1.0.0
	 */
	public function register_post_type() {

		foreach ( $this->post_types as $name => $args ) {

			$args = wp_parse_args( $args, array(

				'public'              => false,
				'exclude_from_search' => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => true,
				'rewrite'             => false,
				'can_export'          => false,
				'taxonomies'          => array(),

			) );

			$taxonomies = $this->get_taxonomy_tracks( $name );

			foreach ( $taxonomies as $track ) {

				$args[ 'taxonomies' ][] = $track->get( 'track_id' );

			} unset( $track );

			register_post_type( $name, $args );

		}

	}

	/**
	 * Register taxonomies tracks for post types in WP.
	 *
	 * @since      1.0.0
	 */
	public function register_taxonomy() {

		$taxonomies = $this->get_taxonomy_tracks();

		foreach ( $taxonomies as $track ) {

			register_taxonomy( $track->get( 'track_id' ), $track->get( 'post_type' ), $track->get( 'taxonomy_args' ) );

		}

	}

	/**
	 * Register metaboxes for meta tracks in WP.
	 *
	 * @since      1.0.0
	 */
	public function register_metaboxes() {

		$meta = $this->get_meta_tracks();

		foreach ( $meta as $track ) {

			if ( $track->get( 'meta_box' ) ) {

				$id = $title = $callback = $screen = $callback_args = NULL;

				$context = 'advanced';

				$priority = 'default';

				extract( $track->get( 'meta_box' ), EXTR_IF_EXISTS );

				add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );

			}

		}

	}

	/**
	 * Register save post hooks for meta tracks in WP.
	 *
	 * @since      1.0.0
	 */
	public function save_metaboxes() {

		$meta = $this->get_meta_tracks();

		foreach ( $meta as $track ) {

			foreach ( $track->get( 'post_type' ) as $post_type ) {

				add_action( "save_post_{$post_type}", array( $track, 'save_post_data_init' ) );

			}

		}

	}


}
