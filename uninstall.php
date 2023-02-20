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
			delete_option( 'auto_replace_broken_links_for_youtube_option_name' );
		}
		restore_current_blog();
	}
} else {	
	delete_option( 'auto_replace_broken_links_for_youtube_option_name' );
}
