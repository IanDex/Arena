<?php
/**
 * Plugin Name:     Zona Extensions
 * Theme Author:    Mariusz Rek - Rascals Themes
 * Theme URI:       http://rascalsthemes.com/zona
 * Author URI:      http://rascalsthemes.com
 * File:            shortcodes.php
 * =========================================================================================================================================
 *
 * @package zona_plugin
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Global shortcode ID */
global $zona_sid;

$zona_sid = 0;


/* ----------------------------------------------------------------------
    Color

    Example Usage:
    [styles classes="" ] ...content... [/styles]
/* ---------------------------------------------------------------------- */
function zona_styles( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'classes'     => ''
    ), $atts));

    return '<span class="' . esc_attr( $classes ) . '" >' . do_shortcode( $content ) . '</span>';
}
add_shortcode( 'styles', 'zona_styles' );


/* ----------------------------------------------------------------------
    Line link

    Example Usage:
    [line_link url="" target="_self" classes="" ]...CONTENT...[/line_link]
/* ---------------------------------------------------------------------- */
function zona_line_link( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'title' => __( 'Link Title', 'zona_plugin' ),
        'url' => '',
        'target' => '',
        'classes'     => ''
    ), $atts));

    if ( $url != '' ) {
      $url = 'href="' . $url . '"';
    }

    return '<a ' . $url . ' class="line-link ' . esc_attr( $classes ) . '" target="'. $target .'">' . do_shortcode( $content ) . '</a>';
}
add_shortcode( 'line_link', 'zona_line_link' );


/* ----------------------------------------------------------------------
    Extended Content

    Example Usage:
    [extended classes="extended"]...CONTENT...[/extended]
/* ---------------------------------------------------------------------- */
function zona_extended( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'classes'     => ''
    ), $atts));

    return '<div class="content--extended ' . esc_attr( $classes ) . '">' . do_shortcode( $content ) . '</div>';
}
add_shortcode( 'extended', 'zona_extended' );


/* ----------------------------------------------------------------------
    Hero text

    Example Usage:
    [hero_text classes=""]...CONTENT...[/hero_text]
/* ---------------------------------------------------------------------- */
function zona_hero_text( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'classes'     => ''
    ), $atts));

    return '<div class="hero-text ' . esc_attr( $classes ) . '">' . do_shortcode( $content ) . '</div>';
}
add_shortcode( 'hero_text', 'zona_hero_text' );


/* ----------------------------------------------------------------------
    SIMPLE TRACKLIST

    Example Usage:
    [tracklist id="1" tracklist_id="simpleTracklist_01"]
    
    Attributes:
    id - Tracklist post id. Default: 0 (integer). 

/* ---------------------------------------------------------------------- */
function zona_simple_tracklist( $atts, $content = null ) {

    global $zona_sid;

    extract(shortcode_atts(array(
         'id'           => 0,
         'tracklist_id' => 'simpleTracklist_01',
         'classes'      => ''
    ), $atts));

    $output = '';

    if ( $id == 0 || ! function_exists( 'scamp_player_get_list' ) || ! scamp_player_get_list( $id ) ) {
        return false;
    }

    $tracklist = scamp_player_get_list( $id );
    $output .= '<ol id="' . esc_attr( $tracklist_id ) . '" class="sp-list">' ."\n";

    $count = 0;

    // Simple style
    foreach ( $tracklist as $track ) {
       if ( ! $track['cover'] || $track['cover'] == '' ) {
            $track['cover'] = get_template_directory_uri() . '/images/no-track-image.png';
        }
        $count ++;
        $output .= '
        <li class="sp-track-item">
            <a href="' .  esc_url( $track['url'] ) . '" class="simple-track sp-play-track" data-cover="' . esc_url( $track['cover'] ) . '" data-artist="' . esc_attr( $track['artists'] ) . '" data-artist_url="' . esc_url( $track['artists_url'] ) . '" data-artist_target="' . esc_attr( $track['artists_target'] ) . '" data-release_url="' . esc_url( $track['release_url'] ) . '" data-release_target="' . esc_attr( $track['release_target'] ) . '" data-shop_url="' . esc_url( $track['cart_url'] ) . '" data-shop_target="' . esc_attr( $track['cart_target'] ) . '" data-free_download="' . esc_attr( $track['free_download'] ) . '">
                <span class="artists">' . $track['artists'] . '</span>
                <span class="track-title">' . $track['title'] . '</span>
            </a>   
        </li>';
    }
    

    $output .= '</ol>' ."\n";

   return $output;
}
add_shortcode( 'simple_tracklist', 'zona_simple_tracklist' );


/* ----------------------------------------------------------------------
    MUSIC ALBUM

    Example Usage:
    [music_album id="1" album_cover="0"]
    
    Attributes:
    id - Tracklist post id. Default: 0 (integer). 


/* ---------------------------------------------------------------------- */
function zona_music_album( $atts, $content = null ) {

    global $zona_sid;

    extract(shortcode_atts(array(
        'id'          => 0,
        'album_cover' => 0,
        'size'        => 'zona-release-thumb',
        'classes'     => '',
        'css'         => ''
    ), $atts));

    $output = '';

    if ( $id == 0 || ! function_exists( 'scamp_player_get_list' ) || ! scamp_player_get_list( $id ) ) {
        return false;
    }

    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'music_album', $atts );

    $zona_sid++;

    $tracklist = scamp_player_get_list( $id );
    $list ='';

    // Album Cover
    $image = wp_get_attachment_image_src( $album_cover, $size );
    $image = $image[0];
    if ( $image || $image != '' ) {
        $classes .= ' is-album-image';
    }
    $output .= '<div class="music-album--wrap ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';

    $count = 0;

    // tracklist Loop
    foreach ( $tracklist as $track ) {
        if ( ! $track['cover'] || $track['cover'] == '' ) {
            $track['cover'] = get_template_directory_uri() . '/images/no-track-image.png';
        }

        if ( $count == 0 ) {
            if ( $image || $image != '' ) {
                $cover = $image;
            } else {
                $cover = $track['cover_full'];
            }
            if ( $track['title']!= '' ) {
                $title = '<span class="is-active">' . $track['title'] . '</span>';
            } else {
                $title = '<span>' . $track['title'] . '</span>';
            }
            if ( $track['artists'] != '' ) {
                $artists = '<span class="is-active">' . $track['artists'] . '</span>';
            } else {
                $artists = '<span>' . $track['artists'] . '</span>';
            }
            $waveform = $track['waveform'];
        }
        $count ++;
        $list .= '
        <li class="sp-track-item">
            <a href="' .  esc_url( $track['url'] ) . '" class="simple-track sp-play-track sp-play-next" data-cover="' . esc_url( $track['cover'] ) . '" data-artist="' . esc_attr( $track['artists'] ) . '" data-artist_url="' . esc_url( $track['artists_url'] ) . '" data-artist_target="' . esc_attr( $track['artists_target'] ) . '" data-release_url="' . esc_url( $track['release_url'] ) . '" data-release_target="' . esc_attr( $track['release_target'] ) . '" data-shop_url="' . esc_url( $track['cart_url'] ) . '" data-shop_target="' . esc_attr( $track['cart_target'] ) . '" data-free_download="' . esc_attr( $track['free_download'] ) . '" data-waveform="'. $track['waveform'] .'" data-cover_full="' . esc_url( $track['cover_full'] ) . '">
                <span class="artists">' . $track['artists'] . '</span>
                <span class="track-title">' . $track['title'] . '</span>
            </a>   
        </li>';
    }
    
    $output .= '<div class="music-album">';

    // Cover wrap
    $output .= '<div class="music-album--cover-wrap">';

    $output .= '<div class="music-album--img-holder">';
    $output .= '<div class="music-album--cover" style="background-image:url(' . esc_url( $cover ) . ')"></div>';
    $output .= '</div>'; // end img-holder

    // CTRL
    $output .= '<div class="music-album--ctrl">';
    $output .= '<a href="#" class="music-album--prev"></a>';
    $output .= '<a href="#" class="music-album--next"></a>';
    $output .= '<a href="#" class="music-album--play"></a>';
    $output .= '</div>'; // end crl wrap
    $output .= '</div>'; // end cover wrap

    // Metadata
    $output .= '<div class="music-album--meta">';
    $output .= '<div class="music-album--title">' . $title . '</div>';
    $output .= '<div class="music-album--artists">' . $artists . '</div>';
    $output .= '</div>';

    // Waveform
    $output .= '<div class="music-album--waveform-wrap">';

        $output .= '<div class="music-album--waveform-top">';

        if ( $waveform != '' ) {
            $output .= '<img src="'.esc_url($waveform).'" alt="track waveform">';
        }
        $output .= '</div>';

        $output .= '<div class="music-album--waveform-bottom">';

        if ( $waveform != '' ) {
            $output .= '<img src="'.esc_url($waveform).'" alt="track waveform">';
        }
        $output .= '</div>';

    $output .= '</div>'; //waveform;


    $output .= '</div>'; // end music album

    // Display list
    $output .= '<ol id="tracklist--' . esc_attr( $zona_sid ) . '" class="sp-list">' . $list . '</ol>';

    $output .= '</div>'; //end wrap

   return $output;
}
add_shortcode( 'music_album', 'zona_music_album' );


