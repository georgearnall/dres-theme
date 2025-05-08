<?php
/**
 * Adds the custom fields to the registration form and profile editor
 *
 */
function dres_rcp_add_user_phone_fields()
{
    $phone_landline = get_user_meta(get_current_user_id(), 'dres_phone_landline', true);
    $phone_mobile = get_user_meta(get_current_user_id(), 'dres_phone_mobile', true); ?>
<style>
	input[type="tel"], input[type="date"] {
		color: #666;
		border: 1px solid #ccc;
	}
</style>
<p>
    <label for="dres_phone_mobile" class="required"><?php _e('Mobile Phone', 'dres'); ?></label>
    <input name="dres_phone_mobile" id="dres_phone_mobile" type="tel"
        value="<?php echo esc_attr($phone_mobile); ?>" />
</p>
<p>
    <label for="dres_phone_landline"><?php _e('Home Phone', 'dres'); ?></label>
    <input name="dres_phone_landline" id="dres_phone_landline" type="tel"
        value="<?php echo esc_attr($phone_landline); ?>" />
</p>
<?php
}
add_action('rcp_after_password_registration_field', 'dres_rcp_add_user_phone_fields');
add_action('rcp_profile_editor_after', 'dres_rcp_add_user_phone_fields');

/**
 * Determines if there are problems with the phone data submitted
 */
function dres_validate_user_phone_fields_on_register($posted)
{
    if (is_user_logged_in()) {
        return;
    }
    dres_validate_user_phone_fields(new WP_Error(), $posted);
}

function dres_validate_user_phone_fields_on_update($posted, $user_id)
{
    dres_validate_user_phone_fields(new WP_Error(), $posted);
}
add_action('rcp_form_errors', 'dres_validate_user_phone_fields_on_register');
add_action('rcp_edit_profile_form_errors', 'dres_validate_user_phone_fields_on_update', 10, 2);

// Validation Logic for all profile updates on front end and back end
function dres_validate_user_phone_fields(&$errors, $posted)
{
    if (empty($posted['dres_phone_mobile'])) {
        if (function_exists('rcp_errors')) {
            rcp_errors()->add('invalid_phone', __('Please enter a Mobile Phone number', 'dres'));
            rcp_errors()->add('invalid_phone_2', __('Please enter a Mobile Phone number', 'dres'), 'register');
        }
        $errors->add('invalid_phone', '<strong>ERROR</strong>: Please enter a Mobile Phone number');
    } elseif (!is_numeric(str_replace(' ', '', $posted['dres_phone_mobile']))) {
        if (function_exists('rcp_errors')) {
            rcp_errors()->add('invalid_phone', __('Please enter a valid Mobile Phone number'));
            rcp_errors()->add('invalid_phone_2', __('Please enter a valid Mobile Phone number'), 'register');
        }
        $errors->add('invalid_phone', '<strong>ERROR</strong>: Please enter a valid Mobile Phone number');
    }

    if (!empty($posted['dres_phone_landline']) && !is_numeric(str_replace(' ', '', $posted['dres_phone_landline']))) {
        if (function_exists('rcp_errors')) {
            rcp_errors()->add('invalid_landline', __('Please enter a valid Home Phone number'));
            rcp_errors()->add('invalid_landline_2', __('Please enter a valid Home Phone number'), 'register');
        }
        $errors->add('invalid_phone', '<strong>ERROR</strong>: Please enter a valid Home Phone number');
    }
}

/**
 * Stores the information submitted during registration
 *
 */
function dres_rcp_save_user_phone_fields($posted, $user_id)
{
    update_user_meta($user_id, 'dres_phone_landline', sanitize_text_field($posted['dres_phone_landline']));

    update_user_meta($user_id, 'dres_phone_mobile', sanitize_text_field($posted['dres_phone_mobile']));
}
function dres_rcp_save_user_phone_fields_on_save($user_id)
{
    dres_rcp_save_user_phone_fields($_POST, $user_id);
}

