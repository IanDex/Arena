<?php
/**
 * Template Name: Events
 *
 * @package zona
 * @since 1.0.0
 */

get_header(); ?>



<?php 

    $zona_opts = zona_opts();

    // Copy query
    $temp_post = $post;
    $query_temp = $wp_query;

    // Thumb Size
    $thumb_size = 'zona-medium-thumb';

    // Options
    $limit = (int)get_post_meta( $wp_query->post->ID, '_limit', true );
    $limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;
    $pagination_method = get_post_meta( $wp_query->post->ID, '_pagination', true ); // pagination-ajax, pagination-default
    $ajax_filter = get_post_meta( $wp_query->post->ID, '_ajax_filter', true );
    $ajax_filter = ! $ajax_filter || $ajax_filter == 'on' ? $ajax_filter = '' : $ajax_filter = 'hidden';
    $event_type = get_post_meta( $wp_query->post->ID, '_event_type', true );

    // BG Image
    $events_bg = get_post_meta( $wp_query->post->ID, '_events_bg', true );
    // If image exists
    if ( $events_bg ) {
        $events_bg = $zona_opts->get_image( $events_bg );
    } else {
        $events_bg = '';
    }

    // Date format
    $date_format = 'd-m';
    if ( $zona_opts->get_option( 'event_date' ) ) {
        $date_format = $zona_opts->get_option( 'event_date' );
    }

?>



<?php 
    // Get Custom Intro Section
    get_template_part( 'inc/custom-intro' );

?>




<!--############ Filter ############ -->
<div class="responsive-mobile events-filter filters-wrapper <?php echo esc_attr( $ajax_filter ); ?>">
    
    <!-- Filter -->
    <div class="filter filter-simple" data-grid="events--list" data-obj='{"action": "zona_events_filter", "filterby": "taxonomy", "cpt": "zona_events", "tax": "zona_events_cats", "limit": "<?php echo esc_attr( $limit ); ?>", "event_type": "<?php echo esc_attr( $event_type ); ?>"}' >

        <ul data-filter-group="">
          <?php /* ?>  
          <li><a href="#" data-filter-name="all" class="is-active anim--reveal-static"><?php esc_html_e( 'All', 'zona' ) ?></a></li>
                <?php 
                    $term_args = array( 'hide_empty' => '1', 'orderby' => 'name', 'order' => 'ASC' );
                    $terms = get_terms( 'zona_events_cats', $term_args );
                    if ( $terms ) {
                        foreach ( $terms as $term ) {
                            echo '<li><a href="#" data-filter-name="' . esc_attr( $term->term_id ) . '" class="anim--reveal-static">' . esc_html( $term->name ) . '</a></li>';
                        }
                    }
                ?>
        <?php  */ ?>
<div class="content" style="padding: 0">
<div class="container">
<div style="margin-bottom: 40px">
    
<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
    <div class="grid-row">

        <div class="grid-12 quitaright" style="position: absolute;">
            <div class="grid-mobile-2">
                <a href=""><img src="http://www.movistararena.co/wp-content/uploads/2018/07/icons8-panel-de-detalles-filled-30.png" style="padding: 12px 0;"></a>
                <a href=""><img src="http://www.movistararena.co/wp-content/uploads/2018/07/icons8-lista-30.png" style="padding: 12px 0;"></a>
            </div>

        <div class="grid-mobile-6">
            
  <label>
    <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
    <input style="border-radius: 4px; margin: 5px" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Busca un evento', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
  </label>



  </div>
  <div class="grid-mobile-4" style="float: left;">
    
     
      <input style="border-radius: 4px; margin: 6px; margin-left: 12px" type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
  </div>
  </div>
    </div>

</form>
</div></div></div>

<style type="text/css">
    @media only screen and (max-width:479px) {

    /* Grid */
    .quitaright{
        right: 0%;
        background-color: #fff;
    }

    .grid-row [class^="grid-"] {
        float: left;
    }
}
</style>


        </ul>
    </div>
    <!-- /filter -->

</div>
<!-- events-filter -->

