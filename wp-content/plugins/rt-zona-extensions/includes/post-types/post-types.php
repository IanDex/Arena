<?php
/**
 * Plugin Name: 	Zona Extensions
 * Theme Author: 	Mariusz Rek - Rascals Themes
 * Theme URI: 		http://rascalsthemes.com/zona
 * Author URI: 		http://rascalsthemes.com
 * File:			post-types.php
 *
 * Register the Post types for SLider (zona_slider), Music (zona_music)
 *
 * =========================================================================================================================================
 *
 * @package zona-extensions
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ----------------------------------------------------------------------
	INIT CLASS
/* ---------------------------------------------------------------------- */
if ( ! class_exists( 'R_Custom_Post' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'classes/class-r-custom-posts.php' );
}


/* ----------------------------------------------------------------------
	MUSIC

	Create a Custom Post type for managing music items.
/* ---------------------------------------------------------------------- */

if ( ! function_exists( 'zona_music_post_type' ) ) :

function zona_music_post_type() {

	global $zona_music;

	// Get panel options
	$panel_options = get_option( 'zona_panel_opts' );

	// Slugs
	if ( isset( $panel_options['music_slug'] ) ) {
		$music_slug = $panel_options['music_slug'];
	} else {
		$music_slug = 'music';
	}
	if ( isset( $panel_options['music_cat_slug'] ) ) {
		$music_cat_slug = $panel_options['music_cat_slug'];
	} else {
		$music_cat_slug = 'music-category';
	}

	/* Class arguments */
	$args = array( 
		'post_name' => 'zona_music', 
		'sortable' => true,
		'admin_path'  => plugin_dir_url( __FILE__ ),
		'admin_url'	 => plugin_dir_path( __FILE__ ),
		'admin_dir' => '',
		'textdomain' => 'zona_plugin'
	);

	/* Post Labels */
	$labels = array(
		'name' => __( 'Music', 'zona_plugin' ),
		'singular_name' => __( 'Music', 'zona_plugin' ),
		'add_new' => __( 'Add New', 'zona_plugin' ),
		'add_new_item' => __( 'Add New Music Item', 'zona_plugin' ),
		'edit_item' => __( 'Edit Music Item', 'zona_plugin' ),
		'new_item' => __( 'New Music Item', 'zona_plugin' ),
		'view_item' => __( 'View Music Item', 'zona_plugin' ),
		'search_items' => __( 'Search Items', 'zona_plugin' ),
		'not_found' =>  __( 'No music found', 'zona_plugin' ),
		'not_found_in_trash' => __( 'No music found in Trash', 'zona_plugin' ), 
		'parent_item_colon' => ''
	);

	/* Post Options */
	$options = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array(
			'slug' => $music_slug,
			'with_front' => false
		),
		'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields'),
		'menu_icon' => 'dashicons-album'
	);

	/* Add Taxonomy */
	register_taxonomy('zona_music_cats', array('zona_music'), array(
		'hierarchical' => true,
		'label' => __( 'Categories', 'zona_plugin' ),
		'singular_label' => __( 'Category', 'zona_plugin' ),
		'query_var' => true,
		'rewrite' => array(
			'slug' => $music_cat_slug,
			'with_front' => false
		),
	));

	/* Add class instance */
	if ( class_exists( 'R_Custom_Post' ) ) {
		$zona_music = new R_Custom_Post( $args, $options );
	}

	/* Remove variables */
	unset( $args, $options );


	/* COLUMN LAYOUT
	 ---------------------------------------------------------------------- */
	add_filter( 'manage_edit-zona_music_columns', 'music_columns' );

	function music_columns( $columns ) {
		
		$cols = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'zona_plugin' ),
			'music_preview' => __( 'Music Preview', 'zona_plugin' ),
			'music_cat' => __( 'Categories', 'zona_plugin' ),
			'date' => __( 'Date', 'zona_plugin' )
		);

		$columns = array_merge($columns, $cols);

		return $columns;
	}

	add_action( 'manage_posts_custom_column', 'music_display_columns' );

	function music_display_columns( $column ) {

		global $post;
	
		switch ( $column ) {
			case 'music_preview':
					if ( has_post_thumbnail( $post->ID ) ) {
						the_post_thumbnail( array( 60, 60 ) );
					}
				break;
			case 'music_cat' :
				$cats = get_the_terms( $post->ID, 'zona_music_cats' );
				if ($cats) {
					foreach( $cats as $taxonomy ) {
						echo $taxonomy->name . ' ';
					}
				}
			break;
		}
	}


	/* COLUMN CAT FILTER
	 ------------------------------------------------------------------------*/

	/* Add Filter */
	add_action('restrict_manage_posts', 'add_zona_cat_filter');

	function add_zona_cat_filter() {

		global $typenow, $zona_music;

		if ( $typenow == 'zona_music' ) {
			$args = array( 'name' => 'zona_music_cats' );
			$filters = get_taxonomies( $args );
			
			foreach ( $filters as $tax_slug ) {
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				
				echo '<select name="' . $tax_slug. '" id="' . $tax_slug . '" class="postform">';
				echo '<option value="">' . __( 'All Categories', 'zona_plugin' ) . '</option>';
				$zona_music->generate_taxonomy_options( $tax_slug, 0, 0 );
				echo "</select>";
			}
		}
	}

	/* Request Filter */
	add_action('request', 'zona_cat_filter');

	function zona_cat_filter( $request ) {
		if ( is_admin() && isset( $request['post_type'] ) && $request['post_type'] == 'zona_music' && isset( $request['zona_music_cats'] ) ) {
			
		  	$term = get_term( $request['zona_music_cats'], 'zona_music_cats' );
			if ( isset( $term->name ) && $term) {
				$term = $term->name;
				$request['term'] = $term;
			}	
		}
		return $request;
	}


}

