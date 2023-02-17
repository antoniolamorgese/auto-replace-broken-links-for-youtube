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
load_plugin_textdomain( 'youtube-broken-link-checker', FALSE, dirname(plugin_basename(__FILE__)) . '/languages' );

/** 
 * Add link "Settings" in Wordpress administration Plugin
 */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'youtube_broken_link_checker_status_link' );
function youtube_broken_link_checker_status_link ( $links ) {
	$mylinks1 = array('<a href="' . admin_url( 'options-general.php?page=youtube-broken-link-checker' ) . '">'.esc_html__('Settings','youtube-broken-link-checker').'</a>');

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
include_once(plugin_dir_path( __FILE__ ) . 'admin/youtube-broken-link-checker-admin.php');
$youtube_broken_link_checker_options = get_option( 'youtube_broken_link_checker_option_name' );
global $wpdb;
$total_rows = $wpdb->get_var("select count(option_value) from wp_options where option_name  = 'youtube_broken_link_checker_option_name'");

/** 
 * Table name with Broken Links
 */
$table_name = $wpdb->prefix . '_youtube_broken_link_checker';

/**
 *	--------------------------------
 * 	Option: "Google Youtube API Key"
 *	--------------------------------
 */	
$Google_Youtube_API_Key='';
if ($total_rows > 0) {
	if(isset($youtube_broken_link_checker_options['youtube_api_key_0'])) {
		$Google_Youtube_API_Key = $youtube_broken_link_checker_options['youtube_api_key_0']; 
	} 
}

/**
 *	-------------------------------------------
 * 	Option: "Sent email every ... broken links"
 *	-------------------------------------------
 */	
$Sent_Email_Every=1;
if ($total_rows > 0) {
	if(isset($youtube_broken_link_checker_options['sent_email_every_3'])) {
		$Sent_Email_Every = intval($youtube_broken_link_checker_options['sent_email_every_3']); 
		if($Sent_Email_Every > 10) $Sent_Email_Every = 10;
		if($Sent_Email_Every < 1)  $Sent_Email_Every = 1;
	} 
}

/**
 * 	Show notice warning if Youtube API Key not defined
 */	
if((!isset($Google_Youtube_API_Key)) || (strlen(trim($Google_Youtube_API_Key)) <= 0)) {
	if(!function_exists('admin_notice_warn_youtube_broken_link_checker')) {
		function admin_notice_warn_youtube_broken_link_checker() {
			$mylinksNotice   = '<a href="' . admin_url( 'options-general.php?page=youtube-broken-link-checker' ) . '">'.__('Please click here and enter your API Key now.','youtube-broken-link-checker').'</a>';
			$warningMessage  = '<div class="notice notice-warning is-dismissible">';
			$warningMessage .=    '<p>'.__('Important: broken youtube links will not be checked if a Youtube API Key is not provided.','youtube-broken-link-checker').' ';
			$warningMessage .=    '<p>'.$mylinksNotice.'</p>';
			$warningMessage .= '</div>'; 
			echo $warningMessage;	 
		}
		add_action( 'admin_notices', 'admin_notice_warn_youtube_broken_link_checker' );
	}
}

/**
 *	------------------------------------
 * 	Option: "Enable_Email_Notifications"
 *	------------------------------------
 */	
$Enable_Email_Notifications = 'NO';
if ($total_rows > 0) {
	if($youtube_broken_link_checker_options['enable_email_notifications_1']==='enable_email_notifications_1') {
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
$Action = 'Hide Video';
if ($total_rows > 0) {
	if(strlen($youtube_broken_link_checker_options['action_2']) >= 3) {
		$Action = $youtube_broken_link_checker_options['action_2'];
	} else {
		$Action = 'Hide Video';
	}
}

/**
 * Insert Youtube Broken Links in MySql Table
 */
if(!function_exists('insert_youtube_broken_link_in_mysql_table')){
	function insert_youtube_broken_link_in_mysql_table($IdPost) {
		global $wpdb;
		global $table_name;
		$DateOperation = date('Y-m-d H:i:s');
		$TitlePost = get_the_title($IdPost);
		$UrlPermalinkPost = get_the_permalink($IdPost);
		$WasSentEmail = 0;
		$wpdb->insert($table_name, array(
			'DateOperation' => $DateOperation,
			'IdPost', $IdPost,
			'TitlePost' => $TitlePost,
			'UrlPermalinkPost' => $UrlPermalinkPost,
			'WasSent' => $WasSentEmail
		));
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

		$total_broken_links = $wpdb->get_var("select count(WasSent) from ".$table_name." where WasSent = 0 ORDER BY DataOperation DESC");
		if($total_broken_links >= $Sent_Email_Every){
			$query = "SELECT * FROM $wpdb->$table_name WHERE ((WasSent = 0) OR (DateOperation >= DATE_SUB(CURDATE(), INTERVAL 10 MONTH)))";
			$result_set_broken_links = $wpdb->get_results($query);
			return $result_set_broken_links;
		}
	}
}			

/**
 * 	Send Email Notifications if required
 */	
if(!function_exists('send_html_email_youtube_broken_link_checker')){
	function send_html_email_youtube_broken_link_checker($action) {
		global $post;
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$email_address = get_option( 'admin_email' );
		if ( empty( $email_address ) ) $email_address = bloginfo('admin_email');
		$subject = __( 'Youtube Broken Link Checker HAS DETECTED', 'youtube-broken-link-checker' );
		if($action == 'Hide Video') {
			$body  = sprintf(__('Youtube Broken Link Checker has detected new broken links on your site', 'youtube-broken-link-checker').'<br>');
			$body .= sprintf(__('The Broken video links are no longer visible within the post', 'youtube-broken-link-checker').'.<br>');
			$body .= sprintf(__("here's the list", 'youtube-broken-link-checker').':<br><br>');
		} else {
			$body  = sprintf(__('Youtube Broken Link Checker has detected new broken links on your site', 'youtube-broken-link-checker').'<br>');
			$body .= sprintf(__("The broken video links have been replaced with another valid URL", 'youtube-broken-link-checker').'.<br>');
			$body .= sprintf(__("here's the list", 'youtube-broken-link-checker').':<br><br>');
		}
		if(function_exists('insert_youtube_broken_link_in_mysql_table')) insert_youtube_broken_link_in_mysql_table($post->ID);
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
if(!function_exists('youtube_broken_link_checker_get_key_frase_current_post')){
	function youtube_broken_link_checker_get_key_frase_current_post(){
		$post_id = get_the_ID();
		$keyword = get_post_meta($post_id, 'meta_keyword', true);
		if (!empty($keyword)) {
			return $keyword;
		} else {
			return get_the_title($post_id);
		}
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
		global $post;
		global $titlePost;
		global $Action;
		$ID = $post->ID;
		$titlePost = get_the_title($ID);
		?>
			<!-- Youtube Broken Link Checker Plugin -->
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
					async function searchYouTubeVideos(apiKey, query) {
						var regionCode = '<?php echo substr(get_locale(), 0, 2); ?>';
						var apiKey = atob(apiKey);
						if(regionCode.length > 0){
							const response = await fetch(`https://www.googleapis.com/youtube/v3/search?part=snippet&q=${query}&type=video&key=${apiKey}&RegionCode=${regionCode}`);
						} else {
							const response = await fetch(`https://www.googleapis.com/youtube/v3/search?part=snippet&q=${query}&type=video&key=${apiKey}`);
						}
						const data = await response.json();
						return data.items;
					}

					/**
					 * Check if youtube Video has Broken link
					 * Google Youtube API Key required
					 */	
					function verifyYoutubeBrokenlinkChecker(APIKey, youtubeUrl, action) {
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
								// Request XMLHTTP OK
								var data = JSON.parse(xhr.responseText);
								if(data.pageInfo.totalResults > 0){
									// Video OK
									console.log('Youtube Broken Link Checker => Video OK');
								} else {
									// Video NOK
									if(action === 'Hide Video') {
										// Hide Video Action
										console.log('Youtube Broken Link Checker => Video NOK => Hide Video');
										jQuery('figure.wp-block-embed').hide('fast'); 
										jQuery('figure.wp-block-embed-youtube').hide('fast');
										jQuery('figcaption.wp-element-caption').hide('fast');
										<?php if($Enable_Email_Notifications == 'YES') send_html_email_youtube_broken_link_checker('Hide Video'); ?>
									} else {  
										// Replace Video Action
										console.log('Youtube Broken Link Checker => Video NOK => Replace Video');
										var querySearch = '<?php echo youtube_broken_link_checker_get_key_frase_current_post(); ?>';
										var youtubeResults = searchYouTubeVideos(apiKey, querySearch);
										youtubeResults.then(function(result) {
										for(i=0; i < result.length; i++){
											videoID = result[i].id.videoId;
											urlYoutube = esc_url('https://www.youtube.com/watch?v='+videoID);
											jQuery('.wp-block-embed__wrapper').text(urlYoutube);
											document.getElementsByClassName("wp-block-embed__wrapper").setAttribute("src", urlYoutube);
											jQuery('figure.wp-block-embed').show('fast'); 
											jQuery('figure.wp-block-embed-youtube').show('fast');
											jQuery('figcaption.wp-element-caption').hide('fast');
											break;
										}
										<?php if($Enable_Email_Notifications == 'YES') send_html_email_youtube_broken_link_checker('Replace Hide'); ?>
										}).catch(function(error) {
											console.error('ERROR: '+error.message);
										});
									}
								}
							} else {
								console.error("ERROR: " + xhr.status);
							}
						}
					}

					/**
					 * Startup Youtube Broken Link Checker
					 * Hide all the blocks Youtube Embedded if There are Broken Link
					 */	
					<?php 
					if(is_page() || is_single()) { 
						if(isset($Google_Youtube_API_Key)) {
							if(strlen($Google_Youtube_API_Key) > 0) { 
								?>
									var SecretKey = '<?php echo esc_js(base64_encode(trim($Google_Youtube_API_Key))); ?>';
									var ytUrl = jQuery('.wp-block-embed__wrapper').text();
									var action = '<?php echo $Action; ?>';
									verifyYoutubeBrokenlinkChecker(SecretKey, ytUrl, action);
								<?php 
							} 	
						} 
					} 
					?>	
				});
			</script>
		<?php 
	}	
	add_action('wp_footer', 'youtube_broken_link_checker_add_Code_html_in_tag_body');
}	

/**
 * Create MySQL Table "youtube_broken_link_checker"
 */
if(!function_exists('create_table_youtube_broken_link_checker')) {
	function create_table_youtube_broken_link_checker() {
		global $wpdb;
		global $table_name;

		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
											id mediumint(9) NOT NULL AUTO_INCREMENT,
											DateOperation DATETIME DEFAULT NOW() NOT NULL,
											IdPost mediumint(9) NOT NULL,
											TitlePost tinytext NOT NULL,
											UrlPermalinkPost tinytext NOT NULL,
											WasSent BOOLEAN DEFAULT 0 NOT NULL,
											PRIMARY KEY (id)
										 )  $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	register_activation_hook( __FILE__, 'create_table_youtube_broken_link_checker' );
}
