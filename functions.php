<?php
/**
 * faster functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package faster
 */

if ( ! function_exists( 'faster_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function faster_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on faster, use a find and replace
		 * to change 'faster' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'faster', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		add_image_size('faster-thumbnail', 255, 176, true);

		// This theme uses wp_nav_menu) in one location.
		register_nav_menus( array(
			'primary-menu' => esc_html__( 'Primary', 'faster' ),
			'secondary-menu' => esc_html__( 'Secondary', 'faster' ),
			'footer-menu' => esc_html__( 'Footer', 'faster' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'faster_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 200,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'faster_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function faster_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'faster' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'faster' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer 1', 'faster' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'First column in the footer.', 'faster' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer 2', 'faster' ),
		'id'            => 'footer-2',
		'description'   => esc_html__( 'Second column in the footer.', 'faster' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer 3', 'faster' ),
		'id'            => 'footer-3',
		'description'   => esc_html__( 'Third column in the footer.', 'faster' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer 4', 'faster' ),
		'id'            => 'footer-4',
		'description'   => esc_html__( 'Fourth column in the footer.', 'faster' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'faster_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function faster_scripts() {
	$faster = wp_get_theme();

	wp_enqueue_style('faster-style', $faster->get_template_directory_uri() . '/css/faster.min.css', array(), $faster->display('Version'));
	wp_enqueue_script( 'faster-script', $faster->get_template_directory_uri() . '/js/faster.min.js', array('jquery'), $faster->display('Version'), true );

	/*if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}*/
}
add_action( 'wp_enqueue_scripts', 'faster_scripts' );

function faster_admin_scripts(){
	$faster = wp_get_theme();

	wp_enqueue_script('faster-admin-script', $faster->get_template_directory_uri() . '/js/admin.min.js', array('jquery'), $faster->display('Version'), true);
	wp_enqueue_style('faster-admin-style', $faster->get_template_directory_uri() . '/css/admin.css', array(), $faster->display('Version'));
}
add_action('admin_enqueue_scripts', 'faster_admin_scripts');

/**
 * If there is no sidebar, let the content speared on the entire width.
 */
add_filter('primary-bootstrap-column', function ($def_column_num){
	if ( empty(get_option('faster_show_sidebar')) ) {
		return 'col-sm-12';
	}
	return $def_column_num;
});

/**
 * Check if we have at least one active footer sidebar.
 *
 * @return bool
 */
function has_active_footer_sidebar() {
	for ( $i = 1; $i < 5; $i++ ) {
		if ( is_active_sidebar('footer-' . $i) ){
			return true;
		}
	}
	return false;
}

/**
 * Extend to the core function wp_dropdown_roles(), so it can accept arrays also
 *
 * @param $selected_roles string|array
 */
function faster_wp_dropdown_roles($selected_roles){
	$r = '';

	$editable_roles = array_reverse( get_editable_roles() );

	foreach ( $editable_roles as $role => $details ) {
		$name = translate_user_role($details['name'] );
		// preselect specified role
		if ( (is_array($selected_roles) && in_array($role, $selected_roles)) ||  $selected_roles == $role ) {
			$r .= "\n\t<option selected='selected' value='" . esc_attr( $role ) . "'>$name</option>";
		} else {
			$r .= "\n\t<option value='" . esc_attr( $role ) . "'>$name</option>";
		}
	}

	echo $r;
}

/**
 * Extend core function get_template_part(), so we can pass it variables.
 *
 * @param $template_names
 * @param array $args
 */
function faster_get_template_part( $template_names, $args = array() ){
	extract($args);
	include(locate_template($template_names));
}

/**
 * Simple shortcode to show last posts from category
 *
 * @param $atts - array of attributes for the shortcode
 * @return String - the posts as html string
 */
function faster_grab_posts_func( $atts ) {

	$atts = shortcode_atts( array(
		'number_posts' 	=> '10', // How much posts to show?
		'category'		=> '1', // From which category to take the posts?
	), $atts, 'grab_posts' );

	$args = array(
		'posts_per_page'	=> $atts['number_posts'],
		'cat'				=> $atts['category'],
	);

	$shortcode_posts = new WP_Query( $args );

	if( $shortcode_posts->have_posts() ) {

		ob_start();

		while( $shortcode_posts->have_posts() ): $shortcode_posts->the_post();


			echo '<article id="post-"' . get_the_ID() . '">';

			if ( has_post_thumbnail() ) {
				echo '<div class="row"><div class="col-sm-4 base-category-img">' . get_the_post_thumbnail() . '</div>';
				echo '<div class="col-sm-8">';
			}

			echo '<header class="entry-header">';
			echo '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></h2>';
			echo '</header>';

			echo '<div class="entry-content">' . get_the_excerpt() . '</div>';

			if ( has_post_thumbnail() ) {
				echo '</div></div>';
			}

			echo '</article>';

		endwhile;

		wp_reset_postdata();

		$output = ob_get_contents();

		ob_end_clean();

		return $output;

	}

	return __('No Posts Found', 'faster');
}
add_shortcode( 'grab_posts', 'faster_grab_posts_func' );

require_once get_template_directory() . '/inc/shortpixel-master/shortpixel-php-req.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Bootstrap navwalker
 */
require get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

/**
 * Meta Boxes
 */
require get_template_directory() . '/inc/meta-boxes.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

if ( file_exists(get_template_directory() . '/inc/custom-snippets.php') ) {
	require get_template_directory() . '/inc/custom-snippets.php';
}

/**
 * Load Woocommerce functions file
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Load theme auto-updater.
 */
require get_template_directory() . '/inc/faster-updater.php';