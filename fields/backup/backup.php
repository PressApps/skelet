<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Option_backup")){
  class SkeletFramework_Option_backup extends SkeletFramework_Options {

    public function __construct( $field, $value = '', $unique = '' ) {
      parent::__construct( $field, $value, $unique );
    }

    public function output() {

      echo $this->element_before();

      echo '<textarea name="'. $this->unique .'[import]"'. $this->element_class() . $this->element_attributes() .'></textarea>';
      submit_button( __( 'Import a Backup', SK_TEXTDOMAIN ), 'primary sk-import-backup', 'backup', false );
      echo '<small>( '. __( 'copy-paste your backup string here', SK_TEXTDOMAIN ).' )</small>';

      echo '<hr />';

      echo '<textarea name="_nonce"'. $this->element_class() . $this->element_attributes() .' disabled="disabled">'. sk_encode_string( get_option( $this->unique ) ) .'</textarea>';
      echo '<a href="'. admin_url( 'admin-ajax.php?action=sk-export-options' ) .'" class="button button-primary" target="_blank">'. __( 'Export and Download Backup', SK_TEXTDOMAIN ) .'</a>';
      echo '<small>-( '. __( 'or', SK_TEXTDOMAIN ) .' )-</small>';
      submit_button( __( '!!! Reset All Options !!!', SK_TEXTDOMAIN ), 'sk-warning-primary sk-reset-confirm', $this->unique . '[resetall]', false );
      echo '<small class="sk-text-warning">'. __( 'Please be sure for reset all of framework options.', SK_TEXTDOMAIN ) .'</small>';
      echo $this->element_after();

    }

  }
}
