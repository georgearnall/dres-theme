<?php
/**
 * Example for adding a custom dob field to the
 * Restrict Content Pro registration form and profile editors.
 */

/**
 * Adds a custom dob field to the registration form and profile editor.
 */
function dres_add_dob_field()
{
    $alternate_dob = get_user_meta(get_current_user_id(), 'dres_dob', true); ?>
<p>
    <label for="dres_dob" class="required"><?php _e('Date of Birth', 'rcp'); ?></label>
    <input type="date" id="dres_dob" name="dres_dob"
        value="<?php echo esc_attr($alternate_dob); ?>" />
    <?php dres_display_user_profile_dob($alternate_dob); ?>
</p>

<?php
}

add_action('rcp_after_password_registration_field', 'dres_add_dob_field');
add_action('rcp_profile_editor_after', 'dres_add_dob_field');

/**
 * Adds the custom dob field to the member edit screen.
 */
function dres_add_dob_member_edit_field($user_id = 0)
{
    $alternate_dob = get_user_meta($user_id, 'dres_dob', true); ?>
<tr valign="top">
    <th scope="row" valign="top">
        <label for="dres_dob"><?php _e('Date of Birth', 'rcp'); ?></label>
    </th>
    <td>
        <input type="date" id="dres_dob" name="dres_dob"
            value="<?php echo esc_attr($alternate_dob); ?>" />
        <p><?php dres_display_user_profile_dob($alternate_dob); ?></p>
    </td>
</tr>
<?php
}

add_action('rcp_edit_member_after', 'dres_add_dob_member_edit_field');

/**
 * Determines if there are problems with the registration data submitted.
 */
function dres_validate_user_dob_fields(&$errors, $posted)
{
    // Remove this segment if you don't want the dob to be required.
    if (empty($posted['dres_dob'])) {
        if (function_exists('rcp_errors')) {
            rcp_errors()->add('missing_alt_dob', __('Please enter your date of birth', 'rcp'));
            rcp_errors()->add('missing_alt_dob_2', __('Please enter your date of birth', 'rcp'), 'register');
        }
        $errors->add('missing_alt_dob', '<strong>ERROR</strong>: Please enter your date of birth');
        return;
    }

    // This throws an error if an dob is entered but it's not a valid dob address.
    if (!empty($posted['dres_dob']) && !valid_date($posted['dres_dob'])) {
        if (function_exists('rcp_errors')) {
            rcp_errors()->add('invalid_alt_dob', __('Please enter a valid date of birth', 'rcp'));
            rcp_errors()->add('invalid_alt_dob_2', __('Please enter a valid date of birth', 'rcp'), 'register');
        }
        $errors->add('invalid_alt_dob', '<strong>ERROR</strong>: Please enter a valid date of birth');
    }
}

function valid_date($dateString)
{
    return (bool)strtotime($dateString);
}
function sanitize_date($dateString)
{
    $dateString = preg_replace('([^0-9/])', '', $dateString);
    $date = strtotime($dateString);
    if (!$date) {
        return;
    }
    return date('Y-m-d', $date);
}

add_action('rcp_form_errors', 'dres_validate_dob_on_register', 10);

/**
 * Determines if there are problems with the dob data submitted
 */
function dres_validate_dob_on_register($posted)
{
    if (is_user_logged_in()) {
        return;
    }
    dres_validate_user_dob_fields(new WP_Error(), $posted);
}

/**
 * Stores the information submitted during registration.
 */
function dres_save_dob_field_on_register($posted, $user_id)
{
    if (!empty($posted['dres_dob'])) {
        update_user_meta($user_id, 'dres_dob', sanitize_date($posted['dres_dob']));
    }
}

add_action('rcp_form_processing', 'dres_save_dob_field_on_register', 10, 2);

/**
 * Stores the information submitted during profile update.
 */
function dres_save_dob_field_on_profile_save($user_id)
{
    if (!empty($_POST['dres_dob']) && valid_date($_POST['dres_dob'])) {
        update_user_meta($user_id, 'dres_dob', sanitize_date($_POST['dres_dob']));
    }
}

add_action('rcp_user_profile_updated', 'dres_save_dob_field_on_profile_save', 10);
add_action('rcp_edit_member', 'dres_save_dob_field_on_profile_save', 10);

// WORDPRESS PROFILE EDITOR
function dres_add_custom_user_profile_dob($user)
{
    $alternate_dob = get_the_author_meta('dres_dob', $user->ID); ?>
<table class="form-table">
    <tr>
        <th>
            <label for="dres_gdpr" class="required"><?php _e('Date of Birth', 'dres'); ?>
            </label></th>
        <td>
            <input type="date" id="dres_dob" name="dres_dob"
                value="<?php echo esc_attr($alternate_dob); ?>" />
            <p><?php dres_display_user_profile_dob($alternate_dob); ?></p>
        </td>
    </tr>
</table>
<?php
}

function dres_display_user_profile_dob($dob_string) {
    if (!empty($dob_string)) {
        $dob = new DateTime($dob_string);
        $now = new DateTime();
        $diff = $now->diff($dob);
        echo '<em>Age: ' . $diff->y . ' years, ' . $diff->m . ' months</em>';
    }
}

add_action('show_user_profile', 'dres_add_custom_user_profile_dob');
add_action('edit_user_profile', 'dres_add_custom_user_profile_dob');
add_action('user_new_form', 'dres_add_custom_user_profile_dob');

add_action('personal_options_update', 'dres_save_dob_field_on_profile_save');
add_action('edit_user_profile_update', 'dres_save_dob_field_on_profile_save');
add_action('user_register', 'dres_save_dob_field_on_profile_save');

add_action('user_profile_update_errors', 'dres_profile_editor_dob_validation');

function dres_profile_editor_dob_validation(&$errors, $update = null, &$user = null)
{
    dres_validate_user_dob_fields($errors, $_POST);
}
