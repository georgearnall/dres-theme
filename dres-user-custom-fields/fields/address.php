<?php
/**
 * Adds the custom fields to the registration form and profile editor
 *
 */
function dres_add_user_address_fields()
{
    $address_line1 = get_user_meta(get_current_user_id(), 'dres_address_line1', true);
    $address_line2 = get_user_meta(get_current_user_id(), 'dres_address_line2', true);
    $address_line3 = get_user_meta(get_current_user_id(), 'dres_address_line3', true);
    $address_line4 = get_user_meta(get_current_user_id(), 'dres_address_line4', true);
    $address_postcode = get_user_meta(get_current_user_id(), 'dres_address_postcode', true); ?>
<h3>Address</h3>
<p>
    <label for="dres_address_line1" class="required"><?php _e('Address Line 1', 'dres'); ?></label>
    <input name="dres_address_line1" id="dres_address_line1" type="text"
        value="<?php echo esc_attr($address_line1); ?>" />
</p>
<p>
    <label for="dres_address_line2"><?php _e('Address Line 2', 'dres'); ?></label>
    <input name="dres_address_line2" id="dres_address_line2" type="text"
        value="<?php echo esc_attr($address_line2); ?>" />
</p>
<p>
    <label for="dres_address_line3" class="required"><?php _e('City/Town', 'dres'); ?></label>
    <input name="dres_address_line3" id="dres_address_line3" type="text"
        value="<?php echo esc_attr($address_line3); ?>" />
</p>
<p>
    <label for="dres_address_line4"><?php _e('County', 'dres'); ?></label>
    <input name="dres_address_line4" id="dres_address_line4" type="text"
        value="<?php echo esc_attr($address_line4); ?>" />
</p>
<p>
    <label for="dres_address_postcode" class="required"><?php _e('Postcode', 'dres'); ?></label>
    <input name="dres_address_postcode" id="dres_address_postcode" type="text"
        value="<?php echo esc_attr($address_postcode); ?>" />
</p>
<?php
}
add_action('rcp_after_password_registration_field', 'dres_add_user_address_fields');
add_action('rcp_profile_editor_after', 'dres_add_user_address_fields');

/**
 * Determines if there are problems with the address data submitted
 */
function dres_validate_user_address_fields_on_register($posted)
{
    if (is_user_logged_in()) {
        return;
    }
    dres_validate_user_address_fields(new WP_Error(), $posted);
}

function dres_validate_user_address_fields_on_update($post, $user_id)
{
    dres_validate_user_address_fields(new WP_Error(), $post);
}

function dres_validate_user_address_fields(&$errors, $posted)
{
    if (empty($posted['dres_address_line1'])) {
        if (function_exists('rcp_errors')) {
            rcp_errors()->add('invalid_address_line1', __('Please enter an Address', 'dres'));
            rcp_errors()->add('invalid_address_line1_2', __('Please enter an Address', 'dres'), 'register');
        }
        $errors->add('invalid_address_line1', '<strong>ERROR</strong>: Please enter an Address');
    }
    if (empty($posted['dres_address_line3'])) {
        if (function_exists('rcp_errors')) {
            rcp_errors()->add('invalid_address_line3', __('Please enter a City/Town', 'dres'));
            rcp_errors()->add('invalid_address_line3_2', __('Please enter a City/Town', 'dres'), 'register');
        }
        $errors->add('invalid_address_line3', '<strong>ERROR</strong>: Please enter a City/Town');
    }
    if (empty($posted['dres_address_postcode'])) {
        if (function_exists('rcp_errors')) {
            rcp_errors()->add('invalid_postcode', __('Please enter a Postcode', 'dres'));
            rcp_errors()->add('invalid_postcode_2', __('Please enter a Postcode', 'dres'), 'register');
        }
        $errors->add('invalid_postcode', '<strong>ERROR</strong>: Please enter a Postcode');
    }
}

add_action('rcp_form_errors', 'dres_validate_user_address_fields_on_register', 10);
add_action('rcp_edit_profile_form_errors', 'dres_validate_user_address_fields_on_update', 10, 2);

/**
 * Stores the information submitted during registration
 *
 */
function dres_save_user_address_fields($posted, $user_id)
{
    if (!empty($posted['dres_address_line1'])) {
        update_user_meta($user_id, 'dres_address_line1', sanitize_text_field($posted['dres_address_line1']));
    }
    if (!empty($posted['dres_address_line2'])) {
        update_user_meta($user_id, 'dres_address_line2', sanitize_text_field($posted['dres_address_line2']));
    }
    if (!empty($posted['dres_address_line3'])) {
        update_user_meta($user_id, 'dres_address_line3', sanitize_text_field($posted['dres_address_line3']));
    }
    if (!empty($posted['dres_address_line4'])) {
        update_user_meta($user_id, 'dres_address_line4', sanitize_text_field($posted['dres_address_line4']));
    }
    if (!empty($posted['dres_address_postcode'])) {
        update_user_meta($user_id, 'dres_address_postcode', sanitize_text_field($posted['dres_address_postcode']));
    }
}
function dres_save_user_address_fields_on_save($user_id)
{
    dres_save_user_address_fields($_POST, $user_id);
}
add_action('rcp_form_processing', 'dres_save_user_address_fields', 10, 2);
add_action('rcp_user_profile_updated', 'dres_save_user_address_fields_on_save', 10);
add_action('rcp_edit_member', 'dres_save_user_address_fields_on_save', 10);

