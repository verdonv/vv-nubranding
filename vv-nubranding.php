<?php
/*
Plugin Name: Verdon's NU Branding
Description: A plugin that adds various Nipissing University branding to other themes. In this first version, a footer with official links and a few configuration options.
Version: 1.0.0
Author: Verdon Vaillancourt
Author URI: http://verdon.ca/
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


define( 'VVNUB_VERSION', '0.0.1' );
define( 'VVNUB__MINIMUM_WP_VERSION', '4.0' );
define( 'VVNUB__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VVNUB__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( VVNUB__PLUGIN_DIR . 'vvnub-settings.php' );
require_once( VVNUB__PLUGIN_DIR . 'vvnub-display.php' );

register_activation_hook( __FILE__, array( 'VVNUB_Settings', 'vvnub_activate') );
register_deactivation_hook( __FILE__, array( 'VVNUB_Settings', 'vvnub_deactivate') );
