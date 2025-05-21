<?php
/**
 * Adds the custom fields to the registration form and profile editor
 *
 */
function dres_rcp_add_user_email_fields()
{
    $work_email = get_user_meta(get_current_user_id(), 'dres_work_email', true); ?>
<p>
    <label for="dres_work_email"><?php _e('Work Email', 'dres'); ?></label>
    <input name="dres_work_email" id="dres_work_email" type="email"
        value="<?php echo esc_attr($work_email); ?>" />
</p>
<?php
}
add_action('rcp_after_password_registration_field', 'dres_rcp_add_user_email_fields');
add_action('rcp_profile_editor_after', 'dres_rcp_add_user_email_fields');

/**
 * Determines if there are problems with the work_email data submitted
 */
function dres_validate_user_email_fields_on_register($posted)
{
    if (is_user_logged_in()) {
        return;
    }
    dres_validate_user_email_fields(new WP_Error(), $posted);
}

function dres_validate_user_email_fields_on_update($posted, $user_id)
{
    dres_validate_user_email_fields(new WP_Error(), $posted);
}
add_action('rcp_form_errors', 'dres_validate_user_email_fields_on_register');
add_action('rcp_edit_profile_form_errors', 'dres_validate_user_email_fields_on_update', 10, 2);

// Validation Logic for all profile updates on front end and back end
function dres_validate_user_email_fields( &$errors, $posted ) {
    if ( ! empty( $posted['dres_work_email'] ) && ! is_email( $posted['dres_work_email'] ) ) {
        if ( function_exists( 'rcp_errors' ) ) {
            rcp_errors()->add( 'invalid_work_email', __( 'Please enter a valid Work Email' ) );
            rcp_errors()->add( 'invalid_work_email_2', __( 'Please enter a valid Work Email' ), 'register' );
        }
        $errors->add( 'invalid_work_email', '<strong>ERROR</strong>: Please enter a valid Work Email' );
    }
}

/**
 * Stores the information submitted during registration
 *
 */
function dres_rcp_save_user_email_fields($posted, $user_id)
{
    update_user_meta($user_id, 'dres_work_email', sanitize_text_field($posted['dres_work_email']));

}
function dres_rcp_save_user_email_fields_on_save($user_id)
{
    dres_rcp_save_user_email_fields($_POST, $user_id);
}

add_action('rcp_form_processing', 'dres_rcp_save_user_email_fields', 10, 2);
add_action('rcp_user_profile_updated', 'dres_rcp_save_user_email_fields_on_save', 10);
add_action('rcp_edit_member', 'dres_rcp_save_user_email_fields_on_save', 10);

/**
 * Adds the custom textarea field to the member edit screen.
 */
function dres_add_textarea_member_edit_work_email($user_id)
{
    if ($user_id instanceof WP_User) {
        $user_id = $user_id->ID;
    }

    $work_email = get_user_meta($user_id, 'dres_work_email', true); ?>
<tr valign="top">
    <th scope="row" valign="top">
    </th>
    <td>

        <label for="dres_work_email"><?php _e('Work Email', 'dres'); ?></label>
        <input name="dres_work_email" id="dres_work_email" type="email"
            value="<?php echo esc_attr($work_email); ?>" />
    </td>
</tr>
<?php
}

add_action('rcp_edit_member_after', 'dres_add_textarea_member_edit_work_email');

function dres_add_custom_user_profile_work_email($user_id = 0)
{
    if ($user_id instanceof WP_User) {
        $user_id = $user_id->ID;
    }

    $work_email = get_user_meta($user_id, 'dres_work_email', true); ?>

<table class="form-table">
    <tr>
        <th>
            <label for="dres_work_email"><?php _e('Work Email', 'dres'); ?>
            </label>
        </th>
        <td>
            <input name="dres_work_email" id="dres_work_email" type="email" class="regular-text"
                value="<?php echo esc_attr($work_email); ?>" />
        </td>
    </tr>
</table>
<?php
}

function dres_save_custom_user_profile_work_email($user_id)
{
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    //add all the update user meta here
    update_user_meta($user_id, 'dres_work_email', $_POST['dres_work_email']);
}

add_action('show_user_profile', 'dres_add_custom_user_profile_work_email');
add_action('edit_user_profile', 'dres_add_custom_user_profile_work_email');
add_action('user_new_form', 'dres_add_custom_user_profile_work_email');

add_action('personal_options_update', 'dres_save_custom_user_profile_work_email');
add_action('edit_user_profile_update', 'dres_save_custom_user_profile_work_email');
add_action('user_register', 'dres_save_custom_user_profile_work_email');

add_action('user_profile_update_errors', 'dres_profile_editor_work_email_validation');

function dres_profile_editor_work_email_validation(&$errors, $update = null, &$user = null)
{
    dres_validate_user_email_fields($errors, $_POST);
}