add_action( 'init', 'zona_music_post_type', 0 );
endif; // End check for function_exists()


/* ----------------------------------------------------------------------
	EVENTS

	Create a Custom Post type for managing events.
/* ---------------------------------------------------------------------- */

if ( ! function_exists( 'zona_events_post_type' ) ) :

function zona_events_post_type() {

	global $zona_events, $pagenow, $current_date;

	// Get panel options
	$panel_options = get_option( 'zona_panel_opts' );

	// Slugs
	if ( isset( $panel_options['events_slug'] ) ) {
		$events_slug = $panel_options['events_slug'];
	} else {
		$events_slug = 'events';
	}
	if ( isset( $panel_options['events_cat_slug'] ) ) {
		$events_cat_slug = $panel_options['events_cat_slug'];
	} else {
		$events_cat_slug = 'event-category';
	}


	/* Class arguments */
	$args = array( 
		'post_name' => 'zona_events', 
		'sortable' => false,
		'admin_path'  => plugin_dir_url( __FILE__ ),
		'admin_url'	 => plugin_dir_path( __FILE__ ),
		'admin_dir' => '',
		'textdomain' => 'zona_plugin'
	);

	/* Post Labels */
	$labels = array(
		'name' => __( 'Events', 'zona_plugin' ),
		'singular_name' => __( 'Events', 'zona_plugin' ),
		'add_new' => __( 'Add New', 'zona_plugin' ),
		'add_new_item' => __( 'Add New Event', 'zona_plugin' ),
		'edit_item' => __( 'Edit Event', 'zona_plugin' ),
		'new_item' => __( 'New Event', 'zona_plugin' ),
		'view_item' => __( 'View Event', 'zona_plugin' ),
		'search_items' => __( 'Search Items', 'zona_plugin' ),
		'not_found' =>  __( 'No events found', 'zona_plugin' ),
		'not_found_in_trash' => __( 'No events found in Trash', 'zona_plugin' ), 
		'parent_item_colon' => ''
	);

	/* Post Options */
	$options = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array(
			'slug' => $events_slug,
			'with_front' => false
		),
		'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields'),
		'menu_icon' => 'dashicons-calendar'
	);

	/* Add Taxonomy */
	register_taxonomy('zona_event_type', array('zona_events'), array(
		'hierarchical' => true,
		'label' => __( 'Event Type', 'zona_plugin' ),
		'singular_label' => __( 'Event Type', 'zona_plugin' ),
		'show_ui' => false,
		'query_var' => true,
		'capabilities' => array(
			'manage_terms' => 'manage_divisions',
			'edit_terms' => 'edit_divisions',
			'delete_terms' => 'delete_divisions',
			'assign_terms' => 'edit_posts'
		),
		'rewrite' => 'event-type',
		'show_in_nav_menus' => false
	));

	/* Add Taxonomy */
	register_taxonomy('zona_events_cats', array('zona_events'), array(
		'hierarchical' => true,
		'label' => __( 'Categories', 'zona_plugin' ),
		'singular_label' => __( 'category', 'zona_plugin' ),
		'query_var' => true,
		'rewrite' => array(
			'slug' => $events_cat_slug,
			'with_front' => false
		),
	));

	/* Add class instance */
	if ( class_exists( 'R_Custom_Post' ) ) {
		$zona_events = new R_Custom_Post( $args, $options );
	}

	/* Remove variables */
	unset( $args, $options );


	/* Helpers Functions
	------------------------------------------------------------------------*/


	/* Insert default taxonomy
	 ------------------------------------------------------------------------*/
	function zona_insert_taxonomy( $cat_name, $parent, $description, $taxonomy ) {
		global $wpdb;

		if ( ! term_exists( $cat_name, $taxonomy ) ) {
			$args = compact(
				$cat_name = esc_sql( $cat_name ),
				$cat_slug = sanitize_title( $cat_name ),
				$parent = 0,
				$description = ''
			);
			wp_insert_term( $cat_name, $taxonomy, $args );
			return;
		}
	  return;
	}


	/* Get Taxonomy ID
	 ------------------------------------------------------------------------*/
	function zona_get_taxonomy_id( $cat_name, $taxonomy ) {
		
		$args = array(
			'hide_empty' => false
		);
		
		$taxonomies = get_terms( $taxonomy, $args );
		if ( $taxonomies ) {
			foreach( $taxonomies as $taxonomy ) {
				
				if ( $taxonomy->name == $cat_name ) {
					return $taxonomy->term_id;
				}
				
			}
		}
		
		return false;
	}


	/* Days left
	 ------------------------------------------------------------------------*/
	function zona_days_left( $start_date, $end_date, $type ) {
		global $current_date;
		
		$now = strtotime( $current_date );
		$start_date = strtotime( $start_date );
		$end_date = strtotime( $end_date );
		
		/* Days left to start date */
		$hours_left_start = ( mktime(0, 0, 0, date( 'm', $start_date ), date( 'd', $start_date ), date( 'Y', $start_date ) ) - $now ) / 3600;
		$days_left_start = ceil( $hours_left_start / 24 );
		
		/* Days left to end date */
		$hours_left_end = ( mktime( 0, 0, 0, date( 'm', $end_date ), date( 'd', $end_date ), date( 'Y', $end_date ) ) - $now ) / 3600;
		$days_left_end = ceil( $hours_left_end / 24 );
		$days_number = ( $days_left_end - $days_left_start ) + 1;
		
		if ( $type == 'days' ) {
			return $days_number;
		}
		
		if ( $type == 'days_left' ) {
			
			/* If future events */
			if ( $days_left_end >= 0 ) {
			
				if ( $days_left_start == 0 ) {
					return '<span style="color:red;font-weight:bold">'. __( 'Start Today', 'zona_plugin' ) .'</span>';
				}
				elseif ( $days_left_start < 0 ) {
					return '<span style="color:red;font-weight:bold">' . __( 'Continued', 'zona_plugin' ) . '</span>';
				}
				elseif ( $days_left_start > 0 ) {
					return $days_left_start;
				}
			
			} else return '-- --';
		}
		
	}


	/* Settings
	------------------------------------------------------------------------*/
	$time_zone = 'local_time'; /* local_time, server_time, UTC */

	/* Timezone */
	$current_date = array();
	$current_date['local_time'] = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
	$current_date['server_time'] = date( 'Y-m-d', current_time( 'timestamp', 1 ) );
	$current_date['UTC'] = date( 'Y-m-d' );
	$current_date = $current_date[ $time_zone ];

	/* Insert default taxonomy */
	if ( is_admin() ) {
		if ( ! term_exists( 'Future events', 'zona_event_type' ) ) {
	    	zona_insert_taxonomy( 'Future events', 0, '', 'zona_event_type' );
		}
		if ( ! term_exists( 'Past events', 'zona_event_type' ) ) {
	    	zona_insert_taxonomy( 'Past events', 0, '', 'zona_event_type' );
	    }
	}


	/* Column Layout
	------------------------------------------------------------------------*/
	add_filter( 'manage_edit-zona_events_columns', 'zona_events_columns' );

	function zona_events_columns( $columns ) {
		global $current_date;
		$cols = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Event Title', 'zona_plugin' ),
			'event_date' => __( 'Event Date', 'zona_plugin' ),
			'event_days' => __( 'Days', 'zona_plugin' ),
			'event_days_left' => __( 'Days Left', 'zona_plugin' ),
			'event_type' => __( 'Type', 'zona_plugin' ),
			'event_repeat' => __( 'Repeat', 'zona_plugin' ),
			'events_cats' => __( 'Categories', 'zona_plugin' ),
			'image_preview' => __( 'Preview', 'zona_plugin' )
		);
		unset( $columns['date'] );

		$columns = array_merge($columns, $cols);

		return $columns;
	}

	add_action( 'manage_posts_custom_column', 'zona_events_display_columns' );

	function zona_events_display_columns( $column ) {
		global $post, $current_date;
		
		$today = strtotime( $current_date );
		
		switch ( $column ) {
			case 'event_date':
				$event_date_start = get_post_custom();
				$event_date_end = get_post_custom();
				echo $event_date_start['_event_date_start'][0] . ' - ' . $event_date_end['_event_date_end'][0];
			break;
			case 'event_days' :
				$event_date_start = get_post_custom();
				$event_date_end = get_post_custom();
				echo zona_days_left( $event_date_start['_event_date_start'][0], $event_date_end['_event_date_end'][0], 'days' );
			break;
			case 'event_days_left' :
				$event_date_start = get_post_custom();
				$event_date_end = get_post_custom();
				echo zona_days_left( $event_date_start['_event_date_start'][0], $event_date_end['_event_date_end'][0], 'days_left' );
			break;
			case 'event_type' :
					$taxonomies = get_the_terms( $post->ID, 'zona_event_type' );
					$event_date_end = get_post_custom();
					if ( $taxonomies ) {
						foreach( $taxonomies as $taxonomy ) {
							if ( strtotime( $event_date_end['_event_date_end'][0] ) >= $today && $taxonomy->name == 'Future events' ) 
							    echo '<strong>' . $taxonomy->name . '</strong>';
							else 
							    echo $taxonomy->name;
						}
					}
			break;
			case 'event_repeat' :
					$custom = get_post_custom();
					if ( isset( $custom['_repeat_event'][0]) && $custom['_repeat_event'][0] != 'none' )
						echo ucfirst( $custom['_repeat_event'][0] );
					
			break;
			case 'events_cats' :
				$cats = get_the_terms( $post->ID, 'zona_events_cats' );
				if ($cats) {
					foreach( $cats as $taxonomy ) {
						echo $taxonomy->name . ' ';
					}
				}
			break;
			
			case 'image_preview':
				if ( has_post_thumbnail( $post->ID ) ) {
					the_post_thumbnail( array( 60, 60 ) );
				}
			break;
		}
	}


	/* Menage Events
	------------------------------------------------------------------------*/
	function manage_events() {
		global $post, $current_date;
		
		$backup = $post;
		$today = strtotime( $current_date );
		$args = array(
			'post_type'     => 'zona_events',
			'zona_event_type' => 'Future events',
			'post_status'   => 'publish, pending, draft, future, private, trash',
			'numberposts'   => '-1',
			'orderby'       => 'meta_value',  
			'meta_key'      => '_event_date_end',
			'order'         => 'ASC',
		  	'meta_query' 	 => array(array('key' => '_event_date_end', 'value' => date('Y-m-d'), 'compare' => '<', 'type' => 'DATE')),
		  );
		$events = get_posts( $args );
		
	 	foreach( $events as $event ) {
			
			$event_date_start = get_post_meta( $event->ID, '_event_date_start', true );
			$event_date_end = get_post_meta( $event->ID, '_event_date_end', true );
			$repeat = get_post_meta( $event->ID, '_repeat_event', true );
			
			/* Move Events */

			// If is set repeat event
			if ( isset( $repeat ) && $repeat != 'none' ) {

				// Weekly
				if ( $repeat == 'weekly' ) {
					$every = get_post_meta( $event->ID, '_every', true );
					$weekly_days = get_post_meta( $event->ID, '_weekly_days', true );

					// Event length
					$start_date = strtotime( $event_date_start );
					$end_date = strtotime( $event_date_end );
					$date_diff = $end_date - $start_date;
					$event_length = floor( $date_diff / (60*60*24) );

					unset( $start_date, $end_date, $date_diff );
					//echo "Differernce is $event_length days";

					// Make dates array
					$weekly_dates  = array();
					$weekly_days_a = array();
					foreach ( $weekly_days as $key => $day ) {
						$start_date = strtotime( "+$every week $day $event_date_start" );
						$date_diff = $start_date - $today;
						$days = floor( $date_diff / (60*60*24) );
						$start_date = date( 'Y-m-d', $start_date );
						$end_date = strtotime( "+$event_length day $start_date" );
						$end_date = date( 'Y-m-d', $end_date );
						$weekly_dates[$key]['day'] = $day;
						$weekly_dates[$key]['days'] = $days;
						$weekly_dates[$key]['start_date'] = $start_date;
						$weekly_dates[$key]['end_date'] = $end_date;
						$weekly_days_a[] = $days;
					}
					// Next event date
					$ne = array_search( min( $weekly_days_a ), $weekly_days_a );
					//print_r($ne);

					// Update event date
					update_post_meta( $event->ID, '_event_date_start', $weekly_dates[$ne]['start_date'] );
					update_post_meta( $event->ID, '_event_date_end', $weekly_dates[$ne]['end_date'] );

				}
			} else {
				wp_set_post_terms( $event->ID, zona_get_taxonomy_id( 'Past events', 'zona_event_type' ), 'zona_event_type', false );
			}
		}
		$post = $backup; 
		wp_reset_query();
	}


	/* Shelude Events
	 ------------------------------------------------------------------------*/
	if ( false === ( $event_task = get_transient( 'event_task' ) ) ) {
	    $current_time = time();
		manage_events();
		set_transient( 'event_task', $current_time, 60*60 );
	}
	//delete_transient('event_task');


	/* Save Events
	 ------------------------------------------------------------------------*/
	function save_postdata_events() {
	   	global $current_date;
		
		if ( isset( $_POST['post_ID'] ) ) {
			$post_id = $_POST['post_ID'];
		} else {
			return; 
		}

		// Inline editor
	 	if ( $_POST['action'] == 'inline-save' ) {
	 		return;
	 	}

	    if ( isset( $_POST['post_type'] ) && $_POST['post_type'] == 'zona_events' ) {
				
	        $today = strtotime( $current_date );
		    $event_date_start = strtotime( get_post_meta( $post_id, '_event_date_start', true ) );
		   
		    $event_date_end = strtotime( get_post_meta( $post_id, '_event_date_end', true ) );
			
	        /* Add Default Date */
		    if ( ! $event_date_start ) {
		  	    add_post_meta( $post_id, '_event_date_start', date( 'Y-m-d', $today) );
		    }
		    if ( ! $event_date_end ) {
			    add_post_meta( $post_id, '_event_date_end', get_post_meta( $post_id, '_event_date_start', true ) );
		    }
		    if ( $event_date_end < $event_date_start ) {
			    update_post_meta( $post_id, '_event_date_end', get_post_meta( $post_id, '_event_date_start', true ) );
		    }
			
			$event_date_start = strtotime( get_post_meta($post_id, '_event_date_start', true ) );
		    $event_date_end = strtotime( get_post_meta($post_id, '_event_date_end', true ) );
			
			/* Add Default Term */
			$taxonomies = get_the_terms( $post_id, 'zona_event_type' );
			if ( ! $taxonomies ) {
				wp_set_post_terms( $post_id, zona_get_taxonomy_id( 'Future events', 'zona_event_type' ), 'zona_event_type', false );	
			}
		    if ( $event_date_end >= $today ) {
		  	    if ( is_object_in_term( $post_id, 'zona_event_type', 'Past events' ) )
		        wp_set_post_terms( $post_id, zona_get_taxonomy_id( 'Future events', 'zona_event_type' ), 'zona_event_type', false );	
		    } else {	
		        if ( is_object_in_term( $post_id, 'zona_event_type', 'Future events' ) )
			    wp_set_post_terms( $post_id, zona_get_taxonomy_id( 'Past events', 'zona_event_type' ), 'zona_event_type', false );
		    }
			
	    }
		
	}
	add_action( 'wp_insert_post', 'save_postdata_events' );


	/* Custom order
	 ------------------------------------------------------------------------*/
	function events_manager_order( $query ) {
		global $pagenow;
		if ( is_admin() && $pagenow == 'edit.php' && isset( $query->query['post_type'] ) ) {
		    $post_type = $query->query['post_type'];
	    	if ($post_type == 'zona_events') {
			   	$events_order = '_event_date_start';
				$query->query_vars['meta_key'] = $events_order;
				$query->query_vars['orderby'] = 'meta_value';
				$query->query_vars['order'] = 'asc';
				$query->query_vars['meta_query'] = array( array( 'key' => $events_order, 'value' => '1900-01-01', 'compare' => '>', 'type' => 'NUMERIC') );
	    	}
	  	}
	}
	add_filter( 'pre_get_posts', 'events_manager_order' );


	/* Event Type Filter
	------------------------------------------------------------------------*/
	function add_events_filter() {

	    global $typenow, $zona_events;

	    if ($typenow == 'zona_events') {
	        $args = array( 'name' => 'zona_event_type' );
	        $filters = get_taxonomies( $args );

	        foreach ( $filters as $tax_slug ) {
	            $tax_obj = get_taxonomy( $tax_slug );
	            $tax_name = $tax_obj->labels->name;

	            echo '<select name="' . $tax_slug. '" id="' . $tax_slug . '" class="postform">';
				echo '<option value="">' . __( 'All Types', 'zona_plugin' ) . '</option>';
	            $zona_events->generate_taxonomy_options( $tax_slug, 0, 0);
	            echo "</select>";
	        }
	    }
	}
	add_action('restrict_manage_posts', 'add_events_filter');

	/* Add Filter - Request */
	add_action('request', 'events_request');

	function events_request( $request ) {
		if ( is_admin() && isset( $request['post_type'] ) && $request['post_type'] == 'zona_events' && isset( $request['zona_event_type'] ) ) {
			$term = get_term( $request['zona_event_type'], 'zona_event_type' );
			if ( isset( $term->name ) && $term ) {
				$term = $term->name;
				$request['term'] = $term;
			}
		}
		return $request;
	}


	/* COLUMN CAT FILTER
	 ------------------------------------------------------------------------*/

	/* Add Filter */
	add_action('restrict_manage_posts', 'add_zona_events_cats_filter');

	function add_zona_events_cats_filter() {

		global $typenow, $zona_events;

		if ( $typenow == 'zona_events' ) {
			$args = array( 'name' => 'zona_events_cats' );
			$filters = get_taxonomies( $args );
			
			foreach ( $filters as $tax_slug ) {
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				
				echo '<select name="' . $tax_slug. '" id="' . $tax_slug . '" class="postform">';
				echo '<option value="">' . __( 'All Categories', 'zona_plugin' ) . '</option>';
				$zona_events->generate_taxonomy_options( $tax_slug, 0, 0 );
				echo "</select>";
			}
		}
	}

	/* Request Filter */
	add_action('request', 'zona_events_cats_filter');

	function zona_events_cats_filter( $request ) {
		if ( is_admin() && isset( $request['post_type'] ) && $request['post_type'] == 'zona_events' && isset( $request['zona_events_cats'] ) ) {
			
		  	$term = get_term( $request['zona_events_cats'], 'zona_events_cats' );
			if ( isset( $term->name ) && $term) {
				$term = $term->name;
				$request['term'] = $term;
			}	
		}
		return $request;
	}

}

