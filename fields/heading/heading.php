<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Heading
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Option_heading")){

	class SkeletFramework_Option_heading extends SkeletFramework_Options {

	  public function __construct( $field, $value = '', $unique = '' ) {
	    parent::__construct( $field, $value, $unique );
	  }

	  public function output() {

	    echo $this->element_before();
	    echo $this->field['content'];
	    echo $this->element_after();

	  }

	}
}