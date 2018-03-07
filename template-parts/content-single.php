<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package faster
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('border-bottom pb-2 pt-2'); ?>>
	<div class="row">
		<div class="col-sm-12">

			<header class="entry-header">
				<?php
				the_title( '<h1 class="entry-title">', '</h1>' );

				if ( 'post' === get_post_type() ) :
					?>
					<div class="entry-meta">
						<?php
						faster_posted_on();
						faster_posted_by();
						?>
					</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->

			<?php if ( has_excerpt() ) : ?>
				<div class="entry-excerpt">
					<?php the_excerpt() ?>
				</div>
			<?php endif; ?>

			<div class="entry-content">
				<?php the_content(); ?>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php faster_entry_footer(); ?>
			</footer><!-- .entry-footer -->

		</div><!-- col-sm-x-->

	</div>
</article><!-- #post-<?php the_ID(); ?> -->
