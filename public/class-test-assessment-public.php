<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://test.eu
 * @since      1.0.0
 *
 * @package    Test_Assessment
 * @subpackage Test_Assessment/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Test_Assessment
 * @subpackage Test_Assessment/public
 * @author     Vasanta Subedi <vasantasubedi301@gmail.com>
 */
class Test_Assessment_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/test-assessment-public.css', array(), $this->version, 'all' );
		wp_register_style('jquery-ui', plugin_dir_url( __FILE__ ) .'css/jquery-ui.css', array(), $this->version, 'all' );
  		wp_enqueue_style( 'jquery-ui' );	
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_enqueue_script('underscore');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/test-assessment-public.js', array( 'jquery', 'wp-util' ), time(), false );
		wp_localize_script($this->plugin_name, "tc_ajax_obj", array("ajax_url" => admin_url("admin-ajax.php")));
	}

	/**
	 * Shortcode Function
	 *
	 * @param [type] $atts
	 * @return void
	 */
	public function display_form_using_shortcode( $atts ) {
		ob_start();
		custom_get_template_part('test-assessment','login-registration');
		$content = ob_get_clean();
		return $content;
	}
	
	/**
	 * Shortcode Function
	 *
	 * @param [type] $atts
	 * @return void
	 */
	public function display_bio_list( $atts ) {
		ob_start();
		custom_get_template_part('test-assessment','bio-list');
		$content = ob_get_clean();
		return $content;
	}

  	/** 
   	* Function to validate and register the user
   	*/
	function custom_user_registration() {
		
		global $errors;
		if ( isset( $_POST['username'] ) && isset( $_POST['email'] ) && isset( $_POST['password'] ) ) {
			$username = sanitize_user( $_POST['username'] );
			$email = sanitize_email( $_POST['email'] );
			$password = $_POST['password'];
			$redirect_url = $_POST['current_page'].'?';
			if ( ! validate_username( $username ) ) {
				wp_redirect( add_query_arg( 'message', '1', $redirect_url ) );
				exit;
			}
			if ( ! is_email( $email ) ) {
				wp_redirect( add_query_arg( 'message', '2', $redirect_url ) );
				exit;
			}
			if ( username_exists( $username ) ) {
				wp_redirect( add_query_arg( 'message', '3', $redirect_url ) );
				exit;
			}
			if ( email_exists( $email ) ) {
				wp_redirect( add_query_arg( 'message', '4', $redirect_url ) );
				exit;
			}
			
			if (!username_exists($username) && !email_exists($email)) {
				$userdata = array(
					'user_login' => $username,
					'user_email' => $email,
					'user_pass' => $password,
					'role' => 'subscriber',
				);
				$user_id = wp_insert_user( $userdata );
				if ( ! is_wp_error( $user_id ) ) {
					wp_set_auth_cookie( $user_id, true );
					wp_set_current_user( $user_id );
					wp_redirect( add_query_arg( 'message', '6', $redirect_url ) );
					exit;
				}
			}
		}
	}
  
  	/**
	 * Function to sync user details between two sites.
	 * @param [type] $user_id
	 * @return void
	*/
	/***
	public function trigger_after_user_is_registered( $user_id, $user_data ){
		$site_url = get_site_url();
		$request = wp_remote_post(
			`{$site_url}/wp-json/wp/v2/user`,
			array(
				'headers' => array(
					'Accept' => 'application/json'
				),
				'body' => array(
					'username'    => $user_data[ 'user_login' ],
					'first_name'  => $user_data[ 'user_login' ]??'',
					'last_name'   => $user_data[ 'last_name' ]??'',
					'email'       => $user_data[ 'user_email' ],
					'roles'       => $user_data[ 'role' ],
					'password'    => $user_data[ 'user_pass' ],
				)
			)
		);
		if( 'OK' === wp_remote_retrieve_response_message( $request ) ) {
			$body = json_decode( wp_remote_retrieve_body( $request ) );
			//update_user_meta( $user_id, 'user_id_2', $body->id );
		}
  	} 
	**/

	/**
	 * Function
	 * Try Logging in 
	 * @return void
	 */
	function test_login_process() {
		global $users; 

		if ( isset( $_POST['test_log_submit'] ) ) {
			$username = isset( $_POST['test_log_username'] ) ? sanitize_text_field( $_POST['test_log_username'] ) : '';
			$password = isset( $_POST['test_log_password'] ) ? $_POST['test_log_password'] : '';
			$user = wp_authenticate( $username, $password );
			$redirect_url = sanitize_url($_SERVER['HTTP_REFERER']);
			if ( is_wp_error( $user ) ) {
				if(!empty($redirect_url)){
					wp_redirect( add_query_arg( 'message', '5', $redirect_url ) );
				} else {
					wp_redirect(home_url());
				}
				exit;
			} else {
				wp_set_auth_cookie( $user->ID, true );
				wp_set_current_user( $user->ID );

				$redirect_url = sanitize_url($_SERVER['HTTP_REFERER']);

				if(!empty($redirect_url)){
					wp_redirect( add_query_arg( 'message', 'success', $redirect_url ) );
				} else {
					wp_redirect(home_url());
				}
				exit;
			}
		}
	}

	/**
	 * Function
	 * Auto Redirect After Logout
	 * @return void
	 */
	public function test_logout_redirect() {
		$redirect_url = sanitize_url($_SERVER['HTTP_REFERER']);
		// Unset authentication cookies to log out the user
		setcookie('wordpress_logged_in_' . COOKIEHASH, '', time() - 3600, '/', COOKIE_DOMAIN);
		setcookie('wordpress_sec_' . COOKIEHASH, '', time() - 3600, '/', COOKIE_DOMAIN);
		setcookie('wordpress_test_cookie', '', time() - 3600, '/', COOKIE_DOMAIN);
	
		// (Optional) Destroy the current session (if used)
		session_destroy();
	
		// (Optional) Clear the current user data
		wp_set_current_user(0);
		if(!empty($redirect_url)){
			wp_safe_redirect($redirect_url).'&action=loggedout';
		} else {
			wp_redirect(home_url()).'&message=2';
		}
		exit;
	}

	/**
	 *  WP Footer For Experience Repeater Template
	 *
	 * @return void
	 */
	function custom_wp_footer_repeater_template(){
		?>
		<script type="text/template" id="tmpl-experience-field-template">
			<div class="experience-field">
				<div class="form-group form-group-{{{data.counter}}} occupation-type">
				</div>
				<div class="form-group company-name">
					<label>Company</label>
					<input type="text" name="bio_data[experience][{{{data.counter}}}][company_name]"/>
				<div>
				<div class="form-group position">
					<label>Position</label>
					<input type="text" name="bio_data[experience][{{{data.counter}}}][position]"/>
				<div>
				<div class="form-group start_date">
					<label>Start Date</label>
					<input type="text" class="custom_date" name="bio_data[experience][{{{data.counter}}}][start_date]"/>
				<div>
				<div class="form-group end_date">    
					<label>End Date</label>
					<input type="text" class="custom_date" name="bio_data[experience][{{{data.counter}}}][end_date]"/>
				<div>
				<button class="remove-experience-field">Remove</button>
			</div>
		</script>
		<?php
	}

	/**
	 * WP Footer loader for select template
	 *
	 * @return void
	 */
	function custom_wp_footer_repeater_select_template(){
		$terms = get_terms(array(
			"taxonomy" => "ta_occupation_type",
			"hide_empty" => false,
		));
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {        
			$select_field = '<select name="bio_data[experience][occupation_type][{{{data.counter}}}]">';
			$select_field .= '<option value="">Select Occupation Type</option>';
			foreach ( $terms as $term ) {
				$select_field .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
			}
			$select_field .= '</select>';
		}
		?>
		<script type="text/html" id="tmpl-occupation-type-select-field">
			<label>Occupation Type</label>
			<?php echo $select_field; ?>	
		</script>
	<?php
	}

	/**
	 * Directly Call the Value for Select
	 */

	function custom_wp_footer_select_template(){
		$terms = get_terms(array(
			"taxonomy" => "ta_occupation_type",
			"hide_empty" => false,
		));
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {        
			$select_field = '<select name="bio_data[experience][occupation_type]">';
			$select_field .= '<option value="">Select Occupation Type</option>';
			foreach ( $terms as $term ) {
				$select_field .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
			}
			$select_field .= '</select>';
		}
	}

	/**
	 * function : Update Account Info
	 *
	 * @return void
	 */
	function custom_update_account_info() {
		if ( isset( $_POST['update_account_submit'] ) && wp_verify_nonce( $_POST['update_account_nonce'], 'update_account_info' ) ) {
			foreach ($_POST as $key => $val) {
				if ($key == 'bio_data') {
					$$key = $val;
				} else {
					$$key = sanitize_text_field($val);
				}
			}
			$bio_data_temp = array();
			foreach ($bio_data as $key => $val) {
				$bio_data_temp[$key] = array();
				foreach ($val as $k => $v) {
					if (!is_array($v)) {
						$bio_data_temp[$key][$k] = sanitize_text_field($v);
					} else {
						$bio_data_temp[$key][$k] = array_map('sanitize_text_field', $v);
					}
				}
			}
			$bio_data = $bio_data_temp;

			
			$user_id = get_current_user_id();
			global $wpdb;
			$query = $wpdb->prepare(
				"SELECT p.ID
				FROM $wpdb->posts AS p
				INNER JOIN $wpdb->postmeta AS pm
					ON p.ID = pm.post_id
				WHERE p.post_type = 'ta_bio_data'
					AND pm.meta_key = 'user_id'
					AND pm.meta_value = %d
				LIMIT 1",
				$user_id
			);
			
			$post_id = $wpdb->get_var($query);
			$prod_cats = !empty($_POST['bio_data']['occupation_type'])?$_POST['bio_data']['occupation_type']:'';
			if (!empty($post_id)) {
				//Update
				$post = array( 
					'ID' => $post_id,
					'post_title'   => (!empty($_POST['bio_data']['basic']['first_name'])?$_POST['bio_data']['basic']['first_name']:'').' '.(!empty($_POST['bio_data']['basic']['last_name'])?$_POST['bio_data']['basic']['last_name']:''),
					'post_status' => 'draft' 
				);
				wp_update_post($post);
				update_post_meta($post_id, 'bio_data', $bio_data);
				update_post_meta($post_id, 'user_id', $user_id);
				wp_set_post_terms( $post_id, $prod_cats, 'ta_occupation_type' );
			}else{
				
				//Insert
				$post_data = array(
					'post_title'   => $_POST['bio_data']['basic']['first_name'].' '.$_POST['bio_data']['basic']['last_name'],
					'post_status'  => 'draft',
					'post_author'  => $user_id,
					'post_type'    => 'ta_bio_data',
				);
				
				$post_id = wp_insert_post($post_data);
				
				if ($post_id && !is_wp_error($post_id)) {
					update_post_meta($post_id, 'bio_data', $bio_data);
					update_post_meta($post_id, 'user_id', $user_id);
					wp_set_post_terms( $post_id, $prod_cats, 'ta_occupation_type' );
				}
			}	
			$redirect_url = sanitize_url($_SERVER['HTTP_REFERER']);
			wp_redirect( add_query_arg( 'updated', 'true', $redirect_url ) );
			exit;
		}
	}

	function export_custom_posts_to_csv() {
		$args = array(
			'post_type' => 'ta_bio_data',
			'posts_per_page' => -1,
		);

		if (isset($_GET['sort'])) {
			$sort = $_GET['sort'];
			switch ($sort) {
				case 'date':
					$args['orderby'] = 'post_date';
				break;
				case 'date':
					$args['orderby'] = 'title';
				break;
			}
		}
		
		if (isset($_GET['sortby'])) {
			$sortby = $_GET['sortby'];
			switch ($sortby) {
				case 'asc':
					$args['order'] = 'ASC';
				break;
				case 'desc':
					$args['order'] = 'DESC';
				break;
			}
		}
		
		if (isset($_GET['filter-category']) && !empty($_GET['filter-category'])) {
			$category_filter = sanitize_text_field($_GET['filter-category']);
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'ta_occupation_type',
					'field' => 'term_id',
					'terms' => $category_filter,
				),
			);
		}

		$custom_posts = new WP_Query($args);
		$csv_values = [];
		$csv_keys =[];
		if ($custom_posts->have_posts()) {
			while ($custom_posts->have_posts()) {
				$custom_posts->the_post();
				$bio_data = get_post_meta(get_the_ID(), 'bio_data', true);
				if(!empty($bio_data)){
					foreach($bio_data as $keys=>$vals ){
						if(!empty($vals) && !is_array($vals)){
							$csv_keys[] = $keys;
							$csv_values[] .= $vals;
						}else{
							if(!empty($vals) && is_array($vals)){
								foreach ($vals as $key=>$val){
									if(!empty($val) && is_array($val)){
										foreach ($val as $k=>$v){
											$csv_keys[] = $k;
											$csv_values[] .= $v;
										}
									}else{
										$csv_keys[] = $key;
										$csv_values[] .= $val;
									}
								}
							}else if(!empty($vals)){
								$csv_keys[] = $keys;
								$csv_values[] .= $vals;
							}
						}
					}
				}
			}
		}
		wp_reset_postdata();
		// Combine keys and values arrays into single rows
        $csv_keys_row = implode(',', $csv_keys);
        $csv_values_row = implode(',', $csv_values);

        // Create final CSV data by combining keys and values rows
        $csv_data = $csv_keys_row . "\n" . $csv_values_row;
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="bio-data-'.time().'.csv"');
		echo $csv_data;
		exit;
	}
}
