<?php
ob_start();
/**
 * Youtube Broken Link Checker
 *
 * @author    Antonio Lamorgese <antonio.lamorgese@gmail.com>
 * @copyright 2023 Antonio Lamorgese
 * @license   GNU General Public License v3.0
 * @link      https://github.com/antoniolamorgese/youtube-broken-link-checker
 * @see       https://jeremyhixon.com/tool/wordpress-option-page-generator/
 */

/**
 * Plugin Name:        Youtube Broken Link Checker
 * Plugin URI:         https://github.com/antoniolamorgese/youtube-broken-link-checker
 * Description:        Youtube Broken Link Checker monitors and checks all internal and external links on your site for broken youtube links. Hide all Broken links youtube videos to improve SEO and user experience. Start <a href="options-general.php?page=youtube-broken-link-checker">Youtube Broken Link checker Settings</a>.
 * Author:             Antonio Lamorgese
 * Author URI:         http://www.phpcodewizard.it/antoniolamorgese/
 * Version:            1.0.0
 * License:            GNU General Public License v3.0
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:        youtube-broken-link-checker
 * Domain Path:        /languages
 * GitHub Plugin URI:  https://github.com/antoniolamorgese/youtube-broken-link-checker
 * Requires at least:  5.6
 * Tested up to:       6.1.1
 * Requires PHP:       5.6 or later
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 3, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) die;

 /**
 * Load Localisation files.
 *
 * Locales found in:
 * 	 - /wp-content/plugins/youtube-broken-link-checker/languages/youtube-broken-link-checker-LOCALE.mo
 */
function youtube_broken_link_checker_textdomain() {
	load_plugin_textdomain( 'youtube-broken-link-checker', FALSE, dirname(plugin_basename(__FILE__)) . '/languages' );
}
add_action('init', 'youtube_broken_link_checker_textdomain');

/** 
 * Add link "Settings" in Wordpress administration Plugin
 */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'youtube_broken_link_checker_status_link' );
function youtube_broken_link_checker_status_link ( $links ) {
	$mylinks1 = array('<a href="' . admin_url( 'options-general.php?page=youtube-broken-link-checker' ) . '">' . esc_html__('Settings', 'youtube-broken-link-checker').'</a>');
	$mylinks2 = array('<a target="_blank" href="https://www.phpcodewizard.it/antoniolamorgese/">Website</a>');
	return array_merge( $links, $mylinks1, $mylinks2 );
}

/** 
 * Add Code in Wordpress Settings for get option settings Plugin
 * Code Generated with "WordPress Option Page Generator" <https://jeremyhixon.com/tool/wordpress-option-page-generator/>
 */
include_once(plugin_dir_path( __FILE__ ) . 'admin/youtube-broken-link-checker-admin.php');
$youtube_broken_link_checker_options = get_option( 'youtube_broken_link_checker_option_name' );
global $wpdb;
$total_rows = $wpdb->get_var("select count(option_value) from wp_options where option_name  = 'youtube_broken_link_checker_option_name'");

/**
 * Enqueue styles "font-awesome" icons & code Javascript
 */
add_action( 'wp_enqueue_scripts', function() {
	/**
	* Add font-awesome icons in plugin
	*/
	wp_enqueue_style( 'styles-fontawesome-youtube-broken-link-checker', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );

	/**
	* Add javascript Code in HEAD section to delete cookie "youtubeUrl"
	*/
	wp_register_script( 'add-javascript-code-youtube-broken-link-checker', '' );
	wp_enqueue_script( 'add-javascript-code-youtube-broken-link-checker' );
	wp_add_inline_script( 'add-javascript-code-youtube-broken-link-checker', "document.cookie = 'youtubeUrl=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';" );
});

/**
 *	------------------------------------
 * 	Get option: "Google Youtube API Key"
 *	------------------------------------
 */	
$Google_Youtube_API_Key='';
if ($total_rows > 0) {
	if(isset($youtube_broken_link_checker_options['google_youtube_api_key_0'])) {
		$Google_Youtube_API_Key = $youtube_broken_link_checker_options['google_youtube_api_key_0']; 
	} else {
		die; 
	}
} else {
	die;
}

