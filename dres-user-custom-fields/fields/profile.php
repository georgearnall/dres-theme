<?php
// Remove unncessary fields from profile editor

function new_contactmethods($contactmethods)
{
    unset($contactmethods['yim'], $contactmethods['aim'], $contactmethods['jabber'], $contactmethods['website']);

    return $contactmethods;
}

add_filter('user_contactmethods', 'new_contactmethods', 10, 1);

if (is_admin()) {
    remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
}