/* ----------------------------------------------------------------------
    TRACKLIST

    Example Usage:
    [tracklist id="1"]
    
    Attributes:
    id - Tracklist post id. Default: 0 (integer). 

/* ---------------------------------------------------------------------- */
function zona_tracklist( $atts, $content = null ) {

    global $zona_sid;

    extract(shortcode_atts(array(
        'id'      => 0,
        'classes' => '',
        'css'     => ''
    ), $atts));

    $output = '';

    if ( $id == 0 || ! function_exists( 'scamp_player_get_list' ) || ! scamp_player_get_list( $id ) ) {
        return false;
    }

    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'tracklist', $atts );

    $zona_sid++;

    $tracklist = scamp_player_get_list( $id );
    $output .= '<div class="tracklist--wrap ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';
    $output .= '<ul id="tracklist--' . esc_attr( $zona_sid ) . '" class="sp-list sp--tracklist">' ."\n";

    $count = 0;

    // Simple style
    foreach ( $tracklist as $track ) {
       if ( ! $track['cover'] || $track['cover'] == '' ) {
            $track['cover'] = get_template_directory_uri() . '/images/no-track-image.png';
        }
        $count ++;
        if ( $track['artists'] != '' ) {
            $artists = '<span class="track-artists">' . $track['artists'] . '</span>';
        } else {
            $artists = '';
        }

        // Waveform
        if ( $track['waveform'] ) {
            $track['waveform'] = 'style="background-image: url(' . $track['waveform'] . ')"';
            $waveform_class = 'is-waveform';
        } else {
            $track['waveform'] = '';
            $waveform_class = 'no-waveform';
        }

        $output .= '
        <li class="sp-track-item">
            <div class="track-row track-row-data">
                <a href="' .  esc_url( $track['url'] ) . '" class="track track-col sp-play-track sp-play-next" data-cover="' . esc_url( $track['cover'] ) . '" data-artist="' . esc_attr( $track['artists'] ) . '" data-artist_url="' . esc_url( $track['artists_url'] ) . '" data-artist_target="' . esc_attr( $track['artists_target'] ) . '" data-release_url="' . esc_url( $track['release_url'] ) . '" data-release_target="' . esc_attr( $track['release_target'] ) . '" data-shop_url="' . esc_url( $track['cart_url'] ) . '" data-shop_target="' . esc_attr( $track['cart_target'] ) . '" data-free_download="' . esc_attr( $track['free_download'] ) . '" data-control="sp-progress-' . esc_attr( $zona_sid ) . '-'. esc_attr( $count ) .'">
                    <span class="track-status"><span class="nr">' . sprintf( "%02d", $count ) . '</span><span class="status-icon"></span></span>
                    <span class="track-title">' . $track['title'] . '</span>'. $artists .'
                </a>';

                if ( $track['lyrics'] != '' || $track['cart_url'] != '' ||  $track['track_length'] != '' ) {

                    if ( $track['lyrics'] == '' && $track['cart_url'] == '' ) {
                        $output .= '<div class="track-col-meta track-col-mobile-hide">';
                    } else {
                        $output .= '<div class="track-col-meta">';
                    }
                
                        $output .= '<div class="track-col track-col-lyrics">';
                        if ( $track['lyrics'] != '' ) {
                            $output .= '<span class="track-lyrics">' . __( 'Lyrics', 'zona_plugin' ) . '</span>';
                        }

                        if ( $track['track_length'] != '' ) {
                            $output .= '<span class="track-length">' . $track['track_length'] . '</span>';
                        }
                        $output .= '</div>';

                        $output .= '<div class="track-col track-col-buttons">';
                        if ( $track['cart_url'] != '' ) {
                            if ( $track['free_download'] == 'yes' ) {
                                $output .= '<a href="' . esc_url($track['cart_url']) . '" target="' . esc_attr( $track['cart_target'] ) . '" class="track-button track-download anim--reveal"><span>' . __( 'Download', 'zona_plugin' ) . '</span></a>';
                            } else {
                                $output .= '<a href="' . esc_url($track['cart_url']) . '" target="' . esc_attr( $track['cart_target'] ) . '" class="track-button track-buy anim--reveal"><span>' . __( 'Buy Track', 'zona_plugin' ) . '</span></a>';
                            }
                        }
                        $output .= '</div>';
                    $output .= '</div>';
                }
            $output .= '</div>'; //row

            $output .= '<div id="sp-progress-' . esc_attr( $zona_sid ) . '-'. esc_attr( $count ) .'" class="track-row track-row-progress sp-content-control '. esc_attr( $waveform_class ) .'">';
               $output .= '
                <span class="sp-content-progress">
                    <span class="sp-content-waveform" ' . $track['waveform'] . '></span>
                    <span class="sp-content-loading"><span ' . $track['waveform'] . '></span></span>
                    <span class="sp-content-position"><span ' . $track['waveform'] . '></span></span>
                </span>';
            $output .= '</div>';

            if ( $track['lyrics'] != '' ) {
            $output .= '<div class="track-row track-row-lyrics">';
               $output .= '
                <div class="track-lyrics-text">
                    <h5>' . $track['title'] . '</h5>
                    ' . wpautop( $track['lyrics'], 1 ) . '
                </div>';
            $output .= '</div>';
            }

        $output .= '</li>';
    }
    

    $output .= '</ul>' ."\n";
    $output .= '</div>' ."\n";

   return $output;
}
add_shortcode( 'tracklist', 'zona_tracklist' );


/* ----------------------------------------------------------------------
    RREVIEW

    Example Usage:
    [button text="" name="Johny Black" company="MIXMAG"]

/* ---------------------------------------------------------------------- */
function zona_review( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'text'    => '',
        'name'    => __( 'John Doe', 'zona_plugin' ),
        'company' => ''
    ), $atts));
    $output = '';
    if ( $company != '' ) {
        $company = '<span class="review--company">' . $company . '</span>';
    }
    return '<div class="review"><p>' . $text . '</p><span class="review--name">' . $name . '</span>' . $company . '</div>';
    

}
add_shortcode( 'review', 'zona_review' );


/* ----------------------------------------------------------------------
    BUTTON

    Example Usage:
    [button link="#" type="" style="" size="btn--medium" target="_self" title="Example Button Text"]

/* ---------------------------------------------------------------------- */

// List
function zona_buttons( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'classes'  => '',
        'align' => 'text-left',
        'css' => ''
    ), $atts));

    $classes .= ' ' . $align;
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'buttons', $atts );
    return '<div class="buttons ' . $classes . ' ' . esc_attr( $css_class ) . '">' . do_shortcode($content) . '</div>';

}
add_shortcode( 'buttons', 'zona_buttons' );
function zona_button( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'size'   => '',
        'title'  => __( 'Example Button Text', 'zona_plugin' ),
        'link'   => '#',
        'target' => '',
        'type'   => '',
        'style'  => '',
        'classes'  => '',
    ), $atts));

    if ( $target == '0' ) {
        $target = 'target="_blank"';
    }
    if ( $size != '' ) {
        $classes .= ' '.$size;
    }
    if ( $style != '' ) {
        $classes .= ' '.$style;
    }
    if ( $type == '' ) {
        return '<a class="btn ' . $classes . '" href="' . $link . '" ' . $target . '><span>' . $title . '</span></a>';
    } else {
        return '<a class="btn btn--frame' . $classes . '" href="' . $link . '" ' . $target . '><span></span>' . $title . '</a>';
    }

}
add_shortcode( 'button', 'zona_button' );


/* ----------------------------------------------------------------------
    SHARE BUTTONS

    Example Usage:
    [share_buttons classes="" ]

/* ---------------------------------------------------------------------- */
function zona_share_buttons_sc( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'classes'  => '',
        'css' => ''
    ), $atts));

    global $post;

    if ( ! isset( $post ) ) {
        return;
    }
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'share_buttons', $atts );
    return '<div class="share-buttons ' . $classes . ' ' . esc_attr( $css_class ) . '">
            <a class="share-button fb-share-btn" target="_blank" href="http://www.facebook.com/sharer.php?u=' . esc_url( get_permalink( $post->ID ) ) . '"><span class="icon icon-facebook"></span></a>
            <a class="share-button twitter-share-btn" target="_blank" href="http://twitter.com/share?url=' . esc_url( get_permalink( $post->ID ) ) . '"><span class="icon icon-twitter"></span></a>
            <a class="share-button gplus-share-btn" target="_blank" href="https://plus.google.com/share?url=' . esc_url( get_permalink( $post->ID ) ) . '"><span class="icon icon-googleplus"></span></a>
        </div>';
   

}
add_shortcode( 'share_buttons', 'zona_share_buttons_sc' );



/* ----------------------------------------------------------------------
    IMAGE BUTTON

    Example Usage:
    [image_button link="#" image="soundcloud" image_custom="0" target="_self"]

/* ---------------------------------------------------------------------- */

// List
function zona_image_buttons( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'classes'  => '',
        'align' => 'text-left',
        'css' => ''
    ), $atts));

    $classes .= ' ' . $align;
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'image_buttons', $atts );
    return '<div class="image-buttons ' . $classes . ' ' . esc_attr( $css_class ) . '">' . do_shortcode($content) . '</div>';

}
add_shortcode( 'image_buttons', 'zona_image_buttons' );
function zona_image_button( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'link'   => '#',
        'target' => '',
        'image'   => 'soundcloud',
        'image_custom' => 0
    ), $atts));

    if ( $target == '0' ) {
        $target = 'target="_blank"';
    }
    
    if ( $image != 'custom' && $image_custom  == 0 ) {
        $image = get_template_directory_uri() . '/images/badge-' . $image . '.png';
    } else {
        $image = wp_get_attachment_image_src( $image_custom, 'full' );

        $image = $image[0];
        if ( ! $image || $image == '' ) {
           return false;
        }
    }
    return '<a href="' . esc_url( $link  ) . '" class="btn--image" ' . $target . '><img src="' . esc_url( $image ) . '"  alt="' . __( 'Image button', 'zona_plugin' ) . '"></a>';    

}
add_shortcode( 'image_button', 'zona_image_button' );


