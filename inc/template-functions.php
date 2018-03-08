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
 * Prevent user from see post following faster_post_visibility value.
 *
 * @param $query WP_Query
 */
function faster_prevent_visibility($query){
	if ( ! is_single() || 'post' != get_post_type() || is_admin() ) {
		return;
	}

	$post_id = get_the_ID();
	$access = 0;
	$visibility = get_post_meta($post_id, 'faster_post_visibility', true);

	if ( $visibility == 'visibility_all' ) {
		return;
	}

	if ( $visibility == 'visibility_logged_in' && ! is_user_logged_in() ) {
		wp_die(sprintf(__('You don&#39;t have sufficient permissions to access this page.<br/><a href="%s">Back to homepage</a>', 'faster'), get_home_url()));
	}

	if ( $visibility == 'visibility_define_roles' ) {
		$user_roles = wp_get_current_user()->roles;
		$visibility_roles = unserialize(get_post_meta($post_id, 'faster_post_visibility_roles', true));
		for ( $i = 0; $i < count($user_roles); $i++ ) {
			if ( in_array($user_roles[$i], $visibility_roles) ) { // if the user can see the post, we are good to go
				$access = 1;
				break;
			}
		}

		if ( ! $access ) {
			wp_die(sprintf(__('You don&#39;t have sufficient permissions to access this page.<br/><a href="%s">Back to homepage</a>', 'faster'), get_home_url()));
		}
	}
}
add_action('pre_get_posts', 'faster_prevent_visibility');

/**
 * Set short picker API Key.
 * @see https://github.com/short-pixel-optimizer/shortpixel-php
 */
ShortPixel\setKey('ngqWXzlZxmjgZnXi8vUv');

/**
 * Hook after image successfully uploaded and compress it
 * @TODO compress also the uploaded image sizes
 *
 * @param $upload array
 * @param $context
 * @return array
 */
function faster_compress_image($upload, $context){
	$upload_dir = wp_upload_dir();

	if ( $upload['type'] != 'image/jpeg' && $upload['type'] != 'image/png' ) {
		return $upload;
	}

	$res = ShortPixel\fromFile($upload['file'])->toFiles($upload_dir['path']);

	return $upload;
}
add_filter('wp_handle_upload', 'faster_compress_image', 10, 2);