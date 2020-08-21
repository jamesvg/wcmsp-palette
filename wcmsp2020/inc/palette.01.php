<?php
/**
 * /inc/palette.01.php -- Add ONE color to the theme.
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
    return $css;
}



function wcmsp2020_inline_style_palette() {
    wp_add_inline_style( 'wcmsp2020-style', get_wcmsp2020_palette_css() );
}
add_action( 'wp_enqueue_scripts', 'wcmsp2020_inline_style_palette', PHP_INT_MAX );