/* ----------------------------------------------------------------------
    EVENT MAP

    Example Usage:
    [event_map address="Level 13, 2 Elizabeth St, Melbourne Victoria 3000 Australia" height="400" depth="15"]

/* ---------------------------------------------------------------------- */
function zona_event_map($atts, $content = null) {
    global $r_option, $zona_sid, $wp_query;
    
    extract(shortcode_atts(array(
        'height' => '400',
        'depth' => '15',
        'zoom_control' => 'true',
        'scrollwheel' => 'false',
        'classes' => ''
    ), $atts));

    if ( ! isset( $wp_query ) && get_post_type( $wp_query->post->ID ) != 'zona_events' ) {
        return false;
    }

    $map_address = get_post_meta( $wp_query->post->ID, '_event_map_address', true );

    if ( ! $map_address || $map_address == '' ) {
        return '<div class="container">' . __( 'Map address option is not set in event post settings.' ) .'</div>';
    }

    $output = '<div class="event-gmap-wrap">';
    $output .= '<div id="event_gmap_' . esc_attr( $zona_sid ) . '" class="gmap" style="height:' . esc_attr( $height ) . 'px" data-address="' . esc_attr( $map_address ) . '" data-zoom="' . esc_attr( $depth ) . '" data-zoom_control="' . esc_attr( $zoom_control ) . '" data-scrollwhell="' . esc_attr( $scrollwheel ) . '">';
    $output .= '</div>';
    $output .= '</div>';

    $zona_sid++;
    
    return $output;
}
add_shortcode('event_map', 'zona_event_map');


/* ----------------------------------------------------------------------
    DETAILS LIST

    Example Usage:
    [details_list]
        [detail label="Color" value="Orange"]
        [detail label="Color" value="Blue"]
        [detail label="Color" value="White"]
    [/details_list]
  

/* ---------------------------------------------------------------------- */

// List
function zona_details_list( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'classes' => '',
        'css' => ''
    ), $atts));
     $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'details_list', $atts );
    return '<ul style="border: 0; padding: 0;" class="details-list' . $classes . ' ' . esc_attr( $css_class ) . '">' . do_shortcode($content) . '</ul>';

}
add_shortcode( 'details_list', 'zona_details_list' );

// Detail
function zona_detail( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'label' => 'Color',
        'value' => 'White',
        'text_link' => '1',
        'url' => '',
        'target' => ''
    ), $atts));

    if ( $text_link == '0' && $url != '' ) {
        if ( $target == '0' ) {
            $target = 'target="_blank"';
        }
        $value = '<a href="' . $url . '" ' . $target . '>' . $value . '</a>';
    }

    return '<li ><div class="detail--title">' . $label . '</div><div class="detail--content">' . $value . '</div></li>';

}
add_shortcode( 'detail', 'zona_detail' );


// Event Detail
function zona_event_detail( $atts, $content = null ) {
    global $wp_query;

    extract(shortcode_atts(array(
        'otacosa' => '',
        'detail' => 'date', //date, place, buttons, share
        'buttons' => 'Tickets|#|1,T&C|#|2',
    ), $atts));

    if ( ! isset( $wp_query ) && get_post_type( $wp_query->post->ID ) != 'zona_events' ) {
        return false;
    }

    $panel_options = get_option( 'zona_panel_opts' );

    $value = '';

     /* Event Date */
    $event_time = get_post_meta( $wp_query->post->ID, '_event_time', true );
    $event_time_start = strtotime( get_post_meta( $wp_query->post->ID, '_event_time_start', true ) );
    $event_date_start = get_post_meta( $wp_query->post->ID, '_event_date_start', true );
    $event_date_start = strtotime( $event_date_start );
    $event_date_end = strtotime( get_post_meta( $wp_query->post->ID, '_event_date_end', true ) );
    /* Event data */
    $event_place = get_post_meta( $wp_query->post->ID, '_event_place', true );
    $event_city = get_post_meta( $wp_query->post->ID, '_event_city', true );
    $map_address = get_post_meta( $wp_query->post->ID, '_event_map_address', true );
    $event_tickets_url = get_post_meta( $wp_query->post->ID, '_event_tickets_url', true );
    $event_tickets_pdf = get_post_meta( $wp_query->post->ID, '_event_tickets_pdf', true );


    if ( $detail == 'buttons' && $buttons != '' ) {
        $buttons = explode( ",", $buttons );
        if ( is_array( $buttons ) ) {
            foreach ( $buttons as $button ) {
                $button = explode( "|", $button );
                if ( is_array( $button ) ) {

                    if ($button[2] == 1) {
                        $value .= '<a href="' . esc_url( $event_tickets_url ) . '" class="btn btn--small detail--btn" >' . $button[0] . '</a>';
                    }else{
                        $value .= '<a href="' . esc_url( $event_tickets_pdf ) . '" class="btn btn--small detail--btn" >' . $button[0] . '</a>';
                    }
                    
                    
                }
            }
        }
    }
    elseif ( $detail == 'share' ) {
        $value .= '<label style="margin:5px">Compartir</label>';
       $value .= '<div class="detail--share"><a class="share-button fb-share-btn" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url( get_permalink( $wp_query->post->ID ) ) . '"><span class="icon icon-facebook"></span></a>
            <a class="share-button twitter-share-btn" target="_blank" href="http://twitter.com/share?url=' . esc_url( get_permalink( $wp_query->post->ID ) ) . '"><span class="icon icon-twitter"></span></a>
            <a class="share-button gplus-share-btn" target="_blank" href="https://plus.google.com/share?url=' . esc_url( get_permalink( $wp_query->post->ID ) ) . '"><span class="icon icon-instagram"></span></a></div>';
    }

    return '<li><div class="detail--title">' . $label . '</div><div class="detail--content">' . $value . '</div></li>';

}
add_shortcode( 'event_detail', 'zona_event_detail' );


/* ----------------------------------------------------------------------
    EVENTS

    Example Usage:
    [events limit="40"]

/* ---------------------------------------------------------------------- */

