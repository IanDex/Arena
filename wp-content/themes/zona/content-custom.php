<?php
/**
 * Theme Name: 		Zona
 * Theme Author: 	Mariusz Rek - Rascals Themes
 * Theme URI: 		http://rascalsthemes.com/zona
 * Author URI: 		http://rascalsthemes.com
 * File:			content-custom.php
 * =========================================================================================================================================
 *
 * @package zona
 * @since 1.0.0
 */
?>

<?php 

   $zona_opts = zona_opts();
?>
	
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
    <?php  the_content( esc_html__( 'Continue reading ', 'zona' ) . '<span class="meta-nav">&rarr;</span>' ); ?>
 	
</article>