<?php
/**
 * Plugin Name:   Zona Extensions
 * Theme Author:  Mariusz Rek - Rascals Themes
 * Theme URI:     http://rascalsthemes.com/zona
 * Author URI:    http://rascalsthemes.com
 * File:      vc-extend.php
 * =========================================================================================================================================
 *
 * @package zona-extensions
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Remove visual composer elements
if ( ! function_exists( 'zona_remove_element' ) ) {

    function zona_remove_element() {
        vc_remove_element('vc_accordion_tab');
        vc_remove_element('vc_accordion');
        vc_remove_element('vc_button');
        vc_remove_element('vc_carousel');
        // vc_remove_element('vc_column_text');
        vc_remove_element('vc_cta_button');
        // vc_remove_element('vc_facebook');
        vc_remove_element('vc_button2');
        vc_remove_element('vc_cta_button2');
        vc_remove_element('vc_flickr');
        // vc_remove_element('vc_gallery');
        // vc_remove_element('vc_gmaps');
        // vc_remove_element('vc_googleplus');
        vc_remove_element('vc_images_carousel');
        // vc_remove_element('vc_item');
        // vc_remove_element('vc_items');
        // vc_remove_element('vc_message');
        // vc_remove_element('vc_pie');
        // vc_remove_element('vc_pinterest');
        vc_remove_element('vc_posts_grid');
        vc_remove_element('vc_posts_slider');
        // vc_remove_element('vc_progress_bar');
        // vc_remove_element('vc_raw_html');
        // vc_remove_element('vc_separator');
        // vc_remove_element('vc_single_image');
        vc_remove_element('vc_tab');
        vc_remove_element('vc_tabs');
        // vc_remove_element('vc_teaser_grid');
        // vc_remove_element('vc_text_separator');
        // vc_remove_element('vc_toggle');
        // vc_remove_element('vc_tweetmeme');
        // vc_remove_element('vc_twitter');
        // vc_remove_element('vc_video');
        // vc_remove_element('vc_raw_js');
        vc_remove_element('vc_tour');
        // vc_remove_element("vc_widget_sidebar");
        // vc_remove_element("vc_wp_search");
        // vc_remove_element("vc_wp_meta");
        // vc_remove_element("vc_wp_recentcomments");
        // vc_remove_element("vc_wp_calendar");
        // vc_remove_element("vc_wp_pages");
        // vc_remove_element("vc_wp_tagcloud");
        // vc_remove_element("vc_wp_custommenu");
        // vc_remove_element("vc_wp_text");
        // vc_remove_element("vc_wp_posts");
        // vc_remove_element("vc_wp_links");
        // vc_remove_element("vc_wp_categories");
        // vc_remove_element("vc_wp_archives");
        // vc_remove_element("vc_wp_rss");
        // vc_remove_element("vc_gallery");
        // vc_remove_element("vc_teaser_grid");
        // vc_remove_element("vc_button");
    }
    zona_remove_element();
}

// Remove visual composer elements
if ( ! function_exists( 'zona_remove_grid' ) ) {

    function zona_remove_grid() {

        vc_remove_element("vc_basic_grid");
        vc_remove_element("vc_basic_grid_filter");
        vc_remove_element("vc_masonry_media_grid");
        vc_remove_element("vc_media_grid");
        vc_remove_element("vc_masonry_grid");
        vc_remove_element("vc_grid_item");

        function grid_elements_menu(){
            remove_menu_page( 'edit.php?post_type=vc_grid_item' );
            remove_submenu_page( 'vc-general', 'edit.php?post_type=vc_grid_item' );
            global $submenu;
            // var_dump($submenu);
             unset( $submenu['vc-general'][6] );
        }
        add_action( 'admin_menu', 'grid_elements_menu' );
      
    }
    zona_remove_grid();
}

// Disable frontend editor
if ( function_exists( 'vc_disable_frontend' ) ){
    $panel_options = get_option( 'zona_panel_opts' );
    if ( isset( $panel_options ) && isset( $panel_options['vc_frontend'] ) && $panel_options['vc_frontend'] == 'off' ) {
        vc_disable_frontend();
    }
}

// Enable post types
$vc_cpt_list = array(
    'page',
    'post',
    'zona_music',
    'zona_events',
    'zona_gallery'
);
vc_set_default_editor_post_types( $vc_cpt_list );


/* ----------------------------------------------------------------------

    ROW EXTEND

/* ---------------------------------------------------------------------- */

vc_add_param( "vc_row", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => __( "Content Width",  'zona_plugin' ),
    "param_name" => "content_width",
    "value" => array(
        "Wide" => "wide",
        "Narrow" => "narrow"
    ),
    "dependency" => Array( 'element' => "full_width", 'value' => array( 'stretch_row' ) )
));



/* ----------------------------------------------------------------------

    REVIEW

/* ---------------------------------------------------------------------- */


function zona_vc_review() {

    vc_map( array(
        "name" => __( "Review", 'zona_plugin' ),
        "base" => "review",
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "textarea",
                "class" => "",
                "heading" => __( "Review Text", 'zona_plugin' ),
                "param_name" => "text",
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Name", 'zona_plugin' ),
                "param_name" => "name",
                "admin_label" => true
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Company", 'zona_plugin' ),
                "param_name" => "company",
                "admin_label" => true
            ),
           
        )
   ));
}

add_action( 'vc_before_init', 'zona_vc_review' );


