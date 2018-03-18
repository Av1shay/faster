<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package faster
 */

$footer_content = get_theme_mod('faster_footer_content') ?  get_theme_mod('faster_footer_content') : __('Theme Faster by Avishay', 'faster');
$bg_color = get_theme_mod('faster_footer_bg_color');
?>

    </div> <!-- .container -->
	</div><!-- #content -->
	<footer id="colophon" class="site-footer border-top pt-2">
        <?php if ( has_active_footer_sidebar() ) : ?>
            <div class="container">
                <?php get_sidebar('footer') ?>
            </div>
        <?php endif ?>
		<div id="footer-site-info" class="site-info" style="background-color: <?php echo '#' . $bg_color ?>">
			<?php echo $footer_content ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