<!-- ############ CONTENT ############ -->
<div class="content" style="background-color: #131415; padding-top: 15px; margin-top: 50px">

    <!-- ############ Container ############ -->
    <div class="container">
        
        <?php 
        if ( $event_type == 'all-events' ) {
                // Future Events
                $future_events = get_posts( array(
                    'post_type' => 'zona_events',
                    'showposts' => -1,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'zona_event_type',
                            'field' => 'slug',
                            'terms' => 'future-events'
                        )
                    ),
                    'orderby' => 'meta_value',
                    'meta_key' => '_event_date_start',
                    'order' => 'ASC'
                ));

                // Past Events
                $past_events = get_posts(array(
                    'post_type' => 'zona_events',
                    'showposts' => -1,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'zona_event_type',
                            'field' => 'slug',
                            'terms' => 'past-events'
                        ),
                    ),
                    'orderby' => 'meta_value',
                    'meta_key' => '_event_date_start',
                    'order' => 'DSC'
                ));

                $future_nr = count( $future_events );
                $past_nr = count( $past_events );

                // echo "Paged: $paged, Future events: $future_nr, Past events: $past_nr";

                $mergedposts = array_merge( $future_events, $past_events ); //combine queries

                $postids = array();
                foreach( $mergedposts as $item ) {
                    $postids[] = $item->ID; //create a new query only of the post ids
                }
                $uniqueposts = array_unique( $postids ); //remove duplicate post ids

                // var_dump($uniqueposts);
                $args = array(
                    'post_type' => 'zona_events',
                    'paged'     => $paged,
                    'post__in'  => $uniqueposts,
                    'orderby' => 'post__in'
                );

            } else {

                // Begin Loop

                /* Set order */
                $order = $event_type == 'future-events' ? $order = 'ASC' : $order = 'DSC';

                // Event type taxonomy
                $tax = array(
                    array(
                       'taxonomy' => 'zona_event_type',
                       'field' => 'slug',
                       'terms' => $event_type
                      )
                );

                // Begin Loop
                $args = array(
                    'post_type'        => 'zona_events',
                    'tax_query'        => $tax,
                    'orderby'          => 'meta_value',
                    'meta_key'         => '_event_date_start',
                    'order'            => $order,
                    'paged'            => get_query_var( 'paged' ),
                    'suppress_filters' => 0 // WPML FIX
                );

            }

            // Posts number
            $temp_args = $args;
            $temp_args['showposts'] = -1;
            $temp_query_count = new WP_Query();
            $temp_query_count->query( $temp_args );
            $posts_nr = $temp_query_count->post_count;

            // Add limit
            $args['showposts'] = $limit;

            $wp_query = new WP_Query();
            $wp_query->query( $args );
        ?>
        
        <?php if ( have_posts() ) : ?>
                
            <div class="events--list-wrap">
               <ul class="events--list" data-min-height="100" style="max-width: none;">
                    
                    <?php // Start the Loop.
                    while ( have_posts() ) : the_post() ?>
                        <?php 
                        /* Datos Agregados de más 
                        $dia = get_post_meta( $wp_query->post->ID, 'dia', true );
                        $mes = get_post_meta( $wp_query->post->ID, 'mes', true );
                        $fondo_evento = get_post_meta( $wp_query->post->ID, 'fondo_evento', true );
                        /* Event Date */
                        $event_time_start = get_post_meta( $wp_query->post->ID, '_event_time_start', true );
                        $event_date_start = get_post_meta( $wp_query->post->ID, '_event_date_start', true );
                        $event_date_start = strtotime( $event_date_start );
                        $event_date_end = strtotime( get_post_meta( $wp_query->post->ID, '_event_date_end', true ) );
                        /* Event data */
                        $event_place = get_post_meta( $wp_query->post->ID, '_event_place', true );
                        $event_city = get_post_meta( $wp_query->post->ID, '_event_city', true );
                        $event_tickets_url = get_post_meta( $wp_query->post->ID, '_event_tickets_url', true );
                        $event_tickets_target = get_post_meta( $wp_query->post->ID, '_event_tickets_new_window', true );

                   //     $imagen = $fondo_evento;
                         ?>