function zona_events( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'limit'        => '40',
        'event_type'   => 'future-events',
        'display_by'   => 'all',
        'terms'        => '',
        'background'  => 'bg--light', // bg--dark,bg--light
        'classes' => '',
        'css' => ''
    ), $atts));
    
    global $wp_query, $post;

    $output = '';
    $panel_options = get_option( 'zona_panel_opts' );

    // Date format
    $date_format = 'd/m/Y';
    if ( isset( $panel_options ) && isset( $panel_options[ 'event_date' ] ) ) {
        $date_format = $panel_options[ 'event_date' ];
    } else {
        $date_format = get_option( 'date_format' );
    }

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'events', $atts );

    // Background
    $classes .= ' ' . $background;

    // Pagination Limit
    $limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;

    if ( isset( $post ) ) { 
        $backup = $post;
    }

    // Taxonomies
    if ( $display_by != 'all' && $terms != '' ) {
        $terms = explode( ',', $terms );
    }

    // ---------------------- All events
    if ( $event_type == 'all-events' ) {

        $future_tax = array(
            'relation' => 'AND',
            array(
               'taxonomy' => 'zona_event_type',
               'field' => 'slug',
               'terms' => 'future-events'
              )
        );

        $past_tax = array(
            'relation' => 'AND',
            array(
               'taxonomy' => 'zona_event_type',
               'field' => 'slug',
               'terms' => 'past-events'
              )
        );

        if ( $display_by != 'all' && $terms != '' ) {

            array_push( $future_tax,
                array(
                    'taxonomy' => $display_by,
                    'field' => 'slug',
                    'terms' => $terms
                )
            );
            array_push( $past_tax, 
                array(
                    'taxonomy' => $display_by,
                    'field' => 'slug',
                    'terms' => $terms
                )
            );
        }

        $future_events = get_posts( array(
            'post_type' => 'zona_events',
            'showposts' => -1,
            'tax_query' => $future_tax,
            'orderby' => 'meta_value',
            'meta_key' => '_event_date_start',
            'order' => 'ASC'
        ));

        // Past Events
        $past_events = get_posts(array(
            'post_type' => 'zona_events',
            'showposts' => -1,
            'tax_query' => $past_tax,
            'orderby' => 'meta_value',
            'meta_key' => '_event_date_start',
            'order' => 'DSC'
        ));

        $future_nr = count( $future_events );
        $past_nr = count( $past_events );

        // echo "Paged: Future events: $future_nr, Past events: $past_nr";

        $mergedposts = array_merge( $future_events, $past_events ); //combine queries

        $postids = array();
        foreach( $mergedposts as $item ) {
            $postids[] = $item->ID; //create a new query only of the post ids
        }
        $uniqueposts = array_unique( $postids ); //remove duplicate post ids

        // var_dump($uniqueposts);
        $args = array(
            'post_type' => 'zona_events',
            'showposts' => $limit,
            'post__in'  => $uniqueposts,
            'orderby' => 'post__in'
        );

    // ---------------------- Future or past events
    } else {

        /* Set order */
        $order = $event_type == 'future-events' ? $order = 'ASC' : $order = 'DSC';

        // Event type taxonomy
        $taxonomies = array(
            array(
               'taxonomy' => 'zona_event_type',
               'field' => 'slug',
               'terms' => $event_type
              )
        );

        if ( $display_by != 'all' && $terms != '' ) {

            array_push( $taxonomies, 
                array(
                    'taxonomy' => $display_by,
                    'field' => 'slug',
                    'terms' => $terms
                )
            );
        }

        // Begin Loop
        $args = array(
            'post_type'        => 'zona_events',
            'showposts'        => $limit,
            'tax_query'        => $taxonomies,
            'orderby'          => 'meta_value',
            'meta_key'         => '_event_date_start',
            'order'            => $order,
            'suppress_filters' => 0 // WPML FIX
        );
    }
    
    $events_query = new WP_Query();
    $events_query->query( $args );

    // begin Loop
    if ( $events_query->have_posts() ) {
       

        $output .= '<div class="events--list-wrap ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">
                    <ul class="events--list">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();

            /* Event Date */
            $event_time_start = get_post_meta( $events_query->post->ID, '_event_time_start', true );
            $event_date_start = get_post_meta( $events_query->post->ID, '_event_date_start', true );
            $event_date_start = strtotime( $event_date_start );
            $event_date_end = strtotime( get_post_meta( $events_query->post->ID, '_event_date_end', true ) );

            /* Event data */
            $event_place = get_post_meta( $events_query->post->ID, '_event_place', true );
            $event_city = get_post_meta( $events_query->post->ID, '_event_city', true );
            $event_tickets_url = get_post_meta( $events_query->post->ID, '_event_tickets_url', true );
            $event_tickets_target = get_post_meta( $events_query->post->ID, '_event_tickets_new_window', true );
       
            $output .= '<li style="border-radius: 4px; margin: 4px; background: #121212; border: 1px solid white; padding: 0px 0;" class="' . implode( ' ', get_post_class( 'grid--item item-anim anim-fadeup', $events_query->post->ID ) ) . '">';
                
                $output .= '<div class="event--col event--col-date" style="color:white">';
                $output .= date_i18n( $date_format, $event_date_start );
                $output .= '</div>';

            $output .= '<div class="event--col event--col-title" style="color:white">';
                $output .= '<div class="event--show-on-mobile event--date" style="color:white">';
                $output .= date_i18n( $date_format, $event_date_start );
                $output .= '</div>';
                 if ( has_term( 'past-events', 'zona_event_type' ) ) {
                    $output .= '<span class="past-event-label">' . __( 'Past Event', 'zona_plugin' ) . '</span>';
                }   
                $output .= '<a href="' . get_the_permalink() .'" class="event--title" style="color:white">' . get_the_title() . '</a>';
                $output .= '<div class="event--show-on-mobile event--city" >';
                $output .= $event_city;
                $output .= '</div>';
            $output .= '</div>';


            $output .= '<div class="event--col event--col-tickets">';
            $output .= '<a class="event--button anim--reveal" href="'. get_the_permalink() . '" target="' . esc_attr( $event_tickets_target ) . '"><span>' . __( 'Más Info', 'zona_plugin' ) . '</span></a><br>';
            if ( $event_tickets_url != '' ) {
        
                if ( $event_tickets_target == 'yes' ) {
                    $event_tickets_target = '_blank';
                } else {
                    $event_tickets_target = '_self';
                }

                $output .= '<a class="event--button anim--reveal" href="'. esc_url( $event_tickets_url ) . '" target="' . esc_attr( $event_tickets_target ) . '"><span>' . __( 'Tickets', 'zona_plugin' ) . '</span></a>';
            }

            $output .= '</div>';

            $output .= '</li>';

       

        } // end loop
        // End list
        $output .= '</ul></div>';

    } // end have_posts

    
    $output .= '<div class="clear"></div>';

    if ( isset( $post ) ) {
        $post = $backup;
    }
    return $output;

}
add_shortcode( 'events', 'zona_events' );


/* ----------------------------------------------------------------------
    GALLERY ALBUMS

    Example Usage:
    [gallery limit="40"]

/* ---------------------------------------------------------------------- */

function zona_gallery_albums( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'limit'      => '5',
        'columns'    => 'grid-4',
        'style'      => 'grid',
        'url'        => '',
        'gap'        => '',
        'display_by' => 'all',
        'terms'      => '',
        'classes'    => '',
        'css'        => ''
    ), $atts));
    
    global $wp_query, $post;

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'gallery_albums', $atts );

    // Thumb Size
    $thumb_size = 'zona-gallery-thumb';

    $output = '';
    $panel_options = get_option( 'zona_panel_opts' );

    // Date format
    $date_format = get_option( 'date_format' );

    // Pagination Limit
    $limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;

    if ( isset( $post ) ) { 
        $backup = $post;
    }

    if ( $url != '' ) {
        $n_cpt = wp_count_posts( 'zona_gallery' );
        $n_cpt_publish = $n_cpt->publish;
    } else {
        $n_cpt_publish = '0';
    }

    // Loop Args.
    $args = array(
        'post_type' => 'zona_gallery',
        'showposts' => $limit
    );

     // Taxonomies
    if ( $display_by != 'all' && $terms != '' ) {
        $terms = explode( ',', $terms );
        $args['tax_query'] = array(
            array(
                'taxonomy' => $display_by,
                'field' => 'slug',
                'terms' => $terms
            )
        );
    }
    
    $i = 0;
    $gallery_query = new WP_Query();
    $gallery_query->query( $args );

    // begin Loop
    if ( $gallery_query->have_posts() ) {
        $output .= '<div class="gallery--albums-wrap-sc ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';
        $output .= '<div class="gallery--grid grid-row mosaic--section ' . esc_attr( $gap ) . '">';
        while ( $gallery_query->have_posts() ) {
            $gallery_query->the_post();

            if ( has_post_thumbnail() ) {

                $last = false;
                if ( ( $gallery_query->current_post ) < ( $gallery_query->post_count -1 ) ) {
                    $last = false;
                } else {
                    if ( $url != '' ) {
                        $last = true;
                    }
                }

                if ( ! $last ) {
                    $link_classes = 'anim--reveal permalink';
                    $permalink = get_permalink();
                } else {
                    $link_classes = 'post-count permalink anim--reveal';
                    $permalink = $url;
                }

                if ( $style == 'grid') {
                    $output .= '<div class="grid--item item-anim anim-fadeup ' . esc_attr( $columns ) . ' grid-tablet-6 grid-mobile-6">';
                    $output .= '<article class="' . implode( ' ', get_post_class( 'gallery--album-sc', $gallery_query->post->ID ) ) . '">';
                    $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                    
                    $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $gallery_query->post->ID ), $thumb_size );
                    $output .= '<img class="gallery--image" src="'.esc_url( $img_src[0] ).'" alt="'. esc_attr( __( 'Post image', 'zona_plugin' ) ) .'">';
                    $output .= '<div class="shade"></div>';
                    if ( ! $last ) {
                        $output .= '<h2 class="gallery--title">'. get_the_title() . '</h2>'; 
                        $output .= '<span class="gallery--date"><span>' . get_the_time( $date_format ) .'</span></span>';    
                    } else {
                        $output .= '<div class="desc-count">' . $n_cpt_publish . '</div><div class="desc-plus"></div>';
                    }
                    $output .= '</a>';
                    $output .= '</article>';
                    $output .= '</div>';
                } else if ( $style == 'mosaic') {

                    if ( ! $last ) {
                        $mosaic_html = '<h2 class="gallery--title">'. get_the_title() . '</h2>'; 
                        $mosaic_html .= '<span class="gallery--date"><span>' . get_the_time( $date_format ) .'</span></span><div class="shade"></div>';
                        $close_mosaic = '';
                    } else {
                        $close_mosaic = '</div>';
                        $mosaic_html = '<div class="desc-count">' . $n_cpt_publish . '</div><div class="desc-plus"></div><div class="shade"></div>';
                    }

                    $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $gallery_query->post->ID ), 'large' );
                    // 1
                    if ( $i % 5 == 0 ) {

                        $output .= '<div class="mosaic--row">';

                            $output .= '<div class="mosaic mosaic-main" style="background-image:url('.esc_url( $img_src[0] ).')">';
                            $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                            $output .= $mosaic_html;
                            $output .= '</a>';
                            $output .= '</div>';
                            $output .= $close_mosaic;
                            
                    }
                    // 2
                    if ( $i % 5 == 1 ) {
                            $output .= '<div class="mosaic mosaic-two" style="background-image:url('.esc_url( $img_src[0] ).')">';
                            $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                            $output .= $mosaic_html;
                            $output .= '</a>';
                            $output .= '</div>';
                            $output .= $close_mosaic;
                    }

                    // 3
                    if ( $i % 5 == 2 ) {
                            $output .= '<div class="mosaic mosaic-two" style="background-image:url('.esc_url( $img_src[0] ).')">';
                            $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                            $output .= $mosaic_html;
                            $output .= '</a>';
                            $output .= '</div>';
                        $output .= '</div>';
                    }

                    // 4
                    if ( $i % 5 == 3 ) {
                        $output .= '<div class="mosaic--row">';
                            $output .= '<div class="mosaic mosaic-two mosaic-narrow" style="background-image:url('.esc_url( $img_src[0] ).')">';
                            $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                            $output .= $mosaic_html;
                            $output .= '</a>';
                            $output .= '</div>';
                            $output .= $close_mosaic;
                       
                    }

                    // 5
                    if ( $i % 5 == 4 ) {
                            $output .= '<div class="mosaic mosaic-two mosaic-big" style="background-image:url('.esc_url( $img_src[0] ).')">';
                            $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                            $output .= $mosaic_html;
                            $output .= '</a>';
                            $output .= '</div>';
                        $output .= '</div>';
                    }

                }


                $i++;   
            }

        } // end loop
        
        $output .= '</div>'; // End grid
        $output .= '</div>'; // End wrap

    } // end have_posts

    
    $output .= '<div class="clear"></div>';

    if ( isset( $post ) ) {
        $post = $backup;
    }
    return $output;

}
add_shortcode( 'gallery_albums', 'zona_gallery_albums' );


