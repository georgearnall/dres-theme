<?php

add_filter( 'rcp_export_csv_cols_members', function( $cols ) {
    // Add custom column headers
    $cols['dres_dob'] = 'Date of Birth';
    $cols['dres_phone_mobile'] = 'Mobile Number';
    return $cols;
});

add_filter( 'rcp_export_memberships_get_data_row', function( $row, $membership ) {

	$user_id = $membership->get_customer()->get_user_id();

    // Get custom field values (assuming user meta)
    $row['dres_dob'] = get_user_meta( $user_id, 'dres_dob', true );
    $row['dres_phone_mobile'] = get_user_meta( $user_id, 'dres_phone_mobile', true );
    return $row;
}, 10, 2 );
