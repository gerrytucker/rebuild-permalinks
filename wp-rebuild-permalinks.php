<?php
/*
Plugin Name: WP-Rebuild Permalinks
Plugin URI: https://github.com/gerrytucker/wp-rebuild-permalinks
Description: Rebuild Wordpress Database Permalinks
Author: Gerry Tucker
Author URI: http://gerrytucker.co.uk/
Version: 0.0.3
License: GPLv2 or later
GitHub Plugin URI: https://github.com/gerrytucker/wp-rebuild-permalinks
GitHub Branch: develop
*/

add_action( 'admin_menu', 'rebuild_permalinks_submenu_page' );

function rebuild_permalinks_submenu_page() {
	
	add_submenu_page(
		'options-general.php',
		__('Rebuild Permalinks'),
		__('Rebuild Permalinks'),
		'manage_options',
		'rebuild-permalinks',
		'rebuild_permalinks_callback'
	);

}

function rebuild_permalinks_callback() {
?>

	<div class="wrap" id="rebuild-permalinks-settings">
		<h2><?php _e('Rebuild Permalinks'); ?></h2>
		
		<form action="#" method="post" id="rebuild_permalinks_form">
			
			<table class="widefat">
				
				<tr>
					<th scope="row">
						<label for="post_type">Select Post Type</label>
					</th>
					<td>
						<select name="post_type">
<?php
	$post_types = get_post_types( array( 'public' => true ), 'names' );
	foreach( $post_types as $post_type ) :
?>
							<option value="<?php echo $post_type; ?>"><?php echo ucfirst( strtolower( $post_type ) ); ?></option>
<?php
	endforeach;
?>
						</select>
					</td>
				</tr>
				
			</table>

			<p>Make sure you have a backup of your WordPress database before cleanup!</p>
			<p>
				<input type="submit" name="submit" class="button-primary" style="width: 300px;" value="<?php _e('Rebuild Selected Permalinks'); ?>"
							 onclick='if (!window.confirm("<?php _e('Are you sure you want to do this?'); ?>")) return false;'>
			</p>

		</form>
		
	</div>

<?php
}

function rebuild_permalinks( $post_type = 'post' ) {
	
	global $wpdb;
	
	$rows = $wpdb->get_results(
		"SELECT id, post_title
		FROM $wpdb->posts
		WHERE post_status = 'publish'
		AND post_type = '$post_type'"
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