/* ----------------------------------------------------------------------

    BUTTONS

/* ---------------------------------------------------------------------- */

function zona_vc_buttons() {

    vc_map( array(
        "name" => __( "Buttons", 'zona_plugin' ),
        "base" => "buttons",
        "as_parent" => array( 'only' => 'button' ),
        "content_element" => true,
        "show_settings_on_create" => false,
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "js_view" => 'VcColumnView',
        "params" => array(
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __( "Align", 'zona_plugin' ),
                    "param_name" => "align",
                    'std' => 'text-left',
                    "value" => array( 'Center' => 'text-center', 'Left' => 'text-left', 'Right' => 'text-right' ),
                    "admin_label" => false
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __( "Extra class name", 'zona_plugin' ),
                    "param_name" => "classes",
                    "value" => '',
                    "admin_label" => true,
                    "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __( 'Css', 'zona_plugin' ),
                    'param_name' => 'css',
                    'group' => __( 'Design options', 'zona_plugin' ),
                )
            )
        )  
    );
}
add_action( 'vc_before_init', 'zona_vc_buttons' );

function zona_vc_button() {

    // Get icons
    if ( function_exists( 'zona_get_icons' ) ) {
        $icons  = zona_get_icons();
    } else {
        $icons = array();
    }

    vc_map( array(
        "name" => __( "Button", 'zona_plugin' ),
        "base" => "button",
        "content_element" => true,
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Title", 'zona_plugin' ),
                "param_name" => "title",
                "value" => 'Button Title',
                "admin_label" => true,
                "description" => __( "Button title.", 'zona_plugin' )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Link", 'zona_plugin' ),
                "param_name" => "link",
                "value" => '#',
                "admin_label" => false,
                "description" => __( "Button LINK.", 'zona_plugin' )
            ),
             array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Type", 'zona_plugin' ),
                "param_name" => "type",
                "value" => array(  'Default' => '', 'Frame' => 'btn--frame' ),
                "std" => '',
                "admin_label" => false
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Size", 'zona_plugin' ),
                "param_name" => "size",
                "value" => array(  'Default' => '', 'Large' => 'btn--big', 'Medium' => 'btn--medium', 'Small' => 'btn--small' ),
                "std" => '',
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Style", 'zona_plugin' ),
                "param_name" => "style",
                "value" => array(  'Default' => '', 'Dark' => 'btn--dark', 'Light' => 'btn--light' ),
                "std" => '',
                "admin_label" => false,
            ),
            array(
                "type" => "checkbox",
                "class" => "",
                "heading" => __( "New Window", 'zona_plugin' ),
                "param_name" => "target",
                "value" => array( 'New window' => '0' ),
                "admin_label" => false,
                "description" => __( "Open link in new window/tab.", 'zona_plugin' )
            ),
            array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __( "Extra class name", 'zona_plugin' ),
                    "param_name" => "classes",
                    "value" => '',
                    "admin_label" => true,
                    "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
                ),
        )
   ));
}

add_action( 'vc_before_init', 'zona_vc_button' );

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_buttons extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_button extends WPBakeryShortCode {
    }
}



/* ----------------------------------------------------------------------

    IMAGE BUTTONS

/* ---------------------------------------------------------------------- */

function zona_vc_image_buttons() {

    vc_map( array(
        "name" => __( "Image Buttons", 'zona_plugin' ),
        "base" => "image_buttons",
        "as_parent" => array( 'only' => 'image_button' ),
        "content_element" => true,
        "show_settings_on_create" => false,
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "js_view" => 'VcColumnView',
        "params" => array(
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __( "Align", 'zona_plugin' ),
                    "param_name" => "align",
                    'std' => 'text-left',
                    "value" => array( 'Center' => 'text-center', 'Left' => 'text-left', 'Right' => 'text-right' ),
                    "admin_label" => false
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __( "Extra class name", 'zona_plugin' ),
                    "param_name" => "classes",
                    "value" => '',
                    "admin_label" => true,
                    "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __( 'Css', 'zona_plugin' ),
                    'param_name' => 'css',
                    'group' => __( 'Design options', 'zona_plugin' ),
                )
            )
        )  
    );
}
add_action( 'vc_before_init', 'zona_vc_image_buttons' );

function zona_vc_image_button() {

    // Get icons
    if ( function_exists( 'zona_get_icons' ) ) {
        $icons  = zona_get_icons();
    } else {
        $icons = array();
    }

    vc_map( array(
        "name" => __( "Image Button", 'zona_plugin' ),
        "base" => "image_button",
        "content_element" => true,
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Link", 'zona_plugin' ),
                "param_name" => "link",
                "value" => '#',
                "admin_label" => false,
                "description" => __( "Button LINK.", 'zona_plugin' )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Image", 'zona_plugin' ),
                "param_name" => "image",
                "value" => array(  
                    'Soundcloud'   => 'soundcloud', 
                    'iTunes'       => 'itunes',
                    'Google Play'  => 'google_play',
                    'Amazon'       => 'amazon',
                    'Bandcamp'     => 'bandcamp',
                    'Custom Image' => 'custom'
                ),
                "std" => 'soundcloud',
                "admin_label" => true
            ),
            array(
                "type" => "attach_image",
                "class" => "",
                "heading" => __( "Custom image max height is 40px.", 'zona_plugin' ),
                "param_name" => "image_custom",
                "dependency" => Array( 'element' => "image", 'value' => array( 'custom' ) )
            ),
            array(
                "type" => "checkbox",
                "class" => "",
                "heading" => __( "New Window", 'zona_plugin' ),
                "param_name" => "target",
                "value" => array( 'New window' => '0' ),
                "admin_label" => false,
                "description" => __( "Open link in new window/tab.", 'zona_plugin' )
            ),
        )
   ));
}

