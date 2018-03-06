<?php
/**
 * faster Theme Customizer
 *
 * @package faster
 */

/**
 * Extend customizer panel.
 *
 * @see https://codex.wordpress.org/Theme_Customization_API
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function faster_customize_register( $wp_customize ) {

	// Theme Options section
	$wp_customize->add_section('faster_theme_options', array(
		'title' => __('Theme Options', 'faster'),
		'priority' => 150,
	));
	/*--------------------------
	Show sidebar
	---------------------------*/
	$wp_customize->add_setting('faster_show_sidebar', array(
		'type' => 'option',
		'default' => 1,
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage'
	));
	$args = array(
		'label' => __('Display Sidebar', 'faster'),
		'section'       => 'faster_theme_options',
		'settings' => 'faster_show_sidebar',
		'priority' => 1,
		'type' => 'checkbox'
	);
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'faster_show_sidebar', $args));

	/*--------------------------
	Show menu search form
	---------------------------*/
	$wp_customize->add_setting('faster_show_nav_search', array(
		'type' => 'option',
		'default' => 1,
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage'
	));
	$args = array(
		'label' => __('Display Main Menu Search Form', 'faster'),
		'section'       => 'faster_theme_options',
		'settings' => 'faster_show_nav_search',
		'priority' => 2,
		'type' => 'checkbox'
	);
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'faster_show_nav_search', $args));

	/*--------------------------
	Footer content
	---------------------------*/
	$wp_customize->add_setting('faster_footer_content', array(
		'default' => __('Theme Faster by <a href="https://planwize.com/">Planwize</a>', 'faster'),
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage'
	));
	$args = array(
		'label' => __('Footer Content', 'faster'),
		'section'       => 'faster_theme_options',
		'settings' => 'faster_footer_content',
		'priority' => 3,
		'type' => 'textarea'
	);
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'faster_footer_content', $args));

	/*--------------------------
	Footer background color
	---------------------------*/
	$wp_customize->add_setting('faster_footer_bg_color', array(
		'capability'            => 'edit_theme_options',
		'default'               => get_theme_support( 'custom-background', 'default-color' ),
		'transport'             => 'postMessage',
		'sanitize_callback'     => 'sanitize_hex_color_no_hash',
		'sanitize_js_callback'  => 'maybe_hash_hex_color',
	));
	$args = array(
		'label' => __('Footer Background Color', 'faster'),
		'section'       => 'faster_theme_options',
		'settings' => 'faster_footer_bg_color',
		'priority' => 4,
	);
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'faster_footer_bg_color', $args));

	/*--------------------------
	Custom code
	---------------------------*/
	$wp_customize->add_setting('faster_php_code_editor', array(
		'default'   => '',
		'transport' => 'postMessage',
	));
	$args = array(
		'label' => __('Code Editor', 'faster'),
		'section'       => 'faster_theme_options',
		'settings' => 'faster_php_code_editor',
		'code_type'   => 'php',
		'description' => __('The code here will be injected to functions.php file. Make sure you know what you are doing before making any changes.'),
		'priority' => 5
	);
	$wp_customize->add_control(new WP_Customize_Code_Editor_Control($wp_customize, 'faster_php_code_editor', $args));
}
add_action( 'customize_register', 'faster_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function faster_customize_preview_js() {
	wp_enqueue_script('faster-customizer',
		get_template_directory_uri() . '/js/customizer.js',
		array( 'customize-preview' ),
		'20151215',
		true
	);
}
add_action('customize_preview_init', 'faster_customize_preview_js');

/**
 * Validate PHP syntax and if it's valid copy the code to custom-snippets.php file
 *
 * @param $validity WP_Error
 * @param $value
 *
 * @return bool|WP_Error
 */
function faster_validate_code($validity, $value){
	$file_path = get_template_directory() . '/inc/custom-snippets.php';

	$code = '<?php ' . $value;
	file_put_contents($file_path, $code);
	exec('php -l ' . $file_path, $output, $result);

	if ( $result == 0 ) { // code is valid
		return $validity;
	}

	// if we got here there is a php syntax error
	file_put_contents($file_path, '');
	$error_msg = __('There is a PHP syntax error, please recheck your code.', 'faster');
	$validity->add('syntax_error', $error_msg);

	return $validity;

}
add_filter('customize_validate_faster_php_code_editor', 'faster_validate_code', 10, 2);