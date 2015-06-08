<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * WP Customize custom controls
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(class_exists("WP_Customize_Control") && !class_exists("WP_Customize_sk_field_Control")){
	class WP_Customize_sk_field_Control extends WP_Customize_Control {

	  public $unique  = '';
	  public $type    = 'sk_field';
	  public $options = array();

	  public function render_content() {

	    $this->options['id'] = $this->id;
	    $this->options['default'] = $this->setting->default;
	    $this->options['attributes']['data-customize-setting-link'] = $this->settings['default']->id;
	    echo sk_add_element( $this->options, $this->value(), $this->unique );

	  }

	}
}
