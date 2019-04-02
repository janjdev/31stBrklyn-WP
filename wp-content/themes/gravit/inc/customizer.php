<?php
/**
 * gravit Theme Customizer
 *
 * @package gravit
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function gravit_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/* new section */
	$wp_customize->add_section( 'gravit_footer' , array(
    'title'      => __( 'Footer', 'gravit' ),
    'priority'   => 999,
	) );

	/* footer text */
	$wp_customize->add_setting( 'footer_text', array(
      'default' => 'Gravit Theme powered by WordPress',
      'sanitize_callback' => 'sanitize_text_field'

    ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'footer_text', array(
	'label'      => __( 'Footer text', 'gravit' ),
	'section'    => 'gravit_footer',
	) ) );

	/* Colors extended */
	$colors = array();

	$colors[] = array(
		'slug'=>'title_text_color', 
		'default' => '#4B4A47',
		'label' => __('Site Title Text Color', 'gravit')
		);

	$colors[] = array(
		'slug'=>'footer_text_color', 
		'default' => '#808080',
		'label' => __('Footer Text Color', 'gravit')
		);

	$colors[] = array(
		'slug'=>'footer_link_color', 
		'default' => '#c2c2c2',
		'label' => __('Footer Link Color', 'gravit')
	);

	$colors[] = array(
		'slug'=>'icon_color', 
		'default' => '#EF3636',
		'label' => __('Icon Color', 'gravit')
	);

	$colors[] = array(
		'slug'=>'about_page_color', 
		'default' => '#FFFFFF',
		'label' => __('Aboute Me Page Background Color', 'gravit')
	);
	
	foreach( $colors as $color ) {
		// SETTINGS
		$wp_customize->add_setting(
			$color['slug'], array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 
				'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color'
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'], 
				array('label' => $color['label'], 
				'section' => 'colors',
				'settings' => $color['slug'])
			)
		);
	}



	$wp_customize->add_section( 'global_options', array(
    'title'          => 'Global Options',
    'priority'       => 20,
	) );

	$wp_customize->add_setting( 'show_icons', array(
	'default'        => false,
	'transport'  =>  'postMessage',
	'sanitize_callback' => 'gravit_sanitize_checkbox'
	) );

	$wp_customize->add_control(
	'show_icons',
	array(
	    'section'   => 'global_options',
	    'label'     => 'Show Post Icons?',
	    'type'      => 'checkbox'
	     )
	 );

	$wp_customize->add_setting( 'remove_space', array(
	'default'        => false,
	'transport'  =>  'postMessage',
	'sanitize_callback' => 'gravit_sanitize_checkbox'
	) );

	$wp_customize->add_control(
	'remove_space',
	array(
	    'section'   => 'global_options',
	    'label'     => 'Remove Space beneath header?',
	    'type'      => 'checkbox'
	     )
	 );
}
add_action( 'customize_register', 'gravit_customize_register' );

function gravit_sanitize_checkbox($input) {
	
	if ( $input ) {		
		$output = '1';
	} else {
		$output = false;
	}
	return $output;
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function gravit_customize_preview_js() {
	wp_enqueue_script( 'gravit_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'gravit_customize_preview_js' );

function gravit_sanitize_input( $input ) {
	return absint( $input );
}