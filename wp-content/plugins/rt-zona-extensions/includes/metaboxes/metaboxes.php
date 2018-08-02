<?php
/**
 * Plugin Name: 	Zona Extensions
 * Theme Author: 	Mariusz Rek - Rascals Themes
 * Theme URI: 		http://rascalsthemes.com/zona
 * Author URI: 		http://rascalsthemes.com
 * File:			metaboxes.php
 * =========================================================================================================================================
 *
 * @package zona-extensions
 * @since 1.0.0
 */

/* ----------------------------------------------------------------------
	INIT CLASS
/* ---------------------------------------------------------------------- */
$panel_options = get_option( 'zona_panel_opts' );

if ( ! class_exists( 'MuttleyBox' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'classes/MuttleyBox.php' );
}

global $wpdb;


/* ----------------------------------------------------------------------
	HELPERS
/* ---------------------------------------------------------------------- */

/* Get post/page data */
if ( isset( $_GET['post'] ) ) { 
	$template_name = get_post_meta( $_GET['post'], '_wp_page_template', true );
	$post_type = get_post_type( $_GET['post'] );
	$post_format = get_post_format( $_GET['post'] );
} else { 
	$template_name = '';
	$post_type = '';
	$post_format = '';
}

if ( isset( $_GET['post_type'] ) )  { 
	$post_type = $_GET['post_type'];
}

/* Post per page */
$pp = get_option( 'posts_per_page' );

// Header Slider
$intro_slider = array( array( 'name' => esc_html__( 'Select slider...', 'zona_plugin' ), 'value' => 'none' ) );
$slider_post_type = 'zona_slider';
$slider_query = $wpdb->prepare(
	"
    SELECT
		{$wpdb->posts}.id,
		{$wpdb->posts}.post_title
  	FROM 
		{$wpdb->posts}
  	WHERE
		{$wpdb->posts}.post_type = %s
	AND 
		{$wpdb->posts}.post_status = 'publish'
	",
	$slider_post_type
);

$sql_slider = $wpdb->get_results( $slider_query );
  
if ( $sql_slider ) {
	$count = 1;
	foreach( $sql_slider as $slider_post ) {
		$intro_slider[$count]['name'] = $slider_post->post_title;
		$intro_slider[$count]['value'] = $slider_post->id;
		$count++;
	}
}

/* Get Audio Tracks  */
$tracks = array( array( 'name' => esc_html__( 'Select tracks...', 'zona_plugin' ), 'value' => 'none' ) );
$tracks_post_type = 'zona_tracks';
$tracks_query = $wpdb->prepare(
	"
    SELECT
		{$wpdb->posts}.id,
		{$wpdb->posts}.post_title
  	FROM 
		{$wpdb->posts}
  	WHERE
		{$wpdb->posts}.post_type = %s
	AND 
		{$wpdb->posts}.post_status = 'publish'
	",
	$tracks_post_type
);

$sql_tracks = $wpdb->get_results( $tracks_query );
  
if ( $sql_tracks ) {
	$count = 1;
	foreach( $sql_tracks as $track_post ) {
		$tracks[$count]['name'] = $track_post->post_title;
		$tracks[$count]['value'] = $track_post->id;
		$count++;
	}
}

// Revslider Slider
$intro_revslider = array( array( 'name' => esc_html__( 'Select slider...', 'zona_plugin' ), 'value' => 'none' ) );
$revslider_post_type = strtolower( $wpdb->prefix ) . 'revslider_sliders';
$sql_revslider = array();
if ( $wpdb->get_var( "SHOW TABLES LIKE '$revslider_post_type'" ) == $revslider_post_type  ) {
	$slides = $wpdb->get_results("SELECT title as name ,id as value FROM $revslider_post_type", ARRAY_A);
	$sql_revslider = $wpdb->get_results( 
	   	$wpdb->prepare( 
	       "
	       SELECT
	       		title as %s,
	       		id as value
	       FROM
	       		{$revslider_post_type}
	       ", "name"
	    ), ARRAY_A );
	if ( $sql_revslider ) {
		array_splice( $sql_revslider, 0, 0, $intro_revslider);
	}
}


