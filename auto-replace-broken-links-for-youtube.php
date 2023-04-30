<?php
ob_start();
/**
 * Auto Replace Broken Links For Youtube
 *
 * @author    Antonio Lamorgese <antonio.lamorgese@gmail.com>
 * @copyright 2023 Antonio Lamorgese
 * @license   GNU General Public License v3.0
 * @link      https://github.com/antoniolamorgese/auto-replace-broken-links-for-youtube
 * @see       https://jeremyhixon.com/tool/wordpress-option-page-generator/
 */

/**
 * Plugin Name:        Auto Replace Broken Links For Youtube
 * Plugin URI:         https://github.com/antoniolamorgese/auto-replace-broken-links-for-youtube
 * Description:        Auto Replace Broken Links For Youtube automatically replaces all broken or invalid YouTube video links in your posts to improve SEO and always ensure your authority on search engines. All Plugin activity is performed in asynchronous mode. Start <a href="options-general.php?page=auto-replace-broken-links-for-youtube">Auto Replace Broken Links For Youtube Settings</a>.
 * Author:             Antonio Lamorgese
 * Author URI:         http://www.phpcodewizard.it/antoniolamorgese/
 * Version:            1.1.0
 * License:            GNU General Public License v3.0
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:        auto-replace-broken-links-for-youtube
 * Domain Path:        /languages
 * GitHub Plugin URI:  https://github.com/antoniolamorgese/auto-replace-broken-links-for-youtube
 * Requires at least:  5.6
 * Tested up to:       6.2.0
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
 * Table name with Broken Links
 */
global $wpdb;
$table_name = $wpdb->prefix . 'auto_replace_broken_links_for_youtube';

 /**
 * Load Localisation files.
 *
 * Locales found in:
 * 	 - /wp-content/plugins/auto-replace-broken-links-for-youtube/languages/auto-replace-broken-links-for-youtube-LOCALE.mo
 */
load_plugin_textdomain( 'auto-replace-broken-links-for-youtube', FALSE, dirname(plugin_basename(__FILE__)) . '/languages' );

/** 
 * Add link "Settings" in Wordpress administration Plugin
 */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'auto_replace_broken_links_for_youtube_status_link' );
function auto_replace_broken_links_for_youtube_status_link ( $links ) {
	$mylinks1 = array('<a href="' . admin_url( 'options-general.php?page=auto-replace-broken-links-for-youtube' ) . '">'.esc_html__('Settings','auto-replace-broken-links-for-youtube').'</a>');

	/** 
	 * Get locale URL website
	 */
	$locale = get_locale();
	// if PolyLang Plugin installed
	if ( function_exists( 'pll_current_language' ) ) {
		if (preg_match('/^[a-zA-Z]{2}$/', pll_current_language())) {
			$locale = pll_current_language();	
		}
	}	
	$urlWebSite='https://www.phpcodewizard.it/antoniolamorgese';
	try {
		if(isset($locale)) {
			if (strpos($locale, 'it') !== false) {
				$urlWebSite=$urlWebSite . '/it';
			} else if (strpos($locale, 'en') !== false) {
				$urlWebSite=$urlWebSite . '/en';
			} else if (strpos($locale, 'es') !== false) {
				$urlWebSite=$urlWebSite . '/es';
			} else if (strpos($locale, 'de') !== false) {
				$urlWebSite=$urlWebSite . '/de';
			} else if (strpos($locale, 'pt') !== false) {
				$urlWebSite=$urlWebSite . '/pt';
			} else if (strpos($locale, 'fr') !== false) {
				$urlWebSite=$urlWebSite . '/fr';
			} else {
				$urlWebSite=$urlWebSite . '/en';
			}
		} else {
			$urlWebSite=$urlWebSite . '/en';
		}
	} catch(Exception $e) {
		$urlWebSite=$urlWebSite . '/en';
	}
	$mylinks2 = array('<a target="_blank" href="' . esc_url($urlWebSite) . '">Website</a>');
	
	/** 
	 * Return Links to administration plugin
	 */
	return array_merge( $links, $mylinks1, $mylinks2 );
}

/** 
 * Add Code in Wordpress Settings for get option settings Plugin
 * Code Generated with "WordPress Option Page Generator" <https://jeremyhixon.com/tool/wordpress-option-page-generator/>
 */
require_once(plugin_dir_path( __FILE__ ) . 'admin/auto-replace-broken-links-for-youtube-admin.php');

/** 
 * Import Options Plugin
 */
