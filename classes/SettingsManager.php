<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// File: settings-manager.php
namespace HRPDEV\WpLiveEventEnhancer;

class SettingsManager {
	public function register_settings(): void {
		// Logic for registering settings and fields
		register_setting( 'wp-liveevent-enhancer-group', 'default_time_zone', 'sanitize_text_field' );
		register_setting( 'wp-liveevent-enhancer-group', 'live_button_html', array(
			'HRPDEV\WpLiveEventEnhancer\Helpers',
			'sanitize_html_input'
		) );

		add_settings_section(
			'wp-liveevent-enhancer-schedule-section',
			'Streaming Schedule',
			array( $this, 'schedule_section_callback' ),
			'wp-liveevent-enhancer'
		);

		add_settings_field(
			'default_time_zone_field',
			'Default Time Zone',
			array( $this, 'default_time_zone_field_callback' ),
			'wp-liveevent-enhancer',
			'wp-liveevent-enhancer-schedule-section'
		);

		add_settings_section(
			'wp-liveevent-enhancer-buttons-section',
			'Buttons',
			array( $this, 'buttons_section_callback' ),
			'wp-liveevent-enhancer'
		);

		add_settings_field(
			'live_button_html_field',
			'Live Button HTML',
			array( $this, 'live_button_html_field_callback' ),
			'wp-liveevent-enhancer',
			'wp-liveevent-enhancer-buttons-section'
		);
	}

	public function settings_page_html(): void {
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

	public function schedule_section_callback(): void {
		echo '<p>Settings for Streaming Schedule.</p>';
	}

	public function default_time_zone_field_callback(): void {
		$setting = get_option( 'default_time_zone' );

		// If the setting is not set, use the WordPress default time zone
		if ( empty( $setting ) ) {
			$setting = get_option( 'timezone_string' );

			// If WordPress time zone is not set, fall back to UTC
			if ( empty( $setting ) ) {
				$setting = 'UTC';
			}
		}

		?>
        <label for="default_time_zone">Default Time Zone</label>
        <input type="text" id="default_time_zone" name="default_time_zone"
               value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
		<?php
	}

	public function buttons_section_callback(): void {
		echo '<p>Settings for buttons.</p>';
	}

	public function live_button_html_field_callback(): void {
		$setting = get_option( 'live_button_html' );

		// Set default button HTML if the setting is empty
		if ( empty( $setting ) ) {
			$setting = '<button class="live-event-button">Live</button>';
		}

		?>
        <label for="live_button_html">Live Button HTML</label>
        <textarea id="live_button_html" name="live_button_html" rows="5"
                  cols="50"><?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?></textarea>
		<?php
	}
}