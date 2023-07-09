<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://https://test.eu
 * @since      1.0.0
 *
 * @package    Test_Assessment
 * @subpackage Test_Assessment/public/partials
 */
?>
<div class="test-assignment-container">
    <?php
    if ( isset( $_GET['message'] ) && !is_user_logged_in() ) {
        switch($_GET['message']){
            case '1':
                $message = __('Invalid username','test-assessment');
            break;
            case '2':
                $message = __('Invalid email address','test-assessment');
            break;
            case '3':
                $message = __('Username already exists','test-assessment');
            break;
            case '4':
                $message = __('Email address already exists','test-assessment');
            break;
            case '5':
                $message = __('Invalid Username or Password. Please Try Again.','test-assessment');
            break;
            case '5':
                $message = __('Successfully Logged In.','test-assessment');
            break;
            case '6':
                $message = __('Successfully Registered and Logged In.','test-assessment');
            break;
            default:
            $message = "";
            break;
        }
        echo '<p class="error-message" style="color:red">' . $message . '</p>';
    }
    ?>
    <?php
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        echo 'Welcome, ' . esc_html( $current_user->display_name ) . '!  <a href="'. wp_logout_url( get_permalink() ).'">'. __('Logout','test-assessment').'</a>';
    } else {
    ?>
    <div class="tabs">
        <div id="tab1" class="tab-content">
            <h4><?php  _e('Register Now','test-assessment'); ?> </h4>    
            <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
                <input type="hidden" name="action" value="custom_user_registration">
                <input type="hidden" name="current_page" value="<?php echo get_the_permalink();?>">
                <div class="form-group">
                    <label for="username"><?php _e('Username','test-assessment'); ?></label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="email"><?php _e('Email','test-assessment'); ?></label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password"><?php _e('Password','test-assessment'); ?></label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <input type="submit" class="button button-secondary" value="<?php _e('Register','test-assessment'); ?>" >
                </div>
            </form>
        </div>
        <div id="tab2" class="tab-content">
            <h4><?php  _e('Already Registered ? Login Now','test-assessment'); ?> </h4>
            <?php
            if ( isset( $error_message ) ) {
                echo '<div class="custom-login-error">' . esc_html( $error_message ) . '</div>';
            }
            ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="test_username">Username :</label>
                    <input type="text" id="test_log_username" name="test_log_username" required>
                <div class="form-group">
                    <label for="test_password">Password :</label>
                    <input type="password" id="test_log_password" name="test_log_password" required>
                </div>
                <input type="submit" name="test_log_submit" value="Login">
            </form>
        </div>
    </div>
    <?php }
    if ( is_user_logged_in() ) {
        custom_get_template_part('test-assessment','dashboard');
    }
    ?>
</div>