<?php
/**
 * Youtube Broken Link Checker
 *
 * @author    Antonio Lamorgese <antonio.lamorgese@gmail.com>
 * @copyright 2023 Antonio Lamorgese
 * @license   GNU General Public License v3.0
 * @link      https://github.com/antoniolamorgese/youtube-broken-link-checker
 * @see https://jeremyhixon.com/tool/wordpress-option-page-generator/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if(! class_exists('YoutubeBrokenLinkChecker')) {
    class YoutubeBrokenLinkChecker {
        private $youtube_broken_link_checker_options;

        public function __construct() {
            add_action( 'admin_menu', array( $this, 'youtube_broken_link_checker_add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'youtube_broken_link_checker_page_init' ) );
        }

        public function youtube_broken_link_checker_add_plugin_page() {
            add_plugins_page(
                'Youtube Broken Link checker', // page_title
                'Youtube Broken Link checker', // menu_title
                'manage_options', // capability
                'youtube-broken-link-checker', // menu_slug
                array( $this, 'youtube_broken_link_checker_create_admin_page' ) // function
            );
        }

        public function youtube_broken_link_checker_create_admin_page() {
            $this->youtube_broken_link_checker_options = get_option( 'youtube_broken_link_checker_option_name' ); ?>

            <div class="wrap">
                <h2>Youtube Broken Link checker</h2>
                <p>Youtube Broken Link Checker monitors and checks all internal and external links on your site for broken youtube links. Hide all links to bad youtube videos to improve SEO and user experience.</p>
                <?php settings_errors(); ?>

                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'youtube_broken_link_checker_option_group' );
                        do_settings_sections( 'youtube-broken-link-checker-admin' );
                        submit_button();
                    ?>
                </form>
            </div>
        <?php }

        public function youtube_broken_link_checker_page_init() {
            register_setting(
                'youtube_broken_link_checker_option_group', // option_group
                'youtube_broken_link_checker_option_name', // option_name
                array( $this, 'youtube_broken_link_checker_sanitize' ) // sanitize_callback
            );

            add_settings_section(
                'youtube_broken_link_checker_setting_section', // id
                'Settings', // title
                array( $this, 'youtube_broken_link_checker_section_info' ), // callback
                'youtube-broken-link-checker-admin' // page
            );

            add_settings_field(
                'google_youtube_api_key_0', // id
                'Google Youtube API Key', // title
                array( $this, 'google_youtube_api_key_0_callback' ), // callback
                'youtube-broken-link-checker-admin', // page
                'youtube_broken_link_checker_setting_section' // section
            );

            add_settings_field(
                'enable_email_notifications_1', // id
                'Enable email notifications', // title
                array( $this, 'enable_email_notifications_1_callback' ), // callback
                'youtube-broken-link-checker-admin', // page
                'youtube_broken_link_checker_setting_section' // section
            );
        }

        public function youtube_broken_link_checker_sanitize($input) {
            $sanitary_values = array();
            if ( isset( $input['google_youtube_api_key_0'] ) ) {
                $sanitary_values['google_youtube_api_key_0'] = sanitize_text_field( $input['google_youtube_api_key_0'] );
            }

            if ( isset( $input['enable_email_notifications_1'] ) ) {
                $sanitary_values['enable_email_notifications_1'] = $input['enable_email_notifications_1'];
            }

            return $sanitary_values;
        }

        public function youtube_broken_link_checker_section_info() {
            
        }

        public function google_youtube_api_key_0_callback() {
            printf(
                '<input class="regular-text" type="text" name="youtube_broken_link_checker_option_name[google_youtube_api_key_0]" id="google_youtube_api_key_0" value="%s">',
                isset( $this->youtube_broken_link_checker_options['google_youtube_api_key_0'] ) ? esc_attr( $this->youtube_broken_link_checker_options['google_youtube_api_key_0']) : ''
            );
        }

        public function enable_email_notifications_1_callback() {
            printf(
                '<input type="checkbox" name="youtube_broken_link_checker_option_name[enable_email_notifications_1]" id="enable_email_notifications_1" value="enable_email_notifications_1" %s> <label for="enable_email_notifications_1">The plugin will send an email to the administrator to notify the operation.</label>',
                ( isset( $this->youtube_broken_link_checker_options['enable_email_notifications_1'] ) && $this->youtube_broken_link_checker_options['enable_email_notifications_1'] === 'enable_email_notifications_1' ) ? 'checked' : ''
            );
        }

    }
}

if ( is_admin() ) {
    if(class_exists('YoutubeBrokenLinkChecker')) {
	   $youtube_broken_link_checker = new YoutubeBrokenLinkChecker();
    }
}   

/* 
 * Retrieve this value with:
 * $youtube_broken_link_checker_options = get_option( 'youtube_broken_link_checker_option_name' ); // Array of All Options
 * $google_youtube_api_key_0 = $youtube_broken_link_checker_options['google_youtube_api_key_0']; // Google Youtube API Key
 * $enable_email_notifications_1 = $youtube_broken_link_checker_options['enable_email_notifications_1']; // Enable email notifications
 */
