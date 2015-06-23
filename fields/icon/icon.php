<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Option_icon")){

  class SkeletFramework_Option_icon extends SkeletFramework_Options {

    public function __construct( $field, $value = '', $unique = '' ) {
      parent::__construct( $field, $value, $unique );
    }

    public function output() {

      echo $this->element_before();

      $value  = $this->element_value();
      $hidden = ( empty( $value ) ) ? ' hidden' : '';

      echo '<div class="sk-icon-select">';
      echo '<span class="sk-icon-preview'. $hidden .'"><i class="'. $value .'"></i></span>';
      echo '<a href="#" class="button button-primary sk-icon-add">'. __( 'Add Icon', SK_TEXTDOMAIN ) .'</a>';
      echo '<a href="#" class="button sk-warning-primary sk-icon-remove'. $hidden .'">'. __( 'Remove Icon', SK_TEXTDOMAIN ) .'</a>';
      echo '<input type="text" name="'. $this->element_name() .'" value="'. $value .'"'. $this->element_class( 'sk-icon-value' ) . $this->element_attributes() .' />';
      echo '</div>';

      echo $this->element_after();

    }

  }
}
