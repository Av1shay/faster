<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package faster
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('border-bottom pb-2 pt-2'); ?>>
    <div class="row">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="col-sm-3">
                <?php faster_post_thumbnail() ?>
            </div>
        <?php endif ?>

        <div class="col-sm-<?php echo has_post_thumbnail() ? '9' : '12' ?>">

            <header class="entry-header">
                <?php
                if ( is_singular() ) :
                    the_title( '<h1 class="entry-title">', '</h1>' );
                else :
                    the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                endif;

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

            <div class="entry-content">
                <?php
                if ( has_excerpt() ) {
                    the_excerpt();
                } else {
                    the_content('');
                }
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'faster' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div><!-- .entry-content -->
            <div style="margin-top: 3.5rem"></div>
            <footer class="entry-footer">
                <?php faster_entry_footer(); ?>
            </footer><!-- .entry-footer -->

        </div><!-- col-sm-x-->

    </div>
</article><!-- #post-<?php the_ID(); ?> -->