add_action('rcp_form_processing', 'dres_rcp_save_user_phone_fields', 10, 2);
add_action('rcp_user_profile_updated', 'dres_rcp_save_user_phone_fields_on_save', 10);
add_action('rcp_edit_member', 'dres_rcp_save_user_phone_fields_on_save', 10);

/**
 * Adds the custom textarea field to the member edit screen.
 */
function dres_add_textarea_member_edit_phone($user_id)
{
    if ($user_id instanceof WP_User) {
        $user_id = $user_id->ID;
    }

    $phone_landline = get_user_meta($user_id, 'dres_phone_landline', true);
    $phone_mobile = get_user_meta($user_id, 'dres_phone_mobile', true); ?>

<tr valign="top">
    <th scope="row" valign="top">
        <label for="dres_phone_mobile"><?php _e('Contact Information', 'dres'); ?></label>
    </th>
    <td>
        <label for="dres_phone_mobile"><?php _e('Mobile Phone', 'dres'); ?></label>
        <input name="dres_phone_mobile" id="dres_phone_mobile" type="tel"
            value="<?php echo esc_attr($phone_mobile); ?>" />

        <label for="dres_phone_landline"><?php _e('Home Phone', 'dres'); ?></label>
        <input name="dres_phone_landline" id="dres_phone_landline" type="tel"
            value="<?php echo esc_attr($phone_landline); ?>" />
    </td>
</tr>
<?php
}

add_action('rcp_edit_member_after', 'dres_add_textarea_member_edit_phone');

function dres_add_custom_user_profile_phone($user_id = 0)
{
    if ($user_id instanceof WP_User) {
        $user_id = $user_id->ID;
    }

    $phone_landline = get_user_meta($user_id, 'dres_phone_landline', true);
    $phone_mobile = get_user_meta($user_id, 'dres_phone_mobile', true); ?>

<style>
    /* This shouldn't really be here */
    .required::after {
        content: ' *';
        font-weight: bold;
        color: #cc3535;
    }

    [for=rcp_user_first]::after,
    [for=rcp_user_last]::after {
        content: ' *';
        font-weight: bold;
        color: #cc3535;
    }
</style>

<table class="form-table">
    <tr>
        <th>
            <label for="dres_phone_mobile" class="required"><?php _e('Mobile Phone', 'dres'); ?>
            </label>
        </th>
        <td>
            <input name="dres_phone_mobile" id="dres_phone_mobile" type="tel" class="regular-text"
                value="<?php echo esc_attr($phone_mobile); ?>" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="dres_phone_landline"><?php _e('Home Phone', 'dres'); ?>
            </label>
        </th>
        <td>
            <input name="dres_phone_landline" id="dres_phone_landline" type="tel" class="regular-text"
                value="<?php echo esc_attr($phone_landline); ?>" />
        </td>
    </tr>
</table>
<?php
}

function dres_save_custom_user_profile_phone($user_id)
{
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    //add all the update user meta here
    update_user_meta($user_id, 'dres_phone_landline', $_POST['dres_phone_landline']);
    update_user_meta($user_id, 'dres_phone_mobile', $_POST['dres_phone_mobile']);
}

add_action('show_user_profile', 'dres_add_custom_user_profile_phone');
add_action('edit_user_profile', 'dres_add_custom_user_profile_phone');
add_action('user_new_form', 'dres_add_custom_user_profile_phone');

add_action('personal_options_update', 'dres_save_custom_user_profile_phone');
add_action('edit_user_profile_update', 'dres_save_custom_user_profile_phone');
add_action('user_register', 'dres_save_custom_user_profile_phone');

add_action('user_profile_update_errors', 'dres_profile_editor_phone_validation');

function dres_profile_editor_phone_validation(&$errors, $update = null, &$user = null)
{
    dres_validate_user_phone_fields($errors, $_POST);
}
