<?php

function custom_get_template_part( $slug, $name = null ) {
    $template = '';

    // Look in the active theme's directory first
    if ( $name ) {
        $template = locate_template( array( "{$slug}-{$name}.php", "{$slug}.php" ) );
    }
    
    // If not found in the theme, look in the plugin's template folder
    if ( ! $template ) {
        $template = plugin_dir_path( __FILE__ ) . "templates/{$slug}-{$name}.php";
    }

    if ( $template ) {
        load_template( $template, false );
    }
}