<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


class SettingsManager {

	public function init():void {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}
	public function register_settings(): void {
		// Logic for registering settings and fields
		register_setting( 'wp-liveevent-enhancer-group', 'default_time_zone', 'sanitize_text_field' );
		register_setting( 'wp-liveevent-enhancer-group', 'live_button_html', array(
			'HRPDEV\WpLiveEventEnhancer\Helpers',
			'sanitize_html_input'
		) );

		add_settings_section(
			'wp-liveevent-enhancer-schedule-section',
			'Streaming Schedule Settings',
			array( $this, 'schedule_section_callback' ),
			'wp-liveevent-enhancer'
		);

		add_settings_field(
			'default_time_zone_field',
			'<label for="default_time_zone">Default Time Zone</label>',
			array( $this, 'default_time_zone_field_callback' ),
			'wp-liveevent-enhancer',
			'wp-liveevent-enhancer-schedule-section'
		);

		add_settings_section(
			'wp-liveevent-enhancer-buttons-section',
			'Buttons Settings',
			array( $this, 'buttons_section_callback' ),
			'wp-liveevent-enhancer'
		);

		add_settings_field(
			'live_button_html_field',
			'<label for="live_button_html">Live Button HTML</label>',
			array( $this, 'live_button_html_field_callback' ),
			'wp-liveevent-enhancer',
			'wp-liveevent-enhancer-buttons-section'
		);
	}

	public function schedule_section_callback(): void {
		// echo '<p>Settings for Streaming Schedule.</p>';
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

		$timezones = timezone_identifiers_list();
		echo '<select id="default_time_zone" name="default_time_zone" class="regular-text">' . PHP_EOL;
		foreach ( $timezones as $timezone ) {
			// Set the 'selected' attribute for the current time zone
			$selected = ( $timezone === $setting ) ? 'selected' : '';

			echo '<option value="' . $timezone . '" ' . $selected . '>' . $timezone . '</option>' . PHP_EOL;
		}
		echo '</select>' . PHP_EOL;
	}

	public function buttons_section_callback(): void {
		// echo '<p>Settings for buttons.</p>';
	}

	public function live_button_html_field_callback(): void {
		$setting = get_option( 'live_button_html' );

		// Set default button HTML if the setting is empty
		if ( empty( $setting ) ) {
			$setting = '<button class="live-event-button">Live</button>';
		}

		echo '<textarea id="live_button_html" name="live_button_html" rows="5"
                  cols="50" class="regular-text">' . ( isset( $setting ) ? esc_attr( $setting ) : '' ) . '</textarea>';
	}
}