<?php
/**
 * Plugin Name:       Zona Extensions
 * Plugin URI:        http://rascalsthemes.com/zona
 * Description:       This is a complimentary plugin for the "Zona" WordPress theme. You can use it to create, manage and update Custom Posts Types. It also has some useful shortcodes available to use.
 * Version:           1.2.4
 * Author:            Mariusz Rek - Rascals Themes
 * Author URI:        http://rascalsthemes.com
 * Text Domain:       zona_plugin
 * License:           See "Licensing" Folder
 * License URI:       See "Licensing" Folder
 * Domain Path:       /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/* ----------------------------------------------------------------------
	PLUGIN TRANSLATION
/* ---------------------------------------------------------------------- */
load_plugin_textdomain( 'zona_plugin', false, 'rt-zona-extensions/languages' );


/* ----------------------------------------------------------------------
	INCLUDE ALL THE FILES NEEDED
/* ---------------------------------------------------------------------- */

// Get panel options
$panel_options = get_option( 'zona_panel_opts' );

// Post Types
load_plugin_textdomain( 'custom_post_class', false, 'rt-zona-extensions/languages' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/post-types/post-types.php' );

// Scamp Player
if ( $panel_options && isset( $panel_options['scamp_player'] ) && $panel_options['scamp_player'] === 'on' ) {
	require_once( plugin_dir_path( __FILE__ ) . 'includes/post-types/scamp-player.php' );
}

// Metaboxes
load_plugin_textdomain( 'muttleybox', false, 'rt-zona-extensions/languages' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/metaboxes/metaboxes.php' );

// Shortcodes
require_once( plugin_dir_path( __FILE__ ) . 'includes/shortcodes/shortcodes-helpers.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/shortcodes/shortcodes.php' );
if ( function_exists('vc_map') ) {
	require_once( plugin_dir_path( __FILE__ ) . 'includes/shortcodes/vc-extend.php' );
}

// Widgets
require_once( plugin_dir_path( __FILE__ ) . 'includes/widgets/widget-twitter.php' );
//require_once( plugin_dir_path( __FILE__ ) . 'includes/widgets/widget-tracks.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/widgets/widget-recent-posts.php' );