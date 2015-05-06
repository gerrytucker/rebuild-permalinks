<?php
/*
Plugin Name: Rebuild Permalinks
Plugin URI: https://github.com/gerrytucker/rebuild--permalinks
Description: Rebuild Permalinks
Author: Gerry Tucker
Author URI: http://gerrytucker.co.uk/
Version: 0.1.3
License: GPLv2 or later
GitHub Plugin URI: https://github.com/gerrytucker/rebuild--permalinks
GitHub Branch: develop
*/

function rebuild_permalinks_admin() {
	include('rebuild-permalinks-admin.php');
}

function rebuild_permalinks_admin_actions() {
	add_options_page('Rebuild Permalinks', 'Rebuild Permalinks', 'manage_options', 'rebuild-permalinks.php', 'rebuild_permalinks_admin');
}

add_action('admin_menu', 'rebuild_permalinks_admin_actions');
