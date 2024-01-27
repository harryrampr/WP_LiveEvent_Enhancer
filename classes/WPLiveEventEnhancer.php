<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

use HRPDEV\WpLiveEventEnhancer\Admin\AdminMenuManager;
use HRPDEV\WpLiveEventEnhancer\Admin\SettingsManager;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class WPLiveEventEnhancer {

	private AdminMenuManager $admin_menu_manager;
	private SettingsManager $settings_manager;
	private ScheduleManager $schedule_manager;

	public function __construct() {
		register_activation_hook( WP_LIVEEVENT_ENHANCER_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( WP_LIVEEVENT_ENHANCER_FILE, array( $this, 'deactivate' ) );
		// Other hooks and initializations

		$this->admin_menu_manager = new AdminMenuManager();
		$this->settings_manager   = new SettingsManager();
		$this->schedule_manager   = new ScheduleManager();
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
		$this->admin_menu_manager->init();
		add_action( 'admin_init', array( $this->settings_manager, 'register_settings' ) );
		add_shortcode( 'liveevent_schedule', array( $this->schedule_manager, 'shortcode_display_schedule' ) );
	}

	// Other methods for functionality
}