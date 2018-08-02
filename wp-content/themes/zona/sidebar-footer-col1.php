<?php
/**
 * Theme Name: 		Zona
 * Theme Author: 	Mariusz Rek - Rascals Themes
 * Theme URI: 		http://rascalsthemes.com/zona
 * Author URI: 		http://rascalsthemes.com
 * File:			sidebar-footer-col1.php
 * =========================================================================================================================================
 *
 * @package zona
 * @since 1.0.0
 */
?>

<?php if ( is_active_sidebar( 'footer-col1-sidebar' )  ) : ?>
	<?php dynamic_sidebar( 'footer-col1-sidebar' ); ?>
<?php endif; ?>