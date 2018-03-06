<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package faster
 */

if ( ! is_active_sidebar( 'sidebar-1' ) || empty(get_option('faster_show_sidebar')) ) {
	return;
}
?>

<aside id="secondary" class="widget-area <?php echo apply_filters('secondary-bootstrap-column', 'col-sm-4') ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