add_action( 'vc_before_init', 'zona_vc_image_button' );

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_image_buttons extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_image_button extends WPBakeryShortCode {
    }
}


/* ----------------------------------------------------------------------

    TRACKLIST

/* ---------------------------------------------------------------------- */

function zona_vc_tracklist() {

    global $wpdb;

    /* Get Audio Tracks  */
    $tracks = array();
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
    $tracks[''] = 0;
    if ( $sql_tracks ) {
        $count = 0;
        foreach( $sql_tracks as $track_post ) {
            $tracks[$track_post->post_title] = $track_post->id;
            // $tracks = array_push($variable, $newValue);
            $count++;
        }
    }

    vc_map( array(
        "name" => __( "Tracklist", 'zona_plugin' ),
        "base" => "tracklist",
        "class" => "",
        "icon" => plugin_dir_url( __FILE__ ) . "assets/images/VC-Scamp-Player.png",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Tracklist", 'zona_plugin' ),
                "param_name" => "id",
                "value" => $tracks,
                "admin_label" => true,
                "description" => __( "Select track ID.", 'zona_plugin' )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
        )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_tracklist' );


/* ----------------------------------------------------------------------

    SINGLE ALBUM

/* ---------------------------------------------------------------------- */

function zona_vc_music_album() {

    global $wpdb;

    /* Get Audio Tracks  */
    $tracks = array();
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
    $tracks[''] = 0;
    if ( $sql_tracks ) {
        $count = 0;
        foreach( $sql_tracks as $track_post ) {
            $tracks[$track_post->post_title] = $track_post->id;
            // $tracks = array_push($variable, $newValue);
            $count++;
        }
    }

    vc_map( array(
        "name" => __( "Music Album", 'zona_plugin' ),
        "base" => "music_album",
        "icon" => plugin_dir_url( __FILE__ ) . "assets/images/VC-Scamp-Player.png",
        "class" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Track", 'zona_plugin' ),
                "param_name" => "id",
                "value" => $tracks,
                "admin_label" => true,
                "description" => __( "Select track ID.", 'zona_plugin' )
            ),
            array(
                "type" => "attach_image",
                "class" => "",
                "heading" => __( "Album Cover", 'zona_plugin' ),
                "param_name" => "album_cover",
                "description" => __( "Set one image/cover album for all tracks.", 'zona_plugin' )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
      )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_music_album' );


/* ----------------------------------------------------------------------

    SHARE BUTTONS

/* ---------------------------------------------------------------------- */

function zona_vc_share_buttons() {

    vc_map( array(
        "name" => __( "Share Buttons", 'zona_plugin' ),
        "base" => "share_buttons",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __( "Extra class name", 'zona_plugin' ),
                    "param_name" => "classes",
                    "value" => '',
                    "admin_label" => true,
                    "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __( 'Css', 'zona_plugin' ),
                    'param_name' => 'css',
                    'group' => __( 'Design options', 'zona_plugin' ),
                )
            )
        )  
    );
}
add_action( 'vc_before_init', 'zona_vc_share_buttons' );


/* ----------------------------------------------------------------------

    EVENT MAP

/* ---------------------------------------------------------------------- */

function zona_vc_event_map() {

    vc_map( array(
        "name" => __( "Event Map", 'zona_plugin' ),
        "base" => "event_map",
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Height", 'zona_plugin' ),
                "param_name" => "height",
                "value" => __( "400", 'zona_plugin' ),
                "description" => "Map height (px).",
                "admin_label" => false
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Depth", 'zona_plugin' ),
                "param_name" => "depth",
                "value" => __( "15", 'zona_plugin' ),
                "description" => 'Zoom depth.',
                "admin_label" => false
            )
           
      )
    ));
}
add_action( 'vc_before_init', 'zona_vc_event_map' );


/* ----------------------------------------------------------------------

    DETAILS LIST

/* ---------------------------------------------------------------------- */

function zona_vc_details_list() {

    vc_map( array(
        "name" => __( "Details List", 'zona_plugin' ),
        "base" => "details_list",
        "as_parent" => array( 'only' => 'detail,event_detail' ),
        "content_element" => true,
        "show_settings_on_create" => false,
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "js_view" => 'VcColumnView',
        "params" => array(
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __( "Extra class name", 'zona_plugin' ),
                    "param_name" => "classes",
                    "value" => '',
                    "admin_label" => true,
                    "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
                ),
                 array(
                    'type' => 'css_editor',
                    'heading' => __( 'Css', 'zona_plugin' ),
                    'param_name' => 'css',
                    'group' => __( 'Design options', 'zona_plugin' ),
                )
            )
        ) 
    );
}

add_action( 'vc_before_init', 'zona_vc_details_list' );

