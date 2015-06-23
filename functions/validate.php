<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Email validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'sk_validate_email' ) ) {
  function sk_validate_email( $value, $field ) {

    if ( ! sanitize_email( $value ) ) {
      return __( 'Please write a valid email address!', SK_TEXTDOMAIN );
    }

  }
  add_filter( 'sk_validate_email', 'sk_validate_email', 10, 2 );
}

/**
 *
 * Numeric validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'sk_validate_numeric' ) ) {
  function sk_validate_numeric( $value, $field ) {

    if ( ! is_numeric( $value ) ) {
      return __( 'Please write a numeric data!', SK_TEXTDOMAIN );
    }

  }
  add_filter( 'sk_validate_numeric', 'sk_validate_numeric', 10, 2 );
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'sk_validate_required' ) ) {
  function sk_validate_required( $value ) {
    if ( empty( $value ) ) {
      return __( 'Fatal Error! This field is required!', SK_TEXTDOMAIN );
    }
  }
  add_filter( 'sk_validate_required', 'sk_validate_required' );
}
