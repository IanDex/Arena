<?php
/**
 * Theme Name: 		Zona
 * Theme Author: 	Mariusz Rek - Rascals Themes
 * Theme URI: 		http://rascalsthemes.com/zona
 * Author URI: 		http://rascalsthemes.com
 * File:			sidebar-footer-col2.php
 * =========================================================================================================================================
 *
 * @package zona
 * @since 1.0.0
 */
?>

<?php if ( is_active_sidebar( 'footer-col2-sidebar' )  ) : ?>
	<?php dynamic_sidebar( 'footer-col2-sidebar' ); ?>
<?php endif; ?>