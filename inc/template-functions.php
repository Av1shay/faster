<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package faster
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function faster_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'faster_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function faster_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'faster_pingback_header' );

/**
 * Remove URL field from comment form.
 *
 * @param $fields array of fields
 *
 * @return array w/o URL field
 */
function faster_remove_commentform_url_field( $fields ) {
	if ( isset( $fields['url'] ) ) {
		unset( $fields['url'] );
	}

	return $fields;
}
add_filter( 'comment_form_default_fields', 'faster_remove_commentform_url_field' );

/**
 * Dynamic styles goes here.
 */
function faster_inline_styles(){
	?>
	<style>
		.nav-links .nav-previous{
			float: <?php echo is_rtl() ? 'right' : 'left' ?>;
		}
		.nav-links .nav-next{
			float: <?php echo is_rtl() ? 'left' : 'right' ?>;
		}

	</style>
	<?php
}
add_action('wp_footer', 'faster_inline_styles');

/**
 * Append read more to post excerpt on archives.
 *
 * @param $excerpt
 *
 * @return string
 */
/*function faster_excerpt_read_more($excerpt){
    if ( is_singular() ) {
        return $excerpt;
    }

	$more_link_text = sprintf(
		wp_kses(
			__( 'Continue reading<span class="sr-only">"%s"</span>', 'faster' ),
			array(
				'span' => array(
					'class' => array(),
				),
			)
		),
		get_the_title()
	);
	$more_link = sprintf('<a href="%s">%s</a>', esc_url(get_the_permalink()), $more_link_text);

	return $excerpt . $more_link;
}
add_filter('the_excerpt', 'faster_excerpt_read_more');*/