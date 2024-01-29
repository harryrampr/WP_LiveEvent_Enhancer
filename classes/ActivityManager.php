<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class ActivityManager {

	public function init(): void {
		add_action( 'admin_init', array( $this, 'install_acf_plugin' ), 20 );
		add_action( 'admin_init', array( $this, 'check_acf_activation' ), 30 );
		add_action( 'init', array( $this, 'register_activities_post_type' ) );
		add_action( 'acf/init', array( $this, 'register_acf_fields' ) );
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

	public function register_activities_post_type(): void {
		$args = array(
			'public'   => true,
			'label'    => 'Activities',
			'supports' => array( 'title', 'editor', 'custom-fields' )
		);

		register_post_type( 'activities', $args );
	}

	public function register_acf_fields(): void {
		if ( function_exists( 'acf_add_local_field_group' ) ):

			/** @noinspection PhpUndefinedFunctionInspection */
			acf_add_local_field_group( array(
				'key'      => 'group_1',
				'title'    => 'Activity Details',
				'fields'   => array(
					array(
						'key'          => 'field_300',
						'label'        => 'Short Description',
						'name'         => 'activity_short_description',
						'type'         => 'text',
						'instructions' => 'Enter activity short description (up to 200 characters).',
						'required'     => 1,
						'maxlength'    => 200,
					),
					array(
						'key'           => 'field_400',
						'label'         => 'Frequency',
						'name'          => 'activity_frequency',
						'type'          => 'select',
						'instructions'  => 'Select the frequency of the activity.',
						'required'      => 1,
						'choices'       => array(
							'daily'    => 'Daily',
							'weekly'   => 'Weekly',
							'monthly'  => 'Monthly',
							'specific' => 'Specific Date'
						),
						'default_value' => array( 'weekly' ),
						'allow_null'    => 0,
						'multiple'      => 0,
						'ui'            => 1,
						'ajax'          => 0,
						'return_format' => 'value',
						'placeholder'   => '',
					),
					array(
						'key'               => 'field_500',
						'label'             => 'Date',
						'name'              => 'activity_dates',
						'type'              => 'date_picker',
						'instructions'      => 'Select the date of the activity.',
						'required'          => 1,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_400',
									'operator' => '==',
									'value'    => 'specific',
								),
							),
						),
					),
					array(
						'key'               => 'field_540',
						'label'             => 'Day',
						'name'              => 'activity_day_of_the_week',
						'type'              => 'select',
						'instructions'      => 'Select the activity day or days.',
						'required'          => 1,
						'choices'           => array(
							'sunday'    => 'Sunday',
							'monday'    => 'Monday',
							'tuesday'   => 'Tuesday',
							'wednesday' => 'Wednesday',
							'thursday'  => 'Thursday',
							'friday'    => 'Friday',
							'saturday'  => 'Saturday',
						),
						'default_value'     => array( 'sunday' ),
						'allow_null'        => 0,
						'multiple'          => 1,
						'ui'                => 1,
						'ajax'              => 0,
						'return_format'     => 'value',
						'placeholder'       => '',
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_400',
									'operator' => '==',
									'value'    => 'weekly',
								),
							),
						),
					),
					array(
						'key'               => 'field_550',
						'label'             => 'Days',
						'name'              => 'activity_day_number',
						'type'              => 'select',
						'instructions'      => 'Select the activity day, days or None.',
						'required'          => 0,
						'choices'           => array(
							                       'none' => 'None',
						                       ) + array_combine( range( 1, 31 ), range( 1, 31 ) ),
						'default_value'     => array( 'none' ),
						'allow_null'        => 0,
						'multiple'          => 1,
						'ui'                => 1,
						'ajax'              => 0,
						'return_format'     => 'value',
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_400',
									'operator' => '==',
									'value'    => 'monthly',
								),
							),
						),
					),
					array(
						'key'               => 'field_560',
						'label'             => 'First Week Day',
						'name'              => 'activity_monthly_first_week_day',
						'type'              => 'select',
						'instructions'      => 'Select the activity day or days for first week of each month.',
						'required'          => 0,
						'choices'           => array(
							'none'      => 'None',
							'sunday'    => 'Sunday',
							'monday'    => 'Monday',
							'tuesday'   => 'Tuesday',
							'wednesday' => 'Wednesday',
							'thursday'  => 'Thursday',
							'friday'    => 'Friday',
							'saturday'  => 'Saturday',
						),
						'default_value'     => array( 'none' ),
						'allow_null'        => 0,
						'multiple'          => 1,
						'ui'                => 1,
						'ajax'              => 0,
						'return_format'     => 'value',
						'placeholder'       => '',
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_400',
									'operator' => '==',
									'value'    => 'monthly',
								),
								array(
									'field'    => 'field_550',
									'operator' => '==',
									'value'    => 'none',
								),
							),
						),
					),
					array(
						'key'               => 'field_570',
						'label'             => 'Second Week Day',
						'name'              => 'activity_monthly_second_week_day',
						'type'              => 'select',
						'instructions'      => 'Select the activity day or days for second week of each month.',
						'required'          => 0,
						'choices'           => array(
							'none'      => 'None',
							'sunday'    => 'Sunday',
							'monday'    => 'Monday',
							'tuesday'   => 'Tuesday',
							'wednesday' => 'Wednesday',
							'thursday'  => 'Thursday',
							'friday'    => 'Friday',
							'saturday'  => 'Saturday',
						),
						'default_value'     => array( 'none' ),
						'allow_null'        => 0,
						'multiple'          => 1,
						'ui'                => 1,
						'ajax'              => 0,
						'return_format'     => 'value',
						'placeholder'       => '',
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_400',
									'operator' => '==',
									'value'    => 'monthly',
								),
								array(
									'field'    => 'field_550',
									'operator' => '==',
									'value'    => 'none',
								),
							),
						),
					),
					array(
						'key'               => 'field_580',
						'label'             => 'Third Week Day',
						'name'              => 'activity_monthly_third_week_day',
						'type'              => 'select',
						'instructions'      => 'Select the activity day or days for third week of each month.',
						'required'          => 0,
						'choices'           => array(
							'none'      => 'None',
							'sunday'    => 'Sunday',
							'monday'    => 'Monday',
							'tuesday'   => 'Tuesday',
							'wednesday' => 'Wednesday',
							'thursday'  => 'Thursday',
							'friday'    => 'Friday',
							'saturday'  => 'Saturday',
						),
						'default_value'     => array( 'none' ),
						'allow_null'        => 0,
						'multiple'          => 1,
						'ui'                => 1,
						'ajax'              => 0,
						'return_format'     => 'value',
						'placeholder'       => '',
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_400',
									'operator' => '==',
									'value'    => 'monthly',
								),
								array(
									'field'    => 'field_550',
									'operator' => '==',
									'value'    => 'none',
								),
							),
						),
					),
					array(
						'key'               => 'field_590',
						'label'             => 'Fourth Week Day',
						'name'              => 'activity_monthly_fourth_week_day',
						'type'              => 'select',
						'instructions'      => 'Select the activity day or days for fourth week of each month.',
						'required'          => 0,
						'choices'           => array(
							'none'      => 'None',
							'sunday'    => 'Sunday',
							'monday'    => 'Monday',
							'tuesday'   => 'Tuesday',
							'wednesday' => 'Wednesday',
							'thursday'  => 'Thursday',
							'friday'    => 'Friday',
							'saturday'  => 'Saturday',
						),
						'default_value'     => array( 'none' ),
						'allow_null'        => 0,
						'multiple'          => 1,
						'ui'                => 1,
						'ajax'              => 0,
						'return_format'     => 'value',
						'placeholder'       => '',
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_400',
									'operator' => '==',
									'value'    => 'monthly',
								),
								array(
									'field'    => 'field_550',
									'operator' => '==',
									'value'    => 'none',
								),
							),
						),
					),
					array(
						'key'           => 'field_600',
						'label'         => 'Start Time',
						'name'          => 'activity_start_time',
						'type'          => 'time_picker',
						'required'      => 1,
						'default_value' => '06:00',
					),
					array(
						'key'           => 'field_700',
						'label'         => 'End Time',
						'name'          => 'activity_end_time',
						'type'          => 'time_picker',
						'required'      => 1,
						'default_value' => '23:00',
					),
					array(
						'key'          => 'field_800',
						'label'        => 'Location',
						'name'         => 'activity_location',
						'type'         => 'text',
						'instructions' => 'Enter activity location if presencial (optional).',
						'required'     => 0,
						'placeholder'  => '#10 Wilson St., Santa Clara, CA',
					),
					array(
						'key'          => 'field_900',
						'label'        => 'Live Stream Viewers URL',
						'name'         => 'activity_live_stream_viewers_url',
						'type'         => 'url',
						'instructions' => 'Enter the URL that viewers should visit for the live stream (optional)',
						'required'     => 0,
						'placeholder'  => 'https://mywebsite.com/livestream',
					),
					array(
						'key'          => 'field_1000',
						'label'        => 'Live Stream Player URL',
						'name'         => 'activity_live_stream_player_url',
						'type'         => 'url',
						'instructions' => 'Enter the URL the player needs to connect to the stream (optional)',
						'required'     => 0,
						'placeholder'  => 'https://www.youtube.com/embed/xxxx?si=yyyyy',
					)
					// ... Add more fields as needed ...
				),
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'activities',
						),
					),
				),
				// ... Other group settings ...
			) );

		endif;
	}

	// ... Other functions ...
}