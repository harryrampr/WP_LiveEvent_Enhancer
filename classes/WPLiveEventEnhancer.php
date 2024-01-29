<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

use HRPDEV\WpLiveEventEnhancer\Admin\AdminMenuManager;
use HRPDEV\WpLiveEventEnhancer\Admin\SettingsManager;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class WPLiveEventEnhancer {

	private AdminMenuManager $admin_menu_manager;
	private SettingsManager $settings_manager;
	private ActivityManager $activity_manager;
	private ScheduleManager $schedule_manager;

	public function __construct() {
		$this->admin_menu_manager = new AdminMenuManager();
		$this->settings_manager   = new SettingsManager();
		$this->activity_manager    = new ActivityManager();
		$this->schedule_manager   = new ScheduleManager();

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
		$this->admin_menu_manager->init();
		$this->settings_manager->init();
		$this->activity_manager->init();
		$this->schedule_manager->init();
	}

	// Other methods for functionality
}