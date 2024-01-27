<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

/**
 * Plugin Name: WP LiveEvent Enhancer
 * Plugin URI:  https://github.com/harryrampr/WP_LiveEvent_Enhancer
 * Description: Enhances live-streaming events with streaming schedules, live-buttons, and more.
 * Version:     1.0
 * Author:      Harry Ramirez
 * Author URI:  https://www.linkedin.com/in/harry-ramirez-picon/
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_LIVEEVENT_ENHANCER_FILE' ) ) {
	define( 'WP_LIVEEVENT_ENHANCER_FILE', __FILE__ );
}

require_once plugin_dir_path( WP_LIVEEVENT_ENHANCER_FILE ) . 'vendor/autoload.php';

( new WPLiveEventEnhancer() )->run();