$auto_replace_broken_links_for_youtube_options = get_option( 'auto_replace_broken_links_for_youtube_option_name' );

/** 
 * Check Options Exists
 */
$total_rows = $wpdb->get_var("select count(option_value) from wp_options where option_name  = 'auto_replace_broken_links_for_youtube_option_name'");

/**
 *	--------------------------------
 * 	Option: "Google Youtube API Key"
 *	--------------------------------
 */	
$Google_Youtube_API_Key='';
if ($total_rows > 0) {
	if(isset($auto_replace_broken_links_for_youtube_options['youtube_api_key_0'])) {
		$Google_Youtube_API_Key = $auto_replace_broken_links_for_youtube_options['youtube_api_key_0']; 
	} 
}

/**
 * 	Show notice warning if Youtube API Key not defined
 */	
if((!isset($Google_Youtube_API_Key)) || (strlen(trim($Google_Youtube_API_Key)) <= 0)) {
	if(!function_exists('admin_notice_warn_auto_replace_broken_links_for_youtube')) {
		function admin_notice_warn_auto_replace_broken_links_for_youtube() {
			$mylinksNotice   = '<a href="' . admin_url( 'options-general.php?page=auto-replace-broken-links-for-youtube' ) . '">'.__('Please click here and enter your API Key now.','auto-replace-broken-links-for-youtube').'</a>';
			$warningMessage  = '<div class="notice notice-warning is-dismissible">';
			$warningMessage .=    '<p>'.__('Important: broken youtube links will not be checked if a Youtube API Key is not provided.','auto-replace-broken-links-for-youtube').' ';
			$warningMessage .=    '<p>' . $mylinksNotice . '</p>';
			$warningMessage .= '</div>'; 
			echo wp_kses_post($warningMessage);	 
		}
		add_action( 'admin_notices', 'admin_notice_warn_auto_replace_broken_links_for_youtube' );
	}
}

/**
 *	----------------
 * 	Option: "Action"
 *	----------------
 *
 *  Valid options:
 *   
 *	Hide Video
 *	Replace Video
 */	
$Action = 'Replace Video';
if ($total_rows > 0) {
	if(strlen($auto_replace_broken_links_for_youtube_options['action_2']) >= 3) {
		$Action = $auto_replace_broken_links_for_youtube_options['action_2'];
	} else {
		$Action = 'Replace Video';
	}
}

/**
 * Insert Youtube Broken Links in MySql Table
 */
if(!function_exists('insert_youtube_broken_link_in_mysql_table')){
	function insert_youtube_broken_link_in_mysql_table($IdPost, $Method) {
		global $wpdb;
		global $table_name;
		$TitlePost = esc_html(preg_replace('/[^A-Za-z \?!]/', '', get_the_title($IdPost)));
		$UrlPermalinkPost = esc_url(get_the_permalink($IdPost));
		$total_links = $wpdb->get_var("select count(WasSent) from " . $table_name . " where WasSent = 0 AND IdPost = ".$IdPost);
		if($total_links <= 0){
			$wpdb->insert($table_name, 
						  array(
								'IdPost' => $IdPost,
								'TitlePost' => $TitlePost,
								'UrlPermalinkPost' => $UrlPermalinkPost,
								'WasSent' => 0,
								'Action' => $Method
						  ),
						  array('%d','%s','%s','%d','%s')  
			);
		}		
	}
}			

/**
 * Create HTML code to include in the BODY tag.
 * Replace or Hide all Broken links youtube videos
 */
