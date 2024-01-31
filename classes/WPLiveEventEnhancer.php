<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

use HRPDEV\WpLiveEventEnhancer\Admin\LiveEventsAdminMenuManager;
use HRPDEV\WpLiveEventEnhancer\Admin\LiveEventsSettingsManager;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class WPLiveEventEnhancer {

	private LiveEventsAdminMenuManager $live_events_admin_menu_manager;
	private LiveEventsSettingsManager $live_events_settings_manager;
	private NextEventsManager $next_events_manager;
	private HivepressManager $hivepress_manager;
	private ScheduleManager $schedule_manager;

	public function __construct() {
		$this->live_events_admin_menu_manager = new LiveEventsAdminMenuManager();
		$this->live_events_settings_manager   = new LiveEventsSettingsManager();
		$this->next_events_manager            = new NextEventsManager();
		$this->hivepress_manager              = new HivepressManager();
		$this->schedule_manager               = new ScheduleManager();

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
		$this->live_events_admin_menu_manager->init();
		$this->live_events_settings_manager->init();
		$this->next_events_manager->init();
		$this->hivepress_manager->init();
		$this->schedule_manager->init();
	}


	// Other methods for functionality
}