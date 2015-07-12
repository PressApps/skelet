<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Checkbox
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Option_button")){
  class SkeletFramework_Option_button extends SkeletFramework_Options {

    public function __construct( $field, $value = '', $unique = '' ) {
      parent::__construct( $field, $value, $unique );
    }

    public function output() {
      echo $this->element_before();
      echo '<input class="button button-primary" type="'. $this->element_type() .'" id="'. $this->field['id'] .'" value="'. $this->field['button_title'] .'"'. $this->element_class() . $this->element_attributes() .'/>';
      echo $this->element_after();

    }

  }
}