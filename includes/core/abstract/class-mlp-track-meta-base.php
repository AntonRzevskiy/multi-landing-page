<?php

/**
 * The file that defines the abstract class track meta for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes/core/abstract
 */

/**
 * Define the track meta.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
abstract class MLP_Track_Meta_Base extends MLP_Track_Base implements MLP_Metadata {


	/**
	 * Params for add_meta_box function.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $meta_box
	 */
	protected $meta_box = array();

	/**
	 * Params for WP_Query.
	 *
	 * @since      1.0.0
	 *
	 * @var        function       $fill_meta_query
	 */
	protected $fill_meta_query;

	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 *
	 * @param      array          $args          Arguments when creating.
	 */
	public function __construct( $args ) {

		$this->type = 'meta';

		$args = wp_parse_args( $args, array(

			'meta_box'        => array(),
			'fill_meta_query' => array( $this, 'fill_meta_query' ),

		) );

		$this->fill_meta_query = $args[ 'fill_meta_query' ];

		parent::__construct( $args );

		if ( false !== $args[ 'meta_box' ] ) {

			$this->meta_box = wp_parse_args( $args[ 'meta_box' ], array(

				'id'            => $this->track_id,
				'title'         => $this->track_id,
				'callback'      => array( $this, 'display_metabox_init' ),
				'screen'        => $this->post_type,
				'context'       => 'side',
				'priority'      => 'low'

			) );

		}

	}

	/**
	 * Fill part of meta query.
	 *
	 * @since      1.0.0
	 *
	 * @param      string         $url           URL value.
	 * @param      object         $track         Current instance.
	 * @param      bool           $strict        Is the query strict.
	 *
	 * @return     array          Part of meta query.
	 */
	abstract public function fill_meta_query( $url, $track, $strict );

	/**
	 * Metabox.
	 *
	 * @since      1.0.0
	 */
	public function display_metabox_init() {

		wp_nonce_field( "_wp_nonce_{$this->track_id}", "_wp_nonce_{$this->track_id}" );

		/**
		 * Fires after meta-box created.
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          Instance.
		 */
		do_action_ref_array( 'mlp_init_metabox_track_display', array( &$this ) );

		/**
		 * Fires after meta-box created.
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          Instance.
		 */
		do_action_ref_array( "mlp_init_metabox_track_{$this->track_id}_display", array( &$this ) );

		$this->display_track_html( $this );

	}

	/**
	 * Display HTML.
	 *
	 * @since      1.0.0
	 *
	 * @param      object         $track         Instance of current track.
	 */
	public function display_track_html( $track ) {

		global $post;

		$data = $this->get_metadata( $track, $post->ID );

		$valid = $this->is_valid_data( $track, $post->ID, $data, array() );

		$value = esc_html( isset( $data[ 0 ] ) ? $data[ 0 ] : '' );

		?>

		<div class="mlp-track <?php echo ( $valid ? 'valid' : 'invalid' ); ?>">
			<label for="<?php echo $track->get( 'track_id' ); ?>"><?php _e( 'Fill track value' ); ?>: </label>
			<input type="text" id="<?php echo $track->get( 'track_id' ); ?>" name="<?php echo $track->get( 'track_id' ); ?>" value="<?php echo $value; ?>">
		</div>

		<?php

	}

	/**
	 * Save.
	 *
	 * @since      1.0.0
	 *
	 * @param      int/string     $post_id       ID of current page.
	 */
	public function save_post_data_init( $post_id ) {

		if ( false === $this->verify_save( $post_id ) ) {

			return;
		}

		$data = $this->get_saving_data();

		/**
		 * .
		 *
		 * This hook will fires if the user’s rights pass.
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          Instance.
		 */
		do_action_ref_array( 'mlp_init_metabox_track_save', array( &$this, $post_id, $data ) );

		/**
		 * .
		 *
		 * This hook will fires if the user’s rights pass.
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          Instance.
		 */
		do_action_ref_array( "mlp_init_metabox_track_{$this->track_id}_save", array( &$this, $post_id, $data ) );

		$this->define_process_meta( $this, $post_id, $data );

	}

	/**
	 * Define process.
	 *
	 * @since      1.0.0
	 *
	 * @param      object         $track         Instance of current track.
	 * @param      int/string     $post_id       Optional. ID of page. Default NULL.
	 * @param      mixed          $new_data      Optional. Saving data. Default empty array.
	 */
	public function define_process_meta( $track, $post_id = NULL, $new_data = '' ) {

		global $post;

		$post_id = intval( ( $post_id ? $post_id : $post->ID ) );

		// get array metadata
		$old_data = $this->get_metadata( $track, $post_id );

		$new_data = $this->prepare_data( $track, $post_id, $new_data, $old_data );

		if ( $this->is_valid_data( $track, $post_id, $new_data, $old_data ) ) {

			if ( count( $old_data ) > count( $new_data ) ) {

				$this->delete_metadata( $track, $post_id, $new_data, $old_data );

			}

			if ( $new_data ) {

				$this->save_metadata( $track, $post_id, $new_data, $old_data );

			}

		}

	}

	/**
	 * Vertify save track.
 	 *
	 * @since      1.0.0
	 *
	 * @param      int/string     $post_id       ID of page.
	 *
	 * @return     bool           True if current user have permissions. False if fail.
	 */
	abstract public function verify_save( $post_id );

	/**
	 * Get data for save.
	 *
	 * @since      1.0.0
	 *
	 * @return     mixed          Saving data.
	 */
	abstract public function get_saving_data();

	/**
	 * Prepare new data.
	 *
	 * @since      1.0.0
	 *
	 * @param      object         $track         Instance of current track.
	 * @param      int/string     $post_id       ID of page.
	 * @param      array          $new_data      Saving data.
	 * @param      array          $old_data      Old data from DB.
	 *
	 * @return     array          New data, equal in type to old.
	 */
	abstract public function prepare_data( $track, $post_id, $new_data, $old_data );

	/**
	 * Is new data valid.
	 *
	 * @since      1.0.0
	 *
	 * @param      object         $track         Instance of current track.
	 * @param      int/string     $post_id       ID of page.
	 * @param      array          $new_data      Saving data.
	 * @param      array          $old_data      Old data from DB.
	 *
	 * @return     bool           True if data valid. False fail.
	 */
	public function is_valid_data( $track, $post_id, $new_data, $old_data ) {

		/**
		 * Fires before returned.
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $track         Instance of current track.
		 * @param      int/string     $post_id       ID of page.
		 * @param      mixed          $new_data      Saving data.
		 * @param      mixed          $old_data      Old data from DB.
		 */
		return apply_filters( 'mlp_is_valid_data', true, $track, $post_id, $new_data, $old_data );
	}

	/**
	 * Get data from DataBase.
	 *
	 * @since      1.0.0
	 *
	 * @param      object         $track         Instance of current track.
	 * @param      int/string     $post_id       Optional. ID of page. Default NULL.
	 *
	 * @return     array          Old data form DB. Empty array if fail.
	 */
	public function get_metadata( $track, $post_id = NULL ) {

		global $post;

		$post_id = intval( ( $post_id ? $post_id : $post->ID ) );

		$data = get_metadata( $this->object_type, $post_id, $this->track_id, false );

		if ( $data ) {

			return $data;
		}

		return array();
	}

	/**
	 * Save or Update data into DB.
	 *
	 * @since      1.0.0
	 *
	 * @param      object         $track         Instance of current track.
	 * @param      int/string     $post_id       ID of page.
	 * @param      array          $new_data      Saving data.
	 * @param      array          $old_data      Old data from DB.
	 */
	public function save_metadata( $track, $post_id, $new_data, $old_data ) {

		/**
		 * Fires before saving.
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $new_data      Saving data.
		 * @param      object         $track         Instance of current track.
		 * @param      int/string     $post_id       ID of page.
		 */
		$new_data = apply_filters( 'mlp_save_metadata', $new_data, $track, $post_id );

		/**
		 * Fires before saving.
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $new_data      Saving data.
		 * @param      object         $track         Instance of current track.
		 * @param      int/string     $post_id       ID of page.
		 */
		$new_data = apply_filters( "mlp_save_{$this->object_type}_metadata", $new_data, $track, $post_id );

		foreach ( $new_data as $key => $new_value ) {

			$old_value = ( isset( $old_data[ $key ] ) ? $old_data[ $key ] : '' );

			update_metadata( $this->object_type, $post_id, $this->track_id, $new_value, $old_value );

		}

	}

	/**
	 * Delete data.
	 *
	 * @since      1.0.0
	 *
	 * @param      object         $track         Instance of current track.
	 * @param      int/string     $post_id       ID of page.
	 * @param      array          $new_data      Saving data.
	 * @param      array          $old_data      Old data from DB.
	 */
	public function delete_metadata( $track, $post_id, $new_data, $old_data ) {

		$old_data = array_slice( $old_data, count( $new_data ) );

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $old_data      Old data from DB.
		 * @param      object         $track         Instance of current track.
		 * @param      int/string     $post_id       ID of page.
		 */
		$old_data = apply_filters( 'mlp_delete_metadata', $old_data, $track, $post_id );

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $old_data      Old data from DB.
		 * @param      object         $track         Instance of current track.
		 * @param      int/string     $post_id       ID of page.
		 */
		$old_data = apply_filters( "mlp_delete_{$this->object_type}_metadata", $old_data, $track, $post_id );

		foreach ( wp_slash( $old_data ) as $old_value ) {

			delete_metadata( $this->object_type, $post_id, $this->track_id, $old_value );

		}

	}


}
