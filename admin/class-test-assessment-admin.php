<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://test.eu
 * @since      1.0.0
 *
 * @package    Test_Assessment
 * @subpackage Test_Assessment/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Test_Assessment
 * @subpackage Test_Assessment/admin
 * @author     Vasanta Subedi <vasantasubedi301@gmail.com>
 */
class Test_Assessment_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Test_Assessment_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Test_Assessment_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/test-assessment-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Test_Assessment_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Test_Assessment_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/test-assessment-admin.js', array( 'jquery' ), $this->version, false );

	}

	function custom_post_type_registrations() {
		$labels = array(
			'name'               => 'Bio Datas',
			'singular_name'      => 'Bio Data',
			'menu_name'          => 'Bio Datas',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Bio Data',
			'edit_item'          => 'Edit Bio Data',
			'new_item'           => 'New Bio Data',
			'view_item'          => 'View Bio Data',
			'search_items'       => 'Search Bio Datas',
			'not_found'          => 'No Bio Datas found',
			'not_found_in_trash' => 'No Bio Datas found in trash',
		);
	
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'has_archive'        => true,
			'publicly_queryable' => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'bio-datas' ),
			'capability_type'    => 'post',
			'menu_icon'          => 'dashicons-admin-users',
			'supports'           => array( 'title', 'thumbnail' ),
			'map_meta_cap'       => true,
			'capabilities' => array(
				'create_posts' => 'do_not_allow',
			)
		);
	
		register_post_type( 'ta_bio_data', $args );
	}
// Register the meta box
function add_custom_meta_box() {
    add_meta_box(
        'Bio Data',
        'Bio Data',
        array($this,'render_custom_meta_box'),
        'ta_bio_data', // Replace with the slug of your custom post type
        'normal',
        'default'
    );
}


// Render the meta box content
function render_custom_meta_box($post) {
    $bio_data =  get_post_meta( $post->ID, 'bio_data', true );
    // Output the meta values
    if(!empty($bio_data)){
		foreach($bio_data as $keys=>$vals ){
			if(!empty($vals) && !is_array($vals)){
				echo '<h5>' . esc_html($vals) . '</h5>';
			}else{
				if(!empty($vals) && is_array($vals)){
					foreach ($vals as $key=>$val){
						if(!empty($val) && is_array($val)){
							foreach ($val as $k=>$v){
								echo '<h5> ' . esc_html($v) . '</h5>';
							}
						}else{
							echo '<h5> ' . esc_html($val) . '</h5>';
						}
					}
				}else if(!empty($vals)){
					echo '<h5> ' . esc_html($vals) . '</h5>';
				}
			}
		}
	} 
}
	function add_custom_menu() {
		add_submenu_page(
			'edit.php?post_type=ta_bio_data',
			'Setting',
			'Settings',
			'manage_options',
			'ta-bio-data-setting',
			//'ta_submenu_callback_function'
		);
	}

	function ta_submenu_callback_function(){
		?>
		<h5>For Login Registration Form /  User Dadhboard </h5>
		<h4><input type="text" value="[test_login_registration]" readonly/><h4>
		<h5>For Bio List</h5>
		<h4><input type="text" value="[test_login_bio_list]" readonly/><h4>
	<?php
	}

	function custom_taxonomy_registration() {
		$labels = array(
			'name'                       => 'Occupation Types',
			'singular_name'              => 'Occupation Type',
			'search_items'               => 'Search Occupation Types',
			'popular_items'              => 'Popular Occupation Types',
			'all_items'                  => 'All Occupation Types',
			'edit_item'                  => 'Edit Occupation Type',
			'update_item'                => 'Update Occupation Type',
			'add_new_item'               => 'Add New Occupation Type',
			'new_item_name'              => 'New Occupation Type Name',
			'separate_items_with_commas' => 'Separate occupation types with commas',
			'add_or_remove_items'        => 'Add or remove occupation types',
			'choose_from_most_used'      => 'Choose from the most used occupation types',
			'not_found'                  => 'No occupation types found',
			'menu_name'                  => 'Occupation Types',
		);
	
		$args = array(
			'hierarchical'      => true, // Set this to true if you want hierarchical categories like default post categories.
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'occupation-type' ), // Replace 'occupation-type' with your desired slug.
		);
	
		register_taxonomy( 'ta_occupation_type', 'ta_bio_data', $args );
	}

	function filter_custom_post_type_by_meta_value($query) {
		if ( is_admin() && current_user_can('subscriber') && in_array ( $query->get('post_type'), array('ta_bio_data') ) ) {
			// $query->set('meta_key', 'user_id');
			// $query->set('meta_value', 'your_meta_value');
		}
	}

	function restrict_custom_post_type_for_subscribers($query) {
		if (is_admin() || !$query->is_main_query()) {
			return;
		}
	
		// Check if the user is a subscriber
		//if (current_user_can('subscriber') && current_user_can('administrator')) {
			$allowed_post_types = array('ta_bio_data'); // Replace with your actual custom post type slug
			$query->set('post_type', $allowed_post_types);
		//}
	}

}
