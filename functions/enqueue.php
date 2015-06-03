<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Framework admin enqueue style and scripts
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cs_admin_enqueue_scripts' ) ) {
  function cs_admin_enqueue_scripts() {
    global $skelet_path;
    
    // admin utilities
    wp_enqueue_media();

    // wp core styles
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'wp-jquery-ui-dialog' );

    // framework core styles
    wp_enqueue_style( 'cs-framework', $skelet_path["uri"] .'/assets/css/cs-framework.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'font-awesome', $skelet_path["uri"] .'/assets/css/font-awesome.css', array(), '4.2.0', 'all' );

    if ( is_rtl() ) {
      wp_enqueue_style( 'cs-framework-rtl', $skelet_path["uri"] .'/assets/css/cs-framework-rtl.css', array(), '1.0.0', 'all' );
    }

    // wp core scripts
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'jquery-ui-dialog' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-accordion' );

    // framework core scripts
    wp_enqueue_script( 'cs-plugins',    $skelet_path["uri"] .'/assets/js/cs-plugins.js',    array(), '1.0.0', true );
    wp_enqueue_script( 'cs-framework',  $skelet_path["uri"] .'/assets/js/cs-framework.js',  array( 'cs-plugins' ), '1.0.0', true );

  }
  add_action( 'admin_enqueue_scripts', 'cs_admin_enqueue_scripts' );
}
