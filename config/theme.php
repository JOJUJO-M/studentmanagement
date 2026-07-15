<?php
// config/theme.php
// GLOBAL THEME FILE - Changing this updates the entire UI

$theme = [
    'primary' => '#4f46e5', // Indigo 600
    'secondary' => '#312e81', // Indigo 900
    'background' => '#f3f4f6', // Gray 100
    'sidebar' => '#0f172a', // Slate 900
    'text' => '#1e293b', // Slate 800
    'light_text' => '#ffffff',
    'accent' => '#06b6d4', // Cyan 500
    'font_family' => "'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif"
];

/**
 * Function to output CSS variables based on the theme
 */
function get_theme_css($theme)
{
    $css = ":root {\n";
    foreach ($theme as $key => $value) {
        $css .= "  --$key: $value;\n";
    }
    $css .= "}";
    return $css;
}