function zona_vc_detail() {

    vc_map( array(
        "name" => __( "Detail", 'zona_plugin' ),
        "base" => "detail",
        "as_child" => array( 'only' => 'details_list' ),
        "content_element" => true,
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Title", 'zona_plugin' ),
                "param_name" => "label",
                "value" => '',
                "admin_label" => true
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Texts", 'zona_plugin' ),
                "param_name" => "value",
                "value" => '',
                "admin_label" => true
            ),
            array(
                "type" => "checkbox",
                "class" => "",
                "heading" => __( "Text as link", 'zona_plugin' ),
                "param_name" => "text_link",
                "value" => array( 'Yes' => '0' ),
                "admin_label" => false
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "URL", 'zona_plugin' ),
                "param_name" => "url",
                "value" => '',
                "admin_label" => true,
                "dependency" => Array( 'element' => "text_link", 'value' => array( '0' ) )
            ),
            array(
                "type" => "checkbox",
                "class" => "",
                "heading" => __( "New Window", 'zona_plugin' ),
                "param_name" => "target",
                "value" => array( 'New window' => '0' ),
                "admin_label" => false,
                "dependency" => Array( 'element' => "text_link", 'value' => array( '0' ) )
            ),
      )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_detail' );


function zona_vc_event_detail() {

    vc_map( array(
        "name" => __( "Event Detail", 'zona_plugin' ),
        "base" => "event_detail",
        "as_child" => array( 'only' => 'details_list' ),
        "content_element" => true,
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Title", 'zona_plugin' ),
                "param_name" => "label",
                "value" => '',
                "admin_label" => true
            ),

            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Get event detail", 'zona_plugin' ),
                "param_name" => "detail",
                "value" => array(  
                    'Date'            => 'date', 
                    'Place'           => 'place',
                    'Tickets Buttons' => 'buttons',
                    'Share Buttons'   => 'share'
                ),
                "std" => 'date',
                "admin_label" => true
            ),
           
            array(
                "type" => "checkbox",
                "class" => "",
                "heading" => __( "Text as link", 'zona_plugin' ),
                "param_name" => "text_link",
                "value" => array( 'Yes' => '0' ),
                "admin_label" => false
            ),
            array(
                "type" => "exploded_textarea",
                "class" => "",
                "heading" => __( "Buttons Links", 'zona_plugin' ),
                "param_name" => "buttons",
                "value" => 'Eventix|#|_blank,Events Pro|#|_blank',
                "desc" => __( "Add custom tickets buttons.(Note: divide links with linebreaks (Enter)) : title|http://yourlink|target", 'zona_plugin' ),
                "admin_label" => false,
                "dependency" => Array( 'element' => "detail", 'value' => array( 'buttons' ) )
            ),
           
      )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_event_detail' );

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_detail extends WPBakeryShortCode {
    }
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_event_detail extends WPBakeryShortCode {
    }
}

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_details_list extends WPBakeryShortCodesContainer {
    }
}


/* ----------------------------------------------------------------------

    EVENTS

/* ---------------------------------------------------------------------- */

