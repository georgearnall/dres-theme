<?php
/**
 * Require first and last names during registration
 *
 * @param array $posted Array of information sent to the form.
 *
 * @return void
 */
function ag_rcp_require_first_and_last_names($posted)
{
    if (is_user_logged_in()) {
        return;
    }

    if (empty($posted['rcp_user_first'])) {
        rcp_errors()->add('first_name_required', __('Please enter your first name', 'rcp'), 'register');
    }

    if (empty($posted['rcp_user_last'])) {
        rcp_errors()->add('last_name_required', __('Please enter your last name', 'rcp'), 'register');
    }
}

add_action('rcp_form_errors', 'ag_rcp_require_first_and_last_names');

/**
 * Require first and last names during profile edit
 *
 * @param array $posted  Array of information sent to the form.
 * @param int   $user_id ID of the user editing their profile.
 *
 * @return void
 */
function ag_rcp_require_first_and_last_name_profile_edit($posted, $user_id)
{
    if (empty($posted['rcp_first_name'])) {
        rcp_errors()->add('first_name_required', __('Please enter your first name', 'rcp'));
    }

    if (empty($posted['rcp_last_name'])) {
        rcp_errors()->add('last_name_required', __('Please enter your last name', 'rcp'));
    }
}

add_action('rcp_edit_profile_form_errors', 'ag_rcp_require_first_and_last_name_profile_edit', 10, 2);
