<?php defined('ABSPATH') || exit('No direct script access allowed');
/*
Plugin Name:  Style Switcher
Description:  Plugin to provide interfaces for client theme tweaks
Version:      1.0.1
Author:       next-iteration.net
Author URI:   https://next-iteration.net
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  style-switcher

*/
use StyleSwitcher\StyleSwitcher;


define( 'STYLESWITCHER__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( STYLESWITCHER__PLUGIN_DIR . 'core/StyleSwitcher.class.php' );

$settings_array = array(
  "hi" => 'true'
);
define( 'STYLESWITCHER__SETTING_ARRAY', $settings_array);

new StyleSwitcher();
