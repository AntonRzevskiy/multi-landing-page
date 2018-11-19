<?php

/**
 * The file that defines the core plugin class.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * The core plugin class.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class Multi_Landing_Page {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 *
	 * @var        MLP_Loader     $loader        Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 *
	 * @var        string         $mlp           The string used to uniquely identify this plugin.
	 */
	protected $mlp;

	/**
	 * The current version of the plugin.
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 *
	 * @var        string         $version       The current version of the plugin.
	 */
	protected $version;

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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies and set the hooks.
	 *
	 * @since      1.0.0
	 */
	public function __construct() {

		if ( defined( 'MLP_VERSION' ) ) {

			$this->version = MLP_VERSION;

		} else {

			$this->version = '1.0.0';

		}

		$this->mlp = 'mlp';

		$this->load_dependencies();

		$this->define_hooks();

		$this->define_public();

		$this->mpl_fully_loaded();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MLP_Loader. Orchestrates the hooks of the plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since      1.0.0
	 *
	 * @access     private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mlp-loader.php';

		/**
		 * The base class for register all tracks.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstract-class-mlp-related-base.php';

		/**
		 * The child class that registers tracks through WP hooks.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mlp-registry.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstract-class-mlp-track-base.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mlp-track-tax.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mlp-track-meta.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mlp-display-metabox.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mlp-save-metabox.php';


		$this->loader = new MLP_Loader();

	}

	/**
	 * Register all of the hooks.
	 *
	 * @since      1.0.0
	 *
	 * @access     private
	 */
	private function define_hooks() {

		$this->registry = new MLP_Registry();

		$this->loader->add_action( 'init', $this->registry, 'add_url_tracks' );
		$this->loader->add_action( 'init', $this->registry, 'add_post_type' );

		$this->loader->add_action( 'init', $this->registry, 'register_post_type' );
		$this->loader->add_action( 'init', $this->registry, 'register_taxonomy' );
		$this->loader->add_action( 'init', $this->registry, 'register_metaboxes' );

		foreach ( $this->registry->get_meta_tracks() as $track ) {

			foreach ( $track->get( 'post_type' ) as $post_type ) {

				$this->loader->add_action( "save_post_{$post_type}", $track, 'save_post_data_init' );

			}

		}

	}

	/**
	 * Load public functionality.
	 *
	 * @since      1.0.0
	 *
	 * @access     private
	 */
	private function define_public() {

		

	}

	/**
	 * Set the final hook of the plugin.
	 *
	 * @since      1.0.0
	 *
	 * @access     private
	 */
	private function mpl_fully_loaded() {
		$this->loader->add_action( 'init', $this, 'mlp_loaded' );
	}

	/**
	 * Fire the hook after the plugin is ready.
	 *
	 * @since      1.0.0
	 */
	public function mlp_loaded() {
        do_action( 'mlp_init' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since      1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since      1.0.0
	 *
	 * @return     string         The name of the plugin.
	 */
	public function get_mlp() {
		return $this->mlp;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since      1.0.0
	 *
	 * @return     MLP_Loader     Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since      1.0.0
	 *
	 * @return     string         The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


}