if(!function_exists('auto_replace_broken_links_for_youtube_add_Code_html_in_tag_body')) {
	function auto_replace_broken_links_for_youtube_add_Code_html_in_tag_body() {
		global $Google_Youtube_API_Key;
		global $Enable_Email_Notifications;
		global $total_rows;
		global $ytUrl;
		global $Action;
		?>
			<!-- Auto Replace Broken Links For Youtube Plugin -->
			<script>
				jQuery(document).ready(function(){

					/**
					 * Return Youtube Video ID from URL video
					 */	
					function getYoutubeVideoId(url) {
						var videoID = "";
						if (url.indexOf("youtube.com") > -1) {
							videoID = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i)[1];
						} else if (url.indexOf("youtu.be") > -1) {
							videoID = url.match(/youtu\.be\/([^"&?\/ ]{11})/i)[1];
						}
						return videoID;
					}

					/**
					 * Search other Youtube Video to replace broken links
					 */	
				 	function searchYouTubeVideos(apiKey, query, currentElement__wrapper) {
						var regionCode = '<?php echo esc_html(substr(get_locale(), 0, 2)); ?>';
 						if(regionCode.length > 0){
							var urlSearchVideoYoutube = 'https://www.googleapis.com/youtube/v3/search?';
							var params = 'part=snippet&q='+query+'&type=video&key='+apiKey+'&RegionCode='+regionCode;
						} else {
							var urlSearchVideoYoutube = 'https://www.googleapis.com/youtube/v3/search?';
							var params = 'part=snippet&q='+query+'&type=video&key='+apiKey;
						}

						const xhr2 = new XMLHttpRequest();
						xhr2.open("GET", urlSearchVideoYoutube + params, true);
						xhr2.send();
						xhr2.onload = function(){
							if (xhr2.readyState === 4 && xhr2.status === 200){
								var data2 = JSON.parse(xhr2.responseText);
								var video_ID = data2.items[0].id.videoId;
								if(video_ID != undefined){
									var urlYoutube = "https://www.youtube.com/watch?v=" + video_ID;
									var titleYoutubeVideo = data2.items[0].snippet.title;

									<?php if(wp_is_mobile()){ ?>
										var urlYoutubeThumbnail = data2.items[0].snippet.thumbnails.medium.url;
										var CodeHTMLthumbnail = '<center><div id="contenitore" style="border-style: none; width:320px; height:180px; border: 1px solid black; position: relative;"><center><a target="_blank" alt="' + titleYoutubeVideo + '" href="'+urlYoutube + '"><img style="width: 320px; height: 180px; max-width: 100%; max-height: 100%; display: block;" src="' + urlYoutubeThumbnail + '"/><img onmouseover="this.style.cursor = pointer;" style="border-radius:5%; max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" src="<?php echo plugin_dir_url(__FILE__); ?>assets/icona-player-youtube.jpg"></a></center></div></center>'; 
									<?php } else { ?>
										var urlYoutubeThumbnail = data2.items[0].snippet.thumbnails.high.url;
										var CodeHTMLthumbnail = '<center><div id="contenitore" style="border-style: none; width:640px; height:480px; border: 1px solid black; position: relative;"><center><a target="_blank" alt="' + titleYoutubeVideo + '" href="' + urlYoutube + '"><img style="width: 640px; height: 480px; max-width: 100%; max-height: 100%; display: block;" src="' + urlYoutubeThumbnail + '"/><img onmouseover="this.style.cursor = pointer;" style="border-radius:5%; max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" src="<?php echo plugin_dir_url(__FILE__); ?>assets/icona-player-youtube.jpg"></a></center></div></center>'; 
									<?php } ?>
									CodeHTMLCaptionThumbnail = '<a target="_blank" alt="' + titleYoutubeVideo + '" href="' + urlYoutube + '">'+titleYoutubeVideo + '</a>'; 

									currentElement__wrapper.html('<p>' + CodeHTMLthumbnail + '<br><center>' + CodeHTMLCaptionThumbnail + '</center></p>');
									jQuery('.wp-element-caption').hide('fast');
								} else {
									currentElement__wrapper.hide('fast');
									jQuery('figure.wp-block-embed').hide('fast');
								}
								
								/**
								 * Save Youtube broken link on database table
								 */	
								<?php 
									//insert_youtube_broken_link_in_mysql_table(get_the_ID(), $Action); 
								?>
							} else {
								currentElement__wrapper.hide('fast');
								jQuery('figure.wp-block-embed').hide('fast');
							}
						}	
					}

					/**
					 * Check if youtube Video has Broken link
					 * Google Youtube API Key required
					 */	
					function verifyYoutubeBrokenlinkChecker( APIKey, youtubeUrl, action, currentElement__wrapper ) {
						const url = youtubeUrl.match(/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/);
						youtubeUrl = url[0]; 
						var apiKey = atob(APIKey);
						var videoId = getYoutubeVideoId(youtubeUrl);
						var urlGoogleApis = "https://www.googleapis.com/youtube/v3/videos?";
						var params = "part=snippet&id=" + videoId + "&key=" + apiKey;
						const xhr1 = new XMLHttpRequest();
						xhr1.open("GET", urlGoogleApis + params, true);
						xhr1.send();
						xhr1.onload = function(){
							if (xhr1.readyState === 4 && xhr1.status === 200){
								/**
								 * Response Request Youtube API...
								 */
								var data1 = JSON.parse(xhr1.responseText);
								if(data1.pageInfo.totalResults != 'undefined'){
									if(data1.pageInfo.totalResults == 0){
										/**
										 * Video Link Broken
										 */	
										if(action === 'Hide Video') {
											/**
											 * Hide Video Action
											 */	
											currentElement__wrapper.hide('fast');
											jQuery('figure.wp-block-embed').hide('fast');

											/**
											 * Save Youtube broken link on database table
											 */	
											<?php 
												//insert_youtube_broken_link_in_mysql_table(get_the_ID(), $Action); 
											?>

										} else {  
											/**
											 * Replace Video Action and search other related Youtube Video
											 */	
											var querySearch = '<?php echo esc_html(preg_replace('/[^A-Za-z \?!]/', '', get_the_title(get_the_ID()))); ?>';
											searchYouTubeVideos( apiKey, querySearch, currentElement__wrapper );
										}
										/**
										 * Youtube video link broken
										 */	
										console.log('VIDEO NOK');
										return false;
									} else {
										/**
										 * Youtube video link Not broken
										 */	
										console.log('VIDEO OK');
										return true;
									}
								}
								/**
								 * Youtube video link Not broken
								 */	
								console.log('VIDEO OK');
								return true;
							}
						}
					}

					/**
					 * Startup Plugin Auto Replace Broken Links For Youtube
					 * Hide all the blocks Youtube Embedded if There are Broken Link
					 */	
					<?php
					if(is_page() || is_single()) { 
						if(isset($Google_Youtube_API_Key)) {
							if(strlen($Google_Youtube_API_Key) > 0) { 
								?>
									var elements = jQuery('.wp-block-embed__wrapper').get();
									for (var i = 0; i < elements.length; i++) {
										/**
										 * Scan all tags that contain video links
										 */	
										var currentElement__wrapper = jQuery(elements[i]);
										var SecretKey = '<?php echo esc_js( base64_encode( trim( $Google_Youtube_API_Key ) ) ); ?>';
										var ytUrl = currentElement__wrapper.text();
										var action = '<?php echo esc_html($Action); ?>';

										/**
										 * Check Youtube video links 
										 */	
										verifyYoutubeBrokenlinkChecker( SecretKey, ytUrl, action, currentElement__wrapper );
									}
								<?php 
							} 	
						} 
					}
					?> 
				});	
			</script>
		<?php
	}	
	add_action('wp_footer', 'auto_replace_broken_links_for_youtube_add_Code_html_in_tag_body');
}	

/**
 * Create MySQL Table "auto_replace_broken_links_for_youtube"
 */
if(!function_exists('create_table_auto_replace_broken_links_for_youtube')) {
	function create_table_auto_replace_broken_links_for_youtube() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'auto_replace_broken_links_for_youtube';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE ".$table_name." (
												id INT NOT NULL AUTO_INCREMENT,
												DateOperation DATETIME DEFAULT NOW() NOT NULL,
												IdPost mediumint(9) NOT NULL,
												TitlePost tinytext NOT NULL,
												UrlPermalinkPost tinytext NOT NULL,
												WasSent BOOLEAN DEFAULT 0 NOT NULL,
												Action tinytext NOT NULL,
												PRIMARY KEY  (id)
										 	 ) ".$charset_collate .";";
		dbDelta( $sql );
	}
	register_activation_hook( __FILE__, 'create_table_auto_replace_broken_links_for_youtube' );
}

/**
 * Drop MySQL Table "auto_replace_broken_links_for_youtube"
 */
if(!function_exists('drop_table_auto_replace_broken_links_for_youtube')) {
	function drop_table_auto_replace_broken_links_for_youtube() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'auto_replace_broken_links_for_youtube';
		$sql = "DROP TABLE IF EXISTS ".$table_name.";";
		$wpdb->query( $sql );

		/**
		 * Clear plugin cache
		 */
		$plugin_name = 'auto-replace-broken-links-for-youtube';
		$cache_path = WP_CONTENT_DIR . "/cache/" . $plugin_folder_name . "/";
		if (is_dir($cache_path)) { 
			$cache_files = glob($cache_path . '*.*'); 
			foreach ($cache_files as $cache_file) { 
				if (is_file($cache_file)) { 
					unlink($cache_file); 
				}
			}
		}
		wp_cache_flush();
		
	}
	register_deactivation_hook( __FILE__, 'drop_table_auto_replace_broken_links_for_youtube' );
}
