<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

class ExternalPluginsChecker {

	private string $plugin_name;
	private string $slug;
	private string $main_file_path;

	public function __construct( $plugin_name, $slug, $folder_name, $main_file_name ) {
		$this->plugin_name    = $plugin_name;
		$this->slug           = $slug;
		$this->main_file_path = '/' . trim( $folder_name, '/\\' ) . '/' . $main_file_name;
	}

	public function init(): void {
		add_action( 'admin_init', array( $this, 'install_plugin' ), 20 );
		add_action( 'admin_init', array( $this, 'check_plugin_activation' ), 30 );
	}

	/** @noinspection PhpFullyQualifiedNameUsageInspection */
	public function install_plugin(): void {
		if ( ! is_plugin_active( ltrim( $this->main_file_path, '/' ) ) && ! file_exists( WP_PLUGIN_DIR . $this->main_file_path ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			include_once ABSPATH . 'wp-admin/includes/file.php';
			include_once ABSPATH . 'wp-admin/includes/misc.php';
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			$installed = false;
			$api       = plugins_api( 'plugin_information', array(
				'slug'   => $this->slug,
				'fields' => array( 'sections' => false )
			) );

			if ( is_wp_error( $api ) ) {
				$this->log_plugin_install_error( $api );
			} else {
				$upgrader  = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
				$installed = $upgrader->install( $api->download_link );
			}

			if ( is_wp_error( $installed ) || ! $installed ) {
				$this->log_plugin_install_error( $installed );
				add_action( 'admin_notices', array( $this, 'failed_install_admin_notice__error' ) );
				deactivate_plugins( plugin_basename( WP_LIVEEVENT_ENHANCER_FILE ) );
				exit;
			}
		}
	}

	private function log_plugin_install_error( $error ): void {
		if ( is_wp_error( $error ) ) {
			// Log the error message for debugging purposes
			error_log( 'WP LiveEvent Enhancer - Plugin Installation Error: ' . $error->get_error_message() );
		}
	}

	public function failed_install_admin_notice__error(): void {
		$class   = 'notice notice-error';
		$message = __( 'Failed to install', 'wp-liveevent-enhancer' ) . ' ' . $this->plugin_name . '. ' .
		           __( 'Please install and activate it manually. Then try to install WP LiveEvent Enhancer again.',
			           'wp-liveevent-enhancer' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	public function check_plugin_activation(): void {
		if ( file_exists( WP_PLUGIN_DIR . $this->main_file_path ) && ! is_plugin_active( ltrim( $this->main_file_path, '/' ) ) ) {

			$activated = activate_plugin( ltrim( $this->main_file_path, '/' )  );

			if ( is_wp_error( $activated ) ) {
				add_action( 'admin_notices', array( $this, 'failed_activation_admin_notice__error' ) );
				deactivate_plugins( plugin_basename( WP_LIVEEVENT_ENHANCER_FILE ) );
				exit;
			}
		}
	}

	public function failed_activation_admin_notice__error(): void {
		$class   = 'notice notice-error';
		$message = __( 'Failed to activate', 'wp-liveevent-enhancer' ) . ' ' . $this->plugin_name . '. ' .
		           __( 'Please activate it manually. Then try to install WP LiveEvent Enhancer again.',
			           'wp-liveevent-enhancer' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

}