add_action( 'init', 'zona_events_post_type', 0 );
endif; // End check for function_exists()


/* ----------------------------------------------------------------------
	GALLERY

	Create a Custom Post type for managing gallery items.
/* ---------------------------------------------------------------------- */

if ( ! function_exists( 'zona_gallery_post_type' ) ) :

function zona_gallery_post_type() {

	global $zona_gallery;

	// Get panel options
	$panel_options = get_option( 'zona_panel_opts' );

	// Slugs
	if ( isset( $panel_options['gallery_slug'] ) ) {
		$gallery_slug = $panel_options['gallery_slug'];
	} else {
		$gallery_slug = 'gallery';
	}
	if ( isset( $panel_options['gallery_cat_slug'] ) ) {
		$gallery_cat_slug = $panel_options['gallery_cat_slug'];
	} else {
		$gallery_cat_slug = 'gallery-category';
	}

	/* Class arguments */
	$args = array( 
		'post_name' => 'zona_gallery', 
		'sortable' => false,
		'admin_path'  => plugin_dir_url( __FILE__ ),
		'admin_url'	 => plugin_dir_path( __FILE__ ),
		'admin_dir' => '',
		'textdomain' => 'zona_plugin'
	);

	/* Post Labels */
	$labels = array(
		'name' => __( 'Gallery', 'zona_plugin' ),
		'singular_name' => __( 'Album', 'zona_plugin' ),
		'add_new' => __( 'Add New Album', 'zona_plugin' ),
		'add_new_item' => __( 'Add New Album', 'zona_plugin' ),
		'edit_item' => __( 'Edit Album', 'zona_plugin' ),
		'new_item' => __( 'New Album', 'zona_plugin' ),
		'view_item' => __( 'View Album', 'zona_plugin' ),
		'search_items' => __( 'Search Items', 'zona_plugin' ),
		'not_found' =>  __( 'No albums found', 'zona_plugin' ),
		'not_found_in_trash' => __( 'No albums found in Trash', 'zona_plugin' ), 
		'parent_item_colon' => ''
	);

	/* Post Options */
	$options = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array(
			'slug' => $gallery_slug,
			'with_front' => false
		),
		'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields'),
		'menu_icon' => 'dashicons-camera'
	);

	/* Add Taxonomy */
	register_taxonomy('zona_gallery_cats', array('zona_gallery'), array(
		'hierarchical' => true,
		'label' => __( 'Categories', 'zona_plugin' ),
		'singular_label' => __( 'category', 'zona_plugin' ),
		'query_var' => true,
		'rewrite' => array(
			'slug' => $gallery_cat_slug,
			'with_front' => false
		),
	));

	/* Add class instance */
	if ( class_exists( 'R_Custom_Post' ) ) {
		$zona_gallery = new R_Custom_Post( $args, $options );
	}

	/* Remove variables */
	unset( $args, $options );


	/* COLUMN LAYOUT
	 ---------------------------------------------------------------------- */
	add_filter( 'manage_edit-zona_gallery_columns', 'gallery_columns' );

	function gallery_columns( $columns ) {
		
		$cols = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'zona_plugin' ),
			'gallery_preview' => __( 'Gallery Preview', 'zona_plugin' ),
			'gallery_cats' => __( 'Categories', 'zona_plugin' ),
			'date' => __( 'Date', 'zona_plugin' )
		);

		$columns = array_merge($columns, $cols);
		return $columns;
	}

	add_action( 'manage_posts_custom_column', 'gallery_display_columns' );

	function gallery_display_columns( $column ) {

		global $post;
	
		switch ( $column ) {
			case 'gallery_preview':
					if ( has_post_thumbnail( $post->ID ) ) {
						the_post_thumbnail( array( 60, 60 ) );
					}
				break;
			case 'gallery_cats' :
				$genres = get_the_terms( $post->ID, 'zona_gallery_cats' );
				if ($genres) {
					foreach( $genres as $taxonomy ) {
						echo $taxonomy->name . ' ';
					}
				}
			break;
		}
	}


	/* COLUMN CAT FILTER
	 ------------------------------------------------------------------------*/

	/* Add Filter */
	add_action('restrict_manage_posts', 'add_zona_gallery_cats_filter');

	function add_zona_gallery_cats_filter() {

		global $typenow, $zona_gallery;

		if ( $typenow == 'zona_gallery' ) {
			$args = array( 'name' => 'zona_gallery_cats' );
			$filters = get_taxonomies( $args );
			
			foreach ( $filters as $tax_slug ) {
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				
				echo '<select name="' . $tax_slug. '" id="' . $tax_slug . '" class="postform">';
				echo '<option value="">' . __( 'All Categories', 'zona_plugin' ) . '</option>';
				$zona_gallery->generate_taxonomy_options( $tax_slug, 0, 0 );
				echo "</select>";
			}
		}
	}

	/* Request Filter */
	add_action('request', 'zona_gallery_cats_filter');

	function zona_gallery_cats_filter( $request ) {
		if ( is_admin() && isset( $request['post_type'] ) && $request['post_type'] == 'zona_gallery' && isset( $request['zona_gallery_cats'] ) ) {
			
		  	$term = get_term( $request['zona_gallery_cats'], 'zona_gallery_cats' );
			if ( isset( $term->name ) && $term) {
				$term = $term->name;
				$request['term'] = $term;
			}	
		}
		return $request;
	}

}

