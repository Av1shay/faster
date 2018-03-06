<?php
/**
 * faster Theme Customizer
 *
 * @package faster
 */

/**
 * Extend customizer's ability.
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

	// SETTINGS
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
	Footer
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
