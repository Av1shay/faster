<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package faster
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php
			$faster_comment_count = get_comments_number();
			if ( '1' === $faster_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'faster' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf( // WPCS: XSS OK.
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $faster_comment_count, 'comments title', 'faster' ) ),
					number_format_i18n( $faster_comment_count ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
			) );
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'faster' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().
	$text_align = is_rtl() ? 'text-left' : 'text-right';
    $comments_form_args = array(
        'comment_field' => '<div class="form-group"><label for="comment">'._x('Comment', 'noun', 'faster').'</label>
            <textarea id="comment" name="comment" class="form-control" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea></div>',
        'class_submit' => 'btn btn-info',
        'submit_field' => '<p class="form-submit '.$text_align.'">%1$s %2$s</p>'
    );
	comment_form($comments_form_args);
	?>

</div><!-- #comments -->
