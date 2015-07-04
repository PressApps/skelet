<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Include
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Option_include")){
	  
	class SkeletFramework_Option_include extends SkeletFramework_Options {

	  public function __construct( $field, $value = '', $unique = '' ) {
	    parent::__construct( $field, $value, $unique );
	  }

	  public function output() {

	    echo $this->element_before();
	    include_once $this->field['content'];
	    echo $this->element_after();

	  }

	}
}
