<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Sorter
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Option_Sorter")){

  class SkeletFramework_Option_Sorter extends SkeletFramework_Options {

    public function __construct( $field, $value = '', $unique = '' ) {
      parent::__construct( $field, $value, $unique );
    }

    public function output(){

      echo $this->element_before();

      $value          = $this->element_value();
      $value          = ( ! empty( $value ) ) ? $value : $this->field['default'];
      $enabled        = ( ! empty( $value['enabled'] ) ) ? $value['enabled'] : array();
      $disabled       = ( ! empty( $value['disabled'] ) ) ? $value['disabled'] : array();
      $enabled_title  = ( isset( $this->field['enabled_title'] ) ) ? $this->field['enabled_title'] : __( 'Enabled Modules', SK_TEXTDOMAIN );
      $disabled_title = ( isset( $this->field['disabled_title'] ) ) ? $this->field['disabled_title'] : __( 'Disabled Modules', SK_TEXTDOMAIN );

      echo '<div class="sk-modules">';
      echo '<h3>'. $enabled_title .'</h3>';
      echo '<ul class="sk-enabled">';
      if( ! empty( $enabled ) ) {
        foreach( $enabled as $en_id => $en_name ) {
          echo '<li><input type="hidden" name="'. $this->element_name( '[enabled]['. $en_id .']' ) .'" value="'. $en_name .'"/><label>'. $en_name .'</label></li>';
        }
      }
      echo '</ul>';
      echo '</div>';

      echo '<div class="sk-modules">';
      echo '<h3>'. $disabled_title .'</h3>';
      echo '<ul class="sk-disabled">';
      if( ! empty( $disabled ) ) {
        foreach( $disabled as $dis_id => $dis_name ) {
          echo '<li><input type="hidden" name="'. $this->element_name( '[disabled]['. $dis_id .']' ) .'" value="'. $dis_name .'"/><label>'. $dis_name .'</label></li>';
        }
      }
      echo '</ul>';
      echo '</div>';
      echo '<div class="clear"></div>';

      echo $this->element_after();

    }

  }
}
