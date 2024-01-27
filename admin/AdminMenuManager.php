<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer\Admin;

class AdminMenuManager {
	public function init(): void {
		add_action( 'admin_menu', array( $this, 'add_admin_menus' ) );
	}

	public function add_admin_menus(): void {
		add_menu_page( 'LiveEvent Enhancer',
			'LiveEvent Enhancer',
			'manage_options',
			'wp-liveevent-enhancer',
			array( $this, 'render_main_page' ) );

		// Add additional menu pages or subpages here
	}

	public function render_main_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
        <div class="wrap">
            <h1><?php esc_html( get_admin_page_title() ); ?></h1>
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

	// Additional methods to render other pages or handle other admin tasks
}