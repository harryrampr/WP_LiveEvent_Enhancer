<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer;

class VerifyPluginLoadedAndActive {

	private string $plugin_name;
	private string $folder_name;
	private string $file_name;

	public function __construct( $plugin_name, $folder_name, $file_name ) {
		$this->plugin_name = $plugin_name;
		$this->folder_name = $folder_name;
		$this->file_name   = $file_name;
	}


}