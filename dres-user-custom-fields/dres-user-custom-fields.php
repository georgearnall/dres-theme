<?php
/*
Plugin Name: DRES User Profile Fields
Plugin URI:  https://garnall.co.uk
Description: Adds custom fields to Restrict content Pro registration pages for DRES.
Version:     1.0
Author:      George Arnall
Author URI:  https://garnall.co.uk
License:     GPL2
License URI: https://garnall.co.uk
*/

// Fields
require_once 'fields/communication-methods.php';
require_once 'fields/railway-interests.php';
require_once 'fields/address.php';
require_once 'fields/dob.php';
require_once 'fields/phone.php';
require_once 'fields/profile.php';
require_once 'fields/name.php';

function logged_in_shortcode($atts, $content = null)
{
    if (is_user_logged_in() && !is_null($content) && !is_feed()) {
        return do_shortcode($content);
    }
}
add_shortcode('logged_in', 'logged_in_shortcode');