/* ----------------------------------------------------------------------
	INTRO HEADER
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Intro Header Options', 'zona_plugin' ), 
	'id' =>'r_intro_options', 
	'page' => array(
		'page',
		'zona_music',
		'zona_events',
		'zona_gallery'
	), 
	'context' => 'normal',
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'post', 
		'default',
		'page-templates/blog-grid.php',
		'page-templates/blog.php',
		'page-templates/music.php',
		'page-templates/music-float.php',
		'page-templates/events.php',
		'page-templates/events-float.php',
		'page-templates/gallery.php',
		'page-templates/gallery-float.php',
		'page-templates/videos.php',
		'page-templates/videos-float.php',
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);	

// Header type
$intro_type = array(
	array( 'name' => esc_html__( 'Simple Page Title', 'zona_plugin' ), 'value' => 'simple_page_title' ),
	array( 'name' => esc_html__( 'Page Title (With Image background)', 'zona_plugin' ), 'value' => 'page_title' ),
	array( 'name' => esc_html__( 'Images Slider', 'zona_plugin' ), 'value' => 'slider' ),
	array( 'name' => esc_html__( 'Disabled', 'zona_plugin' ), 'value' => 'disabled' ),
);

if ( $template_name == 'page-templates/blog-grid.php' || $template_name == 'page-templates/blog.php') {
	array_unshift( $intro_type , array( 'name' => esc_html__( 'Featured Articles Slider (ONLY FOR BLOG)', 'zona_plugin' ), 'value' => 'featured_slider' ) );
}

if ( $post_type == 'zona_music' ) {
	array_unshift( $intro_type , array( 'name' => esc_html__( 'Music - Cover Image with tracklist', 'zona_plugin' ), 'value' => 'music' ) );
}

if ( $post_type == 'zona_events' ) {
	array_unshift( $intro_type , array( 'name' => esc_html__( 'Event - Event Image with details', 'zona_plugin' ), 'value' => 'event' ) );
}

if ( in_array( 'revslider/revslider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	array_unshift( $intro_type , array( 'name' => esc_html__( 'Revolution Slider', 'zona_plugin' ), 'value' => 'revslider' ) );
}

/* Meta options */
$meta_options = array(
	array(
		'name' => esc_html__( 'Intro Header Type', 'zona_plugin' ),
		'id' => '_intro_type',
		'type' => 'select',
		'std' => 'disabled',
	  	'options' => $intro_type,
		'desc' => ''
	),

	// Slider
	array( 
		'name' => esc_html__( 'Slides', 'zona_plugin' ),
		'id' => '_intro_slider',
		'type' => 'media_manager',
		'media_type' => 'slider', // images / audio / slider
		'msg_text' => esc_html__( 'Currently you don\'t have any images, you can add them by clicking on the button below.', 'zona_plugin' ),
		'btn_text' => esc_html__( 'Add Images', 'zona_plugin' ),
		'desc' => esc_html__( 'Add images to slider.', 'zona_plugin' ) . '<br>' . esc_html__( 'NOTE: Please use the CTRL key (PC) or COMMAND key (Mac) to select multiple items.', 'zona_plugin' ),
		'dependency' => array(
	        "element" => '_intro_type',
	        "value" => array( 'slider' )
	    )
	),

	// Featured
	array(
		'name' => esc_html__( 'Featured Category', 'zona_plugin' ),
		'id' => '_featured_cat',
		'type' => 'taxonomy',
		'taxonomy' => 'category',
		'desc' => esc_html__( 'Posts will be displayed from selected category (featured category). Create category and add featured posts.', 'zona_plugin' ),
		'dependency' => array(
	        "element" => '_intro_type',
	        "value" => array( 'featured_slider' )
	    )
	),

	// Featured Limit
	array(
		'name' => esc_html__( 'Posts Limit', 'zona_plugin' ),
		'id' => '_featured_limit',
		'type' => 'range',
		'min' => 2,
		'max' => 10,
		'unit' => esc_html__( 'posts', 'zona_plugin' ),
		'std' => '3',
		'desc' => esc_html__( 'Number of posts visible on slider.', 'zona_plugin' ),
		'dependency' => array(
	        "element" => '_intro_type',
	        "value" => array( 'featured_slider' )
	    )
	),
	array(
		'name' => esc_html__( 'Image', 'zona_plugin' ),
		'id' => '_image',
		'type' => 'add_image',
		'source' => 'media_libary', // all, media_libary, external_link
		'desc' => esc_html__( 'Intro image.', 'zona_plugin' ),
		'dependency' => array(
	        "element" => '_intro_type',
	        "value" => array( 'page_title','music', 'event' )
	    )
	),
	array(
		'name' => esc_html__( 'Overlay', 'zona_plugin' ),
		'id' => '_overlay',
		'type' => 'select',
		'std' => 'disabled',
		'options' => array(
			array( 'name' => esc_html__( 'Disabled', 'zona_plugin' ), 'value' => 'disabled' ),
			array( 'name' => esc_html__( 'Dots Dark', 'zona_plugin' ), 'value' => 'dots-dark' ),
			array( 'name' => esc_html__( 'Dots Light', 'zona_plugin' ), 'value' => 'dots-light' ),
		),
		'desc' => esc_html__( 'Select overlay type.', 'zona_plugin' ),
		'dependency' => array(
	        "element" => '_intro_type',
	        "value" => array( 'page_title','music', 'event' )
	    )
	),
	array(
		'name' => esc_html__( 'Image Opacity', 'zona_plugin' ),
		'id' => '_opacity',
		'type' => 'range',
		'min' => 0,
		'max' => 100,
		'unit' => esc_html__( '%', 'zona_plugin' ),
		'std' => '100',
		'desc' => esc_html__( 'Set image opacity.', 'zona_plugin' ),
		'dependency' => array(
	        "element" => '_intro_type',
	        "value" => array( 'page_title', 'featured_slider', 'music', 'event','slider' )
	    )
	),
	array(
		'name' => esc_html__( 'Height', 'zona_plugin' ),
		'id' => '_min_height',
		'type' => 'range',
		'min' => 100,
		'max' => 1200,
		'unit' => esc_html__( 'px', 'zona_plugin' ),
		'std' => '400',
		'desc' => esc_html__( 'Header section min. height.', 'zona_plugin' ),
		'dependency' => array(
	        "element" => '_intro_type',
	        "value" => array( 'page_title', 'featured_slider', 'slider' )
	    )
	),
	array(
		'name' => esc_html__( 'Image Effect', 'zona_plugin' ),
		'id' => '_image_effect',
		'type' => 'select',
		'std' => 'disabled',
		'options' => array(
			array( 'name' => esc_html__( 'Disabled', 'zona_plugin' ), 'value' => 'disabled' ),
			array( 'name' => esc_html__( 'Parallax', 'zona_plugin' ), 'value' => 'parallax' )
		),
		'desc' => esc_html__( 'Select Image effect.', 'zona_plugin' ),
		'dependency' => array(
	        "element" => '_intro_type',
	        "value" => array( 'page_title', 'featured_slider', 'music', 'event', 'slider' )
	    )

	),
	array(
		'name' => esc_html__( 'Image Filters', 'zona_plugin' ),
		'id' => '_image_filter',
		'type' => 'select',
		'std' => 'image--filter-none',
		'options' => array(
			array( 'name' => esc_html__( 'None', 'zona_plugin' ), 'value' => 'image--filter-none' ),
			array( 'name' => esc_html__( 'Blur', 'zona_plugin' ), 'value' => 'image--filter-blur' ),
			array( 'name' => esc_html__( 'Grayscale', 'zona_plugin' ), 'value' => 'image--filter-grayscale' ),
			array( 'name' => esc_html__( 'Grayscale+Blur', 'zona_plugin' ), 'value' => 'image--filter-mix' ),
		),
		'desc' => esc_html__( 'Select Image filter. Please note: CSS Filters may not work on older browsers.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'page_title', 'featured_slider', 'music', 'event', 'slider' )
		)
	),
	array(
		'name' => esc_html__( 'Disable Page Title', 'zona_plugin' ),
		'id' => '_disable_title',
		'type' => 'switch_button',
		'std' => 'off',
		'desc' => esc_html__( 'Hide title and subtitle.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'page_title' )
		)
	),
	array(
		'name' => esc_html__( 'Custom Title', 'zona_plugin' ),
		'id' => '_custom_title',
		'type' => 'textarea',
		'tinymce' => 'false',
		'std' => '',
		'height' => '40',
		'desc' => esc_html__( 'Replace page title.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'page_title' )
		)
	),
	array(
		'name' => esc_html__( 'Subtitle', 'zona_plugin' ),
		'id' => '_subtitle',
		'type' => 'textarea',
		'tinymce' => 'false',
		'std' => '',
		'height' => '40',
		'desc' => esc_html__( 'Add subtitle.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'page_title' )
		)
	),

	// Music
	array(
		'name' => esc_html__( 'Music Tracks', 'zona_plugin' ),
		'id' => '_intro_music_tracks',
		'type' => 'select_array',
		'options' => $tracks,
		'std' => '',
		'desc' => esc_html__( 'Select your tracks/track. If there are no tracks available, then you can add a audio tracks using TRACKS custom posts menu on the left.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'music' )
		)
	),

	// Music Buttons
	array(
		'name' => esc_html__( 'Buttons', 'zona_plugin' ),
		'id' => '_intro_music_buttons',
		'type' => 'textarea',
		'tinymce' => 'false',
		'std' => '',
		'height' => '100',
		'desc' => esc_html__( 'Add custom buttons.(Note: divide links with linebreaks (Enter)) : type|http://yourlink|target|custom_image_link. ex:', 'zona_plugin' ) . '<br><pre><code>amazon|http://amazon.com|_blank
itunes|http://itunes.com|_blank
google_play|https://play.google.com|_blank
bandcamp|http://bandcamp.com|_blank
amazon-music-stream|http://amazon.com|_blank
apple-music|https://www.apple.com/music|_blank
deezer|https://deezer.com|_blank
napster|https://napster.com|_blank
pandora|https://pandora.com|_blank
spotify|https://spotify.com|_blank
tidal|https://tidal.com|_blank
soundcloud|http://soundcloud.com|_blank</code></pre>',
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'music' )
		)
	),

	// Event Buttons
	array(
		'name' => esc_html__( 'Tickets Buttons', 'zona_plugin' ),
		'id' => '_intro_tickets_buttons',
		'type' => 'textarea',
		'tinymce' => 'false',
		'std' => '',
		'height' => '100',
		'desc' => esc_html__( 'Add custom tickets buttons.(Note: divide links with linebreaks (Enter)) : title|http://yourlink|target. ex:', 'zona_plugin' ) . '<br><pre><code>Eventix|#|_blank
Events Pro|#|_blank
</code></pre>',
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'event' )
		)
	),
	array(
		'name' => esc_html__( 'Display Event Content', 'zona_plugin' ),
		'id' => '_display_event_content',
		'type' => 'switch_button',
		'std' => 'on',
		'desc' => esc_html__( 'Display event content: image with short description .', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'event' )
		)
	),
	array(
		'name' => esc_html__( 'Event Content', 'zona_plugin' ),
		'id' => '_event_content',
		'type' => 'textarea',
		'tinymce' => 'true',
		'std' => '',
		'height' => '100',
		'desc' => esc_html__( 'Event content.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'event' )
		)
	),

	// Slider
	array(
		'name' => esc_html__( 'Navigation', 'zona_plugin' ),
		'id' => '_slider_nav',
		'type' => 'switch_button',
		'std' => 'on',
		'desc' => esc_html__( 'If this opion is on, then you should see the slider navigation.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'slider', 'featured_slider' )
		)
	),
	array(
		'name' => esc_html__( 'Pagination', 'zona_plugin' ),
		'std' => 'on',
		'type' => 'switch_button',
		'id' => '_slider_pagination',
		'desc' => esc_html__( 'If this opion is on, then you should see the slider pagination.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'slider', 'featured_slider' )
		)

	),
	array(
		'name' => esc_html__( 'Pause Time', 'zona_plugin' ),
		'id' => '_slider_pause_time',
		'type' => 'range',
		'min' => 0,
		'max' => 20000,
		'unit' => 'ms',
		'std' => '3000',
		'desc' => esc_html__( 'Determines how long each slide will be shown.  NOTE: Value "0" disable slider timer. Timer is disabled if slider has video background.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'slider', 'featured_slider' )
		)
	),

	// Rev Slider
	array(
		'name' => esc_html__( 'Revolution Slider', 'zona_plugin' ),
		'id' => '_revslider_id',
		'type' => 'select_array',
		'options' => $sql_revslider,
		'std' => '',
		'desc' => esc_html__( 'Select your Revo Slider.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_intro_type',
		    "value" => array( 'revslider' )
		)
	),

);

