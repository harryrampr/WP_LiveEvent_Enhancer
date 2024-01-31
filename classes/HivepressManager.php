<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class HivepressManager {

	public function __construct() {
		add_action( 'after_setup_theme', function () {
			add_theme_support( 'hivepress' );
		} );
	}

	public function init() {

	}

}