<?php

/* Panel Options
------------------------------------------------------------------------*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Options array */
$zona_main_options = array( 


	/* General Settings
	-------------------------------------------------------- */
	array( 
		'type' => 'open',
		'tab_name' => esc_html__( 'General Settings', 'zona' ),
		'tab_id' => 'general-settings',
		'icon' => 'gears'
	),

		array( 
			'type' => 'sub_open',
			'sub_tab_name' => esc_html__( 'General Settings', 'zona' ),
			'sub_tab_id' => 'sub-general-basics'
		),

			// Retina Displays
			array( 
				'name' => esc_html__( 'Retina Displays', 'zona' ),
				'id' => 'retina',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => esc_html__( 'To make this work you need to specify the width and the height of the image directly and provide the same image twice the size withe the @2x selector added at the end of the image name. For instance if you want your "logo.png" file to be retina compatible just include it in the markup with specified width and height ( the width and height of the original image in pixels ) and create a "logo@2x.png" file in the same directory that is twice the resolution.', 'zona' ),
			),

			// Smooth Scroll
			array( 
				'name' => esc_html__( 'Smooth Scroll', 'zona' ),
				'id' => 'smooth_scroll',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => '',
			),

			// Google MAPS API Key
			array(
				'name' => esc_html__( 'Google Maps API Key', 'zona'),
				'id' => 'google_maps_key',
				'type' => 'text',
				'std' => '',
				'desc' => esc_html__( 'Insert your Google Maps API key.', 'zona')
			),

			// Google Map Marker
			array(
				'name' => esc_html__( 'Google Maps Marker', 'zona' ),
				'id' => 'map_marker',
				'type' => 'add_image',
				'source' => 'media_libary',
				'plugins' => array( 'add_image' ),
				'std' => '',
				'button_title' => esc_html__( 'Add Image', 'zona' ),
				'msg' => esc_html__( 'Currently you don\'t have image, you can add one by clicking on the button below.', 'zona' ),
				'desc' => esc_html__( 'Add Google Map Marker (48px x 56px).', 'zona' )
			),

			
		array( 
			'type' => 'sub_close'
		),
		
	
	array( 
		'type' => 'close'
	),


	/* Fonts
	-------------------------------------------------------- */
	array( 
		'type' => 'open',
		'tab_name' => esc_html__( 'Fonts', 'zona' ),
		'tab_id' => 'fonts',
		'icon' => 'font'
	),

		/* Google fonts
		 -------------------------------------------------------- */
		array(
			'type' => 'sub_open',
			'sub_tab_name' => esc_html__( 'Google Web Fonts', 'zona' ),
			'sub_tab_id' => 'sub-google-fonts',
		),
			array(
				'name' => esc_html__( 'Google Fonts', 'zona' ),
				'id' => 'use_google_fonts',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'on',
				'desc' => esc_html__( 'When this option is enabled, the text elements will be automatically replaced with the Google Web Fonts.', 'zona' ),
			),
			array(
				'name' => esc_html__( 'Google Font Code', 'zona' ),
				'id' => 'google_fonts',
				'type' => 'textarea',
				'std' => esc_html__( 'Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&subset=latin-ext', 'zona' ),
				'tinymce' => 'false',
				'height' => '50',
				'desc' => esc_html__( 'Add Google Fonts family.', 'zona' ),
				'dependency' => array(
			        "element" => 'use_google_fonts',
			        "value" => array( 'on' )
			    )
			),
		array(
			'type' => 'sub_close'
		),
		

	array( 
		'type' => 'close'
	),


	/* Sections
	-------------------------------------------------------- */
	array( 
		'type' => 'open',
		'tab_name' => esc_html__( 'Sections', 'zona' ),
		'tab_id' => 'plugins',
		'icon' => 'th-large'
	),	

		array(
			'type' => 'sub_open',
			'sub_tab_name' => esc_html__( 'Music', 'zona' ),
			'sub_tab_id' => 'sub-sections-music',
		),

			// Enable Scamp Player
			array(
				'name' => esc_html__( 'Music Player', 'zona' ),
				'id' => 'scamp_player',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'on',
				'desc' => esc_html__( 'Enable music player. NOTE: Zona Plugin must be instaled and activated.', 'zona' ),
			),

			// Autoplay
			array(
				'name' => esc_html__( 'Autoplay', 'zona' ),
				'id' => 'player_autoplay',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => esc_html__( 'Autoplay tracklist. NOTE: Autoplay does not work on mobile devices.', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),
			
			// Load first track
			array(
				'name' => esc_html__( 'Load First Track', 'zona' ),
				'id' => 'load_first_track',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => esc_html__( 'Load first track from tracklist after load list.', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Startup Tracklist
			array( 
				'name' => esc_html__( 'Startup Tracklist', 'zona' ),
				'id' => 'startup_tracklist',
				'type' => 'posts',
				'post_type' => 'zona_tracks',
				'std' => 'none',
				'options' => array(
				   	array( 'name' => '', 'value' => 'none' ),
				),
				'desc' => esc_html__( 'Select startup tracklist.', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Show Player
			array(
				'name' => esc_html__( 'Show Player on Startup', 'zona' ),
				'id' => 'show_player',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => esc_html__( 'Show player on startup.', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Show Tracklist
			array(
				'name' => esc_html__( 'Show Tracklist on Startup', 'zona' ),
				'id' => 'show_tracklist',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => esc_html__( 'Show playlist on startup.', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Random Tracks
			array(
				'name' => esc_html__( 'Random Play', 'zona' ),
				'id' => 'player_random',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => esc_html__( 'Random play tracks.', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Loop Tracks
			array(
				'name' => esc_html__( 'Loop Tracklist', 'zona' ),
				'id' => 'player_loop',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => esc_html__( 'Loop tracklist.', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Titlebar
			array(
				'name' => esc_html__( 'Change Titlebar', 'zona' ),
				'id' => 'player_titlebar',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => esc_html__( 'Replace browser titlebar on track title.', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Player Skin
			array( 
				'name' => esc_html__( 'Player Skin', 'zona' ),
				'id' => 'player_skin',
				'type' => 'select',
				'std' => 'dark',
				'desc' => esc_html__( 'Select player skin.', 'zona' ),
				'options' => array( 
					array( 'name' => 'Light', 'value' => 'light'),
					array( 'name' => 'Dark', 'value' => 'dark')
				),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Smooth Transitions
			array(
				'name' => esc_html__( 'Smooth Transition', 'zona' ),
				'id' => 'smooth_transition',
				'type' => 'switch_button',
				'std' => 'on',
				'desc' => esc_html__( 'Smooth "fade up" and "fade down" effect. ', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Soundcloud Client ID
			array(
				'name' => esc_html__( 'Soundcloud Client ID', 'zona' ),
				'id' => 'soundcloud_id',
				'type' => 'text',
				'std' => '',
				'desc' => esc_html__( 'Add your Soundcloud ID', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Shoutcast Radio
			array(
				'name' => esc_html__( 'Shoutcast Radio', 'zona' ),
				'id' => 'shoutcast',
				'type' => 'switch_button',
				'std' => 'on',
				'desc' => esc_html__( 'Shoutcast support.', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),

			// Shoutcast Refresh Time
			array(
				'name' => esc_html__( 'Shoutcast Refresh Time (ms)', 'zona' ),
				'id' => 'shoutcast_interval',
				'type' => 'text',
				'std' => '20000',
				'desc' => esc_html__( 'Enter refresh time in ms. (20000 = 20s)', 'zona' ),
				'dependency' => array(
			        "element" => 'shoutcast',
			        "value" => array( 'on' )
			    )
			),

			// Startup Volume
			array( 
				'name' => esc_html__( 'Startup Volume', 'zona' ),
				'id' => 'player_volume',
				'type' => 'range',
				'plugins' => array( 'range' ),
				'min' => 0,
				'max' => 100,
				'unit' => '',
				'std' => '70',
				'desc' => esc_html__( 'Set startup volume.', 'zona' ),
				'dependency' => array(
			        "element" => 'scamp_player',
			        "value" => array( 'on' )
			    )
			),
			
		array(
			'type' => 'sub_close'
		),


		/* Comments
		 -------------------------------------------------------- */
		array(
			'type' => 'sub_open',
			'sub_tab_name' => esc_html__( 'Comments', 'zona' ),
			'sub_tab_id' => 'sub-sections-comments'
		),

			// Custom Comments Date
			array(
				'name' => esc_html__( 'Comment Date Format', 'zona' ),
				'id' => 'custom_comment_date',
				'type' => 'text',
				'std' => 'F j, Y (H:i)',
				'desc' => esc_html__( 'Enter your custom comment date.', 'zona' )
			),

			array(
				'name' => esc_html__( 'DISQUS Comments', 'zona' ),
				'id' => 'disqus_comments',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => esc_html__( 'Enable DISQUS comments. Replace default Wordpress comments.', 'zona' ),
			),

			// Disqus ID
			array(
				'name' => esc_html__( 'DISQUS Shortname', 'zona' ),
				'id' => 'disqus_shortname',
				'type' => 'text',
				'std' => '',
				'desc' => esc_html__( 'Enter DISQUS Website\'s Shortname.', 'zona' ),
				'dependency' => array(
					'element' => 'disqus_comments',
					'value' => array( 'on' )
				)
			),

		array(
			'type' => 'sub_close'
		),
		
		/* Events
		 -------------------------------------------------------- */
		array(
			'type' => 'sub_open',
			'sub_tab_name' => esc_html__( 'Events', 'zona' ),
			'sub_tab_id' => 'sub-section-events',
		),	

			// Custom Event Date
			array(
				'name' => esc_html__( 'Event Date Format', 'zona' ),
				'id' => 'event_date',
				'type' => 'text',
				'std' => 'd-m',
				'desc' => esc_html__( 'Enter your custom event date.', 'zona' )
			),

			// Custom Time
			array(
				'name' => esc_html__( 'Event Time Format', 'zona' ),
				'id' => 'event_time',
				'type' => 'text',
				'std' => 'g:i A',
				'desc' => esc_html__( 'Enter your custom event time e.g: H:i. If time field isn\'t empty the then the time is displayed after event date.', 'zona' )
			),
			
		array(
			'type' => 'sub_close'
		),
		

		/* Permalinks
		 -------------------------------------------------------- */
		array(
			'type' => 'sub_open',
			'sub_tab_name' => esc_html__( 'Permalinks', 'zona' ),
			'sub_tab_id' => 'sub-section-permalinks',
		),	


			// Music
			array(
				'name' => esc_html__( 'Music Slug', 'zona' ),
				'id' => 'music_slug',
				'type' => 'text',
				'std' => 'music',
				'desc' => esc_html__( 'Enter post slug name. No special characters. No spaces. IMPORTANT: When you change post slug name, you have to go to: WordPress Settings > Permalinks and save settings.', 'zona' )
			),
			array(
				'name' => esc_html__( 'Music Categories Slug', 'zona' ),
				'id' => 'music_cat_slug',
				'type' => 'text',
				'std' => 'music-category',
				'desc' => esc_html__( 'Enter post slug name. No special characters. No spaces. IMPORTANT: When you change post slug name, you have to go to: WordPress Settings > Permalinks and save settings.', 'zona' )
			),


			// Events
			array(
				'name' => esc_html__( 'Events Slug', 'zona' ),
				'id' => 'events_slug',
				'type' => 'text',
				'std' => 'events',
				'desc' => esc_html__( 'Enter post slug name. No special characters. No spaces. IMPORTANT: When you change post slug name, you have to go to: WordPress Settings > Permalinks and save settings.', 'zona' )
			),
			array(
				'name' => esc_html__( 'Events Categories Slug', 'zona' ),
				'id' => 'events_cat_slug',
				'type' => 'text',
				'std' => 'event-category',
				'desc' => esc_html__( 'Enter post slug name. No special characters. No spaces. IMPORTANT: When you change post slug name, you have to go to: WordPress Settings > Permalinks and save settings.', 'zona' )
			),


			// Gallery
			array(
				'name' => esc_html__( 'Gallery Slug', 'zona' ),
				'id' => 'gallery_slug',
				'type' => 'text',
				'std' => 'gallery',
				'desc' => esc_html__( 'Enter post slug name. No special characters. No spaces. IMPORTANT: When you change post slug name, you have to go to: WordPress Settings > Permalinks and save settings.', 'zona' )
			),
			array(
				'name' => esc_html__( 'Gallery Categories Slug', 'zona' ),
				'id' => 'gallery_cat_slug',
				'type' => 'text',
				'std' => 'gallery-category',
				'desc' => esc_html__( 'Enter post slug name. No special characters. No spaces. IMPORTANT: When you change post slug name, you have to go to: WordPress Settings > Permalinks and save settings.', 'zona' )
			),

			// Videos
			array(
				'name' => esc_html__( 'Video Slug', 'zona' ),
				'id' => 'videos_slug',
				'type' => 'text',
				'std' => 'video',
				'desc' => esc_html__( 'Enter post slug name. No special characters. No spaces. IMPORTANT: When you change post slug name, you have to go to: WordPress Settings > Permalinks and save settings.', 'zona' )
			),
			array(
				'name' => esc_html__( 'Videos Categories Slug', 'zona' ),
				'id' => 'videos_cat_slug',
				'type' => 'text',
				'std' => 'videos-category',
				'desc' => esc_html__( 'Enter post slug name. No special characters. No spaces. IMPORTANT: When you change post slug name, you have to go to: WordPress Settings > Permalinks and save settings.', 'zona' )
			),

		array(
			'type' => 'sub_close'
		),

	array( 
		'type' => 'close'
	),


	/* Advanced
	-------------------------------------------------------- */
	array( 
		'type' => 'open',
		'tab_name' => esc_html__( 'Advanced', 'zona' ),
		'tab_id' => 'advanced',
		'icon' => 'wrench'
	),

		/* Ajax
		 -------------------------------------------------------- */
		array(
			'type' => 'sub_open',
			'sub_tab_name' => esc_html__( 'Ajax', 'zona' ),
			'sub_tab_id' => 'sub-ajax'
		),

			// Ajax
			array( 
				'name' => esc_html__( 'Ajax Load', 'zona' ),
				'id' => 'ajaxed',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'on',
				'desc' => esc_html__( 'Enable if you want ajax loading.', 'zona' ),
			),

			// Ajax Async
			array(
				'name' => esc_html__( 'Asynchronous', 'zona' ),
				'id' => 'ajax_async',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'on',
				'desc' => esc_html__( 'Asynchronous AJAX.', 'zona' ),
				'dependency' => array(
			        "element" => 'ajaxed',
			        "value" => array( 'on' )
			    )
			),

			// Ajax Cache
			array(
				'name' => esc_html__( 'Ajax Cache', 'zona' ),
				'id' => 'ajax_cache',
				'type' => 'switch_button',
				'plugins' => array( 'switch_button' ),
				'std' => 'off',
				'desc' => esc_html__( 'AJAX Cache.', 'zona' ),
				'dependency' => array(
			        "element" => 'ajaxed',
			        "value" => array( 'on' )
			    )
			),

			// Ajax Elements
			array( 
				'name' => esc_html__( 'AJAX Filter', 'zona' ),
				'id' => 'ajax_elements',
				'type' => 'textarea',
				'tinymce' => 'false',
				'std' => '',
				'height' => '120',
				'desc' => esc_html__( 'Add selectors (Note: divide links with linebreaks (Enter)). These elements will not be processed by AJAX.', 'zona' ),
				'dependency' => array(
			        "element" => 'ajaxed',
			        "value" => array( 'on' )
			    )
			),

			// Ajax reload scripts
			array( 
				'name' => esc_html__( 'AJAX Reload Scripts', 'zona' ),
				'id' => 'ajax_reload_scripts',
				'type' => 'textarea',
				'tinymce' => 'false',
				'std' => '',
				'height' => '120',
				'desc' => esc_html__( 'Add strings for reloaded scripts (Note: divide links with linebreaks (Enter)). These scripts will be reloaded after page loaded by AJAX.', 'zona' ),
				'dependency' => array(
			        "element" => 'ajaxed',
			        "value" => array( 'on' )
			    )
			),

			// Ajax reload containers
			array( 
				'name' => esc_html__( 'AJAX Reload Containers', 'zona' ),
				'id' => 'ajax_reload_containers',
				'type' => 'textarea',
				'tinymce' => 'false',
				'std' => '',
				'height' => '120',
				'desc' => esc_html__( 'Add strings for reloaded containers (Note: divide links with linebreaks (Enter)). These containers will be reloaded after page loaded by AJAX.', 'zona' ),
				'dependency' => array(
			        "element" => 'ajaxed',
			        "value" => array( 'on' )
			    )
			),
			// Ajax exclude links
			array( 
				'name' => esc_html__( 'AJAX Exclude Links', 'zona' ),
				'id' => 'ajax_exclude_links',
				'type' => 'textarea',
				'tinymce' => 'false',
				'std' => '',
				'height' => '120',
				'desc' => esc_html__( 'Add links that should be excluded from AJAX loader (Note: divide links with linebreaks (Enter)). These pages will not be loaded by AJAX.', 'zona' ),
				'dependency' => array(
			        "element" => 'ajaxed',
			        "value" => array( 'on' )
			    )
			),
			// Ajax events
			array( 
				'name' => esc_html__( 'AJAX Events', 'zona' ),
				'id' => 'ajax_events',
				'type' => 'textarea',
				'tinymce' => 'false',
				'std' => '',
				'height' => '120',
				'desc' => esc_html__( 'Add Javascript events (Note: divide links with linebreaks (Enter)). These events will be removed after page page by AJAX.', 'zona' ),
				'dependency' => array(
			        "element" => 'ajaxed',
			        "value" => array( 'on' )
			    )
			),
		array(
			'type' => 'sub_close'
		),
		

		/* Import and export
		 -------------------------------------------------------- */
		array( 
			'type' => 'sub_open',
			'sub_tab_name' => esc_html__( 'Import/Export', 'zona' ),
			'sub_tab_id' => 'sub-import'
		),
			array( 
				'type' => 'export'
			),
			array( 
				'type' => 'import'
			),
		array( 
			'type' => 'sub_close'
		),

	array( 
		'type' => 'close'
	),


	/* Hidden fields
	 -------------------------------------------------------- */
	array( 
		'type' => 'hidden_field',
		'id' => 'theme_name',
		'value' => 'zona'
	),
	
);



/* init Panel
 ------------------------------------------------------------------------*/

/* Class arguments */
$args = array(
	'admin_path'  => '',
	'admin_uri'	 => '',
	'panel_logo' => '',
	'menu_name' => esc_html__( 'Theme Options', 'zona' ), 
	'page_name' => 'panel-main',
	'option_name' => 'zona_panel_opts',
	'admin_dir' => '/admin',
	'menu_icon' => '',
	'textdomain' => 'zona'
	);

/* Add class instance */
$main_panel = new MuttleyPanel( $args, $zona_main_options );

function zona_opts(){
   global $main_panel;
   
   return $main_panel;
}

?>