/* ----------------------------------------------------------------------
    GALLERY IMAGES

    Example Usage:
    [gallery limit="40"]

/* ---------------------------------------------------------------------- */

function zona_gallery_images( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
         'album_id' => 0,
         'limit'      => '5',
         'columns'     => 'grid-4',
         'style' => 'grid',
         'url'        => '0',
         'gap'        => '',
         'classes' => '',
         'css' => ''
    ), $atts));
    
    global $wp_query, $post;

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'gallery_albums', $atts );

    // Thumb Size
    $thumb_size = 'zona-gallery-thumb';

    $output = '';
    $panel_options = get_option( 'zona_panel_opts' );

    // Pagination Limit
    $limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;

    if ( isset( $post ) ) { 
        $backup = $post;
    }

   // IDS
    $images_ids = get_post_meta( $album_id, '_gallery_ids', true ); 

    if ( ! $images_ids || $images_ids == 0 ) {
        return;
    }

    $ids = explode( '|', $images_ids ); 

    $n_cpt_publish = count( $ids );
    
    // Begin Loop
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'post__in' => $ids,
        'orderby' => 'post__in',
        'post_status' => 'any',
        'showposts' => $limit
    );
    $i = 0;
    $gallery_query = new WP_Query();
    $gallery_query->query( $args );

    // begin Loop
    if ( $gallery_query->have_posts() ) {
        $output .= '<div class="gallery--images-wrap-sc ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';
        $output .= '<div class="gallery--grid images--grid grid-row mosaic--section ' . esc_attr( $gap ) . '">';
        while ( $gallery_query->have_posts() ) {
            $gallery_query->the_post();

            $last = false;
            if ( ( $gallery_query->current_post ) < ( $gallery_query->post_count -1 ) ) {
                $last = false;
            } else {
                if ( $url != '0' ) {
                    $last = true;
                }
            }

            $image_att = wp_get_attachment_image_src( get_the_id(), $thumb_size );
            if ( ! $image_att[0] ) { 
                continue;
            }

            $defaults = array(
                'title' => '',
                'custom_link'  => '',
                'thumb_icon' => 'view'
             );

            /* Get image meta */
            $image = get_post_meta( $album_id, '_gallery_ids_' . get_the_id(), true );

            /* Add default values */
            if ( isset( $image ) && is_array( $image ) ) {
                $image = array_merge( $defaults, $image );
            } else {
                $image = $defaults;
            }

            /* Add image src to array */
            $image['src'] = $image_att[0];
            if ( $image[ 'custom_link' ] != '' ) {
                $link = $image[ 'custom_link' ];
                $link_class = 'iframe-link';
            } else {
                $link = wp_get_attachment_image_src( get_the_id(), 'full' );
                $link = $link[0];
                $link_class = '';
            }
            
            if ( ! $last ) {
                $close_mosaic = '';
                $mosaic_html = '<a href="' . esc_url( $link ) . '" class="permalink '. esc_attr( $link_class ) .'  g-item" title="' . esc_attr( $image['title'] ) . '" data-group="gallery"></a>';
            } else {
                $close_mosaic = '</div>';
                $mosaic_html = '<a href="' . get_permalink( $album_id ) . '" class="permalink anim--reveal '. esc_attr( $link_class ) .'"></a><div class="desc-count">' . $n_cpt_publish . '</div><div class="desc-plus"></div><div class="shade"></div>';
            }

            // GRID
            if ( $style == 'grid') {
                $output .= '<div class="grid--item item-anim anim-fadeup ' . esc_attr( $columns ) . ' grid-tablet-6 grid-mobile-6">';
                $output .= '<article class="' . implode( ' ', get_post_class( 'gallery--image-sc', $gallery_query->post->ID ) ) . '">';
                if ( ! $last ) {
                    $output .= '<a href="' . esc_url( $link) .'" class="' . esc_attr( $link_class  ) . ' g-item" title="' . esc_attr( $image['title'] ) . '" data-group="gallery">';
                } else {
                    $output .= '<a href="' . esc_url( $link ) .'" class="' . esc_attr( $link_class  ) . ' " title="' . esc_attr( $image['title'] ) . '" data-group="gallery">';
                    $output .= '<div class="shade"></div>';
                    $output .= '<div class="desc-plus"></div>';
                    $output .= '<div class="desc-count">' . $n_cpt_publish . '</div>';
                }
                $output .= '<img src="' . esc_url( $image['src'] ) . '" alt="' . esc_attr( __( 'Gallery thumbnail', 'zona_plugin' ) ) . '" title="' . esc_attr( $image['title'] ) . '">';
               
                $output .= '</a>'; 
                $output .= '</article>';
                $output .= '</div>';

            // MOSAIC
            } else if ( $style == 'mosaic') {

                    $mosaic_img = wp_get_attachment_image_src( get_the_id(), 'large' );
                    $mosaic_img = $mosaic_img[0];

                    // 1
                    if ( $i % 5 == 0 ) {

                        $output .= '<div class="mosaic--row">';

                            $output .= '<div class="mosaic mosaic-main" style="background-image:url('.esc_url( $mosaic_img ).')">';
                            $output .= $mosaic_html;
                            $output .= '</div>';
                            $output .= $close_mosaic;
                            
                    }
                    // 2
                    if ( $i % 5 == 1 ) {
                            $output .= '<div class="mosaic mosaic-two" style="background-image:url('.esc_url( $mosaic_img ).')">';
                            $output .= $mosaic_html;
                            $output .= '</div>';
                            $output .= $close_mosaic;
                    }

                    // 3
                    if ( $i % 5 == 2 ) {
                            $output .= '<div class="mosaic mosaic-two" style="background-image:url('.esc_url( $mosaic_img ).')">';
                            $output .= $mosaic_html;
                            $output .= '</div>';
                        $output .= '</div>';
                    }

                    // 4
                    if ( $i % 5 == 3 ) {
                        $output .= '<div class="mosaic--row">';
                            $output .= '<div class="mosaic mosaic-two mosaic-narrow" style="background-image:url('.esc_url( $mosaic_img ).')">';
                            $output .= $mosaic_html;
                            $output .= '</div>';
                            $output .= $close_mosaic;
                    }

                    // 5
                    if ( $i % 5 == 4 ) {
                            $output .= '<div class="mosaic mosaic-two mosaic-big" style="background-image:url('.esc_url( $mosaic_img ).')">';
                            $output .= $mosaic_html;
                            $output .= '</div>';
                        $output .= '</div>';
                    }
            }
           
            
            $i++;  
            

        } // end loop
        $output .= '</div>'; // End grid
        $output .= '</div>'; // End wrap

    } // end have_posts

    
    $output .= '<div class="clear"></div>';

    if ( isset( $post ) ) {
        $post = $backup;
    }
    return $output;

}
add_shortcode( 'gallery_images', 'zona_gallery_images' );


/* ----------------------------------------------------------------------
    VIDEOS

    Example Usage:
    [gallery limit="40"]

/* ---------------------------------------------------------------------- */