function zona_vc_events() {

    vc_map( array(
        "name" => __( "Events", 'zona_plugin' ),
        "base" => "events",
        "icon" => "",
        "class" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(

            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Event Type", 'zona_plugin' ),
                "param_name" => "event_type",
                "value" => array( 
                    'Future Events' => 'future-events', 
                    'Past Events' => 'past-events',
                    'All Events' => 'all-events'
                ),
                "admin_label" => true
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Display", 'zona_plugin' ),
                "param_name" => "display_by",
                "value" => array( 
                    'All' => 'all', 
                    'By Categories' => 'zona_events_cats'
                ),
                "admin_label" => false
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Taxonomies", 'zona_plugin' ),
                "param_name" => "terms",
                "admin_label" => false,
                "description" => __( "Type slugs separated by commas. ex: festivals,open-air", 'zona_plugin' ),
                "dependency" => Array( 'element' => "display_by", 'value' => array( 'zona_events_cats' ) )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Limit", 'zona_plugin' ),
                "param_name" => "limit",
                "value" => '40',
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Background", 'zona_plugin' ),
                "param_name" => "background",
                "value" => array( 'Light' => 'bg--light', 'Dark' => 'bg--dark' ),
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
            ),
             array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
        )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_events' );


/* ----------------------------------------------------------------------

    GALLERY ALBUMS

/* ---------------------------------------------------------------------- */

function zona_vc_gallery() {

    vc_map( array(
        "name" => __( "Gallery Albums", 'zona_plugin' ),
        "base" => "gallery_albums",
        "icon" => "",
        "class" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(

            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Display", 'zona_plugin' ),
                "param_name" => "display_by",
                "value" => array( 
                    'All' => 'all', 
                    'By Categories' => 'zona_gallery_cats'
                ),
                "admin_label" => false
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Taxonomies", 'zona_plugin' ),
                "param_name" => "terms",
                "admin_label" => false,
                "description" => __( "Type slugs separated by commas. ex: dj-nando,general-midi,zona", 'zona_plugin' ),
                "dependency" => Array( 'element' => "display_by", 'value' => array( 'zona_gallery_cats' ) )
            ),

            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Style", 'zona_plugin' ),
                "param_name" => "style",
                'std' => 'grid',
                "value" => array( 'Grid' => 'grid', 'Mosaic' => 'mosaic' ),
                "admin_label" => false
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Columns", 'zona_plugin' ),
                "param_name" => "columns",
                'std' => 'grid-4',
                "value" => array( '3 Columns' => 'grid-4', '4 Columns' => 'grid-3', '2 Columns' => 'grid-6' ),
                "admin_label" => false,
                "dependency" => Array( 'element' => "style", 'value' => array( 'grid' ) )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Gap", 'zona_plugin' ),
                "param_name" => "gap",
                "value" => array( 'Without Gap' => '', 'Medium Gap' => 'grid-row-pad', 'Large Gap' => 'grid-row-pad-large'),
                "admin_label" => false,
                "dependency" => Array( 'element' => "style", 'value' => array( 'grid' ) )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Limit", 'zona_plugin' ),
                "param_name" => "limit",
                "value" => '5',
                "admin_label" => false,
                
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Link", 'zona_plugin' ),
                "param_name" => "url",
                "value" => '',
                "admin_label" => false,
                "description" => __( "Last item will be a link with items counter.", 'zona_plugin' )
            ),

            array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __( "Extra class name", 'zona_plugin' ),
                    "param_name" => "classes",
                    "value" => '',
                    "admin_label" => true,
                    "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
                ),
             array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
        )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_gallery' );


/* ----------------------------------------------------------------------

    GALLERY Images

/* ---------------------------------------------------------------------- */

function zona_vc_gallery_images() {
    global $wpdb;

     /* Get Sliders  */
    $albums = array();
    $albums_post_type = 'zona_gallery';
    $albums_query = $wpdb->prepare(
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
        $albums_post_type
    );

    $sql_albums = $wpdb->get_results( $albums_query );
    $albums[''] = '';
    if ( $sql_albums ) {
        $count = 0;
        foreach( $sql_albums as $track_post ) {
            $albums[$track_post->post_title] = $track_post->id;
            // $albums = array_push($variable, $newValue);
            $count++;
        }
    }

    vc_map( array(
        "name" => __( "Gallery Images", 'zona_plugin' ),
        "base" => "gallery_images",
        "icon" => "",
        "class" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
             array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Display Images from", 'zona_plugin' ),
                "param_name" => "album_id",
                "value" => $albums
            ),
            array(
                "type" => "checkbox",
                "class" => "",
                "heading" => __( "Link", 'zona_plugin' ),
                "param_name" => "url",
                "value" => '0',
                "admin_label" => false,
                "description" => __( "Last item will be a link with items counter.", 'zona_plugin' )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Style", 'zona_plugin' ),
                "param_name" => "style",
                'std' => 'grid',
                "value" => array( 'Grid' => 'grid', 'Mosaic' => 'mosaic' ),
                "admin_label" => false
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Columns", 'zona_plugin' ),
                "param_name" => "columns",
                'std' => 'grid-4',
                "value" => array( '3 Columns' => 'grid-4', '4 Columns' => 'grid-3', '2 Columns' => 'grid-6' ),
                "admin_label" => false,
                "dependency" => Array( 'element' => "style", 'value' => array( 'grid' ) )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Gap", 'zona_plugin' ),
                "param_name" => "gap",
                "value" => array( 'Without Gap' => '', 'Medium Gap' => 'grid-row-pad', 'Large Gap' => 'grid-row-pad-large'),
                "admin_label" => false,
                "dependency" => Array( 'element' => "style", 'value' => array( 'grid' ) )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Limit", 'zona_plugin' ),
                "param_name" => "limit",
                "value" => '5',
                "admin_label" => false, 
            ),
            array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __( "Extra class name", 'zona_plugin' ),
                    "param_name" => "classes",
                    "value" => '',
                    "admin_label" => true,
                    "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
                ),
             array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
        )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_gallery_images' );


/* ----------------------------------------------------------------------

     VIDEOS

/* ---------------------------------------------------------------------- */

function zona_vc_videos() {

    vc_map( array(
        "name" => __( "Videos", 'zona_plugin' ),
        "base" => "videos",
        "icon" => "",
        "class" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(

            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Style", 'zona_plugin' ),
                "param_name" => "style",
                'std' => 'grid',
                "value" => array( 'Grid' => 'grid', 'Mosaic' => 'mosaic' ),
                "admin_label" => false
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Columns", 'zona_plugin' ),
                "param_name" => "columns",
                'std' => 'grid-4',
                "value" => array( '3 Columns' => 'grid-4', '4 Columns' => 'grid-3', '2 Columns' => 'grid-6' ),
                "admin_label" => false,
                "dependency" => Array( 'element' => "style", 'value' => array( 'grid' ) )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Gap", 'zona_plugin' ),
                "param_name" => "gap",
                "value" => array( 'Without Gap' => '', 'Medium Gap' => 'grid-row-pad', 'Large Gap' => 'grid-row-pad-large'),
                "admin_label" => false,
                "dependency" => Array( 'element' => "style", 'value' => array( 'grid' ) )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Limit", 'zona_plugin' ),
                "param_name" => "limit",
                "value" => '5',
                "admin_label" => false,
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Link", 'zona_plugin' ),
                "param_name" => "url",
                "value" => '',
                "admin_label" => false,
                "description" => __( "Last item will be a link with items counter.", 'zona_plugin' )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
            ),
             array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
        )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_videos' );


/* ----------------------------------------------------------------------

    SINGLE VIDEO

/* ---------------------------------------------------------------------- */

function zona_vc_single_video() {
    global $wpdb;

     /* Get Sliders  */
    $videos = array();
    $videos_post_type = 'zona_videos';
    $videos_query = $wpdb->prepare(
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
        $videos_post_type
    );

    $sql_videos = $wpdb->get_results( $videos_query );
    $videos[''] = '';
    if ( $sql_videos ) {
        $count = 0;
        foreach( $sql_videos as $track_post ) {
            $videos[$track_post->post_title] = $track_post->id;
            // $videos = array_push($variable, $newValue);
            $count++;
        }
    }

    vc_map( array(
        "name" => __( "Single Video", 'zona_plugin' ),
        "base" => "single_video",
        "icon" => "",
        "class" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
             array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Select Video", 'zona_plugin' ),
                "param_name" => "id",
                "value" => $videos
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Thumb Size", 'zona_plugin' ),
                "param_name" => "thumb_size",
                "value" => 'large',
                "admin_label" => false, 
                "description" => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'zona_plugin' )
            ),
            array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __( "Extra class name", 'zona_plugin' ),
                    "param_name" => "classes",
                    "value" => '',
                    "admin_label" => true,
                    "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
                ),
             array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
        )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_single_video' );


/* ----------------------------------------------------------------------

    GOOGLE MAPS

/* ---------------------------------------------------------------------- */

function zona_vc_google_maps() {

    vc_map( array(
        "name" => __( "Google Maps", 'zona_plugin' ),
        "base" => "google_maps",
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Height", 'zona_plugin' ),
                "param_name" => "height",
                "value" => __( "400", 'zona_plugin' ),
                "description" => "Map height (px).",
                "admin_label" => false
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Address", 'zona_plugin' ),
                "param_name" => "address",
                "value" => __( "Level 13, 2 Elizabeth St, Melbourne Victoria 3000 Australia", 'zona_plugin' ),
                "description" => 'Map address.',
                "admin_label" => true
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Depth", 'zona_plugin' ),
                "param_name" => "depth",
                "value" => __( "15", 'zona_plugin' ),
                "description" => 'Zoom depth.',
                "admin_label" => false
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Zoom Control", 'zona_plugin' ),
                "param_name" => "zoom_control",
                "value" => array(
                    "No" => "false",
                    "Yes" => "true"
                ),
                "description" => "Zoom control."
            ),
           array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Scroll Whell", 'zona_plugin' ),
                "param_name" => "scrollwheel",
                "value" => array(
                    "No" => "false",
                    "Yes" => "true"
                ),
                "description" => "Mouse scroll whell."
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
           
      )
    ));
}
add_action( 'vc_before_init', 'zona_vc_google_maps' );


/* ----------------------------------------------------------------------

    EVENT COUNTDOWN

/* ---------------------------------------------------------------------- */

function zona_vc_event_countdown() {

    // Get events 
    $future_tax = array(
        array(
           'taxonomy' => 'zona_event_type',
           'field' => 'slug',
           'terms' => 'future-events'
          )
    );
    $future_events = get_posts( array(
        'post_type' => 'zona_events',
        'showposts' => -1,
        'tax_query' => $future_tax,
        'orderby' => 'meta_value',
        'meta_key' => '_event_date_start',
        'order' => 'ASC'
    ));

    $events = array();
    foreach( $future_events as $event ) {
        $events[$event->post_title] = $event->ID;
    }

    vc_map( array(
        "name" => __( "Event Countdown", 'zona_plugin' ),
        "base" => "event_countdown",
        "icon" => "",
        "class" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
             array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Style", 'zona_plugin' ),
                "param_name" => "style",
                "value" => array( 'Compact' => 'compact', 'Big' => 'big' ),
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Display", 'zona_plugin' ),
                "param_name" => "display_by",
                "value" => array( 
                    'All' => 'all', 
                    'By Categories' => 'zona_events_cats'
                ),
                "admin_label" => false
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Taxonomies", 'zona_plugin' ),
                "param_name" => "terms",
                "admin_label" => false,
                "description" => __( "Type slugs separated by commas. ex: dj-nando,general-midi,zona", 'zona_plugin' ),
                "dependency" => Array( 'element' => "display_by", 'value' => array( 'zona_events_cats' ) )
            ),
            array(
                "type" => "checkbox",
                "class" => "",
                "heading" => __( "Select Custom Event", 'zona_plugin' ),
                "param_name" => "custom_event",
                "value" => array( 'Yes, please' => '0' ),
                'std' => '',
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Custom Event ID", 'zona_plugin' ),
                "param_name" => "custom_event_id",
                "value" => $events,
                "admin_label" => true,
                "dependency" => Array( 'element' => "custom_event", 'value' => array( '0' ) )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Background", 'zona_plugin' ),
                "param_name" => "background",
                "value" => array( 'Light' => 'bg--light', 'Dark' => 'bg--dark' ),
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
        ),
   ) );
}

