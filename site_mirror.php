<?php
	/*
	Plugin Name: Site Mirror
	Description: Use two different addresses for the same site's content
	Author: Paul Baptist
	Version: 0.1
	Author URI: https://github.com/Kaji01
	*/
	
	## Customize these values to reflect the sites on your network
	$primary_site_id = 1;
	$secondary_site_ids = array(3);

	// Check that we are on a multisite networks o we don't crash things
	if (function_exists('get_current_blog_id')) {
		// If we're on a secondary site, switch to the primary and add the appropriate filters
		if (in_array(get_current_blog_id(), $secondary_site_ids)) {
			switch_to_blog($primary_site_id);
			
			// Sync active plugin lists
			$active_plugins = get_option('active_plugins');
			
			restore_current_blog(); // Go back, for just a moment
			update_option('active_plugins', $active_plugins);
			switch_to_blog($primary_site_id);
			
			add_filter('alloptions', 'bcm_sm_intercept_site_options', 1);
		}
	}
	
	/**
	 * Intercepts all option calls and directs them to the current site's wp_options table instead of the primary site's
	 * table
	 */
	function bcm_sm_intercept_site_options($alloptions) {
		$primary_site_id = get_current_blog_id(); // Should be the primary site, as set above
		
		restore_current_blog(); // Go back to the original blog
		remove_filter('alloptions', 'bcm_sm_intercept_site_options', 1); // Prevent an endless loop
		
		$alloptions = wp_load_alloptions(); // Get all the options
		// This is run as part of every get_option() call, so this means you don't have to plan out every possible pre_get_option_{$option}

		switch_to_blog($primary_site_id); // Restore the site ID to the primary site so we can continue accessing its data
		
		add_filter('alloptions', 'bcm_sm_intercept_site_options', 1, 1); // Re-add the filter so it applies on the next call
		
		return $alloptions; // Pass the filtered value back
	}
