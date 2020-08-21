<?php
/**
 * /inc/palette.03.php -- Include our ONE color in the block editor palette.
 */

function wcmsp2020_customize_color_controls( $wp_customize ) {
    $wp_customize->add_setting( 'palette-text', array(
        'default' => '#193244',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control( $wp_customize, 'palette-text', array(
            'label' => 'Text',
            'section' => 'colors',
            'settings' => 'palette-text',
        ))
    );
}
add_action( 'customize_register', 'wcmsp2020_customize_color_controls' );



function get_wcmsp2020_palette_css() {
    $color = get_theme_mod( 'palette-text', '#193244' );
    $css = ':root{--palette-text:' . $color . ';}';
    $css .= '.has-background.has-palette-text-background-color{background-color:' . $color . ';}';
    $css .= '.has-text-color.has-palette-text-color{color:' . $color . ';}';
    return $css;
}



function wcmsp2020_inline_style_palette() {
    wp_add_inline_style( 'wcmsp2020-style', get_wcmsp2020_palette_css() );
}
add_action( 'wp_enqueue_scripts', 'wcmsp2020_inline_style_palette', PHP_INT_MAX );



function wcmsp2020_custom_admin_head() {
    echo '<style>' . get_wcmsp2020_palette_css() . '</style>';
}
add_action( 'admin_head', 'wcmsp2020_custom_admin_head' );



function wcmsp2020_gutenberg_css() {
    add_theme_support( 'editor-styles' );
    add_editor_style( 'style-editor.css' );
}
add_action( 'after_setup_theme', 'wcmsp2020_gutenberg_css' );



function wcmsp2020_editor_color_palette() {
    $color = get_theme_mod( 'palette-text', '#193244' );
    $editor_color_palette[] = array(
        'name' => 'Text',
        'slug' => 'palette-text',
        'color' => $color,
    );
    add_theme_support( 'editor-color-palette', $editor_color_palette );
}
add_action( 'after_setup_theme', 'wcmsp2020_editor_color_palette' );
