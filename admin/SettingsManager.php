<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


class SettingsManager {

	public function init(): void {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function register_settings(): void {
		// Logic for registering settings and fields
		register_setting( 'wp-liveevent-enhancer-group', 'default_time_zone', 'sanitize_text_field' );
		register_setting( 'wp-liveevent-enhancer-group', 'live_button_css_selector', 'sanitize_text_field' );
		register_setting( 'wp-liveevent-enhancer-group', 'streaming_live_css_class', 'sanitize_text_field' );

		add_settings_section(
			'wp-liveevent-enhancer-streaming-section',
			'Streaming Settings',
			array( $this, 'schedule_section_callback' ),
			'wp-liveevent-enhancer'
		);

		add_settings_field(
			'default_time_zone_field',
			'<label for="default_time_zone">Default Time Zone</label>',
			array( $this, 'default_time_zone_field_callback' ),
			'wp-liveevent-enhancer',
			'wp-liveevent-enhancer-streaming-section'
		);

		add_settings_section(
			'wp-liveevent-enhancer-buttons-section',
			'Buttons Settings',
			array( $this, 'buttons_section_callback' ),
			'wp-liveevent-enhancer'
		);

		add_settings_field(
			'live_button_css_selector_field',
			'<label for="live_button_css_selector">Live Button CSS Selector</label>',
			array( $this, 'live_button_css_selector_field_callback' ),
			'wp-liveevent-enhancer',
			'wp-liveevent-enhancer-buttons-section'
		);

		add_settings_field(
			'streaming_live_css_class_field',
			'<label for="streaming_live_css_class">Streaming Live CSS Class</label>',
			array( $this, 'streaming_live_css_class_field_callback' ),
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
		echo '<p class="regular-text"><strong>Important:</strong> The \'Time Zone\' must be set correctly for players and buttons to start at the right time on other time zones.</p><br><br>' . PHP_EOL;
	}

	public function buttons_section_callback(): void {
		// echo '<p>Settings for buttons.</p>';
	}

	public function live_button_css_selector_field_callback(): void {
		$setting = get_option( 'live_button_css_selector' );

		if ( empty( $setting ) ) {
			$setting = '.button .live-button';
		}

		echo '<input type="text" id="live_button_css_selector" name="live_button_css_selector"
                  class="regular-text" value="' . esc_attr( $setting ) . '" />';
		echo '<p class="regular-text"><strong>Important:</strong> The selector must be set correctly for \'Live Button\' to be found by JavaScript.</p>' . PHP_EOL;

	}

	public function streaming_live_css_class_field_callback(): void {
		$setting = get_option( 'streaming_live_css_class' );

		if ( empty( $setting ) ) {
			$setting = 'streaming-live';
		}

		echo '<input type="text" id="streaming_live_css_class" name="streaming_live_css_class"
                  class="regular-text" value="' . esc_attr( $setting ) . '" />';
		echo '<p class="regular-text">This class will be added by JavaScript to enable \'Live Button\' at streaming hours.</p>' . PHP_EOL;
	}
}