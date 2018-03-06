<?php
/**
 * Display footer widget area(s).
 *
 *@package faster
 */

//How many footer widgets areas are currently active
$currently_active = 0 ;
if ( is_active_sidebar('footer-1') )	$currently_active++;
if ( is_active_sidebar('footer-2') )	$currently_active++;
if ( is_active_sidebar('footer-3') )	$currently_active++;
if ( is_active_sidebar('footer-4') )	$currently_active++;

?>
<div class="footer-widget-areas row">

	<?php if ( is_active_sidebar('footer-1') ) : ?>
		<div class="widget-area col-sm-<?php echo 12/$currently_active; ?>">
			<?php dynamic_sidebar('footer-1')?>
		</div>
	<?php endif; ?>
	<?php if ( is_active_sidebar('footer-2') ) : ?>
		<div class="widget-area col-sm-<?php echo 12/$currently_active; ?>">
			<?php dynamic_sidebar('footer-2')?>
		</div>
	<?php endif; ?>
	<?php if ( is_active_sidebar('footer-3') ) : ?>
		<div class="widget-area col-sm-<?php echo 12/$currently_active; ?>">
			<?php dynamic_sidebar('footer-3')?>
		</div>
	<?php endif; ?>
	<?php if ( is_active_sidebar('footer-4') ) : ?>
		<div class="widget-area col-sm-<?php echo 12/$currently_active; ?>">
			<?php dynamic_sidebar('footer-4')?>
		</div>
	<?php endif; ?>


</div><!-- .footer-widget-area -->