add_action( 'vc_before_init', 'zona_vc_event_countdown' );


/* ----------------------------------------------------------------------

    MUSIC GRID

/* ---------------------------------------------------------------------- */

function zona_vc_music() {

    vc_map( array(
        "name" => __( "Music GRID", 'zona_plugin' ),
        "base" => "music_grid",
        "icon" => "",
        "class" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
             array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Link Action", 'zona_plugin' ),
                "param_name" => "link_action",
                "value" => array( __( "Go to music album", 'zona_plugin' ) => 'permalink',  __( "Play music", 'zona_plugin' ) => 'play_music', __( "Disabled", 'zona_plugin' ) => 'disabled'),
                "admin_label" => false,
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Post IDs", 'zona_plugin' ),
                "param_name" => "posts_in",
                "value" => '',
                "admin_label" => false,
                "description" => __( "Filter multiple posts by ID. Enter here the post IDs separated by commas (ex: 21,34,418). To exclude posts from this module add them with '-' (ex: -10, -26).", 'zona_plugin' )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Display", 'zona_plugin' ),
                "param_name" => "display_by",
                "value" => array( 
                    'All' => 'all', 
                    'By Categories' => 'zona_music_cats'
                ),
                "admin_label" => false,
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Taxonomies", 'zona_plugin' ),
                "param_name" => "terms",
                "admin_label" => false,
                "description" => __( "Type slugs separated by commas. ex: dj-nando,general-midi,zona", 'zona_plugin' ),
                "dependency" => Array( 'element' => "display_by", 'value' => array( 'zona_music_cats' ) )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Order By", 'zona_plugin' ),
                "param_name" => "order",
                "value" => array( 'Custom' => 'menu_order', 'Title' => 'title', 'Date' => 'date' ),
                "admin_label" => false,
                "description" => __( "Select menu order.", 'zona_plugin' )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Order", 'zona_plugin' ),
                "param_name" => "orderby",
                "value" => array( 'Ascending' => 'ASC', 'Descending' => 'DESC' ),
                "admin_label" => false,
                "description" => __( "Select menu order.", 'zona_plugin' )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Columns", 'zona_plugin' ),
                "param_name" => "columns",
                'std' => 'grid-4',
                "value" => array( '3 Columns' => 'grid-4', '4 Columns' => 'grid-3', '2 Columns' => 'grid-6' ),
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Gap", 'zona_plugin' ),
                "param_name" => "gap",
                "value" => array( 'Without Gap' => '', 'Medium Gap' => 'grid-row-pad', 'Large Gap' => 'grid-row-pad-large'),
                "admin_label" => false,
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Limit", 'zona_plugin' ),
                "param_name" => "limit",
                "value" => '5',
                "admin_label" => false,
            ),
             array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
        )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_music' );


/* ----------------------------------------------------------------------

    MUSIC CAROUSEL

/* ---------------------------------------------------------------------- */

function zona_vc_music_carousel() {

    vc_map( array(
        "name" => __( "Music Carousel", 'zona_plugin' ),
        "base" => "music_carousel",
        "icon" => "",
        "class" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Link Action", 'zona_plugin' ),
                "param_name" => "link_action",
                "value" => array( __( "Go to music album", 'zona_plugin' ) => 'permalink',  __( "Play music", 'zona_plugin' ) => 'play_music', __( "Disabled", 'zona_plugin' ) => 'disabled'),
                "admin_label" => false,
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Post IDs", 'zona_plugin' ),
                "param_name" => "posts_in",
                "value" => '',
                "admin_label" => false,
                "description" => __( "Filter multiple posts by ID. Enter here the post IDs separated by commas (ex: 21,34,418). To exclude posts from this module add them with '-' (ex: -10, -26).", 'zona_plugin' )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Display", 'zona_plugin' ),
                "param_name" => "display_by",
                "value" => array( 
                    'All' => 'all', 
                    'By Categories' => 'zona_music_cats'
                ),
                "admin_label" => false,
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Taxonomies", 'zona_plugin' ),
                "param_name" => "terms",
                "admin_label" => false,
                "description" => __( "Type slugs separated by commas. ex: dj-nando,general-midi,zona", 'zona_plugin' ),
                "dependency" => Array( 'element' => "display_by", 'value' => array( 'zona_music_cats' ) )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Background", 'zona_plugin' ),
                "param_name" => "background",
                "value" => array( 'Light' => 'bg--light', 'Dark' => 'bg--dark' ),
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Order By", 'zona_plugin' ),
                "param_name" => "order",
                "value" => array( 'Custom' => 'menu_order', 'Title' => 'title', 'Date' => 'date' ),
                "admin_label" => false,
                "description" => __( "Select menu order.", 'zona_plugin' )
            ),
           array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Visible Releases", 'zona_plugin' ),
                "param_name" => "visible_items",
                "value" => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Gap", 'zona_plugin' ),
                "param_name" => "gap",
                "value" => array( 'Without Gap' => '', 'Medium Gap' => 'carousel-pad'),
                "admin_label" => false,
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Limit", 'zona_plugin' ),
                "param_name" => "limit",
                "value" => '5',
                "admin_label" => false,
            ),
             array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
        )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_music_carousel' );


/* ----------------------------------------------------------------------

    PRICING COLUMN

/* ---------------------------------------------------------------------- */

function zona_vc_pricing_column() {

    vc_map( array(
        "name" => __( "Pricing Column", 'zona_plugin' ),
        "base" => "pricing_column",
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Background", 'zona_plugin' ),
                "param_name" => "background",
                "value" => array( 'Light' => 'bg--light', 'Dark' => 'bg--dark' ),
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Title", 'zona_plugin' ),
                "param_name" => "title",
                "value" => __( "Basic Plan", 'zona_plugin' ),
                "description" => "",
                "admin_label" => true
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Price", 'zona_plugin' ),
                "param_name" => "price",
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Currency", 'zona_plugin' ),
                "param_name" => "currency",
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Price Period", 'zona_plugin' ),
                "param_name" => "period",
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Link", 'zona_plugin' ),
                "param_name" => "link",
                "description" => ""
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Target", 'zona_plugin' ),
                "param_name" => "target",
                "value" => array(
                    "" => "",
                    "Self" => "_self",
                    "Blank" => "_blank",
                    "Parent" => "_parent"
                ),
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Button Text", 'zona_plugin' ),
                "param_name" => "button_text",
                "description" => ""
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Important", 'zona_plugin' ),
                "param_name" => "important",
                "value" => array(
                    "No" => "no",
                    "Yes" => "yes"
                ),
                "description" => ""
            ),
            array(
                "type" => "exploded_textarea",
                "class" => "",
                "heading" => __( "Content", 'zona_plugin' ),
                "param_name" => "list",
                "value" => "2x option 1,Free option 2,Unlimited option 3,Unlimited option 4,1x option 5",
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
      )
    ));
}

