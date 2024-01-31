<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class HivepressManager {

	private ExternalPluginsChecker $external_plugin_checker;

	public function __construct() {
		add_action( 'after_setup_theme', function () {
			add_theme_support( 'hivepress' );
		} );
		$this->external_plugin_checker = new ExternalPluginsChecker( 'HivePress', 'hivepress',
			'hivepress', 'hivepress.php' );
	}

	public function init(): void {
		$this->external_plugin_checker->init();
	}

}