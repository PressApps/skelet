<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Group
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Option_group")){

  class SkeletFramework_Option_group extends SkeletFramework_Options {

    public function __construct( $field, $value = '', $unique = '' ) {
      parent::__construct( $field, $value, $unique );
    }

    public function output() {

      echo $this->element_before();

      $last_id     = ( is_array( $this->value ) ) ? count( $this->value ) : 0;
      $acc_title   = ( isset( $this->field['accordion_title'] ) ) ? $this->field['accordion_title'] : __( 'Adding', SK_TEXTDOMAIN );
      $field_title = ( isset( $this->field['fields'][0]['title'] ) ) ? $this->field['fields'][0]['title'] : $this->field['fields'][1]['title'];
      $field_id    = ( isset( $this->field['fields'][0]['id'] ) ) ? $this->field['fields'][0]['id'] : $this->field['fields'][1]['id'];
      $search_id   = sk_array_search( $this->field['fields'], 'id', $acc_title );

      if( ! empty( $search_id ) ) {

        $acc_title = ( isset( $search_id[0]['title'] ) ) ? $search_id[0]['title'] : $acc_title;
        $field_id  = ( isset( $search_id[0]['id'] ) ) ? $search_id[0]['id'] : $field_id;

      }

      echo '<div class="sk-group hidden">';

        echo '<h4 class="sk-group-title">'. $acc_title .'</h4>';
        echo '<div class="sk-group-content">';
        foreach ( $this->field['fields'] as $field_key => $field ) {
          $field['sub']   = true;
          $unique         = $this->unique .'[_nonce]['. $this->field['id'] .']['. $last_id .']';
          $field_default  = ( isset( $field['default'] ) ) ? $field['default'] : '';
          echo sk_add_element( $field, $field_default, $unique );
        }
        echo '<div class="sk-element sk-text-right"><a href="#" class="button sk-warning-primary sk-remove-group">'. __( 'Remove', SK_TEXTDOMAIN ) .'</a></div>';
        echo '</div>';

      echo '</div>';

      echo '<div class="sk-groups sk-accordion">';

        if( ! empty( $this->value ) ) {

          foreach ( $this->value as $key => $value ) {

            $title = ( isset( $this->value[$key][$field_id] ) ) ? $this->value[$key][$field_id] : '';

            if ( is_array( $title ) && isset( $this->multilang ) ) {
              $lang  = sk_language_defaults();
              $title = $title[$lang['current']];
              $title = is_array( $title ) ? $title[0] : $title;
            }

            $field_title = ( ! empty( $search_id ) ) ? $acc_title : $field_title;

            echo '<div class="sk-group">';
            echo '<h4 class="sk-group-title">'. $field_title .': '. $title .'</h4>';
            echo '<div class="sk-group-content">';

            foreach ( $this->field['fields'] as $field_key => $field ) {
              $field['sub'] = true;
              $unique = $this->unique . '[' . $this->field['id'] . ']['.$key.']';
              $value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';
              echo sk_add_element( $field, $value, $unique );
            }

            echo '<div class="sk-element sk-text-right"><a href="#" class="button sk-warning-primary sk-remove-group">'. __( 'Remove', SK_TEXTDOMAIN ) .'</a></div>';
            echo '</div>';
            echo '</div>';

          }

        }

      echo '</div>';

      echo '<a href="#" class="button button-primary sk-add-group">'. $this->field['button_title'] .'</a>';

      echo $this->element_after();

    }

  }
}