/* Add class instance */
$intro_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	PAGE LAYOUT
/* ---------------------------------------------------------------------- */

/* Sidebars Array */
if ( isset( $panel_options[ 'custom_sidebars' ] ) ) {
	$s_list = $panel_options[ 'custom_sidebars' ];
} else {
	$s_list = null;
}

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Layout Options', 'zona_plugin' ), 
	'id' =>'r_page_layout_options', 
	'page' => array(
		'page',
		'zona_music',
		'zona_events'
	), 
	'context' => 'side', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'default'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
	array(
		'name' => esc_html__( 'Page Layout', 'zona_plugin' ),
		'id' => '_page_layout',
		'type' => 'select_image',
		'std' => 'narrow',
		'images' => array(
			array( 'id' => 'narrow', 'image' => plugin_dir_url( __FILE__ ) .  'assets/images/icons/thin.png'),
			array( 'id' => 'wide', 'image' => plugin_dir_url( __FILE__ ) .  'assets/images/icons/wide.png'),
			array( 'id' => 'vc', 'image' => plugin_dir_url( __FILE__ ) .  'assets/images/icons/vc.png')
		),
		'desc' => esc_html__( 'Choose the page layout.', 'zona_plugin' )
	)
);

/* Add class instance */
$page_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	Single Post Options
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Post Options', 'zona_plugin' ), 
	'id' =>'r_post_options', 
	'page' => array(
		'post'
	), 
	'context' => 'side', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'default'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
	array(
		'name' => esc_html__( 'Header Type', 'zona_plugin' ),
		'id' => '_post_header_type',
		'type' => 'select',
		'std' => 'post_featured_image',
		'options' => array(
			array( 'name' => esc_html__( 'Featured Image', 'zona_plugin' ), 'value' => 'post_featured_image' ),
			array( 'name' => esc_html__( 'Custom Image', 'zona_plugin' ), 'value' => 'post_custom_image' ),
			array( 'name' => esc_html__( 'Video', 'zona_plugin' ), 'value' => 'post_video' ),
			array( 'name' => esc_html__( 'Soundcloud', 'zona_plugin' ), 'value' => 'post_audio_sc' ),
			array( 'name' => esc_html__( 'Youtube', 'zona_plugin' ), 'value' => 'post_yt' ),
			array( 'name' => esc_html__( 'Vimeo', 'zona_plugin' ), 'value' => 'post_vimeo' ),
			array( 'name' => esc_html__( 'Disabled', 'zona_plugin' ), 'value' => 'post_disabled' ),
		),
		'desc' => esc_html__( 'Select post header type.', 'zona_plugin' )
	),

	// Custom Image
	array(
		'name' => esc_html__( 'Image', 'zona_plugin' ),
		'id' => '_post_image',
		'type' => 'add_image',
		'source' => 'media_libary', // all, media_libary, external_link
		'desc' => esc_html__( 'Custom post header image.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_post_header_type',
		    "value" => array( 'post_custom_image' )
		)
	),

	// Audio Soundcloud
	array(
		'name' => esc_html__( 'Soundcloud', 'zona_plugin' ),
		'id' => '_sc_embed',
		'type' => 'textarea',
		'tinymce' => 'false',
		'std' => '',
		'height' => '100',
		'desc' => esc_html__( 'Paste iframe code from soundcloud track.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_post_header_type',
		    "value" => array( 'post_audio_sc' )
		)
	),
	// Youtube
	array(
		'name' => esc_html__( 'Youtube Link', 'zona_plugin' ),
		'id' => '_post_yt',
		'type' => 'text',
		'std' => '',
		'desc' => esc_html__( 'Paste Youtube link.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_post_header_type',
		    "value" => array( 'post_yt' )
		)
	),
	// Vimeo
	array(
		'name' => esc_html__( 'Vimeo Link', 'zona_plugin' ),
		'id' => '_post_vimeo',
		'type' => 'text',
		'std' => '',
		'desc' => esc_html__( 'Paste Vimeo link.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_post_header_type',
		    "value" => array( 'post_vimeo' )
		)
	),
);

