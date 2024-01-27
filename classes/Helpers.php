<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

class Helpers {

	public static function sanitize_html_input( $input ): string {
		return wp_kses_post( $input );
	}

	// Other helper methods...
}