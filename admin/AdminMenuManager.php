<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer\Admin;

class AdminMenuManager {
	public function init(): void {
		add_action( 'admin_menu', array( $this, 'add_admin_menus' ) );
	}

	public function add_admin_menus(): void {
		add_menu_page( 'LiveEvent Enhancer',
			'LiveEvent Enhancer Settings',
			'manage_options',
			'wp-liveevent-enhancer',
			array( $this, 'render_main_page' ) );

		add_submenu_page(
			'wp-liveevent-enhancer', // Parent slug
			'Live Event Control Panel', // Page title
			'Live Event Control Panel', // Menu title
			'manage_options', // Capability
			'wp-liveevent-enhancer-live-event-control', // Menu slug
			array( $this, 'render_live_event_control' ) // Callback function
		);

		// Add additional menu pages or subpages here
	}

	public function render_main_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
        <div class="wrap">
            <h1 style="margin-bottom: 3rem;"><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <!--suppress HtmlUnknownTarget -->
            <form action="options.php" method="post">
				<?php
				settings_fields( 'wp-liveevent-enhancer-group' );
				do_settings_sections( 'wp-liveevent-enhancer' );
				wp_nonce_field( 'wp_liveevent_enhancer_settings_action', 'wp_liveevent_enhancer_settings_nonce' );
				submit_button( 'Save Settings' );
				?>
            </form>
        </div>
		<?php
	}

	public function render_live_event_control(): void {
		// Check user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Content of the new subpage
		?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <p>This is the content of the submenu page.</p>
            <!-- Additional HTML and PHP for the subpage can be added here -->
        </div>
		<?php
	}


	// Additional methods to render other pages or handle other admin tasks
}