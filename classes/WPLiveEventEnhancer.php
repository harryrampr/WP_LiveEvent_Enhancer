<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class WPLiveEventEnhancer {
	public function __construct() {
		register_activation_hook( WP_LIVEEVENT_ENHANCER_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( WP_LIVEEVENT_ENHANCER_FILE, array( $this, 'deactivate' ) );
		// Other hooks and initializations
	}

	public function activate(): void {
		// Activation tasks
	}

	public function deactivate(): void {
		// Deactivation tasks
	}

	public function run(): void {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		// Initialize the SettingsManager
		$settings_manager = new SettingsManager();
		add_action( 'admin_init', array( $settings_manager, 'register_settings' ) );

		// Initialize the ScheduleManager
		$schedule_manager = new ScheduleManager();
		add_shortcode( 'liveevent_schedule', array( $schedule_manager, 'shortcode_display_schedule' ) );
	}

	public function add_admin_menu(): void {
		add_menu_page(
			'LiveEvent Enhancer Settings',
			'LiveEvent Enhancer',
			'manage_options',
			'wp-liveevent-enhancer',
			array( $this, 'settings_page_html' )
		);
	}


	// Other methods for functionality
}