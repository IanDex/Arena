<?php
/**
 * Plugin Name: 	Zona Extensions
 * Theme Author: 	Mariusz Rek - Rascals Themes
 * Theme URI: 		http://rascalsthemes.com/zona
 * Author URI: 		http://rascalsthemes.com
 * File:			shortcodes-helpers.php
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
	REQUIRED STYLES AND SCRIPTS
/* ---------------------------------------------------------------------- */
function zona_scripts() {

	wp_enqueue_style( 'zona-shortcodes' , plugins_url( 'assets/css/shortcodes.css' , __FILE__ ) , array() , '1.0' , 'all' );
    wp_enqueue_script( 'countdown', plugins_url( 'assets/js/jquery.countdown.js' , __FILE__ ), false, false, true );
	wp_enqueue_script( 'zona-shortcodes' , plugins_url( 'assets/js/shortcodes.js' , __FILE__ ) , array( 'jquery' ) , '1.0' , true );

}
add_action( 'wp_enqueue_scripts' , 'zona_scripts' );


/* ----------------------------------------------------------------------
    SHARE GRID BUTTONS
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'zona_share_grid_buttons' ) ) :
function zona_share_grid_buttons( $post_id ) {
    return '
            <a class="share-button fb-share-btn" target="_blank" href="http://www.facebook.com/sharer.php?u=' . esc_url( get_permalink( $post_id ) ) . '"><span class="icon icon-facebook"></span></a>
            <a class="share-button twitter-share-btn" target="_blank" href="http://twitter.com/share?url=' . esc_url( get_permalink( $post_id ) ) . '"><span class="icon icon-twitter"></span></a>
            <a class="share-button gplus-share-btn" target="_blank" href="https://plus.google.com/share?url=' . esc_url( get_permalink( $post_id ) ) . '"><span class="icon icon-googleplus"></span></a>
            <a class="share-button linkedin-share-btn" target="_blank" href="https://www.linkedin.com/cws/share?url=' . esc_url( get_permalink( $post_id ) ) . '"><span class="icon icon-linkedin"></span></a>';
}
endif;

/* ----------------------------------------------------------------------
    THEME ICONS
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'zona_get_icons' ) ) :
function zona_get_icons() {

    // Icomoon parser
    // $json = '';
    // $obj = json_decode( $json );

    // foreach ( $obj->icons as $icon ) {

    //     echo "'".$icon->properties->name."', ";

    // }
    
    $icons = array('', 'pushpin', 'home', 'pencil', 'pencil2', 'droplet', 'image', 'image2', 'images', 'camera2', 'music', 'headphones', 'play', 'film', 'bullhorn', 'connection', 'tag', 'cart', 'support', 'phone2', 'envelope', 'location', 'map', 'clock', 'calendar', 'screen', 'download', 'upload', 'bubbles', 'user', 'users', 'quotes-left', 'spinner', 'search', 'zoomin', 'zoomout', 'expand', 'wrench', 'equalizer', 'cog', 'cog2', 'pie', 'stats', 'bars', 'bars2', 'gift', 'rocket2', 'fire', 'lab', 'lightning', 'list', 'numbered-list', 'menu', 'menu2', 'cloud', 'earth', 'link', 'eye', 'eye-blocked', 'bookmark', 'star', 'star2', 'star3', 'heart', 'thumbs-up', 'thumbs-up2', 'cancel-circle', 'close', 'checkmark', 'minus', 'plus', 'play2', 'pause', 'volume-high', 'arrow-right', 'arrow-left', 'arrow-up', 'arrow-right2', 'arrow-down', 'arrow-left2', 'newtab', 'code', 'share', 'mail', 'googleplus', 'google-drive', 'facebook', 'twitter', 'feed', 'youtube', 'youtube2', 'vimeo', 'lanyrd', 'flickr', 'flickr2', 'picassa', 'dribbble', 'forrst', 'deviantart', 'steam', 'github', 'github2', 'wordpress', 'joomla', 'blogger', 'tumblr', 'yahoo', 'tux', 'apple', 'finder', 'android', 'windows', 'soundcloud', 'skype', 'reddit', 'linkedin', 'lastfm', 'delicious', 'stumbleupon', 'stackoverflow', 'pinterest', 'xing', 'flattr', 'paypal', 'yelp', 'html5', 'html52', 'css3', 'chrome', 'firefox', 'IE', 'opera', 'number', 'number2', 'number3', 'number4', 'number5', 'number6', 'number7', 'number8', 'number9', 'number10', 'phone', 'tablet', 'window', 'monitor', 'ipod', 'camera', 'lamp', 'diamond', 'paperplane', 'rocket', 'globe', 'ruler', 'chevron-left', 'chevron-right', 'chevron-up', 'chevron-down', 'angle-left', 'angle-right', 'angle-up', 'angle-down');

    asort( $icons );

    return $icons;
}

endif;


/* ----------------------------------------------------------------------
    TWITTER FEED
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'zona_twitter_feed' ) ) :
function zona_twitter_feed( $options ) {

    // Defaults options
    $defaults = array(
        'time'       => 30,
        'limit'      => '1',
        'username'   => '',
        'replies'    => 'no',
        'api_key'    => '',
        'api_secret' => ''
    );

    if ( isset( $options ) && is_array( $options ) ) {
        $options = array_merge( $defaults, $options );
    } else { 
        $options = $defaults;
    }

    // Extract $options
    extract( $options, EXTR_PREFIX_SAME, "twitter" );

    // Errors
    $errors = '';

    if ( empty( $api_key ) ) $errors = __( 'ERROR: Missing API Key.', 'zona_plugin' );
    if ( empty( $api_secret ) ) $errors = __( 'ERROR: Missing API Secret.', 'zona_plugin' );
    if ( empty( $username ) ) $errors = __( 'ERROR: Missing Twitter Feed User Name.', 'zona_plugin' );
    if ( $errors ) {
        return '<p class="error">ERROR: ' . $errors . '</p>';
    }

    // Replies
    if ( $replies == 'yes' ) {
        $replies = '0';
    } else {
        $replies = '1';
    }

    // Vars
    $trans_name = 'rascals_tweets_' . $username;
    $token = '';
    $count = 1;
    $output = '';

    // delete_transient( $trans_name );

    /* Shelude feed */
    if ( false === ( $tweet_task = get_transient( $trans_name ) ) ) {

        $bearer_token_credential = $api_key . ':' . $api_secret;
        $credentials = base64_encode( $bearer_token_credential );
        
        $args = array(
            'method' => 'POST',
            'httpversion' => '1.1',
            'blocking' => true,
            'headers' => array( 
                'Authorization' => 'Basic ' . $credentials,
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
            ),
            'body' => array( 'grant_type' => 'client_credentials' )
        );

        add_filter( 'https_ssl_verify', '__return_false' );
        $response = wp_remote_post( 'https://api.twitter.com/oauth2/token', $args );

        $keys = json_decode( $response['body'] );
        
        if ( $keys ) {
            $token = $keys->{'access_token'};

            $args = array(
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => array( 
                    'Authorization' => "Bearer $token"
                )
            );
            add_filter('https_ssl_verify', '__return_false');
            $api_url = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=$username&count=20&exclude_replies=$replies&include_rts=0";

            $response = wp_remote_get( $api_url, $args );

            set_transient( $trans_name, $response['body'], 60 * $time );

        } else {
            delete_transient( $trans_name );
            return false;       
        }
        
    } 

    @$json = json_decode( get_transient( $trans_name ) );

    if ( ! empty( $json ) ){

        /* If feed has error */
        if ( isset( $json->errors ) ) {
            $errors = '';

            foreach ( $json->errors as $error ) {
                $errors .= '<p class="error">ERROR: ' . $error->code . ': ' . $error->message . '</p>';
            }

            // Delete transient
            delete_transient( $trans_name );
            return $errors;
        }

        $tweets_a = array();
        foreach ( $json as $tweet ) {
            $datetime = $tweet->created_at;
            $date = date('F j, Y, g:i a', strtotime( $datetime));
            $time = date('g:ia', strtotime( $datetime ) );
            $date = human_time_diff( strtotime( $date ), current_time( 'timestamp', 1 ) );
            $tweet_text = $tweet->text;
            
            // check if any entites exist and if so, replace then with hyperlinked versions
            $tweet_text = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $tweet_text);

            $tweet_text = preg_replace('/https:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '<a href="http://$1" target="_blank">https://$1</a>&nbsp;', $tweet_text);

            // convert @ to follow
            $tweet_text = preg_replace("/(@([_a-z0-9\-]+))/i","<a href=\"http://twitter.com/$2\" title=\"Follow $2\" >$1</a>",$tweet_text);

            // convert # to search
            $tweet_text = preg_replace("/(#([_a-z0-9\-]+))/i","<a href=\"https://twitter.com/search?q=%23$2&amp;src=hash\" title=\"Search $1\" >$1</a>",$tweet_text);

            $tweets_a[$count]['text'] = $tweet_text;
            $tweets_a[$count]['date'] = '<a href="https://twitter.com/' . esc_attr( $username ) . '/statuses/' . $tweet->id_str . '">' . $date . ' ' . __('ago', 'zona_plugin') . '</a>';
            
            // if ( $count == $limit ) {
            //     break;
            // }
            $count++;
                
        }
          
        return $tweets_a;
    } else {
        return '<p class="error">' . __( 'ERROR: Username not exists or Twitter API error.', 'zona_plugin' ) . '</p>';
        delete_transient( $trans_name );
    }

    
}

endif;