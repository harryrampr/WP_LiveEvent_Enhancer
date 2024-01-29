<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class ScheduleManager {

	public function __construct() {

	}

	public function init():void {
		add_shortcode( 'liveevent_schedule', array( $this, 'shortcode_display_schedule' ) );
	}

	public function shortcode_display_schedule(): string {
		// Handling for the [liveevent_schedule] shortcode
		$html = '';
		$html .= 'Some HTML';
		$html .= 'Other HTML';

		return $html;
	}
}