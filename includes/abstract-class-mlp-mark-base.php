<?php

/**
 * The file that defines the abstract class mark for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the mark base.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
abstract class MLP_Mark_Base {


	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @var        string         $mark_id       .
	 */
	protected $mark_id;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @var        string         $type          .
	 */
	protected $type;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @var        bool	          $full_match    .
	 */
	protected $full_match;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           .
	 */
	public function is_tax() {
		return (bool) $this->type === 'taxonomy';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           .
	 */
	public function is_meta() {
		return (bool) $this->type === 'meta';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           .
	 */
	public function is_strict() {
		return (bool) $this->full_match;
	}


}
