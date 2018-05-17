<?php
/*
Plugin Name: Verdon's NU Branding
Description: A plugin that adds various Nipissing University branding to other themes. In this first version, a footer with official links and a few configuration options.
Version: 1.3.0
Author: Verdon Vaillancourt
Author URI: http://verdon.ca/
Update URL: https://github.com/verdonv/vv-nubranding/
License: GPLv2 or later
Text Domain: vv-nubranding
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/* setup a few constants */
define( 'VVNUB_VERSION', '1.3.0' );
define( 'VVNUB__MINIMUM_WP_VERSION', '4.0' );
define( 'VVNUB__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VVNUB__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/* check for the installed version */
if (get_option( 'vvnub_version' )) {
	$installed_ver = get_option( 'vvnub_version' );
} else {
	$installed_ver = '1.0.0';
}

/* update if required */
if ($installed_ver != VVNUB_VERSION) {
	switch($installed_ver):
		case '1.0.0'; // added new options
			$old_opt = get_option( 'vvnub_settings' );
			$new_opt = array();
			$new_opt[vvnub_showlink_1] = '1';
			$new_opt[vvnub_showlink_2] = '1';
			$new_opt[vvnub_showlink_3] = '1';
			$new_opt[vvnub_showlink_4] = '1';
			$new_opt[vvnub_showlink_5] = '1';
			$new_opt[vvnub_showlink_6] = '1';
			$new_opt[vvnub_showlink_7] = '1';
			$new_opt[vvnub_showlink_8] = '1';
			$new_opt[vvnub_customlink_1_disp] = 'hide';
			$new_opt[vvnub_customlink_1_label] = '';
			$new_opt[vvnub_customlink_1_url] = '';
			$new_opt[vvnub_customlink_2_disp] = 'hide';
			$new_opt[vvnub_customlink_2_label] = '';
			$new_opt[vvnub_customlink_2_url] = '';
			$updated_opt = array_merge ($old_opt, $new_opt);
			update_option( 'vvnub_settings', $updated_opt );
			update_option( 'vvnub_version', VVNUB_VERSION );
		break;
		case '1.1.0':
		break;
		case '1.2.0':
		break;
		case '1.3.0':
			// do I need to remove satAdd setting and vvnub_fgcol2 setting or just ignore them?
		break;
	endswitch;
}

/* get the required pages and classes */
require_once( VVNUB__PLUGIN_DIR . 'vvnub-settings.php' );
require_once( VVNUB__PLUGIN_DIR . 'vvnub-display.php' );

/* activation and deactivation hooks */
register_activation_hook( __FILE__, array( 'VVNUB_Settings', 'vvnub_activate') );
register_deactivation_hook( __FILE__, array( 'VVNUB_Settings', 'vvnub_deactivate') );


/* add a settings link to the row in the plugin page */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'vvnub_plugin_action_links', 10, 2 );
function vvnub_plugin_action_links($links, $file) {
	static $this_plugin;
	if (!$this_plugin) {
		$this_plugin = plugin_basename(__FILE__);
	}
	if ($file == $this_plugin) {
		$settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=vv_nubranding_options">Settings</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}

