<?php
/*
Plugin Name: Rebuild Permalinks
Plugin URI: http://gerrytucker.co.uk/wp-plugins/rebuild-permalinks.zip
Description: Rebuild Permalinks
Author: Gerry Tucker
Author URI: http://gerrytucker.co.uk/
Version: 1.0
License: GPLv2 or later
*/

function rebuild_permalinks_admin() {
	
	include 'rebuild-permalinks-admin.php';
	
}

function rebuild_permalinks_admin_actions() {

	add_options_page(
		__('Rebuild Permalinks'),
		__('Rebuild Permalinks'),
		'administrator',
		'rebuild-permalinks',
		'rebuild_permalinks_admin'
	);
	
}

add_action('admin_menu', 'rebuild_permalinks_admin_actions');

function rebuild_permalinks( $posttype = 'post' ) {
	
	global $wpdb;
	
	$rows = $wpdb->get_results(
		"SELECT id, post_title
		FROM $wpdb->posts
		WHERE post_status = 'publish'
		AND post_type = '$posttype'"
	);
	
	$count = 0;
	
	foreach( $rows as $row ) {
		
		$post_title = _clear_diacritics( $row->post_title );
		$post_name = sanitize_title_with_dashes( $post_title );
		$guid = home_url() . '/' . sanitize_title_with_dashes( $post_title );
		$wpdb->query(
			"UPDATE $wpdb->posts
			SET post_name = '" . $post_name . "',
			guid = '" . $guid . "'
			WHERE ID = $row->id"
		);
		$count++;
	}
	
	return $count;
}

function _clear_diacritics( $post_title ) {
	
	$diacritics = array(
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 
		'Æ' => 'A', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
		'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c', 'Ç' => 'C', 'ç' => 'c',
		'Ď' => 'D', 'ď' => 'd',
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ě' => 'E', 'è' => 'e', 
		'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ě' => 'e',
		'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
		'Ñ' => 'N', 'ñ' => 'n',
		'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 
		'ð' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
		'Ŕ' => 'R', 'Ř' => 'R', 'Ŕ' => 'R', 'ŕ' => 'r', 'ř' => 'r',
		'Š' => 'S', 'š' => 's', 'Ś' => 'S', 'ś' => 's',
		'Ť' => 'T', 'ť' => 't',
		'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'ù' => 'u', 'ú' => 'u', 
		'û' => 'u', 'ü' => 'u',
		'Ý' => 'Y', 'ÿ' => 'y', 'ý' => 'y', 'ý' => 'y',
		'Ž' => 'Z', 'ž' => 'z', 'Ź' => 'Z', 'ź' => 'z',
		'Đ' => 'Dj', 'đ' => 'dj', 'Þ' => 'B', 'ß' => 's', 'þ' => 'b',
	);

	return strtr($post_title, $diacritics);
}

