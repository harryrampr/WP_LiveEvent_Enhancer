<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class LiveEventsManager {

	public function init(): void {
		add_action( 'admin_init', array( $this, 'install_acf_plugin' ), 20 );
		add_action( 'admin_init', array( $this, 'check_acf_activation' ), 30 );
		add_action( 'init', array( $this, 'register_live_events_post_type' ), 10 );
		add_action( 'acf/init', array( $this, 'register_acf_fields' ), 10 );
	}


	public function install_acf_plugin(): void {
		if ( ! is_plugin_active( 'advanced-custom-fields/acf.php' ) && ! file_exists( WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			include_once ABSPATH . 'wp-admin/includes/file.php';
			include_once ABSPATH . 'wp-admin/includes/misc.php';
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			$installed   = false;
			$plugin_slug = 'advanced-custom-fields';
			$api         = plugins_api( 'plugin_information', array(
				'slug'   => $plugin_slug,
				'fields' => array( 'sections' => false )
			) );


			if ( is_wp_error( $api ) ) {
				$this->log_plugin_error( $api );
			} else {
				$upgrader  = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
				$installed = $upgrader->install( $api->download_link );
			}

			if ( is_wp_error( $installed ) || ! $installed ) {
				$this->log_plugin_error( $installed );
				add_action( 'admin_notices', array( $this, 'failed_install_admin_notice__error' ) );
				deactivate_plugins( plugin_basename( WP_LIVEEVENT_ENHANCER_FILE ) );
				wp_redirect( admin_url( 'plugin-install.php?s=advanced+custom+fields&tab=search&type=term' ) );
				exit;
			}
		}
	}

	private function log_plugin_error( $error ): void {
		if ( is_wp_error( $error ) ) {
			// Log the error message for debugging purposes
			error_log( 'WP LiveEvent Enhancer - Plugin Installation Error: ' . $error->get_error_message() );
		}
	}

	public function failed_install_admin_notice__error(): void {
		$class   = 'notice notice-error';
		$message = __( 'Failed to activate Advanced Custom Fields. Please install and activate it manually. Then try to install WP LiveEvent Enhancer again.', 'wp-liveevent-enhancer' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	public function check_acf_activation(): void {
		if ( file_exists( WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php' ) && ! is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {

			$activated = activate_plugin( 'advanced-custom-fields/acf.php' );

			if ( is_wp_error( $activated ) ) {
				add_action( 'admin_notices', array( $this, 'failed_install_admin_notice__error' ) );
				deactivate_plugins( plugin_basename( WP_LIVEEVENT_ENHANCER_FILE ) );
				wp_redirect( admin_url( 'plugin-install.php?s=advanced+custom+fields&tab=search&type=term' ) );
				exit;
			}
		}
	}

	public function register_live_events_post_type(): void {
		$args = array(
			'public'   => true,
			'label'    => 'LiveEvents',
			'supports' => array( 'title', 'editor', 'custom-fields' )
		);

		register_post_type( 'wplee-live-event', $args );
	}

	public function register_acf_fields(): void {
		if ( function_exists( 'acf_add_local_field_group' ) ):

			/** @noinspection PhpUndefinedFunctionInspection */
			acf_add_local_field_group( array(
				'key'      => 'live_event_group_1',
				'title'    => 'Live Event Details',
				'fields'   => array(
					array(
						'key'          => 'field_100',
						'label'        => 'Short Description',
						'name'         => 'event_short_description',
						'type'         => 'text',
						'instructions' => 'Enter the event short description (up to 200 characters).',
						'required'     => 1,
						'maxlength'    => 200,
					),
					array(
						'key'               => 'field_200',
						'label'             => 'Date',
						'name'              => 'event_date',
						'type'              => 'date_picker',
						'instructions'      => 'Select the event date.',
						'required'          => 1,
					),
					array(
						'key'           => 'field_300',
						'label'         => 'Start Time',
						'name'          => 'event_start_time',
						'type'          => 'time_picker',
						'required'      => 1,
						'default_value' => '06:00',
					),
					array(
						'key'           => 'field_400',
						'label'         => 'End Time',
						'name'          => 'event_end_time',
						'type'          => 'time_picker',
						'required'      => 1,
						'default_value' => '23:00',
					),
					array(
						'key'          => 'field_500',
						'label'        => 'Location',
						'name'         => 'event_location',
						'type'         => 'text',
						'instructions' => 'If presencial assistance is an option, enter the event location. (optional).',
						'required'     => 0,
						'placeholder'  => '#10 Wilson St., Santa Clara, CA',
					),
					array(
						'key'          => 'field_1000',
						'label'        => 'Live Stream Player URL',
						'name'         => 'event_live_stream_player_url',
						'type'         => 'url',
						'instructions' => 'Enter URL the Player needs to connect to the stream (optional). You may add/update this URL at seconds from event start time.',
						'required'     => 0,
						'placeholder'  => 'https://www.youtube.com/embed/xxxx?si=yyyyy',
					),
					array(
						'key'               => 'field_1100',
						'label'             => 'Stream Type',
						'name'              => 'event_stream_type',
						'type'              => 'select',
						'instructions'      => 'Select the event\'s type of stream.',
						'required'          => 1,
						'choices'           => array(
							'video' => 'Video',
							'audio' => 'Audio Only',
						),
						'default_value'     => array( 'video' ),
						'allow_null'        => 0,
						'multiple'          => 0,
						'ui'                => 1,
						'ajax'              => 0,
						'return_format'     => 'value',
						'placeholder'       => '',
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_1000',
									'operator' => '!=',
									'value'    => '',
								),
							),
						),
					),
					// ... Add more fields as needed ...
				),
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'wplee-live-event',
						),
					),
				),
				// ... Other group settings ...
			) );

		endif;
	}

	// ... Other functions ...
}