/**
 *	----------------------------------------
 * 	Get option: "Enable_Email_Notifications"
 *	----------------------------------------
 */	
$Enable_Email_Notifications = 'YES';
if ($total_rows > 0) {
	if($youtube_broken_link_checker_options['enable_email_notifications_1']==='enable_email_notifications_1') {
		$Enable_Email_Notifications = 'YES'; 
	} else {
		$Enable_Email_Notifications = 'NO'; 
	}
}

/**
 * Create HTML code to include in the BODY tag.
 * Hide all Broken links youtube videos
 */
if(!function_exists('youtube_broken_link_checker_add_Code_html_in_tag_body')) {
	function youtube_broken_link_checker_add_Code_html_in_tag_body() {
		global $Google_Youtube_API_Key;
		global $Enable_Email_Notifications;
		global $total_rows;
		global $ytUrl;
		?>
			<script>
			jQuery(document).ready(function(){
				function getYoutubeVideoId(url) {
					const regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
					const match = url.match(regExp);

					if (match && match[2].length === 11) {
						return match[2];
					} else {
						return null;
					}
				}

				function verifyYoutubeVideo(APIKey, youtubeUrl) {
					returnValue = false;
					jQuery.ajax({
						async: false,
						cache: false,
						url: 'https://www.googleapis.com/youtube/v3/videos',
						data: {
							part: 'status',
							id: getYoutubeVideoId(youtubeUrl),
							key: APIKey
						},
						success: function(response) {
							let videoInfoDisponibili = response.pageInfo.totalResults;
							if(videoInfoDisponibili > 0) {
								let videoPubblico = (response.items[0].status.privacyStatus === 'public');
								let videoEmbeddable = (response.items[0].status.embeddable);
								if (videoPubblico && videoEmbeddable) {
									returnValue = true;
								}
							}
						}
					});
					return returnValue;
				}
				var SecretKey = '<?php esc_html(trim($Google_Youtube_API_Key)); ?>';
				var ytUrl = jQuery('.wp-block-embed__wrapper').text();
				if (typeof ytUrl !== "undefined") {
					if(verifyYoutubeVideo(SecretKey, ytUrl)) {
						jQuery('figure.wp-block-embed-youtube').show('fast'); 
						document.cookie = 'youtubeUrl=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
					} else {	
						jQuery('figure.wp-block-embed-youtube').hide('fast'); 
						document.cookie = 'youtubeUrl='+ytUrl;
					}   
				} else {
					document.cookie = 'youtubeUrl=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
				}

			});
			</script>
		<?php 
		if(isset($_COOKIE['youtubeUrl'])){
			if($Enable_Email_Notifications === 'YES') {
				try {
					$headers[] = 'From: Wordpress <wordpress@phpcodewizard.it>';
					$to = bloginfo('admin_email');
					if(isset($to)) {
						$subject  = esc_html__('Youtube Broken Link Checker HAS DETECTED', 'youtube-broken-link-checker');
						$message  = esc_html__('Youtube Broken Link Checker has detected new broken links on your site', 'youtube-broken-link-checker')."<br>";
						$message .=	esc_html__("here's the list", "youtube-broken-link-checker").":<br><br>";
						$message .=	"Post: " . get_the_title() . "<br>";
						$message .=	"URL Post: " . get_the_permalink() . "<br>";
						$message .=	"URL Youtube: " . esc_url($_COOKIE['youtubeUrl']);
						if(is_page()) {
							wp_mail( $to, $subject, $message, $headers );
							setcookie("youtubeUrl", "", time() - 3600);				

						}
					}
				} catch(Exception $e) {
					setcookie("youtubeUrl", "", time() - 3600);				
				}	
			} else {
				setcookie("youtubeUrl", "", time() - 3600);				
			}
		}	
	}	
	add_action('wp_footer', 'youtube_broken_link_checker_add_Code_html_in_tag_body');
}	