add_action( 'init', 'zona_gallery_post_type', 0 );


/* Pagination fix */
function zona_gallery_disable_canonical_redirect( $query ) {
	
    if ( ! is_home() && ! is_404() && isset( $query->query_vars['post_type'] ) && 'zona_gallery' == $query->query_vars['post_type'] ) {
        remove_filter( 'template_redirect', 'redirect_canonical' );
    }

}
add_action( 'parse_query', 'zona_gallery_disable_canonical_redirect' );

endif; // End check for function_exists()



/* ----------------------------------------------------------------------
	VIDEOS

	Create a Custom Post type for managing videos items.
/* ---------------------------------------------------------------------- */

if ( ! function_exists( 'zona_videos_post_type' ) ) :

function zona_videos_post_type() {

	global $zona_videos;

	// Get panel options
	$panel_options = get_option( 'zona_panel_opts' );

	// Slugs
	if ( isset( $panel_options['videos_slug'] ) ) {
		$videos_slug = $panel_options['videos_slug'];
	} else {
		$videos_slug = 'video';
	}
	if ( isset( $panel_options['videos_cat_slug'] ) ) {
		$videos_cat_slug = $panel_options['videos_cat_slug'];
	} else {
		$videos_cat_slug = 'videos-category';
	}

	/* Class arguments */
	$args = array( 
		'post_name' => 'zona_videos', 
		'sortable' => true,
		'admin_path'  => plugin_dir_url( __FILE__ ),
		'admin_url'	 => plugin_dir_path( __FILE__ ),
		'admin_dir' => '',
		'textdomain' => 'zona_plugin'
	);

	/* Post Labels */
	$labels = array(
		'name' => __( 'Videos', 'zona_plugin' ),
		'singular_name' => __( 'Video', 'zona_plugin' ),
		'add_new' => __( 'Add New Video', 'zona_plugin' ),
		'add_new_item' => __( 'Add New Video', 'zona_plugin' ),
		'edit_item' => __( 'Edit Video', 'zona_plugin' ),
		'new_item' => __( 'New Video', 'zona_plugin' ),
		'view_item' => __( 'View Video', 'zona_plugin' ),
		'search_items' => __( 'Search Items', 'zona_plugin' ),
		'not_found' =>  __( 'No videos found', 'zona_plugin' ),
		'not_found_in_trash' => __( 'No videos found in Trash', 'zona_plugin' ), 
		'parent_item_colon' => ''
	);

	/* Post Options */
	$options = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array(
			'slug' => $videos_slug,
			'with_front' => false
		),
		'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields'),
		'menu_icon' => 'dashicons-video-alt2'
	);

	/* Add Taxonomy */
	register_taxonomy('zona_videos_cats', array('zona_videos'), array(
		'hierarchical' => true,
		'label' => __( 'Categories', 'zona_plugin' ),
		'singular_label' => __( 'category', 'zona_plugin' ),
		'query_var' => true,
		'rewrite' => array(
			'slug' => $videos_cat_slug,
			'with_front' => false
		),
	));

	/* Add class instance */
	if ( class_exists( 'R_Custom_Post' ) ) {
		$zona_videos = new R_Custom_Post( $args, $options );
	}

	/* Remove variables */
	unset( $args, $options );


	/* COLUMN LAYOUT
	 ---------------------------------------------------------------------- */
	add_filter( 'manage_edit-zona_videos_columns', 'videos_columns' );

	function videos_columns( $columns ) {
		
		$cols = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'zona_plugin' ),
			'videos_preview' => __( 'Video Preview', 'zona_plugin' ),
			'date' => __( 'Date', 'zona_plugin' )
		);

		$columns = array_merge($columns, $cols);
		return $columns;
	}

	add_action( 'manage_posts_custom_column', 'videos_display_columns' );

	function videos_display_columns( $column ) {

		global $post;
	
		switch ( $column ) {
			case 'videos_preview':
					if ( has_post_thumbnail( $post->ID ) ) {
						the_post_thumbnail( array( 60, 60 ) );
					}
				break;
		}
	}


	/* COLUMN CAT FILTER
	 ------------------------------------------------------------------------*/

	/* Add Filter */
	add_action('restrict_manage_posts', 'add_zona_videos_cats_filter');

	function add_zona_videos_cats_filter() {

		global $typenow, $zona_videos;

		if ( $typenow == 'zona_videos' ) {
			$args = array( 'name' => 'zona_videos_cats' );
			$filters = get_taxonomies( $args );
			
			foreach ( $filters as $tax_slug ) {
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				
				echo '<select name="' . $tax_slug. '" id="' . $tax_slug . '" class="postform">';
				echo '<option value="">' . __( 'All Categories', 'zona_plugin' ) . '</option>';
				$zona_videos->generate_taxonomy_options( $tax_slug, 0, 0 );
				echo "</select>";
			}
		}
	}

	/* Request Filter */
	add_action('request', 'zona_videos_cats_filter');

	function zona_videos_cats_filter( $request ) {
		if ( is_admin() && isset( $request['post_type'] ) && $request['post_type'] == 'zona_videos' && isset( $request['zona_videos_cats'] ) ) {
			
		  	$term = get_term( $request['zona_videos_cats'], 'zona_videos_cats' );
			if ( isset( $term->name ) && $term) {
				$term = $term->name;
				$request['term'] = $term;
			}	
		}
		return $request;
	}


}

add_action( 'init', 'zona_videos_post_type', 0 );

endif; // End check for function_exists()