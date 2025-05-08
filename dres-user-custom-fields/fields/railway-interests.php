<?php
/**
 * Example for adding a custom textarea field to the
 * Restrict Content Pro registration form and profile editors.
 */

/**
 * Adds a custom textarea field to the registration form and profile editor.
 */
function dres_add_textarea_field()
{
    $interests = get_user_meta(get_current_user_id(), 'dres_railway_interests', true); ?>
<p>
    <label for="dres_railway_interests"><?php _e('Railway Interests', 'dres'); ?></label>
    <textarea id="dres_railway_interests"
        name="dres_railway_interests"><?php echo esc_textarea($interests); ?></textarea>
</p>
<?php
}

add_action('rcp_after_password_registration_field', 'dres_add_textarea_field');
add_action('rcp_profile_editor_after', 'dres_add_textarea_field');

/**
 * Adds the custom textarea field to the member edit screen.
 */
function dres_add_textarea_member_edit_field($user_id = 0)
{
    $interests = get_user_meta($user_id, 'dres_railway_interests', true); ?>
<tr valign="top">
    <th scope="row" valign="top">
        <label for="dres_railway_interests"><?php _e('Railway Interests', 'dres'); ?></label>
    </th>
    <td>
        <textarea id="dres_railway_interests" name="dres_railway_interests" class="large-text" rows="10"
            cols="30"><?php echo esc_textarea($interests); ?></textarea>
    </td>
</tr>
<?php
}

add_action('rcp_edit_member_after', 'dres_add_textarea_member_edit_field');

/**
 * Stores the information submitted during registration.
 */
function dres_save_textarea_field_on_register($posted, $user_id)
{
    if (!empty($posted['dres_railway_interests'])) {
        update_user_meta($user_id, 'dres_railway_interests', wp_filter_nohtml_kses($posted['dres_railway_interests']));
    }
}

add_action('rcp_form_processing', 'dres_save_textarea_field_on_register', 10, 2);

/**
 * Stores the information submitted during profile update.
 */
function dres_save_textarea_field_on_profile_save($user_id)
{
    update_user_meta($user_id, 'dres_railway_interests', wp_filter_nohtml_kses($_POST['dres_railway_interests']));
}

add_action('rcp_user_profile_updated', 'dres_save_textarea_field_on_profile_save', 10);
add_action('rcp_edit_member', 'dres_save_textarea_field_on_profile_save', 10);

// WORDPRESS PROFILE EDITOR
function dres_add_custom_user_profile_fields($user)
{
    ?>

<table class="form-table">
    <tr>
        <th>
            <label for="address"><?php _e('Railway Interests', 'dres'); ?>
            </label></th>
        <td>
            <textarea id="dres_railway_interests" name="dres_railway_interests"
                class="regular-text"><?php echo esc_attr(get_the_author_meta('dres_railway_interests', $user->ID)); ?></textarea>
        </td>
    </tr>
</table>
<?php
}

function dres_save_custom_user_profile_fields($user_id)
{
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    update_user_meta($user_id, 'dres_railway_interests', wp_filter_nohtml_kses($_POST['dres_railway_interests']));
    //add all the update user meta here
}

add_action('show_user_profile', 'dres_add_custom_user_profile_fields');
add_action('edit_user_profile', 'dres_add_custom_user_profile_fields');
add_action('user_new_form', 'dres_add_custom_user_profile_fields');

add_action('personal_options_update', 'dres_save_custom_user_profile_fields');
add_action('edit_user_profile_update', 'dres_save_custom_user_profile_fields');
