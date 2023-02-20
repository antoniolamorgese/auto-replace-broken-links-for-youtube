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
 * Description:        Auto Replace Broken Links For Youtube monitors and checks all internal and external links on your site for broken youtube links. Hide all Broken links youtube videos to improve SEO and user experience. Start <a href="options-general.php?page=auto-replace-broken-links-for-youtube">Auto Replace Broken Links For Youtube Settings</a>.
 * Author:             Antonio Lamorgese
 * Author URI:         http://www.phpcodewizard.it/antoniolamorgese/
 * Version:            1.0.0
 * License:            GNU General Public License v3.0
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:        auto-replace-broken-links-for-youtube
 * Domain Path:        /languages
 * GitHub Plugin URI:  https://github.com/antoniolamorgese/auto-replace-broken-links-for-youtube
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
	if ( function_exists( 'pll_current_language' ) || isset($_COOKIE[ 'pll_language' ]) ) {
		$locale = isset($_COOKIE['pll_language']) ? $_COOKIE['pll_language'] : pll_current_language();	
		if(isset($locale)) $locale = $locale . '_';		
	}	
	$urlWebSite='https://www.phpcodewizard.it/antoniolamorgese';
	try {
		if(isset($locale)) {
			if (strpos($locale, 'it_') !== false) {
				$urlWebSite=$urlWebSite . '/it';
			} else if (strpos($locale, 'en_') !== false) {
				$urlWebSite=$urlWebSite . '/en';
			} else if (strpos($locale, 'es_') !== false) {
				$urlWebSite=$urlWebSite . '/es';
			} else if (strpos($locale, 'de_') !== false) {
				$urlWebSite=$urlWebSite . '/de';
			} else if (strpos($locale, 'pt_') !== false) {
				$urlWebSite=$urlWebSite . '/pt';
			} else if (strpos($locale, 'fr_') !== false) {
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
include_once(plugin_dir_path( __FILE__ ) . 'admin/auto-replace-broken-links-for-youtube-admin.php');
$auto_replace_broken_links_for_youtube_options = get_option( 'auto_replace_broken_links_for_youtube_option_name' );
global $wpdb;
$total_rows = $wpdb->get_var("select count(option_value) from wp_options where option_name  = 'auto_replace_broken_links_for_youtube_option_name'");

/** 
 * Table name with Broken Links
 */
$table_name = $wpdb->prefix . 'auto_replace_broken_links_for_youtube';

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
 *	-------------------------------------------
 * 	Option: "Sent email every ... broken links"
 *	-------------------------------------------
 */	
$Sent_Email_Every = 5;
if ($total_rows > 0) {
	if(isset($auto_replace_broken_links_for_youtube_options['sent_email_every_3'])) {
		$Sent_Email_Every = intval($auto_replace_broken_links_for_youtube_options['sent_email_every_3']); 
		if($Sent_Email_Every > 10) $Sent_Email_Every = 10;
		if($Sent_Email_Every < 1)  $Sent_Email_Every = 5;
		if(!isset($Sent_Email_Every)) $Sent_Email_Every = 5;
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
			$warningMessage .=    '<p>'.$mylinksNotice.'</p>';
			$warningMessage .= '</div>'; 
			echo $warningMessage;	 
		}
		add_action( 'admin_notices', 'admin_notice_warn_auto_replace_broken_links_for_youtube' );
	}
}

/**
 *	------------------------------------
 * 	Option: "Enable_Email_Notifications"
 *	------------------------------------
 */	
$Enable_Email_Notifications = 'NO';
if ($total_rows > 0) {
	if($auto_replace_broken_links_for_youtube_options['enable_email_notifications_1']==='enable_email_notifications_1') {
		$Enable_Email_Notifications = 'YES'; 
	} else {
		$Enable_Email_Notifications = 'NO'; 
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
		$TitlePost = trim(get_the_title($IdPost));
		$UrlPermalinkPost = get_the_permalink($IdPost);
		$total_links = $wpdb->get_var("select count(WasSent) from ".$table_name." where WasSent = 0 AND IdPost = ".$IdPost);
		if($total_links <= 0){
			$wpdb->insert($table_name, 
						array(
							'IdPost' => $IdPost,
							'TitlePost' => $TitlePost,
							'UrlPermalinkPost' => $UrlPermalinkPost,
							'WasSent' => 0,
							'Action' => $Method,
						),
						array('%d','%s','%s','%d','%s')  
			);
		}		
	}
}			

/**
 * Update Youtube Broken Links in MySql Table after Sent email
 */
if(!function_exists('update_youtube_broken_link_in_mysql_table')){
	function update_youtube_broken_link_in_mysql_table($IdPost) {
		global $wpdb;
		global $table_name;

		$data = array(
			'WasSent' => 1
		);

		$where = array(
			'IdPost' => $IdPost
		);

		$wpdb->update( $table_name, $data, $where );
	}
}			

/**
 * Get Youtube Broken Links in MySql Table
 */
if(!function_exists('get_youtube_broken_link_in_mysql_table')){
	function get_youtube_broken_link_in_mysql_table() {
		global $wpdb;
		global $Sent_Email_Every;
		global $table_name;

		$total_broken_links = $wpdb->get_var("select count(WasSent) from ".$table_name." where WasSent = 0");
		if($total_broken_links >= $Sent_Email_Every){
			$query = "SELECT IdPost, DateOperation, TitlePost, UrlPermalinkPost, WasSent, Action FROM ".$table_name." WHERE (WasSent = 0)";
			$result_set_broken_links = $wpdb->get_results($query);
			return $result_set_broken_links;
		}
	}
}			

/**
 * 	Send Email Notifications if required
 */	
if(!function_exists('send_html_email_auto_replace_broken_links_for_youtube')){
	function send_html_email_auto_replace_broken_links_for_youtube($action) {
		global $auto_replace_broken_links_for_youtube_options;
		global $post;
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$email_address = get_option( 'admin_email' );
		if ( empty( $email_address ) ) $email_address = bloginfo('admin_email');
		$subject = __( 'Auto Replace Broken Links For Youtube HAS DETECTED', 'auto-replace-broken-links-for-youtube' );
		if($action == 'Hide Video') {
			$body  = sprintf(__('Auto Replace Broken Links For Youtube has detected new broken links on your site', 'auto-replace-broken-links-for-youtube').'<br>');
			$body .= sprintf(__('The Broken video links are no longer visible within the post', 'auto-replace-broken-links-for-youtube').'.<br>');
			$body .= sprintf(__("here's the list", 'auto-replace-broken-links-for-youtube').':<br><br>');
		} else {
			$body  = sprintf(__('Auto Replace Broken Links For Youtube has detected new broken links on your site', 'auto-replace-broken-links-for-youtube').'<br>');
			$body .= sprintf(__("The broken video links have been replaced with another valid URL", 'auto-replace-broken-links-for-youtube').'.<br>');
			$body .= sprintf(__("here's the list", 'auto-replace-broken-links-for-youtube').':<br><br>');
		}
		if(function_exists('insert_youtube_broken_link_in_mysql_table')) insert_youtube_broken_link_in_mysql_table(get_the_ID(), $auto_replace_broken_links_for_youtube_options['action_2']);
		if(function_exists('get_youtube_broken_link_in_mysql_table')){
			if(function_exists('update_youtube_broken_link_in_mysql_table')){
				$resultSet = get_youtube_broken_link_in_mysql_table();
				if (!empty($resultSet)) {
					foreach ($resultSet as $result) {
						update_youtube_broken_link_in_mysql_table($result->IdPost);
						$body .= sprintf('<b>Post:</b> ' . $result->TitlePost . '<br>');
						$body .= sprintf('<b>URL Post:</b> ' . $result->UrlPermalinkPost . '<br><br>');
					}
					wp_mail( $email_address, $subject, $body, $headers );
				}
			}	
		}	
	}
}

/**
 * Get Key frase Or Title Current Post
 */
if(!function_exists('auto_replace_broken_links_for_youtube_get_key_frase_current_post')){
	function auto_replace_broken_links_for_youtube_get_key_frase_current_post(){
		$post_id = get_the_ID();
		return preg_replace('/[^A-Za-z \?!]/','',get_the_title($post_id));
	}
}

/**
 * Create HTML code to include in the BODY tag.
 * Hide all Broken links youtube videos
 */
if(!function_exists('auto_replace_broken_links_for_youtube_add_Code_html_in_tag_body')) {
	function auto_replace_broken_links_for_youtube_add_Code_html_in_tag_body() {
		global $Google_Youtube_API_Key;
		global $Enable_Email_Notifications;
		global $total_rows;
		global $ytUrl;
		global $post;
		global $titlePost;
		global $Action;
		$ID = $post->ID;
		$titlePost = get_the_title($ID);
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
						var regionCode = '<?php echo substr(get_locale(), 0, 2); ?>';
 						if(regionCode.length > 0){
							var urlSearchVideoYoutube = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q='+query+'&type=video&key='+apiKey+'&RegionCode='+regionCode;
						} else {
							var urlSearchVideoYoutube = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q='+query+'&type=video&key='+apiKey;
						}

						var xhr = new XMLHttpRequest();
						xhr.open("GET", urlSearchVideoYoutube, true);
						xhr.send();

						xhr.onload = function(){
							if (xhr.status === 200){
								var data = JSON.parse(xhr.responseText);
								video_ID = data.items[0].id.videoId;
								if(video_ID != undefined){
									urlYoutube = "https://www.youtube.com/watch?v="+video_ID;
									titleYoutubeVideo = data.items[0].snippet.title;
									<?php if(wp_is_mobile()){ ?>
										urlYoutubeThumbnail = data.items[0].snippet.thumbnails.medium.url;
										CodeHTMLthumbnail = '<a target="_blank" alt="'+titleYoutubeVideo+'" href="'+urlYoutube+'"><img style="width:320px; height:180px;" src="'+urlYoutubeThumbnail+'"</a>'; 
									<?php } else { ?>
										urlYoutubeThumbnail = data.items[0].snippet.thumbnails.high.url;
										CodeHTMLthumbnail = '<a target="_blank" alt="'+titleYoutubeVideo+'" href="'+urlYoutube+'"><img style="width:480px; height:360px;" src="'+urlYoutubeThumbnail+'"</a>'; 
									<?php } ?>
									CodeHTMLCaptionThumbnail = '<a target="_blank" alt="'+titleYoutubeVideo+'" href="'+urlYoutube+'">'+titleYoutubeVideo+'</a>'; 

									currentElement__wrapper.html(CodeHTMLthumbnail);
									c = currentElement__wrapper.children('figcaption.wp-element-caption').get();
									if(c>0) jQuery(c[0]).html(CodeHTMLCaptionThumbnail);

									<?php if($Enable_Email_Notifications == 'YES') 
										send_html_email_auto_replace_broken_links_for_youtube('Replace Hide'); 
									?>
								} else {
									currentElement__wrapper.hide('fast');
									c = currentElement__wrapper.children('figcaption.wp-element-caption').get();
									if(c>0) jQuery(c[0]).hide('fast');
									<?php if($Enable_Email_Notifications == 'YES') 
										send_html_email_auto_replace_broken_links_for_youtube('Hide Video'); 
									?>
								}
							} else {
								currentElement__wrapper.hide('fast');
								c = currentElement__wrapper.children('figcaption.wp-element-caption').get();
								if(c>0) jQuery(c[0]).hide('fast');
								<?php if($Enable_Email_Notifications == 'YES') 
									send_html_email_auto_replace_broken_links_for_youtube('Hide Video'); 
								?>
							}
						}	
					}

					/**
					 * Check if youtube Video has Broken link
					 * Google Youtube API Key required
					 */	
					function verifyYoutubeBrokenlinkChecker(APIKey, youtubeUrl, action, currentElement__wrapper) {
						const url = youtubeUrl.match(/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/);
						youtubeUrl = url[0]; 
						var apiKey = atob(APIKey);
						var videoId = getYoutubeVideoId(youtubeUrl);
						var xhr = new XMLHttpRequest();
						var urlGoogleApis = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=" + videoId + "&key=" + apiKey;
						xhr.open("GET", urlGoogleApis, true);
						xhr.send();

						xhr.onload = function(){
							if (xhr.status === 200){
								// Request Youtube API OK
								var data = JSON.parse(xhr.responseText);
								if(data.pageInfo.totalResults <= 0){
									// Video NOK
									if(action === 'Hide Video') {
										// Hide Video Action
										currentElement__wrapper.hide('fast');
										c = currentElement__wrapper.children('figcaption.wp-element-caption').get();
										if(c>0) jQuery(c[0]).hide('fast');

										<?php if($Enable_Email_Notifications == 'YES') 
											send_html_email_auto_replace_broken_links_for_youtube('Hide Video'); 
										?>
									} else {  
										// Replace Video Action
										var querySearch = '<?php echo auto_replace_broken_links_for_youtube_get_key_frase_current_post(); ?>';
										searchYouTubeVideos( apiKey, querySearch, currentElement__wrapper );
									}
								}
							} else {
								console.error("ERROR: " + xhr.status);
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
										var currentElement__wrapper = jQuery(elements[i]);
										var SecretKey = '<?php echo esc_js(base64_encode(trim($Google_Youtube_API_Key))); ?>';
										var ytUrl = jQuery(currentElement__wrapper).text();
										var action = '<?php echo $Action; ?>';
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
	}
	register_deactivation_hook( __FILE__, 'drop_table_auto_replace_broken_links_for_youtube' );
}
