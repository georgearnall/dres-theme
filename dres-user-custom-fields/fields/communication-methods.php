<?php
/**
 * Example for adding a custom multicheck field to the
 * Restrict Content Pro registration form and profile editors.
 */

/**
 * Adds a multicheck field to the registration form an dprofile editor.
 */
function rcp_add_multicheck_field()
{
    $communication_method = get_user_meta(get_current_user_id(), 'dres_communication_method', true);

    if (!is_array($communication_method)) {
        $communication_method = [];
    } ?>
<p class="required">
    <?php _e('Preferred Communication Methods', 'dres'); ?>
</p>
<style type="text/css">
	.communication-methods-checkboxes {
		  display: grid;
		  grid-template-columns: min-content auto;
		  gap: 7px;
	}
	.communication-methods-checkboxes input {
		  margin: 0;
	}
</style>
<p class="communication-methods-checkboxes">
    <input name="dres_communication_method[]" id="dres_communication_method_email" type="checkbox" value="email" <?php checked(in_array('email', $communication_method)); ?>/>
    <label for="dres_communication_method_email"><?php _e('Email', 'dres'); ?></label>
    <br>
    <input name="dres_communication_method[]" id="dres_communication_method_post" type="checkbox" value="post" <?php checked(in_array('post', $communication_method)); ?>/>
    <label for="dres_communication_method_post"><?php _e('Post', 'dres'); ?></label>
    <br>
    <input name="dres_communication_method[]" id="dres_communication_method_phone" type="checkbox" value="phone" <?php checked(in_array('phone', $communication_method)); ?>/>
    <label for="dres_communication_method_phone"><?php _e('Phone', 'dres'); ?></label>
</p>
<?php
}

add_action('rcp_after_password_registration_field', 'rcp_add_multicheck_field');
add_action('rcp_profile_editor_after', 'rcp_add_multicheck_field');

/**
 * Adds the custom multicheck field to the member edit screen.
 */
function ag_rcp_add_multicheck_member_edit_fields($user_id = 0)
{
    if ($user_id instanceof WP_User) {
        $user_id = $user_id->ID;
    }
    $communication_method = get_user_meta($user_id, 'dres_communication_method', true);

    if (empty($communication_method)) {
        $communication_method = [];
    } ?>
<tr valign="top">
    <th scope="row" valign="top">
        <label for="dres_communication_method" class="required"><?php _e('Communication Methods', 'dres'); ?></label>
    </th>
    <td>
        <input name="dres_communication_method[]" id="dres_communication_method_email" type="checkbox" value="email"
            <?php checked(in_array('email', $communication_method)); ?>/>
        <span class="description"><?php _e('Email', 'dres'); ?></span>
        <br>
        <input name="dres_communication_method[]" id="dres_communication_method_post" type="checkbox" value="post"
            <?php checked(in_array('post', $communication_method)); ?>/>
        <span class="description"><?php _e('Post', 'dres'); ?></span>
        <br>
        <input name="dres_communication_method[]" id="dres_communication_method_phone" type="checkbox" value="phone"
            <?php checked(in_array('phone', $communication_method)); ?>/>
        <span class="description"><?php _e('Phone', 'dres'); ?></span>
    </td>
</tr>
<?php
}
add_action('rcp_edit_member_after', 'ag_rcp_add_multicheck_member_edit_fields');

/**
 * Shows an error message if none of the multicheck options are selected.
 * Remove this code if you want the multicheck to be optional.
 */
function dres_validate_address_fields(&$errors, $posted)
{
    if (empty($posted['dres_communication_method']) || !is_array($posted['dres_communication_method'])) {
        if (function_exists('rcp_errors')) {
            rcp_errors()->add('invalid_communication_method', __('Please select at least one communication method', 'dres'));
            rcp_errors()->add('invalid_communication_method_2', __('Please select at least one communication method', 'dres'), 'register');
        }
        $errors->add('invalid_communication_method', '<strong>ERROR</strong>: Please select at least one communication method');
    }
}

function dres_validate_address_fields_on_register($posted)
{
    if (is_user_logged_in()) {
        return;
    }

    dres_validate_address_fields(new WP_Error(), $posted);
}

function dres_validate_address_fields_on_update($posted, $user_id)
{
    dres_validate_address_fields(new WP_Error(), $posted);
}

add_action('rcp_form_errors', 'dres_validate_address_fields_on_register', 10);
add_action('rcp_edit_profile_form_errors', 'dres_validate_address_fields_on_update', 10, 2);

/**
 * Stores the information submitted during registration
 *
 * Stores all the selected communication_method in an array.
 */
function ag_rcp_save_multicheck_field_on_register($posted, $user_id)
{
    if (!empty($posted['dres_communication_method']) && is_array($posted['dres_communication_method'])) {
        // First sanitize the array.
        $communication_method = array_map('sanitize_text_field', $posted['dres_communication_method']);

        // Now save.
        update_user_meta($user_id, 'dres_communication_method', $communication_method);
    }
}
add_action('rcp_form_processing', 'ag_rcp_save_multicheck_field_on_register', 10, 2);

/**
 * Stores the information submitted profile update
 *
 * Stores all the selected communication_method in an array.
 */
function ag_rcp_save_multicheck_field_on_profile_save($user_id)
{
    if (!empty($_POST['dres_communication_method']) && is_array($_POST['dres_communication_method'])) {
        // First sanitize the array.
        $communication_method = array_map('sanitize_text_field', $_POST['dres_communication_method']);

        // Now save.
        update_user_meta($user_id, 'dres_communication_method', $communication_method);
    } else {
        // Delete the user meta if no checkboxes are selected.
        delete_user_meta($user_id, 'dres_communication_method');
    }
}
add_action('rcp_user_profile_updated', 'ag_rcp_save_multicheck_field_on_profile_save', 10);
add_action('rcp_edit_member', 'ag_rcp_save_multicheck_field_on_profile_save', 10);

function dres_add_custom_user_profile_communication_method($user)
{
    ?>
<h3><?php _e('Extra Profile Information', 'dres'); ?>
</h3>

<table class="form-table">
    <?php ag_rcp_add_multicheck_member_edit_fields($user); ?>
</table>
<?php
}

add_action('show_user_profile', 'dres_add_custom_user_profile_communication_method');
add_action('edit_user_profile', 'dres_add_custom_user_profile_communication_method');
add_action('user_new_form', 'dres_add_custom_user_profile_communication_method');

add_action('personal_options_update', 'ag_rcp_save_multicheck_field_on_profile_save');
add_action('edit_user_profile_update', 'ag_rcp_save_multicheck_field_on_profile_save');
add_action('user_register', 'ag_rcp_save_multicheck_field_on_profile_save');

// profile editor validation
add_action('user_profile_update_errors', 'dres_profile_editor_communication_preferences_validation');

function dres_profile_editor_communication_preferences_validation(&$errors, $update = null, &$user = null)
{
    dres_validate_address_fields($errors, $_POST);
}