/* Add class instance */
$page_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	BLOG GRID
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Blog Options', 'zona_plugin' ), 
	'id' =>'r_blog_options', 
	'page' => array(
		'page'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'page-templates/blog-grid.php'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);	


/* Meta options */
$meta_options = array(

	// Limit
	array(
		'name' => esc_html__( 'Blog Posts Per Page', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 100,
		'unit' => esc_html__( 'items', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of blog posts visible on page.', 'zona_plugin' )
	),

	// Columns
	array(
		'name' => esc_html__( 'Columns', 'zona_plugin' ),
		'id' => '_columns',
		'type' => 'select',
		'options' => array(
			array( 'name' => esc_html__( '2 Columns', 'zona_plugin' ), 'value' => 'grid-6' ),
			array( 'name' => esc_html__( '3 Columns', 'zona_plugin' ), 'value' => 'grid-4' )
		),
		'std' => 'cols--2',
		'desc' => esc_html__( 'Number of columns.', 'zona_plugin' )
	),

	// Display filter
	array(
		'name' => esc_html__( 'Display Featured Images', 'zona_plugin' ),
		'std' => 'off',
		'type' => 'switch_button',
		'id' => '_featured_images',
		'desc' => esc_html__( 'If this opion is on, then you should see featured image regardless of mouse-overs.', 'zona_plugin' )
	),

	// More posts
	array(
		'name' => esc_html__( 'Pagination Method', 'zona_plugin' ),
		'id' => '_pagination',
		'type' => 'select',
		'options' => array(
			array( 'name' => esc_html__( 'AJAX Loader', 'zona_plugin' ), 'value' => 'pagination-ajax' ),
			array( 'name' => esc_html__( 'Default Pagination', 'zona_plugin' ), 'value' => 'pagination-default' )
		),
		'std' => 'ajax',
		'desc' => esc_html__( 'Display AJAX filter or Ajax "Load More" button.', 'zona_plugin' )
	),

	// Display filter
	array(
		'name' => esc_html__( 'Display Ajax Filter', 'zona_plugin' ),
		'std' => 'on',
		'type' => 'switch_button',
		'id' => '_ajax_filter',
		'desc' => esc_html__( 'If this opion is on, then you should see category filter.', 'zona_plugin' )
	),

);

/* Add class instance */
$blog_grid_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	BLOG LIST
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Blog Options', 'zona_plugin' ), 
	'id' =>'r_blog_list_options', 
	'page' => array(
		'page'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'page-templates/blog.php'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);	


/* Meta options */
$meta_options = array(

	// Limit
	array(
		'name' => esc_html__( 'Blog Posts Per Page', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 100,
		'unit' => esc_html__( 'items', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of blog posts visible on page.', 'zona_plugin' )
	),

	// Display filter
	array(
		'name' => esc_html__( 'Display Featured Images', 'zona_plugin' ),
		'std' => 'off',
		'type' => 'switch_button',
		'id' => '_featured_images',
		'desc' => esc_html__( 'If this opion is on, then you should see featured image regardless of mouse-overs.', 'zona_plugin' )
	),

	
);

/* Add class instance */
$blog_list_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	MUSIC GRID
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Music Options', 'zona_plugin' ), 
	'id' =>'r_music_options', 
	'page' => array(
		'page'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'page-templates/music.php'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);	


/* Meta options */
$meta_options = array(

	// Limit
	array(
		'name' => esc_html__( 'Music Items Per Page', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 100,
		'unit' => esc_html__( 'items', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of music posts visible on page.', 'zona_plugin' )
	),

	// Columns
	array(
		'name' => esc_html__( 'Columns', 'zona_plugin' ),
		'id' => '_columns',
		'type' => 'select',
		'options' => array(
			array( 'name' => esc_html__( '2 Columns', 'zona_plugin' ), 'value' => 'grid-6' ),
			array( 'name' => esc_html__( '3 Columns', 'zona_plugin' ), 'value' => 'grid-4' )
		),
		'std' => 'cols--2',
		'desc' => esc_html__( 'Number of columns.', 'zona_plugin' )
	),

	// More posts
	array(
		'name' => esc_html__( 'Pagination Method', 'zona_plugin' ),
		'id' => '_pagination',
		'type' => 'select',
		'options' => array(
			array( 'name' => esc_html__( 'AJAX Loader', 'zona_plugin' ), 'value' => 'pagination-ajax' ),
			array( 'name' => esc_html__( 'Default Pagination', 'zona_plugin' ), 'value' => 'pagination-default' )
		),
		'std' => 'ajax',
		'desc' => esc_html__( 'Display default pagination (1,2,3...) or Ajax "Load more" button.', 'zona_plugin' )
	),

	// Display filter
	array(
		'name' => esc_html__( 'Display Ajax Filter', 'zona_plugin' ),
		'std' => 'on',
		'type' => 'switch_button',
		'id' => '_ajax_filter',
		'desc' => esc_html__( 'If this opion is on, then you should see category filter.', 'zona_plugin' )
	),

	// More posts
	array(
		'name' => esc_html__( 'Thumbnail Style', 'zona_plugin' ),
		'id' => '_thumb_style',
		'type' => 'select',
		'options' => array(
			array( 'name' => esc_html__( 'Simply - Images with links without icons and effects', 'zona_plugin' ), 'value' => 'simply' ),
			array( 'name' => esc_html__( 'Advanced - Images with icons', 'zona_plugin' ), 'value' => 'advanced' )
		),
		'std' => 'simply',
		'desc' => esc_html__( 'Select thumbnails style.', 'zona_plugin' )
	),

);

/* Add class instance */
$music_grid_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	MUSIC FLOAT
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Music Options', 'zona_plugin' ), 
	'id' =>'r_music_float_options', 
	'page' => array(
		'page'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'page-templates/music-float.php'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);	


/* Meta options */
$meta_options = array(

	// Limit
	array(
		'name' => esc_html__( 'Music Items Per Page', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 100,
		'unit' => esc_html__( 'items', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of music posts visible on page.', 'zona_plugin' )
	)

);

/* Add class instance */
$music_float_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	Single Music Post
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Music Post Options', 'zona_plugin' ), 
	'id' =>'r_music_post_options', 
	'page' => array(
		'zona_music'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'default'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
	array(
		'name' => esc_html__( 'Thumbnail Preview Tracks', 'zona_plugin' ),
		'id' => '_thumb_tracks',
		'type' => 'select_array',
		'options' => $tracks,
		'std' => '',
		'desc' => esc_html__( 'Select your tracks, then "Play" button will be visible. If there are no tracks available, then you can add a audio tracks using TRACKS custom posts menu on the left.', 'zona_plugin' )
	),
	// Display Link
	array(
		'name' => esc_html__( 'Disable Thumbnail Link', 'zona_plugin' ),
		'std' => 'off',
		'type' => 'switch_button',
		'id' => '_disable_link',
		'desc' => esc_html__( 'If this opion is on, then link to post will be disabled.', 'zona_plugin' )
	),


);

/* Add class instance */
$music_post_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	TRACKS - POST TYPE OPTIONS
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Playlist Options', 'zona_plugin'), 
	'id' =>'r_audio_options', 
	'page' => array(
		'zona_tracks'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'post'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
	array( 
		'name' => esc_html__( 'Music Tracks', 'zona_plugin' ),
		'id' => '_audio_tracks',
		'type' => 'media_manager',
		'media_type' => 'audio', // images / audio / slider
		'msg_text' => esc_html__( 'Currently you don\'t have any audio tracks, you can add them by clicking on the button below.', 'zona_plugin'),
		'btn_text' => esc_html__( 'Add Tracks', 'zona_plugin'),
		'desc' => esc_html__( 'Add audio tracks.', 'zona_plugin' ) . '<br>' . esc_html__( 'NOTE: Please use the CTRL key (PC) or COMMAND key (Mac) to select multiple items.', 'zona_plugin' )
	)
);

/* Add class instance */
$tracks_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	EVENTS - TEMPLATE OPTIONS
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Events Options', 'zona_plugin' ), 
	'id' =>'r_evens_list_options', 
	'page' => array(
		'page'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'page-templates/events.php'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
	
	// Type
	array(
		'name' => esc_html__( 'Events Type', 'zona_plugin' ),
		'id' => '_event_type',
		'type' => 'select',
		'std' => 'future-events',
		'options' => array(
			array('name' => esc_html__( 'Future events', 'zona_plugin' ), 'value' => 'future-events'),
			array('name' => esc_html__( 'Past events', 'zona_plugin' ), 'value' => 'past-events'),
			array('name' => esc_html__( 'All events', 'zona_plugin' ), 'value' => 'all-events')
		),
		'desc' => esc_html__( 'Choose the events type.', 'zona_plugin' )
	),

	// Limit
	array(
		'name' => esc_html__( 'Events Per Page', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 100,
		'unit' => esc_html__( 'events', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of events visible on page.', 'zona_plugin' )
	),

	// Pagination
	array(
		'name' => esc_html__( 'Pagination Method', 'zona_plugin' ),
		'id' => '_pagination',
		'type' => 'select',
		'options' => array(
			array( 'name' => esc_html__( 'AJAX Loader', 'zona_plugin' ), 'value' => 'pagination-ajax' ),
			array( 'name' => esc_html__( 'Default Pagination', 'zona_plugin' ), 'value' => 'pagination-default' )
		),
		'std' => 'ajax',
		'desc' => esc_html__( 'Display default pagination (1,2,3...) or Ajax "Load more" button.', 'zona_plugin' )
	),

	// Display filter
	array(
		'name' => esc_html__( 'Display Ajax Filter', 'zona_plugin' ),
		'std' => 'on',
		'type' => 'switch_button',
		'id' => '_ajax_filter',
		'desc' => esc_html__( 'If this opion is on, then you should see category filter.', 'zona_plugin' )
	),

	// Background Image
	array(
		'name' => esc_html__( 'Background Image.', 'zona_plugin' ),
		'id' => '_events_bg',
		'type' => 'add_image',
		'source' => 'media_libary', // all, media_libary, external_link
		'desc' => esc_html__( 'Add background image.', 'zona_plugin' ),
	),

);


/* Add class instance */
$event_list_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	EVENTS FLOAT - TEMPLATE OPTIONS
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Events Options', 'zona_plugin' ), 
	'id' =>'r_evens_list_options', 
	'page' => array(
		'page'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'page-templates/events-float.php'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
	
	// Type
	array(
		'name' => esc_html__( 'Events Type', 'zona_plugin' ),
		'id' => '_event_type',
		'type' => 'select',
		'std' => 'future-events',
		'options' => array(
			array('name' => esc_html__( 'Future events', 'zona_plugin' ), 'value' => 'future-events'),
			array('name' => esc_html__( 'Past events', 'zona_plugin' ), 'value' => 'past-events'),
			array('name' => esc_html__( 'All events', 'zona_plugin' ), 'value' => 'all-events')
		),
		'desc' => esc_html__( 'Choose the events type.', 'zona_plugin' )
	),
	// Limit
	array(
		'name' => esc_html__( 'Events Per Page', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 100,
		'unit' => esc_html__( 'events', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of events visible on page.', 'zona_plugin' )
	),

);


/* Add class instance */
$event_float_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );



/* ----------------------------------------------------------------------
	EVENTS DATE - POST TYPE OPTIONS
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Event Date', 'zona_plugin' ), 
	'id' =>'r_event_date_options', 
	'page' => array(
		'zona_events'
	), 
	'context' => 'side', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'post'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
					  
	array(
		'name' => esc_html__( 'Event Date', 'zona_plugin' ),
		'id' => array(
			array('id' => '_event_date_start', 'std' => date('Y-m-d')),
			array('id' => '_event_date_end', 'std' => date('Y-m-d'))
		),
		'type' => 'date_range',
		'desc' => esc_html__( 'Enter the event date; eg 2010-09-11', 'zona_plugin' )
	),
	// Display filter
	array(
		'name' => esc_html__( 'Display Event Time', 'zona_plugin' ),
		'std' => 'on',
		'type' => 'switch_button',
		'id' => '_event_time',
		'desc' => esc_html__( 'If this opion is on, then you should see category event time.', 'zona_plugin' )
	),
	array(
		'name' => esc_html__( 'Event Time', 'zona_plugin' ),
		'id' => array(
			array('id' => '_event_time_start', 'std' => '21:00'),
			array('id' => '_event_time_end', 'std' => '00:00')
		),
		'type' => 'time_range',
		'desc' => esc_html__( 'Enter the event time; eg 21:00 or 09:00 pm', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_event_time',
		    "value" => array( 'on' )
		)
	),
	array(
		'name' => esc_html__( 'Repeat', 'zona_plugin' ),
		'type' => 'select',
		'id' => '_repeat_event',
		'std' => 'default',
		'options' => array(
			array('name' => esc_html__( 'None', 'zona_plugin' ), 'value' => 'none'),
			array('name' => esc_html__( 'Weekly', 'zona_plugin' ), 'value' => 'weekly')
			//array('name' => esc_html__( 'Monthly', 'zona_plugin' ), 'value' => 'monthly'),
		),
		'desc' => esc_html__( 'Repeat event.', 'zona_plugin' )
	),
	array(
		'name' => esc_html__( 'Every', 'zona_plugin' ),
		'id' => '_every',
		'type' => 'range',
		'min' => 1,
		'max' => 52,
		'unit' => esc_html__( 'week(s)', 'zona_plugin' ),
		'std' => '1',
		'desc' => esc_html__( 'Repeat event every week(s).', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_repeat_event',
		    "value" => array( 'weekly' )
		)
	),
	array(
		'name' => esc_html__( 'Day(s)', 'zona_plugin' ),
		'id' => '_weekly_days',
		'type' => 'multiselect',
		'std' => array('friday'),
		'options' => array(
			array('name' => esc_html__( 'Monday', 'zona_plugin' ), 'value' => 'monday'),
			array('name' => esc_html__( 'Tuesday', 'zona_plugin' ), 'value' => 'tuesday'),
			array('name' => esc_html__( 'Wednesday', 'zona_plugin' ), 'value' => 'wednesday'),
			array('name' => esc_html__( 'Thursday', 'zona_plugin' ), 'value' => 'thursday'),
			array('name' => esc_html__( 'Friday', 'zona_plugin' ), 'value' => 'friday'),
			array('name' => esc_html__( 'Saturday', 'zona_plugin' ), 'value' => 'saturday'),
			array('name' => esc_html__( 'Sunday', 'zona_plugin' ), 'value' => 'sunday'),
		),
		'desc' => esc_html__( 'Please use the CTRL key (PC) or COMMAND key (Mac) to select multiple items.', 'zona_plugin' ),
		'dependency' => array(
		    "element" => '_repeat_event',
		    "value" => array( 'weekly' )
		)
		),
);

/* Add class instance */
$event_date_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	EVENTS OPTIONS - POST TYPE OPTIONS
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Event Options', 'zona_plugin' ), 
	'id' =>'r_event_options', 
	'page' => array(
		'zona_events'
	), 
	'context' => 'side', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'post'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
					  
	array(
		'name' => esc_html__( 'City', 'zona_plugin' ),
		'id' => '_event_city',
		'type' => 'text',
		'desc' => esc_html__( 'Enter event city e.g: London, UK', 'zona_plugin' )
	),
	array(
		'name' => esc_html__( 'Club/Place', 'zona_plugin' ),
		'id' => '_event_place',
		'type' => 'text',
		'desc' => esc_html__( 'Enter event club/place e.g: Fabric', 'zona_plugin' )
	),
	array(
		'name' => esc_html__( 'Tickets Link', 'zona_plugin' ),
		'id' => array(
			array( 'id' => '_event_tickets_url', 'std' => ''),
			array( 'id' => '_event_tickets_new_window', 'std' => 'no') 
		),
		'type' => 'easy_link',
		'desc' => esc_html__( 'Add link or select from WordPress.', 'zona_plugin' ),
	),
	array(
		'name' => esc_html__( 'Map Address', 'zona_plugin' ),
		'id' => '_event_map_address',
		'type' => 'textarea',
		'tinymce' => 'false',
		'std' => '',
		'height' => '40',
		'desc' => esc_html__( 'Add event address, it will be used also for Google Map. Address format: Level 13, 2 Elizabeth St, Melbourne Victoria 3000 Australia', 'zona_plugin' ),
	),
	
);

/* Add class instance */
$event_date_options_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	GALLERY - POST TYPE OPTIONS
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Album Images', 'zona_plugin' ), 
	'id' =>'r_gallery_options', 
	'page' => array(
		'zona_gallery'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'post'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
	array( 
		'name' => esc_html__( 'Images', 'zona_plugin' ),
		'id' => '_gallery_ids',
		'type' => 'media_manager',
		'media_type' => 'images', // images / audio / slider
		'msg_text' => esc_html__( 'Currently you don\'t have any photos, you can add them by clicking on the button below.', 'zona_plugin' ),
		'btn_text' => esc_html__( 'Add Photos', 'zona_plugin' ),
		'desc' => esc_html__( 'Add photos.', 'zona_plugin' ) . '<br>' . esc_html__( 'NOTE: Please use the CTRL key (PC) or COMMAND key (Mac) to select multiple items.', 'zona_plugin' )
	)
);

/* Add class instance */
$gallery_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );

/* ALBUM OPTIONS */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Album Options', 'zona_plugin' ), 
	'id' =>'r_album_options', 
	'page' => array(
		'zona_gallery'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'post'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
	array(
		'name' => esc_html__( 'Images Limit', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 200,
		'unit' => '',//esc_html__( 'events', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of images limit on page.', 'zona_plugin' )
	)	
);

/* Add class instance */
$gallery_album_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
/* GALLERY GRID - TEMPLATE OPTIONS
------------------------------------------------------------------------*/

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Gallery Options', 'zona_plugin' ), 
	'id' =>'r_gallery_options', 
	'page' => array(
		'page'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'page-templates/gallery.php',
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'
);


/* Meta options */
$meta_options = array(

	// Limit
	array(
		'name' => esc_html__( 'Albums Per Page', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 100,
		'unit' => esc_html__( 'items', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of video posts visible on page.', 'zona_plugin' )
	),

	// More posts
	array(
		'name' => esc_html__( 'Pagination Method', 'zona_plugin' ),
		'id' => '_pagination',
		'type' => 'select',
		'options' => array(
			array( 'name' => esc_html__( 'AJAX Loader', 'zona_plugin' ), 'value' => 'pagination-ajax' ),
			array( 'name' => esc_html__( 'Default Pagination', 'zona_plugin' ), 'value' => 'pagination-default' )
		),
		'std' => 'ajax',
		'desc' => esc_html__( 'Display default pagination (1,2,3...) or Ajax "Load more" button.', 'zona_plugin' )
	),

	// Display filter
	array(
		'name' => esc_html__( 'Display Ajax Filter', 'zona_plugin' ),
		'std' => 'on',
		'type' => 'switch_button',
		'id' => '_ajax_filter',
		'desc' => esc_html__( 'If this opion is on, then you should see category filter.', 'zona_plugin' )
	),

);

/* Add class instance */
$album_template_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
/* GALLERY FLOAT - TEMPLATE OPTIONS
------------------------------------------------------------------------*/

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Gallery Options', 'zona_plugin' ), 
	'id' =>'r_gallery_options', 
	'page' => array(
		'page'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'page-templates/gallery-float.php',
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);


/* Meta options */
$meta_options = array(

	// Limit
	array(
		'name' => esc_html__( 'Albums Per Page', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 100,
		'unit' => esc_html__( 'items', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of video posts visible on page.', 'zona_plugin' )
	)

);

/* Add class instance */
$gallery_float_template_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	VIDEOS - POST TYPE OPTIONS
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Video Options', 'zona_plugin' ), 
	'id' =>'r_videos_options', 
	'page' => array(
		'zona_videos'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'post'
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);

/* Meta options */
$meta_options = array(
	array( 
		'name' => esc_html__( 'Video URL', 'zona_plugin' ),
		'id' => '_video_url',
		'type' => 'text',
		'std' => '',
		'desc' => esc_html__( 'Add Youtube or Vimeo link here. e.g: https://www.youtube.com/watch?v=lMJXxhRFO1k', 'zona_plugin' )
	),
	array( 
		'name' => esc_html__( 'Click Action', 'zona_plugin' ),
		'id' => '_click_action',
		'type' => 'select',
		'options' => array(
			array( 'name' => esc_html__( 'Open video in popup window', 'zona_plugin' ), 'value' => 'open_in_lightbox' ),
			array( 'name' => esc_html__( 'Open video on the new page', 'zona_plugin' ), 'value' => 'open_on_page' )
		),
		'std' => '',
		'desc' => esc_html__( 'Open video in popup window or on the page.', 'zona_plugin' )
	)
);

/* Add class instance */
$videos_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
/* VIDEOS GRID - TEMPLATE OPTIONS
------------------------------------------------------------------------*/

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Videos Options', 'zona_plugin' ), 
	'id' =>'r_videos_options', 
	'page' => array(
		'page'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'page-templates/videos.php',
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'
);


/* Meta options */
$meta_options = array(

	// Limit
	array(
		'name' => esc_html__( 'Videos Per Page', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 100,
		'unit' => esc_html__( 'items', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of video posts visible on page.', 'zona_plugin' )
	),

	// More posts
	array(
		'name' => esc_html__( 'Pagination Method', 'zona_plugin' ),
		'id' => '_pagination',
		'type' => 'select',
		'options' => array(
			array( 'name' => esc_html__( 'AJAX Loader', 'zona_plugin' ), 'value' => 'pagination-ajax' ),
			array( 'name' => esc_html__( 'Default Pagination', 'zona_plugin' ), 'value' => 'pagination-default' )
		),
		'std' => 'ajax',
		'desc' => esc_html__( 'Display default pagination (1,2,3...) or Ajax "Load more" button.', 'zona_plugin' )
	),

	// Display filter
	array(
		'name' => esc_html__( 'Display Ajax Filter', 'zona_plugin' ),
		'std' => 'on',
		'type' => 'switch_button',
		'id' => '_ajax_filter',
		'desc' => esc_html__( 'If this opion is on, then you should see category filter.', 'zona_plugin' )
	),

);

/* Add class instance */
$videos_grid_template_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
/* VIDEOS FLOAT - TEMPLATE OPTIONS
------------------------------------------------------------------------*/

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Videos Options', 'zona_plugin' ), 
	'id' =>'r_gallery_options', 
	'page' => array(
		'page'
	), 
	'context' => 'normal', 
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'page-templates/videos-float.php',
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);


/* Meta options */
$meta_options = array(

	// Limit
	array(
		'name' => esc_html__( 'Videos Per Page', 'zona_plugin' ),
		'id' => '_limit',
		'type' => 'range',
		'min' => $pp,
		'max' => 100,
		'unit' => esc_html__( 'items', 'zona_plugin' ),
		'std' => '8',
		'desc' => esc_html__( 'Number of video posts visible on page.', 'zona_plugin' )
	)

);

/* Add class instance */
$videos_float_template_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );


/* ----------------------------------------------------------------------
	SHARE OPTIONS
/* ---------------------------------------------------------------------- */

/* Meta info */ 
$meta_info = array(
	'title' => esc_html__( 'Share Options', 'zona_plugin' ), 
	'id' =>'r_share_options', 
	'page' => array(
		'post', 
		'page',
		'zona_music',
		'zona_events',
		'zona_gallery',
		'zona_videos'
	), 
	'context' => 'side',
	'priority' => 'high', 
	'callback' => '', 
	'template' => array( 
		'post', 
		'default',
		'page-templates/music.php',
		'page-templates/music-float.php',
		'page-templates/events.php',
		'page-templates/events-float.php',
		'page-templates/gallery-float.php',
		'page-templates/gallery.php',
		'page-templates/videos.php',
		'page-templates/videos-float.php',
	),
	'admin_path'  => plugin_dir_url( __FILE__ ),
	'admin_uri'	 => plugin_dir_path( __FILE__ ),
	'admin_dir' => '',
	'textdomain' => 'zona_plugin'

);	

/* Meta options */
$meta_options = array(

	array(
		'name' => esc_html__( 'Image', 'zona_plugin' ),
		'id' => 'share_image',
		'type' => 'add_image',
		'width' => '160',
		'height' => '160',
		'source' => 'media_libary', // all, media_libary, external_link
		'crop' => 'c',
		'button_title' => esc_html__('Add Image', 'zona_plugin' ),
		'msg' => esc_html__('Currently you don\'t have share image, you can add one by clicking on the button below.', 'zona_plugin' ),
		'desc' => esc_html__('Use images that are at least 1200 x 630 pixels for the best display on high resolution devices. At the minimum, you should use images that are 600 x 315 pixels to display link page posts with larger images. If share data isn\'t visible on Facebook, please use this link:', 'zona_plugin' ) . '<br>'.'<a href="https://developers.facebook.com/tools/debug/" target="_blank">Facbook Debuger</a>'
	),
	array(
		'name' => esc_html__( 'Video', 'zona_plugin' ),
		'id' => '_share_video',
		'type' => 'text',
		'std' => '',
		'desc' => esc_html__( 'Video URL.', 'zona_plugin' )
	),
	array(
		'name' => esc_html__( 'Title', 'zona_plugin' ),
		'id' => '_share_title',
		'type' => 'text',
		'std' => '',
		'desc' => esc_html__( 'A clear title without branding or mentioning the domain itself.', 'zona_plugin' )
	),
	array(
		'name' => esc_html__( 'Short Description', 'zona_plugin' ),
		'id' => '_share_description',
		'type' => 'textarea',
		'tinymce' => 'false',
		'std' => '',
		'height' => '80',
		'desc' => esc_html__( 'A clear description, at least two sentences long.', 'zona_plugin' )
	)

);

/* Add class instance */
$fb_box = new MuttleyBox( $meta_options, $meta_info );

/* Remove variables */
unset( $meta_options, $meta_info );