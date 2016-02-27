<?php
/**
 * @package Rotator Selector
 */
/*
Plugin Name: Rotator Selector
Plugin URI: https://github.com/typicalmike002/rotator-selector
Description: Adds an "Include in Rotator" option to the default WordPress uploader.
Version: 1.0
Author: typicalmike002
Author URI: https://github.com/typicalmike002/
License: MIT
Text Domain: rotator-selector
*/




/**
 * Adds the "Include in Rotator" option to the WordPress Media Uploader.
 *
 * @param $firm_fields 
 * @param $post
 * @return $form_fields
*/
function rotator_selector( $form_feilds, $post ) {

	wp_nonce_field( basename(__FILE__), 'nonce' );

	$field_value = get_post_meta( $post->ID, 'rotator', true );

	if ( $field_value == 'on' ) {
		$is_checked = " checked='checked'";
	}

	$html = "<input type='checkbox' name='rotator'" . $is_checked . "'/>";

	$form_fields['rotator'] = array(
		'label' => 'Include in Rotator',
		'input' => 'html',
		'html' => $html
	);

	return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'rotator_selector', 10, 2 );



/**
 * Save value of "Include in Rotator" selection in media uploader.
 *
 * @param $post
 * @param $attachment
 * @return $post
*/
function save_value( $post, $attachment ) {

	$is_valid_nonce = (isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], basename(__FILE__) ) ) ? 'true' : 'false' ;
	$is_valid_user = current_user_can( 'upload_files', $post['ID'] );

	if ( !$is_valid_nonce || !$is_valid_user ) { return; /* Data is not valid. */ }

	$chk = isset( $_POST['rotator'] ) && $_POST['rotator'] ? 'on' : 'off';
	update_post_meta( $post['ID'], 'rotator', $chk );

	return $post;
}
add_filter( 'attachment_fields_to_save', 'save_value', 10, 2 );

