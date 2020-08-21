<?php
/**
 * /inc/palette.04.php -- Iterate using an array; add ANOTHER color.
 */

function wcmsp2020_get_palette()
{
    return array(
        'palette-text' => array(
            'name' => 'Text',
            'color' => '#193244',
        ),
        'palette-bg' => array(
            'name' => 'Background',
            'color' => '#FFFFFF',
        ),
    );
}



function wcmsp2020_customize_color_controls( $wp_customize ) {
    $palette = wcmsp2020_get_palette();
    foreach ($palette as $key => $item) {
        $wp_customize->add_setting($key, array(
            'default' => $item['color'],
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control($wp_customize, $key, array(
                'label' => $item['name'],
                'section' => 'colors',
                'settings' => $key,
            ))
        );
    }
}
add_action( 'customize_register', 'wcmsp2020_customize_color_controls' );



function get_wcmsp2020_palette_css() {
    $palette = wcmsp2020_get_palette();
    foreach ($palette as $key => $item) {
        $color = get_theme_mod($key, $item['color']);
        $css_vars[] = '--' . $key . ':' . $color . ';';
        $css_style[] = '.has-background.has-' . $key . '-background-color{background-color:' . $color . ';}';
        $css_style[] = '.has-text-color.has-' . $key . '-color{color:' . $color . ';}';
    }
    $root = !empty($css_vars) ? ':root{' . implode(' ', $css_vars) . ' }' : '';
    $style = !empty($css_style) ? implode(' ', $css_style) : '';
    return $root . "\n" . $style;
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
    $palette = wcmsp2020_get_palette();
    foreach ($palette as $key => $item) {
        $color = get_theme_mod($key, $item['color']);
        $editor_color_palette[] = array(
            'name' => $item['name'],
            'slug' => $key,
            'color' => $color,
        );
    }
    add_theme_support('editor-color-palette', $editor_color_palette);
}
add_action( 'after_setup_theme', 'wcmsp2020_editor_color_palette' );
