<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Content
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Option_content")){
	  
	class SkeletFramework_Option_content extends SkeletFramework_Options {

	  public function __construct( $field, $value = '', $unique = '' ) {
	    parent::__construct( $field, $value, $unique );
	  }

	  public function output() {
	  	
	  	// include partial template
	  	$options    = isset($this->field["options"])?$this->field["options"]:$this->field["content"];
	  	$options    = ( is_array( $options ) ) ? $options :  $this->element_data( $options );
        echo $this->element_before();
	    echo $this->field['content'];
	    echo $this->element_after();

	  }

	}
}