function zona_videos( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'limit'   => '5',
        'columns' => 'grid-4',
        'style'   => 'grid',
        'url'     => '',
        'gap'     => '',
        'classes' => '',
        'css'     => ''
    ), $atts));
    
    global $wp_query, $post;

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'videos', $atts );

    // Thumb Size
    $thumb_size = 'zona-gallery-thumb';

    $output = '';
    $panel_options = get_option( 'zona_panel_opts' );


    // Pagination Limit
    $limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;

    if ( isset( $post ) ) { 
        $backup = $post;
    }

    if ( $url != '' ) {
        $n_cpt = wp_count_posts( 'zona_videos' );
        $n_cpt_publish = $n_cpt->publish;
    } else {
        $n_cpt_publish = '0';
    }

    // Loop Args.
    $args = array(
        'post_type' => 'zona_videos',
        'showposts' => $limit
    );
    
    $i = 0;
    $videos_query = new WP_Query();
    $videos_query->query( $args );

    // begin Loop
    if ( $videos_query->have_posts() ) {
        $output .= '<div class="videos--wrap-sc ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';
        $output .= '<div class="videos--grid grid-row mosaic--section ' . esc_attr( $gap ) . '">';
        while ( $videos_query->have_posts() ) {
            $videos_query->the_post();

            $video_url = get_post_meta( $videos_query->post->ID, '_video_url', true );
            $click_action = get_post_meta( $videos_query->post->ID, '_click_action', true ); 

            if ( function_exists( 'zona_get_video_img' ) && zona_get_video_img( $videos_query->post->ID, $thumb_size ) ) {

                $last = false;
                if ( ( $videos_query->current_post ) < ( $videos_query->post_count -1 ) ) {
                    $last = false;
                } else {
                    if ( $url != '' ) {
                        $last = true;
                    }
                }

                if ( ! $last ) {
                    $link_classes = 'video-sc anim--reveal permalink iframebox';
                    $permalink = $video_url;
                    if ( $click_action != '' && $click_action == 'open_on_page' ) {
                        $permalink = get_permalink();
                        $video_classes = 'video-sc anim--reveal permalink';
                    }
                } else {
                    $link_classes = 'video-sc post-count permalink anim--reveal';
                    $permalink = $url;
                }

                if ( $style == 'grid') {
                    $output .= '<div class="grid--item item-anim anim-fadeup ' . esc_attr( $columns ) . ' grid-tablet-6 grid-mobile-6">';
                    $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                    
                    $img_src = zona_get_video_img( $videos_query->post->ID, $thumb_size );
                    $output .= '<img class="videos--image" src="'.esc_url( $img_src ).'" alt="'. esc_attr( __( 'Post image', 'zona_plugin' ) ) .'">';
                    if ( ! $last ) {
                        $output .= '<h2 class="videos--title">'. get_the_title() . '</h2>'; 
                        $output .= '<span class="videos--play-layer"><span class="icon icon-play"></span></span>';    
                    } else {
                        $output .= '<div class="desc-count">' . $n_cpt_publish . '</div><div class="desc-plus"></div>';
                        $output .= '<div class="shade"></div>';
                    }
                    $output .= '</a>';

                    $output .= '</div>';
                } else if ( $style == 'mosaic') {

                    if ( ! $last ) {
                        $mosaic_html = '<h2 class="videos--title">'. get_the_title() . '</h2><span class="videos--play-layer"><span class="icon icon-play"></span></span>';     
                        $close_mosaic = '';
                    } else {
                        $close_mosaic = '</div>';
                        $mosaic_html = '<div class="desc-count">' . $n_cpt_publish . '</div><div class="desc-plus"></div><div class="shade"></div>';
                    }

                    $img_src = zona_get_video_img( $videos_query->post->ID, 'large' );
                    // 1
                    if ( $i % 5 == 0 ) {

                        $output .= '<div class="mosaic--row">';

                            $output .= '<div class="mosaic mosaic-main" style="background-image:url('.esc_url( $img_src ).')">';
                            $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                            $output .= $mosaic_html;
                            $output .= '</a>';
                            $output .= '</div>';
                            $output .= $close_mosaic;
                            
                    }
                    // 2
                    if ( $i % 5 == 1 ) {
                            $output .= '<div class="mosaic mosaic-two" style="background-image:url('.esc_url( $img_src ).')">';
                            $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                            $output .= $mosaic_html;
                            $output .= '</a>';
                            $output .= '</div>';
                            $output .= $close_mosaic;
                    }

                    // 3
                    if ( $i % 5 == 2 ) {
                            $output .= '<div class="mosaic mosaic-two" style="background-image:url('.esc_url( $img_src ).')">';
                            $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                            $output .= $mosaic_html;
                            $output .= '</a>';
                            $output .= '</div>';
                        $output .= '</div>';
                    }

                    // 4
                    if ( $i % 5 == 3 ) {
                        $output .= '<div class="mosaic--row">';
                            $output .= '<div class="mosaic mosaic-two mosaic-narrow" style="background-image:url('.esc_url( $img_src ).')">';
                            $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                            $output .= $mosaic_html;
                            $output .= '</a>';
                            $output .= '</div>';
                            $output .= $close_mosaic;
                       
                    }

                    // 5
                    if ( $i % 5 == 4 ) {
                            $output .= '<div class="mosaic mosaic-two mosaic-big" style="background-image:url('.esc_url( $img_src ).')">';
                            $output .= '<a href="' . esc_url( $permalink ) . '" class="'. esc_attr( $link_classes ) .'">';
                            $output .= $mosaic_html;
                            $output .= '</a>';
                            $output .= '</div>';
                        $output .= '</div>';
                    }

                }


                $i++;   
            }

        } // end loop
        
        $output .= '</div>'; // End grid
        $output .= '</div>'; // End wrap

    } // end have_posts

    
    $output .= '<div class="clear"></div>';

    if ( isset( $post ) ) {
        $post = $backup;
    }
    return $output;

}
add_shortcode( 'videos', 'zona_videos' );


/* ----------------------------------------------------------------------
    SINGLE VIDEO

    Example Usage:
    [single_video id="0"]

/* ---------------------------------------------------------------------- */

function zona_single_video( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'id'      => 0,
        'thumb_size' => 'large',
        'classes' => '',
        'css' => ''
    ), $atts));
    
    global $wp_query, $post;

    if ( $id == 0 ) {
        return false;
    }

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'single_video', $atts );

    $output = '';
   
    $video_url = get_post_meta( $id, '_video_url', true );
    $click_action = get_post_meta( $id, '_click_action', true );
    $video_classes = 'video-sc anim--reveal permalink iframebox';
    if ( $click_action != '' && $click_action == 'open_on_page' ) {
        $video_url = get_permalink($id);
        $video_classes = 'video-sc anim--reveal permalink';
    }

    if ( function_exists( 'zona_get_video_img' ) && zona_get_video_img( $id, $thumb_size ) ) {

        $output .= '<div class="single-video--wrap ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';
        $output .= '<a href="' . esc_url( $video_url ) . '" class="' . esc_attr( $video_classes ) . '">';
        $img_src = zona_get_video_img( $id, $thumb_size );
        $output .= '<img class="videos--image" src="'.esc_url( $img_src ).'" alt="'. esc_attr( __( 'Post image', 'zona_plugin' ) ) .'">';
        $output .= '<h2 class="videos--title">'. get_the_title($id) . '</h2>'; 
        $output .= '<span class="videos--play-layer"><span class="icon icon-play"></span></span>';    
        $output .= '</a>';       
        $output .= '</div>'; // End wrap

    } // has thumb

    return $output;

}
add_shortcode( 'single_video', 'zona_single_video' );


/* ----------------------------------------------------------------------
    GOOGLE MAPS

    Example Usage:
    [google_maps address="Level 13, 2 Elizabeth St, Melbourne Victoria 3000 Australia" height="400" depth="15" zoom_control="true" scrollwhell="false"]

/* ---------------------------------------------------------------------- */
function zona_google_maps($atts, $content = null) {
    global $r_option, $zona_sid;
    
    extract(shortcode_atts(array(
        'height' => '400',
        'address' => 'Level 13, 2 Elizabeth St, Melbourne Victoria 3000 Australia',
        'depth' => '15',
        'zoom_control' => 'true',
        'scrollwheel' => 'false',
        'classes' => '',
        'css' => ''
    ), $atts));

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'google_maps', $atts );

    $output = '<div class="gmap-wrap ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';
    $output .= '<div id="gmap_' . esc_attr( $zona_sid ) . '" class="gmap" style="height:' . esc_attr( $height ) . 'px" data-address="' . esc_attr( $address ) . '" data-zoom="' . esc_attr( $depth ) . '" data-zoom_control="' . esc_attr( $zoom_control ) . '" data-scrollwhell="' . esc_attr( $scrollwheel ) . '">';
    $output .= '</div>';
    $output .= '</div>';

    $zona_sid++;
    
    return $output;
}
add_shortcode('google_maps', 'zona_google_maps');


/* ----------------------------------------------------------------------
    EVENT COUNTDOWN

    Example Usage:
    [event_countdown event_id="0"]

/* ---------------------------------------------------------------------- */

function zona_event_countdown( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'style'           => 'compact', // compact, big
        'custom_event'    => '',
        'custom_event_id' => '0',
        'display_by'      => 'all',
        'background'      => 'bg--light', // bg--dark,bg--light
        'terms'           => '',
        'classes' => '',
        'css' => ''
    ), $atts));
    
    $custom_event_id = (int)$custom_event_id;
    global $post;

    if ( isset( $post ) ) { 
        $backup = $post;
    }

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'event_countdown', $atts );

    // Event type taxonomy
    $tax = array(
        array(
           'taxonomy' => 'zona_event_type',
           'field' => 'slug',
           'terms' => 'future-events'
          )
    );

     // Taxonimies
    if ( $display_by != 'all' && $terms != '' && $custom_event == '' ) {
        $terms = explode( ',', $terms );
        array_push( $tax,
            array(
                'taxonomy' => $display_by,
                'field' => 'slug',
                'terms' => $terms
            )
        );
    }

    $args = array(
        'post_type'        => 'zona_events',
        'showposts'        => 1,
        'tax_query'        => $tax,
        'orderby'          => 'meta_value',
        'meta_key'         => '_event_date_start',
        'order'            => 'ASC',
        'suppress_filters' => 0 // WPML FIX
    );
    if ( $custom_event_id ==! 0 ) {
        $custom_event_id_a = array();
        $custom_event_id_a[0] = $custom_event_id;
        $args['post__in'] = $custom_event_id_a;
    }

    $events = get_posts( $args );
    $events_count = count( $events );

    if ( $events_count !== 0 ) {
        $custom_event_id = $events[0]->ID;
    }

    $panel_options = get_option( 'zona_panel_opts' );

    if ( $custom_event_id == 0 ) {
        return false;
    }
    $output = '';

    // Get event date and time
    $event_date = strtotime( get_post_meta( $custom_event_id, '_event_date_start', true ) );
    $event_time = strtotime( get_post_meta( $custom_event_id, '_event_time_start', true ) );

    // Date format
    $date_format = 'd/m/Y';
    if ( isset( $panel_options ) && isset( $panel_options[ 'event_date' ] ) ) {
        $date_format = $panel_options[ 'event_date' ];
    } else {
        $date_format = get_option( 'date_format' );
    }

   
   /* Event data */
    $event_place = get_post_meta( $custom_event_id, '_event_place', true );
    $event_city = get_post_meta( $custom_event_id, '_event_city', true );
    $event_tickets_url = get_post_meta( $custom_event_id, '_event_tickets_url', true );
    $event_tickets_target = get_post_meta( $custom_event_id, '_event_tickets_new_window', true );
    $countdown_ = get_post_meta( $custom_event_id, 'countdown_', true );
    $Tour = get_post_meta( $custom_event_id, 'Tour', true );

    $classes .=' ' . $style . ' ' . $background;

    $output .= '
        <div class="event-countdown ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">
        <header class="content-header">
        <h6 class="upcoming-title">' . esc_attr( $Tour ) . '</h6>';
        

        /* LA fecha se muestra con este codigo
        <h6 class="upcoming-locations"><span>' . date_i18n( $date_format, $event_date ) . '</span>' . $event_place . '</h6>
        </header>';
        /*

        /*
            Countdown/
        */

        $output .= '
        <div class="countdown" data-event-date="' . esc_attr( $countdown_ ) . ':00" style="text-align: end; margin-top: -32px; width: 90%;">
        <div class="days" data-label="' . esc_attr( __( 'Dias', 'zona_plugin' ) ) . '" style="font-size: 38px;">00</div>
        <div class="hours" data-label="' . esc_attr( __( 'Horas', 'zona_plugin' ) ) . '" style="font-size: 38px;">00</div>
        </div>';
        /* Minutos y segundos desabilitados
        <div class="minutes" data-label="' . esc_attr( __( 'Minutes', 'zona_plugin' ) ) . '">00</div>
        <div class="seconds" data-label="' . esc_attr( __( 'Seconds', 'zona_plugin' ) ) . '">00</div>
        </div>';
        */

        /*
            /Countdown
        */

        $output .= '
        </header>
        </div>
    ';
    if ( isset( $post ) ) {
        $post = $backup;
    }
    return $output;

}
add_shortcode( 'event_countdown', 'zona_event_countdown' );


