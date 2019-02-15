<?php

/**
 * The file that defines the interface for work with metadata.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes/core/interface
 */

/**
 * Define the interface metadata for meta tracks.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
interface MLP_Metadata {


	/**
	 * Vertify save track.
 	 *
	 * Confirm user permissions to save.
 	 *
 	 * The page ID can be passed as a number or string.
	 * Type control is not supported.
 	 *
	 * @since      1.0.0
	 *
	 * @param      int/string     $post_id       ID of page.
	 *
	 * @return     bool           True if current user have permissions. False if fail.
	 */
	public function verify_save( $post_id );

	/**
	 * Get data for save.
	 *
	 * Usually this function return user input.
	 *
	 * @since      1.0.0
	 *
	 * @return     mixed          Saving data.
	 */
	public function get_saving_data();

	/**
	 * Define process.
	 *
	 * This function should define the process of saving and/or deleting data relative to the current page.
	 *
	 * @since      1.0.0
	 *
	 * @param      MLP_Metadata   $track         Instance of current track.
	 * @param      int/string     $post_id       Optional. ID of page. Default NULL.
	 * @param      mixed          $new_data      Optional. Saving data. Default empty array.
	 */
	public function define_process_meta( self $track, $post_id = NULL, $new_data = '' );

	/**
	 * Prepare new data.
	 *
	 * This function should balance the data types. New & Old.
	 *
	 * @since      1.0.0
	 *
	 * @param      MLP_Metadata   $track         Instance of current track.
	 * @param      int/string     $post_id       ID of page.
	 * @param      mixed          $new_data      Saving data.
	 * @param      mixed          $old_data      Old data from DB.
	 *
	 * @return     mixed          New data, equal in type to old.
	 */
	public function prepare_data( self $track, $post_id, $new_data, $old_data );

	/**
	 * Is new data valid.
	 *
	 * @since      1.0.0
	 *
	 * @param      MLP_Metadata   $track         Instance of current track.
	 * @param      int/string     $post_id       ID of page.
	 * @param      mixed          $new_data      Saving data.
	 * @param      mixed          $old_data      Old data from DB.
	 *
	 * @return     bool           True if data valid. False fail.
	 */
	public function is_valid_data( self $track, $post_id, $new_data, $old_data );

	/**
	 * Get data from DataBase.
	 *
	 * @since      1.0.0
	 *
	 * @param      MLP_Metadata   $track         Instance of current track.
	 * @param      int/string     $post_id       Optional. ID of page. Default NULL.
	 *
	 * @return     mixed          Old data form DB.
	 */
	public function get_metadata( self $track, $post_id = NULL );

	/**
	 * Save or Update data into DB.
	 *
	 * @since      1.0.0
	 *
	 * @param      MLP_Metadata   $track         Instance of current track.
	 * @param      int/string     $post_id       ID of page.
	 * @param      mixed          $new_data      Saving data.
	 * @param      mixed          $old_data      Old data from DB.
	 */
	public function save_metadata( self $track, $post_id, $new_data, $old_data );

	/**
	 * Delete data.
	 *
	 * @since      1.0.0
	 *
	 * @param      MLP_Metadata   $track         Instance of current track.
	 * @param      int/string     $post_id       ID of page.
	 * @param      mixed          $new_data      Saving data.
	 * @param      mixed          $old_data      Old data from DB.
	 */
	public function delete_metadata( self $track, $post_id, $new_data, $old_data );

	/**
	 * Display HTML.
	 *
	 * Render HTML of current track.
	 * This function should display input field.
	 *
	 * @since      1.0.0
	 *
	 * @param      MLP_Metadata   $track         Instance of current track.
	 * @param      int/string     $post_id       ID of current page.
	 * @param      array          $data          Data from DB.
	 * @param      bool           $valid         True if data valid. False fail.
	 */
	public function display_track_html( self $track, $post_id, $data, $valid );


}
