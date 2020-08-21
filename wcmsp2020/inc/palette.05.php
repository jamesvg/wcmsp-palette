<?php
/**
 * /inc/palette.05.php -- Extend the array to TWELVE palette colors.
 */

function wcmsp2020_get_palette()
{
    return array(
        'palette-text' => array(
            'name' => 'Text',
            'color' => '#193244',
        ),
        'palette-text-alt' => array(
            'name' => 'Text Alt',
            'color' => '#545A5E',
        ),
        'palette-bg' => array(
            'name' => 'Background',
            'color' => '#FFFFFF',
        ),
        'palette-bg-alt' => array(
            'name' => 'Background Alt',
            'color' => '#EEEEEE',
        ),
        'palette-interactive' => array(
            'name' => 'Interactive',
            'color' => '#22A6B7',
        ),
        'palette-attention' => array(
            'name' => 'Attention',
            'color' => '#F47B4B',
        ),
        'palette-brand-1' => array(
            'name' => 'Brand 1',
            'color' => '#1F3E54',
        ),
        'palette-brand-2' => array(
            'name' => 'Brand 2',
            'color' => '#F47B4B',
        ),
        'palette-brand-3' => array(
            'name' => 'Brand 3',
            'color' => '#0F1F2A',
        ),
        'palette-brand-4' => array(
            'name' => 'Brand 4',
            'color' => '#963109',
        ),
        'palette-dark' => array(
            'name' => 'Dark',
            'color' => '#222222',
        ),
        'palette-light' => array(
            'name' => 'Light',
            'color' => '#F1F1F1',
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
        $css_vars[] = '--' . $key . '-darker:' . color_luminance($color, -.33) . ';';
        $css_vars[] = '--' . $key . '-lighter:' . color_luminance($color, .33) . ';';
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



/**
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
 * @link https://gist.github.com/stephenharris/5532899
 * @param str $hex Colour as hexadecimal (with or without hash);
 * @param float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
 * @return str Lightened/Darkend colour as hexadecimal (with hash);
 */
function color_luminance($hex, $percent)
{
    $hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
    $new_hex = '#';
    if ( strlen( $hex ) < 6 ) {
        $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
    }
    for ($i = 0; $i < 3; $i++) {
        $dec = hexdec( substr( $hex, $i*2, 2 ) );
        $dec = min( max( 0, $dec + $dec * $percent ), 255 );
        $new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
    }
    return $new_hex;
}



function wcmsp_acf_color_palette() {
    $palette = wcmsp2020_get_palette();
    foreach ($palette as $key => $item) {
        $color = get_theme_mod($key, $item['color']);
        $acf_color_palette[] = $color;
    }
    echo '
        <script type="text/javascript">
        (function($) {
            acf.add_filter("color_picker_args", function( args, $field ) {
                args.palettes = ' . json_encode($acf_color_palette) . ';
                return args;
            });
        })(jQuery);
        </script>
    ';
}
add_action( 'acf/input/admin_footer', 'wcmsp_acf_color_palette' );
