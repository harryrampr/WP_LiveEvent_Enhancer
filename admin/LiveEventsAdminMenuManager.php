<?php declare( strict_types=1 );

namespace HRPDEV\WpLiveEventEnhancer\Admin;

class LiveEventsAdminMenuManager {
	public function init(): void {
		add_action( 'admin_menu', array( $this, 'add_admin_menus' ) );
	}

	public function add_admin_menus(): void {
		add_menu_page( 'LiveEvent Enhancer',
			'LiveEvent Enhancer',
			'manage_options',
			'wp-liveevent-enhancer-main',
			array( $this, 'render_main_page' ) );

		add_submenu_page(
			'wp-liveevent-enhancer-main',
			'LiveEvent Control Panel',
			'Control Panel',
			'manage_options',
			'wp-liveevent-enhancer-control-panel',
			array( $this, 'render_control_panel_page' )
		);

		// Add additional subpages here

		add_submenu_page(
			'wp-liveevent-enhancer-main',
			'LiveEvent Enhancer Settings',
			'Settings',
			'manage_options',
			'wp-liveevent-enhancer-settings',
			array( $this, 'render_settings_page' )
		);

		// Add additional menu pages here
	}

	public function render_main_page(): void {
		// Check user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>
        <div class="wrap" style="max-width: 1024px">
            <h1>WP LiveEvent Enhancer</h1>
            <h3>Revolutionizing Your Live Streaming Experience</h3>
            <p class="">In today's digital world, live streaming is an indispensable tool for connecting
                with your audience.
                However, most streams are confined to platforms like YouTube and Facebook, where you have limited
                control over the viewer experience. That's where WP LiveEvent Enhancer steps in – our mission is to
                empower you to reclaim your audience and bring them to a space you control: your own website.</p>

            <h4>Why Choose WP LiveEvent Enhancer?</h4>

            <ol>
                <li><strong>Centralized Audience Control:</strong> Seamlessly integrate your live stream from various
                    platforms onto your
                    website. This centralization ensures that your audience, regardless of their original platform,
                    converges at your domain, offering you unparalleled control over the viewer experience.
                </li>
                <li><strong>Enhanced Viewer Engagement Tools:</strong> We provide an array of innovative tools designed
                    to elevate your
                    live events. From interactive widgets to real-time feedback options, our tools are crafted to keep
                    your audience engaged and immersed in your content.
                </li>
                <li><strong>Exclusive Gadget Additions:</strong> Stay ahead of the curve with our constantly updated
                    selection of
                    Gadgets. These unique add-ons are designed to enhance viewer interaction and offer experiences that
                    mainstream platforms can't match.
                </li>
                <li><strong>Customizable Features for a Unique Experience:</strong> Our features are not just
                    enhancements; they are
                    transformational. Tailor the viewer experience to suit your brand and event theme, making each live
                    stream uniquely yours.
                </li>
                <li><strong>Maximize Viewer Preference:</strong> By offering an enriched viewing experience that large
                    platforms fail to
                    provide, we make your website the preferred destination for watching live events. This not only
                    boosts your viewer retention but also significantly enhances brand loyalty.
                </li>
            </ol>

            <p class="">Join us at WP LiveEvent Enhancer, and transform the way you connect with your
                audience. Experience the power of full control and unparalleled viewer engagement, all on your own
                website.</p>

            <!-- Additional HTML and PHP for the subpage can be added here -->
        </div>
		<?php
	}

	public function render_control_panel_page(): void {
		// Check user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>
        <div class="wrap" style="max-width: 1024px">
            <h1>LiveEvent Control Panel</h1>
            <p class="">Here your control panel UI</p>


            <!-- Additional HTML and PHP for the subpage can be added here -->
        </div>
		<?php
	}

	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
        <div class="wrap">
            <h1 style="margin-bottom: 3rem;"><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <!--suppress HtmlUnknownTarget -->
            <form action="options.php" method="post">
				<?php
				settings_fields( 'wp-liveevent-enhancer-group' );
				do_settings_sections( 'wp-liveevent-enhancer' );
				wp_nonce_field( 'wp_liveevent_enhancer_settings_action', 'wp_liveevent_enhancer_settings_nonce' );
				submit_button( 'Save Settings' );
				?>
            </form>
        </div>
		<?php
	}


	// Additional methods to render other pages or handle other admin tasks
}