add_action( 'vc_before_init', 'zona_vc_pricing_column' );


/* ----------------------------------------------------------------------

    SERVICE BOX

/* ---------------------------------------------------------------------- */

function zona_vc_service_box() {

    vc_map( array(
        "name" => __( "Service Box", 'zona_plugin' ),
        "base" => "service_box",
        "class" => "",
        "icon" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Background", 'zona_plugin' ),
                "param_name" => "background",
                "value" => array( 'Light' => 'bg--light', 'Dark' => 'bg--dark' ),
            ),
            array(
                "type" => "attach_image",
                "class" => "",
                "heading" => __( "Image", 'zona_plugin' ),
                "param_name" => "image",
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Image width", 'zona_plugin' ),
                "param_name" => "width",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Set image fixed width in px e.g.: 80px.", 'zona_plugin' )
            ),
             array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Image height", 'zona_plugin' ),
                "param_name" => "height",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Set image fixed height in px e.g.: 80px.", 'zona_plugin' )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Link", 'zona_plugin' ),
                "param_name" => "link",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Add custom link e.g. http://google.com.", 'zona_plugin' )
            ),
            array(
                "type" => "checkbox",
                "class" => "",
                "heading" => __( "New Window", 'zona_plugin' ),
                "param_name" => "target",
                "value" => array( 'New window' => '0' ),
                "admin_label" => false,
                "description" => __( "Open link in new window/tab.", 'zona_plugin' )
            ),
            array(
                "type" => "exploded_textarea",
                "class" => "",
                "heading" => __( "Text", 'zona_plugin' ),
                "param_name" => "text",
                "value" => "Lorem ipsum dolor sit amet consectetuer, elit sed diam nonummy nibh",
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'zona_plugin' )
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
      )
    ));
}

