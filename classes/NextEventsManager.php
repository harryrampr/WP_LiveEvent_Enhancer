<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class NextEventsManager {

	public function init(): void {
		add_action( 'init', array( $this, 'register_next_events_post_type' ), 20 );
		add_action( 'acf/init', array( $this, 'register_acf_fields' ), 20 );
	}

	public function register_next_events_post_type(): void {
		$args = array(
			'public'   => true,
			'label'    => 'NextEvents',
			'supports' => array( 'title', 'editor', 'custom-fields' )
		);

		register_post_type( 'wplee-next-event', $args );
	}

	public function register_acf_fields(): void {
		if ( function_exists( 'acf_add_local_field_group' ) ):

			/** @noinspection PhpUndefinedFunctionInspection */
			acf_add_local_field_group( array(
				'key'      => 'next_event_group_1',
				'title'    => 'Next Event Details',
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
						'key'           => 'field_200',
						'label'         => 'Frequency',
						'name'          => 'event_frequency',
						'type'          => 'select',
						'instructions'  => 'Select the event frequency.',
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
						'key'               => 'field_300',
						'label'             => 'Date',
						'name'              => 'event_date',
						'type'              => 'date_picker',
						'instructions'      => 'Select the event date.',
						'required'          => 1,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_200',
									'operator' => '==',
									'value'    => 'specific',
								),
							),
						),
					),
					array(
						'key'               => 'field_360',
						'label'             => 'Days',
						'name'              => 'event_days_week',
						'type'              => 'select',
						'instructions'      => 'Select the event days, every week.',
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
									'field'    => 'field_200',
									'operator' => '==',
									'value'    => 'weekly',
								),
							),
						),
					),
					array(
						'key'               => 'field_370',
						'label'             => 'Days',
						'name'              => 'event_days_month',
						'type'              => 'select',
						'instructions'      => 'Select the event days, every month.',
						'default_value'     => 'last_day',
						'required'          => 1,
						'choices'           => $this->generate_monthly_days_array(),
						'allow_null'        => 0,
						'multiple'          => 1,
						'ui'                => 1,
						'ajax'              => 0,
						'return_format'     => 'value',
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_200',
									'operator' => '==',
									'value'    => 'monthly',
								),
							),
						),
					),
					array(
						'key'           => 'field_400',
						'label'         => 'Start Time',
						'name'          => 'event_start_time',
						'type'          => 'time_picker',
						'required'      => 1,
						'default_value' => '06:00',
					),
					array(
						'key'           => 'field_500',
						'label'         => 'End Time',
						'name'          => 'event_end_time',
						'type'          => 'time_picker',
						'required'      => 1,
						'default_value' => '23:00',
					),
					array(
						'key'          => 'field_600',
						'label'        => 'Location',
						'name'         => 'event_location',
						'type'         => 'text',
						'instructions' => 'If presencial assistance is an option, enter the event location. (optional).',
						'required'     => 0,
						'placeholder'  => '#10 Wilson St., Santa Clara, CA',
					),
					array(
						'key'          => 'field_700',
						'label'        => 'Live Stream Viewers URL',
						'name'         => 'event_live_stream_viewers_url',
						'type'         => 'url',
						'instructions' => 'Enter the URL you want viewers to visit for the live event (optional).',
						'required'     => 0,
						'placeholder'  => 'https://mywebsite.com/livestream',
					),
					array(
						'key'          => 'field_800',
						'label'        => 'Live Stream Player URL',
						'name'         => 'event_live_stream_player_url',
						'type'         => 'url',
						'instructions' => 'Enter URL the Player needs to connect to the stream (optional). You may add/update this URL at seconds from event start time.',
						'required'     => 0,
						'placeholder'  => 'https://www.youtube.com/embed/xxxx?si=yyyyy',
					),
					array(
						'key'               => 'field_900',
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
									'field'    => 'field_800',
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
							'value'    => 'wplee-next-event',
						),
					),
				),
				// ... Other group settings ...
			) );

		endif;
	}

	private function generate_monthly_days_array(): array {
		$result      = [];
		$days        = [ "monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday" ];
		$occurrences = [ "first", "second", "third", "fourth", "last" ];

		foreach ( $occurrences as $occurrence ) {
			foreach ( $days as $day ) {
				// Construct the key using the occurrence and the day
				$key = $occurrence . '_' . $day;

				// Capitalize the first letter of each word for the value
				$formatted_occurrence = ucfirst( $occurrence );
				$formatted_day        = ucfirst( $day );

				// Combine them to create the value
				$value = $formatted_occurrence . ' ' . $formatted_day;

				// Add to the result array
				$result[ $key ] = $value;
			}
		}


		for ( $i = 1; $i <= 31; $i ++ ) {
			$result[ 'day_' . $i ] = 'Day ' . $i;
		}

		$result['last_day'] = 'Last Day';

		return $result;
	}


	// ... Other functions ...
}