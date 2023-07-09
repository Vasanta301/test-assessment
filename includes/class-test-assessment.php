<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://test.eu
 * @since      1.0.0
 *
 * @package    Test_Assessment
 * @subpackage Test_Assessment/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Test_Assessment
 * @subpackage Test_Assessment/includes
 * @author     Vasanta Subedi <vasantasubedi301@gmail.com>
 */
class Test_Assessment {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Test_Assessment_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TEST_ASSESSMENT_VERSION' ) ) {
			$this->version = TEST_ASSESSMENT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'test-assessment';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Test_Assessment_Loader. Orchestrates the hooks of the plugin.
	 * - Test_Assessment_i18n. Defines internationalization functionality.
	 * - Test_Assessment_Admin. Defines all hooks for the admin area.
	 * - Test_Assessment_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-test-assessment-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-test-assessment-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-test-assessment-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-test-assessment-public.php';

		/**
		 * All Publicly Accessible Functions
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/libraries-public-functions.php';
		$this->loader = new Test_Assessment_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Test_Assessment_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Test_Assessment_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Test_Assessment_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_admin, 'custom_post_type_registrations');
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_custom_menu');
		$this->loader->add_action( 'init', $plugin_admin, 'custom_taxonomy_registration');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Test_Assessment_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_shortcode( 'test_login_registration', $plugin_public, 'display_form_using_shortcode' );
		$this->loader->add_shortcode( 'test_login_bio_list', $plugin_public, 'display_bio_list' );

		$this->loader->add_action( 'admin_post_nopriv_custom_user_registration', $plugin_public, 'custom_user_registration' );
		//$this->loader->add_action( 'user_register', $plugin_public, 'trigger_after_user_is_registered',10,2 );
		
		$this->loader->add_action( 'init', $plugin_public, 'test_login_process',10,2 );

		$this->loader->add_action( 'wp_logout', $plugin_public, 'test_logout_redirect',10,2 );
		$this->loader->add_action( 'admin_post_update_account_info', $plugin_public, 'custom_update_account_info',10,2 );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'custom_wp_footer_repeater_template');
		$this->loader->add_action( 'wp_footer', $plugin_public, 'custom_wp_footer_repeater_select_template');
		
		$this->loader->add_action( 'wp_ajax_get_occupation_type_terms', $plugin_public, 'get_occupation_type_terms');
		$this->loader->add_action( 'wp_ajax_nopriv_get_occupation_type_terms', $plugin_public, 'get_occupation_type_terms');
		$this->loader->add_action('admin_post_export_custom_posts_csv', $plugin_public, 'export_custom_posts_to_csv');
		$this->loader->add_action('admin_post_nopriv_export_custom_posts_csv',  $plugin_public, 'export_custom_posts_to_csv');
		
	}
	
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Test_Assessment_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
}
