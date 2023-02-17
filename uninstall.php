<?php
/**
 * Youtube Broken Link Checker
 *
 * Uninstalling deletes options.
 *
 */
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();

// Delete option plugin
if (is_multisite()) {
	$blogs = wp_list_pluck( wp_get_sites(), 'blog_id' );
	if ($blogs) {
		foreach($blogs as $blog) {
			switch_to_blog($blog['blog_id']);
			delete_option( 'youtube_broken_link_checker_option_name' );
		}
		restore_current_blog();
	}
} else {	
	delete_option( 'youtube_broken_link_checker_option_name' );
}

/**
 * Drop MySQL Table "youtube_broken_link_checker"
 */
if(!function_exists('drop_table_youtube_broken_link_checker')) {
	function drop_table_youtube_broken_link_checker() {
		global $wpdb;
		$table_name = $wpdb->prefix . '_youtube_broken_link_checker';
		$sql = "DROP TABLE IF EXISTS $table_name;";
		$wpdb->query( $sql );
	}
	register_uninstall_hook( __FILE__, 'drop_table_youtube_broken_link_checker' );
}
