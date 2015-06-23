<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Add framework element
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! function_exists( 'sk_add_element' ) ) {
  function sk_add_element( $field = array(), $value = '', $unique = '' ) {
    global $skelet_path;

    $output     = '';
    $depend     = '';
    $sub        = ( isset( $field['sub'] ) ) ? 'sub-': '';
    $unique     = ( isset( $unique ) ) ? $unique : '';
    $languages  = sk_language_defaults();
    $class      = 'SkeletFramework_Option_' . $field['type'];
    $wrap_class = ( isset( $field['wrap_class'] ) ) ? ' ' . $field['wrap_class'] : '';
    $hidden     = ( isset( $field['show_only_language'] ) && ( $field['show_only_language'] != $languages['current'] ) ) ? ' hidden' : '';
    $is_pseudo  = ( isset( $field['pseudo'] ) ) ? ' sk-pseudo-field' : '';
   
    if ( isset( $field['dependency'] ) ) {
      $hidden  = ' hidden';
      $depend .= ' data-'. $sub .'controller="'. $field['dependency'][0] .'"';
      $depend .= ' data-'. $sub .'condition="'. $field['dependency'][1] .'"';
      $depend .= " data-". $sub ."value='". $field['dependency'][2] ."'";
    }

    $output .= '<div class="sk-element sk-field-'. $field['type'] . $is_pseudo . $wrap_class . $hidden .'"'. $depend .'>';

    if( isset( $field['title'] ) ) {
      $field_desc = ( isset( $field['desc'] ) ) ? '<p class="sk-text-desc">'. $field['desc'] .'</p>' : '';
      $output .= '<div class="sk-title"><h4>' . $field['title'] . '</h4>'. $field_desc .'</div>';
    }

    $output .= ( isset( $field['title'] ) ) ? '<div class="sk-fieldset">' : '';

    $value   = ( !isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
    $value   = ( isset( $field['value'] ) ) ? $field['value'] : $value;

    if( class_exists( $class ) ) {
      ob_start();
      $element = new $class( $field, $value, $unique );
      $element->output();
      $output .= ob_get_clean();
    } else {
      $output .= '<p>'. __( 'This field class is not available!', SK_TEXTDOMAIN ) .'</p>';
    }

    $output .= ( isset( $field['title'] ) ) ? '</div>' : '';
    $output .= '<div class="clear"></div>';
    $output .= '</div>';

    return $output;

  }
}

/**
 *
 * Encode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_encode_string' ) ) {
  function sk_encode_string( $string ) {
    return rtrim( strtr( call_user_func( 'base'. '64' .'_encode', addslashes( gzcompress( serialize( $string ), 9 ) ) ), '+/', '-_' ), '=' );
  }
}

/**
 *
 * Decode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_decode_string' ) ) {
  function sk_decode_string( $string ) {
    return unserialize( gzuncompress( stripslashes( call_user_func( 'base'. '64' .'_decode', rtrim( strtr( $string, '-_', '+/' ), '=' ) ) ) ) );
  }
}

/**
 *
 * Get google font from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_get_google_fonts' ) ) {
  function sk_get_google_fonts() {

    global $sk_google_fonts;

    if( ! empty( $sk_google_fonts ) ) {

      return $sk_google_fonts;

    } else {

      ob_start();
      sk_locate_template( 'fields/typography/google-fonts.json' );
      $json = ob_get_clean();

      $sk_google_fonts = json_decode( $json );

      return $sk_google_fonts;
    }

  }
}

/**
 *
 * Get icon fonts from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_get_icon_fonts' ) ) {
  function sk_get_icon_fonts( $file ) {

    ob_start();
    sk_locate_template( $file );
    $json = ob_get_clean();

    return json_decode( $json );

  }
}

/**
 *
 * Array search key & value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_array_search' ) ) {
  function sk_array_search( $array, $key, $value ) {

    $results = array();

    if ( is_array( $array ) ) {
      if ( isset( $array[$key] ) && $array[$key] == $value ) {
        $results[] = $array;
      }

      foreach ( $array as $sub_array ) {
        $results = array_merge( $results, sk_array_search( $sub_array, $key, $value ) );
      }

    }

    return $results;

  }
}

/**
 *
 * Load options fields
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_load_option_fields' ) ) {
  function sk_load_option_fields() {
    global $skelet_path;
   
    $located_fields = array();
    foreach ( glob( $skelet_path["dir"] .'skelet/fields/*/*.php' ) as $sk_field ) {
      $located_fields[] = basename( $sk_field );
      sk_locate_template( str_replace(  $skelet_path["dir"].'skelet/', '', $sk_field ) ,$skelet_path);
    }

    $override_name = apply_filters( 'sk_framework_override', 'sk-framework-override' );
    $override_dir  = get_template_directory() .'/skelet/'. $override_name .'/fields';
    if( is_dir( $override_dir ) ) {

      foreach ( glob( $override_dir .'/*/*.php' ) as $override_field ) {

        if( ! in_array( basename( $override_field ), $located_fields ) ) {

          sk_locate_template( str_replace(  $skelet_path["dir"] .'-override', '', $override_field ) );

        }

      }

    }

    do_action( 'sk_load_option_fields' );

  }
}