function dres_add_textarea_member_edit_address($user_id = 0)
{
    if ($user_id instanceof WP_User) {
        $user_id = $user_id->ID;
    }

    $address_line1 = get_user_meta($user_id, 'dres_address_line1', true);
    $address_line2 = get_user_meta($user_id, 'dres_address_line2', true);
    $address_line3 = get_user_meta($user_id, 'dres_address_line3', true);
    $address_line4 = get_user_meta($user_id, 'dres_address_line4', true);
    $address_postcode = get_user_meta($user_id, 'dres_address_postcode', true); ?>
<tr valign="top">
    <th scope="row" valign="top">
        <label for="dres_address_line1"><?php _e('Address', 'dres'); ?></label>
    </th>
    <td>
        <p>
            <label for="dres_address_line1" class="required"><?php _e('Address Line 1', 'dres'); ?></label>
            <input name="dres_address_line1" id="dres_address_line1" type="text"
                value="<?php echo esc_attr($address_line1); ?>" />
        </p>
        <p>
            <label for="dres_address_line2"><?php _e('Address Line 2', 'dres'); ?></label>
            <input name="dres_address_line2" id="dres_address_line2" type="text"
                value="<?php echo esc_attr($address_line2); ?>" />
        </p>
        <p>
            <label for="dres_address_line3" class="required"><?php _e('City/Town', 'dres'); ?></label>
            <input name="dres_address_line3" id="dres_address_line3" type="text"
                value="<?php echo esc_attr($address_line3); ?>" />
        </p>
        <p>
            <label for="dres_address_line4"><?php _e('County', 'dres'); ?></label>
            <input name="dres_address_line4" id="dres_address_line4" type="text"
                value="<?php echo esc_attr($address_line4); ?>" />
        </p>
        <p>
            <label for="dres_address_postcode" class="required"><?php _e('Postcode', 'dres'); ?></label>
            <input name="dres_address_postcode" id="dres_address_postcode" type="text"
                value="<?php echo esc_attr($address_postcode); ?>" />
        </p>
    </td>
</tr>
<?php
}

function dres_save_textarea_field_on_profile_save_address($user_id)
{
    update_user_meta($user_id, 'dres_address_line1', $_POST['dres_address_line1']);
    update_user_meta($user_id, 'dres_address_line2', $_POST['dres_address_line2']);
    update_user_meta($user_id, 'dres_address_line3', $_POST['dres_address_line3']);
    update_user_meta($user_id, 'dres_address_line4', $_POST['dres_address_line4']);
    update_user_meta($user_id, 'dres_address_postcode', $_POST['dres_address_postcode']);
}

add_action('rcp_user_profile_updated', 'dres_save_textarea_field_on_profile_save_address', 10);
add_action('rcp_edit_member', 'dres_save_textarea_field_on_profile_save_address', 10);

add_action('rcp_edit_member_after', 'dres_add_textarea_member_edit_address');

function dres_add_custom_user_profile_address($user)
{
    ?>

<table class="form-table">
    <?php dres_add_textarea_member_edit_address($user); ?>
</table>
<?php
}

function dres_save_custom_user_profile_address($user_id)
{
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    update_user_meta($user_id, 'dres_address_line1', $_POST['dres_address_line1']);
    update_user_meta($user_id, 'dres_address_line2', $_POST['dres_address_line2']);
    update_user_meta($user_id, 'dres_address_line3', $_POST['dres_address_line3']);
    update_user_meta($user_id, 'dres_address_line4', $_POST['dres_address_line4']);
    update_user_meta($user_id, 'dres_address_postcode', $_POST['dres_address_postcode']);
    //add all the update user meta here
}

add_action('show_user_profile', 'dres_add_custom_user_profile_address');
add_action('edit_user_profile', 'dres_add_custom_user_profile_address');
add_action('user_new_form', 'dres_add_custom_user_profile_address');

add_action('personal_options_update', 'dres_save_custom_user_profile_address');
add_action('edit_user_profile_update', 'dres_save_custom_user_profile_address');
add_action('user_register', 'dres_save_custom_user_profile_address');

add_action('user_profile_update_errors', 'dres_profile_editor_address_validation');

function dres_profile_editor_address_validation(&$errors, $update = null, &$user = null)
{
    dres_validate_user_address_fields($errors, $_POST);
}
