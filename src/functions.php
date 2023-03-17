<?php
add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);

function enqueue_child_theme_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

add_theme_support('custom-logo');
function expound_logo_setup()
{
    $defaults = [
        'height' => 100,
        'width' => 100,
        'flex-height' => true,
        'flex-width' => true,
        'header-text' => ['site-title', 'site-description'],
    ];
    add_theme_support('custom-logo', $defaults);
}
add_action('after_setup_theme', 'expound_logo_setup');

/**
 * Theme Setup
 *
 */
function dres_colors()
{
    // Disable Custom Colors
    add_theme_support('disable-custom-colors');

    // Editor Color Palette
    add_theme_support('editor-color-palette', [
        [
            'name' => __('Blue', 'dres'),
            'slug' => 'blue',
            'color' => '#138bcf',
        ],
        [
            'name' => __('Grey', 'dres'),
            'slug' => 'grey',
            'color' => '#3a3a3a',
        ],
        [
            'name' => __('Red', 'dres'),
            'slug' => 'red',
            'color' => '#cc3535',
        ],
        [
            'name' => __('White', 'dres'),
            'slug' => 'white',
            'color' => '#fff',
        ],
    ]);
}
add_action('after_setup_theme', 'dres_colors');

// Allows committte users to renew
add_filter('rcp_can_renew_deactivated_membership_levels', '__return_true');
