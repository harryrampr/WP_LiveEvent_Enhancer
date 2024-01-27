<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class ScheduleManager {

	private $schedule_file;

	public function __construct() {
		$this->schedule_file = plugin_dir_path( WP_LIVEEVENT_ENHANCER_FILE ) . 'data/schedule.json';
		if ( ! file_exists( $this->schedule_file ) ) {
			file_put_contents( $this->schedule_file, json_encode( array() ) );
		}
	}

	private function read_schedule(): array {
		$json_data = file_get_contents( $this->schedule_file );

		return json_decode( $json_data, true );
	}

	private function save_schedule( $schedule ): void {
		file_put_contents( $this->schedule_file, json_encode( $schedule ) );
	}

	public function add_activity( $day, $activity ): void {
		$schedule = $this->read_schedule();
		if ( ! isset( $schedule[ $day ] ) ) {
			$schedule[ $day ] = array();
		}
		$schedule[ $day ][] = $activity;
		$this->save_schedule( $schedule );
	}

	public function update_activity( $day, $activityIndex, $newActivity ): void {
		$schedule = $this->read_schedule();
		if ( isset( $schedule[ $day ][ $activityIndex ] ) ) {
			$schedule[ $day ][ $activityIndex ] = $newActivity;
			$this->save_schedule( $schedule );
		}
	}

	public function delete_activity( $day, $activityIndex ): void {
		$schedule = $this->read_schedule();
		if ( isset( $schedule[ $day ][ $activityIndex ] ) ) {
			array_splice( $schedule[ $day ], $activityIndex, 1 );
			$this->save_schedule( $schedule );
		}
	}

	public function shortcode_display_schedule(): string {
		// Handling for the [liveevent_schedule] shortcode
		$html = '';
		$html .= 'Some HTML';
		$html .= 'Other HTML';

		return $html;
	}
}