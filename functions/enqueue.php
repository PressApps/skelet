<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Framework admin enqueue style and scripts
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'sk_admin_enqueue_scripts' ) ) {
  function sk_admin_enqueue_scripts() {
    global $skelet_path;
    
    // admin utilities
    wp_enqueue_media();

    // wp core styles
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'wp-jquery-ui-dialog' );

    // framework core styles
    wp_enqueue_style( 'sk-framework', $skelet_path["uri"] .'/assets/css/sk-framework.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'sk-icons', $skelet_path["uri"] .'/assets/css/sk-icons.css', array(), '1.0.0', 'all' );

    if ( is_rtl() ) {
      wp_enqueue_style( 'sk-framework-rtl', $skelet_path["uri"] .'/assets/css/sk-framework-rtl.css', array(), '1.0.0', 'all' );
    }

    // wp core scripts
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'jquery-ui-dialog' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-accordion' );

    // framework core scripts
    wp_enqueue_script( 'sk-plugins',    $skelet_path["uri"] .'/assets/js/sk-plugins.js',    array(), '1.0.0', true );
    wp_enqueue_script( 'sk-framework',  $skelet_path["uri"] .'/assets/js/sk-framework.js',  array( 'sk-plugins' ), '1.0.0', true );

  }
  add_action( 'admin_enqueue_scripts', 'sk_admin_enqueue_scripts' );
}

