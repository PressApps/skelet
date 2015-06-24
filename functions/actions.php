<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'sk_get_icons' ) ) {
  function sk_get_icons() {
    global $skelet_path;
    $jsons = glob( $skelet_path["dir"]. 'skelet/fields/icon/*.json' );

    if( ! empty( $jsons ) ) {

      foreach ( $jsons as $path ) {

        $object = sk_get_icon_fonts( $skelet_path["dir"]. 'skelet/fields/icon/'. basename( $path ) );

        if( is_object( $object ) ) {

          echo ( count( $jsons ) >= 2 ) ? '<h4 class="sk-icon-title">'. $object->name .'</h4>' : '';

          foreach ( $object->icons as $icon ) {
            echo '<a class="sk-icon-tooltip" data-icon="'. $icon .'" data-title="'. $icon .'"><span class="sk-icon sk-selector"><i class="'. $icon .'"></i></span></a>';
          }

        } else {
          echo '<h4 class="sk-icon-title">'. __( 'Error! Can not load json file.', SK_TEXTDOMAIN ) .'</h4>';
        }

      }

    }

    do_action( 'sk_add_icons' );

    die();
  }
  add_action( 'wp_ajax_sk-get-icons', 'sk_get_icons' );
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'sk_set_icons' ) ) {
  function sk_set_icons() {

    echo '<div id="sk-icon-dialog" class="sk-dialog" title="'. __( 'Add Icon', SK_TEXTDOMAIN ) .'">';
    echo '<div class="sk-dialog-header sk-text-center"><input type="text" placeholder='. __( 'Search a Icon...', SK_TEXTDOMAIN ) .'" class="sk-icon-search" /></div>';
    echo '<div class="sk-dialog-load"><div class="sk-icon-loading">'. __( 'Loading...', SK_TEXTDOMAIN ) .'</div></div>';
    echo '</div>';

  }
  add_action( 'admin_footer', 'sk_set_icons' );
  add_action( 'customize_controls_print_footer_scripts', 'sk_set_icons' );
}