add_action( 'vc_before_init', 'zona_vc_service_box' );


/* ----------------------------------------------------------------------

    POSTS GRID

/* ---------------------------------------------------------------------- */

function zona_vc_posts() {

    vc_map( array(
        "name" => __( "Posts GRID", 'zona_plugin' ),
        "base" => "posts_grid",
        "icon" => "",
        "class" => "",
        "category" => __( 'by Rascals', 'zona_plugin' ),
        "params" => array(
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Sort order", 'zona_plugin' ),
                "param_name" => "order",
                "value" => array( 
                    esc_attr__( "Latest (default)", 'zona_plugin' ) => 'date',
                    esc_attr__( "Alphabetical A -> Z", 'zona_plugin' ) => 'title',
                    esc_attr__( "Random", 'zona_plugin' ) => 'rand',
                    esc_attr__( "Most Commented", 'zona_plugin' ) => 'comment_count',
                    
                ),
                "admin_label" => false,
                "description" => __( "How to sort the posts.", 'zona_plugin' )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Post IDs", 'zona_plugin' ),
                "param_name" => "posts_in",
                "value" => '',
                "admin_label" => false,
                "description" => __( "Filter multiple posts by ID. Enter here the post IDs separated by commas (ex: 21,34,418). To exclude posts from this module add them with '-' (ex: -10, -26).", 'zona_plugin' )
            ),
            array(
                "type" => "exploded_textarea",
                "class" => "",
                "heading" => __( "Categories", 'zona_plugin' ),
                "param_name" => "categories_in",
                "value" => "",
                "description" => __( "Filter multiple categories by ID. Enter here the categories IDs separated by commas (ex: 21,34,418). To exclude categories from this module add them with '-' (ex: -10, -26).", 'zona_plugin' )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __( "Columns", 'zona_plugin' ),
                "param_name" => "columns",
                'std' => 'grid-4',
                "value" => array( '3 Columns' => 'grid-4', '2 Columns' => 'grid-6', '1 Column' => 'grid-12' ),
                "admin_label" => false,
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Limit post number", 'zona_plugin' ),
                "param_name" => "limit",
                "value" => '6',
                "admin_label" => false,
                "description" => __( "If the field is empty the limit post number will be 6", 'zona_plugin' )
            ),
             array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Offset", 'zona_plugin' ),
                "param_name" => "offset",
                "value" => '',
                "admin_label" => false,
                "description" => __( "Start the count with an offset. If you have a block that shows 3 posts before this one, you can make this one start from the 4'th post (by using offset 3)", 'zona_plugin' )
            ),
            array(
                "type" => "checkbox",
                "class" => "",
                "param_name" => "show_featured",
                "value" => array( 'Show Posts Images' => '0' ),
                "admin_label" => false,
            ),
             array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Extra class name", 'zona_plugin' ),
                "param_name" => "classes",
                "value" => '',
                "admin_label" => true,
                
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'zona_plugin' ),
                'param_name' => 'css',
                'group' => __( 'Design options', 'zona_plugin' ),
            )
        )
   ) );
}

add_action( 'vc_before_init', 'zona_vc_posts' );