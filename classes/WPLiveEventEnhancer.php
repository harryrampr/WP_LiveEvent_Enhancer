<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class WPLiveEventEnhancer {

	private ( SettingsManager ) $settings_manager;

	private ( ScheduleManager ) $schedule_manager;

	public function __construct() {
		register_activation_hook( WP_LIVEEVENT_ENHANCER_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( WP_LIVEEVENT_ENHANCER_FILE, array( $this, 'deactivate' ) );
		// Other hooks and initializations

		$this->settings_manager = new SettingsManager();
		$this->schedule_manager = new ScheduleManager();
	}

	public function activate(): void {
		// Activation tasks
		$data_dir = plugin_dir_path( WP_LIVEEVENT_ENHANCER_FILE ) . 'data';
		if ( ! is_dir( $data_dir ) ) {
			mkdir( $data_dir, 0755, true );
		}
	}

	public function deactivate(): void {
		// Deactivation tasks
	}

	public function run(): void {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_action( 'admin_init', array( $this->settings_manager, 'register_settings' ) );

		add_shortcode( 'liveevent_schedule', array( $this->schedule_manager, 'shortcode_display_schedule' ) );
	}

	public function add_admin_menu(): void {
		add_menu_page(
			'LiveEvent Enhancer Settings',
			'LiveEvent Enhancer',
			'manage_options',
			'wp-liveevent-enhancer',
			array( $this->settings_manager, 'settings_page_html' )
		);
	}


	// Other methods for functionality
}