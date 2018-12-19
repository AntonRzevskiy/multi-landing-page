<?php

/**
 * The file that defines the trait for display content of metabox.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the track meta display functions.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
trait MLP_Display_Metabox {


	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function display_track_html( $track ) {

		global $post;

		$data = $this->get_metadata( $track, $post->ID );

		$valid = $this->is_valid_data( $track, $post->ID, $data, array() );

		$value = esc_html( wp_unslash( isset( $data[ 0 ] ) ? $data[ 0 ] : '' ) );

		?>

		<div class="mlp-track <?php echo ( $valid ? 'valid' : 'invalid' ); ?>">
			<label for="<?php echo $track->get( 'track_id' ); ?>"><?php _e( 'Fill track value' ); ?>: </label>
			<input type="text" id="<?php echo $track->get( 'track_id' ); ?>" name="<?php echo $track->get( 'track_id' ); ?>" value="<?php echo $value; ?>">
		</div>

		<?php

	}


}
