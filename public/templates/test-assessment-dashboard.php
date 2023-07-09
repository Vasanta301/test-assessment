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
global $wpdb;

$current_user = wp_get_current_user();
$current_user_id = get_current_user_id();

$bioWpdbQuery = $wpdb->prepare(
    "SELECT pm.meta_value
    FROM $wpdb->postmeta AS pm
    INNER JOIN $wpdb->postmeta AS pm_user
        ON pm.post_id = pm_user.post_id
    WHERE pm.meta_key = 'bio_data'
        AND pm_user.meta_key = 'user_id'
        AND pm_user.meta_value = %d
    LIMIT 1",
    $current_user_id
);

$bio_data = maybe_unserialize($wpdb->get_var($bioWpdbQuery));
?>
<div class="container">
    <h4>Add/Edit Bio</h4>
    <?php if ( isset( $_GET['updated'] ) && $_GET['updated'] === 'true' ) : ?>
        <p class="success"><?php _e( 'Account information updated successfully!', '' ); ?></p>
    <?php endif; ?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <div class="bio-form-section-wrap">
            <label for="field_data">   
                <h3><?php _e( 'Personal Information', '' ); ?></h3>
            </label>
            <div class="bio-form-section-inn-wrap">
                <div class="form-group">
                    <label for="first_name">First Name : </label> 
                    <input type="text" id="display_name" name="bio_data[basic][first_name]" value="<?php echo 
                    isset($bio_data['basic']['first_name']) && !empty($bio_data['basic']['first_name'])?$bio_data['basic']['first_name']:esc_attr( $current_user->user_firstname ); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name : </label> 
                    <input type="text" id="last_name" name="bio_data[basic][last_name]" value="<?php echo 
                    isset($bio_data['basic']['last_name']) && !empty($bio_data['basic']['last_name'])?$bio_data['basic']['last_name']:esc_attr( $current_user->user_lastname ); ?>" required>
                </div>
                <div class="form-group">
                    <label for="user_email">Email : </label>
                    <input type="email" id="user_email" name="bio_data[basic][email]" value="<?php echo 
                    isset($bio_data['basic']['email']) && !empty($bio_data['basic']['email'])?$bio_data['basic']['email']:esc_attr( $current_user->user_email ); ?>" required>
                </div>
                <div class="form-group">
                    <label for="user_bio">Bio : </label>
                    <textarea id="user_bio" name="bio_data[basic][description]"><?php echo 
                    isset($bio_data['basic']['description']) && !empty($bio_data['basic']['description'])?$bio_data['basic']['description']:esc_attr( $current_user->description ); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="user_bio">Your Portfolio Link : </label>
                    <input type="url" id="url" name="bio_data[basic][url]" value="<?php echo 
                    isset($bio_data['basic']['url']) && !empty($bio_data['basic']['url'])?$bio_data['basic']['url']:esc_attr( $current_user->user_url ); ?>">
                </div>
                <?php do_action('bio_data_form_after_fields', $bio_data);?>
            </div>
        </div>
        <div class="bio-form-section-wrap">
            <label>   
                <h3><?php _e( 'Education', '' ); ?></h3>
            </label>
            <div class="bio-form-section-inn-wrap">
                <div class="form-group">
                    <h5>Secondary School</h5>
                    <label>
                        <span>Institute</span>
                        <input type="text" id="bio_data[education][seconday_school][institute]" name="bio_data[education][seconday_school][institute]" value="<?php echo isset($bio_data['education']['seconday_school']['institute'])?$bio_data['education']['seconday_school']['institute']:''; ?>">
                    </label>
                </div>
                <div class="form-group">
                    <h5>High School</h5>
                    <label>
                        <input type="checkbox" id="bio_data[education][high_school][not_applicable]" name="bio_data[education][high_school][not_applicable]" <?php echo isset($bio_data['education']['high_school']['not_applicable']) && $bio_data['education']['high_school']['not_applicable'] == "on"?'checked="checked"':''; ?> />
                        <span>Not Applicable</span>
                    </label>
                    <label>
                        <span>Institute</span>
                        <input type="text" id="bio_data[education][high_school][institute]" name="bio_data[education][high_school][institute]" value="<?php echo isset($bio_data['education']['high_school']['institute'])?$bio_data['education']['high_school']['institute']:''; ?>">
                    </label>
                    <label>
                        <span>Studied</span>
                        <input type="text" id="bio_data[education][high_school][studied]" name="bio_data[education][high_school][studied]" value="<?php echo isset($bio_data['education']['high_school']['studied'])?$bio_data['education']['high_school']['studied']:''; ?>">
                    </label>
                </div>
                <div class="form-group">
                    <h5>Bachelor's Degree</h5>
                    <label>
                        <input type="checkbox" id="bio_data[education][bachelors_degree][not_applicable]" name="bio_data[education][bachelors_degree][not_applicable]" <?php echo isset($bio_data['education']['bachelors_degree']['not_applicable']) && $bio_data['education']['bachelors_degree']['not_applicable'] == "on"?'checked="checked"':''; ?> />
                        <span>Not Applicable</span>
                    </label>
                    <label>
                        <span>Institute</span>
                        <input type="text" id="bio_data[education][bachelors_degree][institute]" name="bio_data[education][bachelors_degree][institute]" value="<?php echo isset($bio_data['education']['bachelors_degree']['institute'])?$bio_data['education']['bachelors_degree']['institute']:''; ?>">
                    </label>
                    <label>
                        <span>Studied</span>
                        <input type="text" id="bio_data[education][bachelors_degree][studied]" name="bio_data[education][bachelors_degree][studied]" value="<?php echo isset($bio_data['education']['bachelors_degree']['studied'])?$bio_data['education']['bachelors_degree']['studied']:''; ?>">
                    </label>
                </div>
                <div class="form-group">
                    <h5>Master's Degree</h5>
                    <label for="bio_data[education][masters_degree][not_applicable]">
                        <input type="checkbox" id="bio_data[education][masters_degree][not_applicable]" name="bio_data[education][masters_degree][not_applicable]" <?php echo isset($bio_data['education']['masters_degree']['not_applicable']) && $bio_data['education']['masters_degree']['not_applicable'] == "on"?'checked="checked"':''; ?> />
                        <span>Not Applicable</span>
                    </label>
                    <label>
                        <span>Institute</span>
                        <input type="text" id="bio_data[education][masters_degree][institute]" name="bio_data[education][masters_degree][institute]" value="<?php echo isset($bio_data['education']['masters_degree']['institute'])?$bio_data['education']['masters_degree']['institute']:''; ?>">
                    </label>
                    <label>
                        <span>Studied</span>
                        <input type="text" id="bio_data[education][masters_degree][studied]" name="bio_data[education][masters_degree][studied]" value="<?php echo isset($bio_data['education']['masters_degree']['studied'])?$bio_data['education']['masters_degree']['studied']:''; ?>">
                    </label>
                </div>
            </div>
        </div>
        <div class="bio-form-section-wrap" id="experience-fields-container">
                <label for="field_data">   
                    <h3><?php _e( 'Work Experience', '' ); ?></h3>
                </label>
                <div class="bio-form-section-inn-wrap" id="experience-fields-inner-container">
                    <label>Select Occupation Type</label>
                    <?php 
                     $terms = get_terms( array(
                        'taxonomy'   => 'ta_occupation_type',
                        'hide_empty' => false,
                    ) );
                
                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                       
                        $occ_typ_select_field = '<select name="bio_data[occupation_type]">';
                        $occ_typ_select_field .= '<option value="">Select Occupation Type</option>';
                       foreach ( $terms as $term ) {
                            $occ_typ_selected = isset($bio_data['occupation_type']) && $bio_data['occupation_type'] == $term->term_id?'selected="selected"':'';
                            $occ_typ_select_field .= '<option value="' . $term->term_id . '" '.$occ_typ_selected.'>' . $term->name . '</option>';
                        }
                
                        $occ_typ_select_field .= '</select>';
                
                        echo $occ_typ_select_field;
                    }
                    
                    if(!empty($bio_data['experience'])){
                    foreach( $bio_data['experience'] as $field_key => $user_experiences ) { ?>
                        <div class="experience-field experience-field-custom-un">
                        <?php 
                            /*** 
                            *   Call Value inside repeater        
                            <div class="form-group">
                                <label>Occupation Type</label>
                                                  
                                $terms = get_terms( array(
                                    'taxonomy'   => 'ta_occupation_type',
                                    'hide_empty' => false,
                                ) );
                            
                                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                                
                                    $select_field = '<select name="bio_data[experience]['.$field_key.'][occupation_type]">';
                                    $select_field .= '<option value="">Select Occupation Type</option>';
                                    
                                    foreach ( $terms as $term ) {
                                        $selected = isset($user_experiences[$field_key]['occupation_type']) 
                                        && !empty($user_experiences[$field_key]['occupation_type']) && $user_experiences[$field_key]['occupation_type'] == $term->term_id?'selected="selected"':'';
                                        $select_field .= '<option value="' . $term->term_id . '" '.$selected.'>' . $term->name . '</option>';
                                    }
                            
                                    $select_field .= '</select>';
                            
                                    echo $select_field;
                                } 
                            <div>
                            */
                            ?>
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" name="bio_data[experience][<?php echo $field_key;?>][company_name]" 
                                value="<?php echo isset($user_experiences['company_name']) 
                                && !empty($user_experiences['company_name'])?$user_experiences['company_name']:'';?>" />
                            <div>
                            <div class="form-group">
                                <label>Position</label>
                                <input type="text" name="bio_data[experience][<?php echo $field_key;?>][position]" 
                                value="<?php echo isset($user_experiences['position']) 
                                && !empty($user_experiences['position'])?$user_experiences['position']:'';?>" />
                            <div>
                            <div class="form-group">    
                                <label>Start Date</label>
                                <input type="text" class="custom_date" name="bio_data[experience][<?php echo $field_key;?>][start_date]"
                                value="<?php echo isset($user_experiences['start_date']) 
                                && !empty($user_experiences['start_date'])?$user_experiences['start_date']:'';?>" />
                            <div>
                            <div class="form-group">    
                                <label>End Date</label>
                                <input type="text" class="custom_date" name="bio_data[experience][<?php echo $field_key;?>][end_date]" 
                                value="<?php echo isset($user_experiences['end_date']) 
                                && !empty($user_experiences['end_date'])?$user_experiences['end_date']:'';?>" />
                            <div>
                            <button class="remove-experience-field">Remove</button>
                        </div>
                    <?php } } ?>
                </div>
        </div>
        <button type="button" id="add-experience-field" class="button button-primary"><?php _e( 'Add Experience +', '' ); ?></button>
        <div class="form-group">
          <input type="submit" name="update_account_submit" value="Update Account">
          <input type="hidden" name="action" value="update_account_info">
          <?php wp_nonce_field( 'update_account_info', 'update_account_nonce' ); ?>
        </div>
    </form>
</div>