<?php

/**
 * The file that defines the class for display content of metabox.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the track meta html types.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Display_Metabox {


	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct() {

		add_action( 'mlp_init_metabox_track_display', array( $this, 'define_metabox_style' ) );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function define_metabox_style( $track ) {

		if ( false === isset( $track->args[ 'metabox_html' ][ 'type' ] ) ) {

			return;
		}

		switch ( $track->args[ 'metabox_html' ][ 'type' ] ) {

			case 'text':

				$this->display_type_text( $track );

		}

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function display_type_text( $track ) {

		?>

		<div class="mlp-track">
			<label for="<?php echo $track->id; ?>"><?php _e( 'Fill track value' ); ?>: </label>
			<input type="text" id="<?php echo $track->id; ?>" name="<?php echo $track->id; ?>" value="<?php echo $this->get_meta_value( $track ); ?>">
		</div>

		<?php

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function get_meta_value( $track ) {

		global $post;

		$value = get_metadata( $track->object_type, $post->ID, $track->track_id, false );

		if ( $value ) {

			return $value[0];
		}

		return '';
	}


}