/* ----------------------------------------------------------------------
    MUSIC GRID

    Example Usage:
    [music_grid filter="yes" limit="40" order="menu_order"]

/* ---------------------------------------------------------------------- */

function zona_music_grid( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'limit'       => '6',
        'posts_in'    => '',
        'order'       => 'menu_order',
        'orderby'     => 'ASC',
        'columns'     => 'grid-4',
        'url'         => '',
        'gap'         => '',
        'display_by'  => 'all',
        'terms'       => '',
        'link_action' => 'permalink', //permalink, play_music / disabled
        'classes'     => '',
        'css'         => ''
    ), $atts));
    
    global $wp_query, $post, $zona_sid;

    $output = '';
    $panel_options = get_option( 'zona_panel_opts' );

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'music_grid', $atts );

    // Thumb Size
    $thumb_size = 'zona-medium-thumb';

    // Pagination Limit
    $limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;

    if ( isset( $post ) ) { 
        $backup = $post;
    }

    if ( $url != '' ) {
        $n_cpt = wp_count_posts( 'zona_music' );
        $n_cpt_publish = $n_cpt->publish;
    }

    // Loop Args.
    $args = array(
        'post_type' => 'zona_music',
        'orderby'   => $order, // menu_order, date, title
        'order'     => $orderby,
        'showposts' => $limit
    );

     /* Posts in */
    if ( $posts_in != '' ) {
        $args['post__in'] = explode( ',', $posts_in );
    }

     // Taxonimies
    if ( $display_by != 'all' && $terms != '' ) {
        $terms = explode( ',', $terms );
        $args['tax_query'] = array(
            array(
                'taxonomy' => $display_by,
                'field' => 'slug',
                'terms' => $terms
            )
        );
    }
    
    $music_query = new WP_Query();
    $music_query->query( $args );

    // begin Loop
    if ( $music_query->have_posts() ) {
        $output .= '<div class="music--grid-wrap-sc ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';
        $output .= '<div class="music--grid grid-row ' . esc_attr( $gap ) . '">';
        while ( $music_query->have_posts() ) {
            $music_query->the_post();

            if ( has_post_thumbnail() ) {

                 // ID
                $zona_sid++;

                $last = false;
                if ( ( $music_query->current_post ) < ( $music_query->post_count -1 ) ) {
                    $last = false;
                } else {
                    if ( $url != '' ) {
                        $last = true;
                    }
                }
                if ( ! $last ) {
                    $permalink = get_permalink();
                } else {
                    $permalink = $url;
                }
                $disabled_link = get_post_meta( $music_query->post->ID, '_disable_link', true );
                if ( ! $disabled_link || $disabled_link == 'off' ) {
                    $disabled_link = false;
                } else {
                    $disabled_link = true;
                }

                $track_id = get_post_meta( $music_query->post->ID, '_thumb_tracks', true );

                $output .= '<div class="grid--item item-anim anim-fadeup ' . esc_attr( $columns ) . ' grid-tablet-6 grid-mobile-6">';
                $output .= '<article class="' . implode( ' ', get_post_class( 'music-sc', $music_query->post->ID ) ) . '">';

                // Permalink
                if ( $link_action == 'permalink' && ! $disabled_link ) {
                    $output .= '<a href="' . esc_url( $permalink ) . '" class="music--click-layer"></a>';

                // Music play
                } elseif ( $link_action == 'play_music' && $track_id != 'none' && ! $disabled_link ) {
                    $tracklist_id = 'simple_tracklist_' . $zona_sid;
                    $output .= '<a href="#" class="music--click-layer sp-play-list" data-id="' . esc_attr( $tracklist_id ) . '"></a>';
                    
                    $output .= '<div class="hidden">';
                        if ( function_exists( 'zona_simple_tracklist' ) ) {
                            $output .= zona_simple_tracklist( array( 'id' =>  $track_id, 'tracklist_id' => $tracklist_id ) );
                        } 
                    $output .= '</div>';
                // Disabled
                } else {
                    $output .= '<a class="music--click-layer"></a>';
                }
                
                $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $music_query->post->ID ), $thumb_size );
                $output .= '<img class="music--image" src="'.esc_url( $img_src[0] ).'" alt="'. esc_attr( __( 'Post image', 'zona_plugin' ) ) .'">';
                if ( $last ) {
                    $output .= '<div class="desc-count">' . $n_cpt_publish . '</div><div class="desc-plus"></div>';
                    $output .= '<div class="shade"></div>';
                }
                $output .= '</article>';
                $output .= '</div>';
            }

        } // end loop
        $output .= '</div>'; // End grid
        $output .= '</div>'; // End wrap

    } // end have_posts

    if ( isset( $post ) ) {
        $post = $backup;
    }
    return $output;

}
add_shortcode( 'music_grid', 'zona_music_grid' );


/* ----------------------------------------------------------------------
    MUSIC CAROUSEL

    Example Usage:
    [music_carousel filter="yes" limit="40" order="menu_order"]

/* ---------------------------------------------------------------------- */

function zona_music_carousel( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'limit'         => '6',
        'posts_in'      => '',
        'order'         => 'menu_order',
        'background'    => 'bg--light', // bg--dark,bg--light
        'gap'           => '',
        'visible_items' => '1',
        'display_by'    => 'all',
        'terms'         => '',
        'link_action'   => 'permalink', //permalink, play_music / disabled
        'classes'       => '',
        'css'           => ''
    ), $atts));
    
    global $wp_query, $post, $zona_sid;

    $output = '';
    $panel_options = get_option( 'zona_panel_opts' );

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'music_carousel', $atts );

    // Thumb Size
    $thumb_size = 'zona-medium-thumb';

    // Pagination Limit
    $limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;

    if ( isset( $post ) ) { 
        $backup = $post;
    }

    // Classes
    $classes .= ' ' . $background . ' ' . $gap;

    // Loop Args.
    $args = array(
        'post_type' => 'zona_music',
        'orderby'   => $order, // menu_order, date, title
        'order'     => 'ASC',
        'showposts' => $limit
    );

     /* Posts in */
    if ( $posts_in != '' ) {
        $args['post__in'] = explode( ',', $posts_in );
    }

     // Taxonimies
    if ( $display_by != 'all' && $terms != '' ) {
        $terms = explode( ',', $terms );
        $args['tax_query'] = array(
            array(
                'taxonomy' => $display_by,
                'field' => 'slug',
                'terms' => $terms
            )
        );
    }
    
    $music_query = new WP_Query();
    $music_query->query( $args );

    // begin Loop
    if ( $music_query->have_posts() ) {
        $output .= '<div class="slider--carousel-wrap ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';
        $output .= '<div id="music-carousel-id' . esc_attr( $zona_sid ) . '" class="slider--carousel slider--carousel-music" data-auto-height="false" data-slider-pagination="true" data-slider-nav="false" data-slider-speed="500" data-items="' . esc_attr( $visible_items ) . '" data-slider-pause-time="0">';
    
        while ( $music_query->have_posts() ) {
            $music_query->the_post();

            if ( has_post_thumbnail() ) {

                // ID
                $zona_sid++;

                $disabled_link = get_post_meta( $music_query->post->ID, '_disable_link', true );
                if ( ! $disabled_link || $disabled_link == 'off' ) {
                    $disabled_link = false;
                } else {
                    $disabled_link = true;
                }

                $track_id = get_post_meta( $music_query->post->ID, '_thumb_tracks', true );

                $output .= '<article class="' . implode( ' ', get_post_class( 'slider--carousel-slide', $music_query->post->ID ) ) . '">';

                // Permalink
                if ( $link_action == 'permalink' && ! $disabled_link ) {
                    $output .= '<a href="' . get_permalink() . '" class="click-layer">';

                // Music play
                } elseif ( $link_action == 'play_music' && $track_id != 'none' && ! $disabled_link ) {
                    $tracklist_id = 'simple_tracklist_' . $zona_sid;
                    $output .= '<a href="#" class="click-layer sp-play-list" data-id="' . esc_attr( $tracklist_id ) . '">';
                    
                // Disabled
                } else {
                    $output .= '<a class="click-layer">';
                }

                $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $music_query->post->ID ), $thumb_size );
                $output .= '<img class="music--image" src="'.esc_url( $img_src[0] ).'" alt="'. esc_attr( __( 'Post image', 'zona_plugin' ) ) .'">';
                $output .= '</a>';
                
                // Get tracks
                if ( $link_action == 'play_music' && $track_id != 'none' && ! $disabled_link ) {
                    $output .= '<div class="hidden">';
                        if ( function_exists( 'zona_simple_tracklist' ) ) {
                            $output .= zona_simple_tracklist( array( 'id' =>  $track_id, 'tracklist_id' => $tracklist_id ) );
                        } 
                    $output .= '</div>';
                }
                $output .= '</article>';
            }

        } // end loop
        $output .= '</div>'; // End carouesl
        $output .= '</div>'; // End wrap

    } // end have_posts

    if ( isset( $post ) ) {
        $post = $backup;
    }
    return $output;

}
add_shortcode( 'music_carousel', 'zona_music_carousel' );


