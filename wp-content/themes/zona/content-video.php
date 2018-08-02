<?php
/**
 * Theme Name:      Zona
 * Theme Author:    Mariusz Rek - Rascals Themes
 * Theme URI:       http://rascalsthemes.com/zona
 * Author URI:      http://rascalsthemes.com
 * File:            content.php
 * =========================================================================================================================================
 *
 * @package zona
 * @since 1.0.0
 */
?>

<?php 

   $zona_opts = zona_opts();
   
   // Video Link
   $video_url = get_post_meta( $wp_query->post->ID, '_video_url', true );


?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'single--post-article' ); ?>>

    <!-- ############ POST HEADER ############ -->
    <header class="single--post-header content--extended">
        <h1><?php the_title(); ?></h1>
        <?php if ( $video_url != '' ) : ?>
            
        <div class="single--media">

                <?php

                $yt_rx = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
                $has_match_youtube = preg_match($yt_rx, $video_url, $yt_matches);

                $vm_rx = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/';
                $has_match_vimeo = preg_match($vm_rx, $video_url, $vm_matches);

                //Then we want the video id which is:
                if ( $has_match_youtube ) {
                    $video_id = $yt_matches[5]; 
                    $type = 'youtube';
                    $video_str = 'https://www.youtube.com/embed/%s?autoplay=1&autohide=1';
                }
                elseif ( $has_match_vimeo ) {
                    $video_id = $vm_matches[5];
                    $type = 'vimeo';
                    $video_str = 'https://player.vimeo.com/video/%s?autoplay=1';
                }
                else {
                    $video_id = 0;
                    $type = 'none';
                }


            ?>

            <div class="video">
                <iframe frameborder="0" src="<?php echo esc_attr( sprintf( $video_str, $video_id ) ) ?>" width="1200" height="688"></iframe>
            </div>


          
        </div>

        <?php endif ?>
    </header>

    <?php the_content( esc_html__( '...View the rest of this post', 'zona' ) ); ?>

    <?php
        wp_link_pages( array(
            'before'    => '<div class="page-links">' . esc_html__( 'Jump to Page', 'zona' ),
            'after'     => '</div>',
        ) );
    ?>

    <footer class="single--post-footer">

        <!-- Meta -->
        <div class="single--post-meta meta--cols">
            <div class="meta--col">
                <?php if ( function_exists( 'zona_meta_share_buttons' ) ) { echo zona_meta_share_buttons( $post->ID ); } ?>

            </div>
        </div>
       
    </footer>

</article><!-- #post-## -->