<!-- Listado de Eventos by Cristtian <?php echo atrib_imagen_destacada(); ?>-->
            <div style="border-bottom: 1px solid #222;" <?php post_class( 'grid--item item-anim anim-fadeup' ); ?>> 
                <div class="grid-row">
                    <div class="grid-2" style="padding-top: 20px">
                        <div style="font-size:15px; text-align: center; color: #00A9E0"><strong>JUL</strong></div>
                        <div style="font-size:45px; text-align: center; color: #fff; font-weight: bold; margin: 4px"><strong>19</strong></div>
                        <div style="font-size:15px; text-align: center; color: #00A9E0"><strong>JUE</strong></div>
                    </div>
                    <div class="grid-2" style="padding: 10px;">

                                <img class="music--image" src="<?php echo atrib_imagen_destacada(); ?>" alt="<?php esc_attr_e( 'Event image', 'zona' ) ?>">
                    </div>
                    <div class="grid-4" style="padding-top: 30px">
                        <h5><?php the_title() ?></h5>
                    </div>
                    <div class="grid-2" style="padding-top: 10px">
                        <div class="grid-12" style="padding-top: 30px">
                            <div style="font-size:15px; text-align: left; color: #fff; font-weight: bold; line-height: 2px;">Thu, Jul 19</div>
                            <div style="font-size:13px; text-align: left; color: #fff;">6:00 pm</div>
                        </div>
                        <div class="grid-12" style="padding-top: 30px">
                            <div style="font-size:15px; text-align: left; color: #fff; font-weight: bold; line-height: 2px;">The Forum</div>
                            <div style="font-size:13px; text-align: left; color: #fff;">Inglewood, CA</div>
                        </div>
                    </div>
                    <div class="grid-2 grid-tablet-12 grid-mobile-12" style="padding-top: 30px">
                        <div class="grid-12 ">    
                            <a style="min-width: 100%; min-height: 39px; line-height: inherit;" class="event--button anim--reveal" href="<?php the_permalink() ?>"><span><strong><?php esc_html_e( 'Más Info', 'zona' ) ?></strong></span></a>
                        </div>
                        <div class="grid-12">
                            <a style="min-width: 100%; min-height: 39px; background-color: #fff; line-height: inherit;" class="event--button anim--reveal" href="<?php echo esc_html($event_tickets_url) ?>"><span>
                               <strong><?php esc_html_e( 'Tickets', 'zona' ) ?></strong></span></a>
                        </div>
                    </div>
                </div>
            </div>


<!-- // Listado de Eventos by Cristtian-->


<!--
                         <div class="grid-4" style="padding: 10px; ">
                            <a href="<?php //the_permalink() ?>">
                                <div class="contenedor">
                                    <span class="opacar">
                            <img src="<?php //echo esc_html( $fondo_evento ) ?>" style="width: auto; height: auto; "></span>
                            <div>
                            <div class="mes"><h3 id="mes"><?php //echo esc_html( $mes ) ?></h3></div>
                            <div class="dia"><h1 id="dia"><?php //echo esc_html( $dia ) ?></h1></div>
                            <div class="artista"><b id="artista">Shaggy & Sting</b></div>
                            </div> </div>
                       </a>
                       </div>

                         
                       -->
            
                    <?php endwhile; ?>
                </ul>

            </div>

            <!-- Stykes -->
<style type="text/css">

.contenedor{
    position: relative;
    display: inline-block;
    text-align: center;

}
    .mes{
    position: absolute;
    bottom: 90px;
    left: 10px;
}
.dia{
    position: absolute;
    bottom: 50px;
    left: 10px;
}
.artista{
    position: absolute;
    bottom: 50px;
    left: 10px;
}

#mes{
    font-family: 'Arial';
    color: #00ECFF;
}

#dia{
    font-family: 'Arial';
    color: #FF0000
}

#artista{
    font-family: 'Arial';
    color: #FF00E4
}

.opacar img {
filter:alpha(opacity=50);
-moz-opacity: 0.5;
opacity: 0.7;}

.opacar:hover img {
filter:alpha(opacity=100)3000;
-moz-opacity: 1.0;
opacity: 1.0;
}
</style>
              
            <!-- //Onclick -->            


                <!-- /list -->
            <div class="clear"></div>
            <?php if ( $pagination_method == 'pagination-ajax' ) : ?>
                <div class="load-more-wrap <?php if ( $posts_nr <= $limit ) { echo esc_attr( 'hidden' ); } ?>">
                    <a href="#" data-pagenum="2" class="btn--frame btn--dark btn--big load-more"><span></span><?php esc_html_e( 'Ver más', 'zona' ) ?></a>
                 </div>
            <?php else : ?>
                <?php zona_paging_nav(); ?>
            <?php endif; // pagination ?>
        <?php else : ?>
            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'zona' ); ?></p>
        <?php endif; // have_posts() ?>
            
    </div>
    <!-- /container -->
</div>
<!-- /content -->
<?php get_footer(); ?>