/* ----------------------------------------------------------------------
    PRICE TABLE

    Example Usage:

/* ---------------------------------------------------------------------- */

function zona_pricing_column( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'title'       => '',
        'price'       => '0',
        'currency'    => '$',
        'period'      => '',
        'link'        => '#',
        'target'      => '_self',
        'button_text' => 'Buy Now',
        'important'   => '',
        'list'        => '2x option 1,Free option 2,Unlimited option 3,Unlimited option 4,1x option 5',
        'background'  => 'bg--light', // bg--dark,bg--light
        'classes'     => '',
        'css'         => ''
    ), $atts));

    $output = '';
    $html_list = '';

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'pricing_column', $atts );

    // Classes
    $classes .= ' ' . $background;
    
    $output .= '<div class="price-table ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';
        
    if ( $important == "yes" ){
        $output .= "<div class='price-table-inner important-price'>";
    } else {
        $output .= "<div class='price-table-inner'>";
    }

    if ( $list != '' ){

        $list = explode( ',', $list );
        if ( is_array( $list) ) {
                $html_list .= '<ul>';
                foreach ( $list as $li ) {
                    $html_list .= '<li>' . $li . '</li>';
                }
                $html_list .= '</ul>';
        }
    }

    $output .= "<ul>";
    $output .= "<li class='prices'>";
    $output .= "<div class='price-wrapper'>";
    $output .= "<sup class='value'>" . $currency . "</sup>";
    $output .= "<span class='price'>" . $price . "</span>";
    $output .= "<sub class='mark'>" . $period . "</sub>";
    $output .= "</div>";
    $output .= "</li>"; // end prices
    $output .= "<li class='table-title'>" . $title . "</li>";
    
    $output .= '<li class="price-content-list">' . $html_list . '</li>'; 
    
    $output .= "<li class='price-button-wrapper'>";
    $output .= "<a class='btn medium' href='$link' target='$target'>" . $button_text . "</a>";
    $output .= "</li>"; // end button
    
    $output .= "</ul>";
    $output .= "</div>"; // end price-table-inner
    $output .="</div>"; // end price-table
    
    return $output;

}
add_shortcode( 'pricing_column', 'zona_pricing_column' );


/* ----------------------------------------------------------------------
    SERVICE BOX
    Example Usage:

/* ---------------------------------------------------------------------- */

function zona_service_box( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'image'      => 0,
        'text'       => 'Lorem ipsum dolor sit amet consectetuer, elit sed diam nonummy nibh',
        'background' => 'bg--light', // bg--dark,bg--light
        'width'      => '',
        'height'     => '',
        'link'       => '',
        'target'     => '',
        'classes'    => '',
        'css'        => ''
    ), $atts));

    $output = '';
    $html_list = '';

    // Link
    if ( $link != '' ) {
        $link = 'href="' . $link . '"';
    }
    if ( $target == '0' ) {
        $target = 'target="_blank"';
    }

    // Sizes
    if ( $width != '' ) {
        $width = 'width:' . $width . ';';
    }
    if ( $height != '' ) {
        $height = 'height:' . $height . ';';
    }

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'service_box', $atts );

    // Classes
    $classes .= ' ' . $background;
    
    $output .= '<a ' . $link . ' ' . $target . ' class="service--box ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';

     // Album Cover
    $image = wp_get_attachment_image_src( $image, 'full' );
    $image = $image[0];
    if ( $image || $image != 0 ) {

        $output .= "<div class='service--image'>";
        $output .= "<div class='service--image-inner'>";
        $output .= '<img src="'. esc_url( $image ) . '" alt="service image" style="' . $width . $height . '">';
        $output .= "</div>";
        $output .= "</div>";
    } 

    if ( $text != '' ){
        $output .= "<div class='service--text'>";
        $text = str_replace(',', '<br>', $text);
        $output .= $text;
        $output .= "</div>";
    }

    $output .="</a>"; // end service box
    
    return $output;

}
add_shortcode( 'service_box', 'zona_service_box' );


/* ----------------------------------------------------------------------
    POSTS GRID

    Example Usage:
    [music_grid filter="yes" limit="40" order="menu_order"]

/* ---------------------------------------------------------------------- */

function zona_posts_grid( $atts, $content = null ) {
    
    extract(shortcode_atts(array(
        'limit'         => '6',
        'columns'       => 'grid-4',
        'url'           => '',
        'gap'           => '',
        'show_featured' => '',
        'categories_in' => '',
        'posts_in'      => '',
        'offset'        => '',
        'order'         => 'date',
        'classes'       => '',
        'css'           => ''
    ), $atts));
    
    global $wp_query, $post;

    $output = '';
    $panel_options = get_option( 'zona_panel_opts' );

    // Date format
    $date_format = get_option( 'date_format' );

    // CSS editor
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'posts_grid', $atts );

    // Thumb Size
    $thumb_size = 'zona-main-thumb';

    // Pagination Limit
    $limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;

    if ( isset( $post ) ) { 
        $backup = $post;
    }

    // Loop Args.
    $args = array(
        'showposts' => $limit,
        'orderby' => $order
    );

    /* Offset */
    if ( $offset != '' ) {
        $args['offset'] = $offset;
    }

    /* Posts in */
    if ( $posts_in != '' ) {
        $args['post__in'] = explode( ',', $posts_in );
    }

    /* Categories */
    if ( $categories_in != '' ) {
        $args['cat'] = $categories_in;
    }

    $posts_query = new WP_Query();
    $posts_query->query( $args );

    // begin Loop
    if ( $posts_query->have_posts() ) {
        $output .= '<div class="blog--grid-wrap-sc ' . esc_attr( $classes ) . ' ' . esc_attr( $css_class ) . '">';
        $output .= '<div class="blog--grid grid-row">';
        while ( $posts_query->have_posts() ) {
            $posts_query->the_post();

            $output .= '<div class="grid--item item-anim anim-fadeup ' . esc_attr( $columns ) . ' grid-tablet-12 grid-mobile-12">';
            $output .= '<article class="' . esc_attr( implode( ' ', get_post_class( 'post-sc', $posts_query->post->ID ) ) ) . '">';

            $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $posts_query->post->ID ), $thumb_size );
            if ( $show_featured == '0' ) {
                $output .= '<img class="featured--image" src="' . esc_url( $img_src[0] ) . '" alt="' . esc_attr__( 'Post image', 'zona' ) .'">';
            }
            $output .= '<div class="article--preview">';
            $output .= '<figure style="background-image: url( '. esc_url( $img_src[0] ) . ')"></figure>';
            $output .= '</div>';
                  
            $output .= '<h2 class="article--title"><a href="' . esc_url( get_permalink() ) . '" >' . get_the_title() . '</a></h2>';
            $output .= '<div class="article--excerpt">';
            if (  has_excerpt() ) {
                $output .=  wp_trim_words( get_the_excerpt(), 30, ' [...]' );
            } else {
                $output .= wp_trim_words( strip_shortcodes( get_the_content() ), 30, ' [...]' ); 
            }
            $output .= '</div>';
            $output .= '<footer class="article--footer meta--cols">';
            $output .= '<div class="meta--col">';
            $output .= '<span class="meta--author-image">' . get_avatar( get_the_author_meta( 'email' ), '50' ) .'</span>';
            $output .= '<div class="meta--author">';
            $output .= esc_html__( 'by', 'zona' ) . ' <a href="' . get_author_posts_url( $posts_query->post->post_author ) . '" class="author-name">' . esc_html( get_the_author_meta( 'display_name', $posts_query->post->post_author ) ) . '</a><br>';
            $output .= '<span class="meta--date">' . get_the_time( $date_format ) .'</span>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<div class="meta--col meta--col-link">';
            $output .= '<a href="' . esc_url( get_permalink() ) . '" class="btn--read-more meta--link">' . esc_html__( 'Read more', 'zona' ) . '</a>';
            $output .= '</div>';
            $output .= '</footer>';
            $output .= '</article>';
            $output .= '</div>';

        } // end loop
        $output .= '</div>'; // End grid
        $output .= '</div>'; // End wrap

    } // end have_posts

    if ( isset( $post ) ) {
        $post = $backup;
    }
    return $output;

}
add_shortcode( 'posts_grid', 'zona_posts_grid' );