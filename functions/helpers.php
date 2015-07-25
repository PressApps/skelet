<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Add framework element
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! function_exists( 'sk_add_element' ) ) {
  function sk_add_element( $field = array(), $value = '', $unique = '' ) {
    global $skelet_path;

    $output     = '';
    $depend     = '';
    $sub        = ( isset( $field['sub'] ) ) ? 'sub-': '';
    $unique     = ( isset( $unique ) ) ? $unique : '';
    $languages  = sk_language_defaults();
    $class      = isset( $field['type']) ?'SkeletFramework_Option_' . $field['type']:'';
    $wrap_class = ( isset( $field['wrap_class'] ) ) ? ' ' . $field['wrap_class'] : '';
    $hidden     = ( isset( $field['show_only_language'] ) && ( $field['show_only_language'] != $languages['current'] ) ) ? ' hidden' : '';
    $is_pseudo  = ( isset( $field['pseudo'] ) ) ? ' sk-pseudo-field' : '';
   
    if ( isset( $field['dependency'] ) ) {
      $hidden  = ' hidden';
      $depend .= ' data-'. $sub .'controller="'. $field['dependency'][0] .'"';
      $depend .= ' data-'. $sub .'condition="'. $field['dependency'][1] .'"';
      $depend .= " data-". $sub ."value='". $field['dependency'][2] ."'";
    }

    $output .= '<div class="sk-element sk-field-'. (isset($field['type'])?$field['type']:'') . $is_pseudo . $wrap_class . $hidden .'"'. $depend .'>';

    if( isset( $field['title'] ) ) {
      $field_desc = ( isset( $field['desc'] ) ) ? '<p class="sk-text-desc">'. $field['desc'] .'</p>' : '';
      $output .= '<div class="sk-title"><h4>' . $field['title'] . '</h4>'. $field_desc .'</div>';
    }

    $output .= ( isset( $field['title'] ) ) ? '<div class="sk-fieldset">' : '';

    $value   = ( !isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
    $value   = ( isset( $field['value'] ) ) ? $field['value'] : $value;

    if( class_exists( $class ) ) {
      ob_start();
      $element = new $class( $field, $value, $unique );
      $element->output();
      $output .= ob_get_clean();
    } else {
      $output .= '<p>'. __( 'This field class is not available!', SK_TEXTDOMAIN ) .'</p>';
    }

    $output .= ( isset( $field['title'] ) ) ? '</div>' : '';
    $output .= '<div class="clear"></div>';
    $output .= '</div>';

    return $output;

  }
}

/**
 *
 * Encode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_encode_string' ) ) {
  function sk_encode_string( $string ) {
    return rtrim( strtr( call_user_func( 'base'. '64' .'_encode', addslashes( gzcompress( serialize( $string ), 9 ) ) ), '+/', '-_' ), '=' );
  }
}

/**
 *
 * Decode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_decode_string' ) ) {
  function sk_decode_string( $string ) {
    return unserialize( gzuncompress( stripslashes( call_user_func( 'base'. '64' .'_decode', rtrim( strtr( $string, '-_', '+/' ), '=' ) ) ) ) );
  }
}

/**
 *
 * Get google font from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_get_google_fonts' ) ) {
  function sk_get_google_fonts() {

    global $sk_google_fonts, $skelet_path;

    if( ! empty( $sk_google_fonts ) ) {

      return $sk_google_fonts;

    } else {

      ob_start();
      sk_locate_template( str_replace(  $skelet_path["dir"].'skelet/', '', 'fields/typography/google-fonts.json') ,$skelet_path);
      $json = ob_get_clean();

      $sk_google_fonts = json_decode( $json );

      return $sk_google_fonts;
    }

  }
}

/**
 *
 * Get icon fonts from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_get_icon_fonts' ) ) {
  function sk_get_icon_fonts( $file ) {

    //ob_start();
    //sk_locate_template( $file );
    //$json = ob_get_clean();

    return json_decode( file_get_contents($file) );

  }
}

/**
 *
 * Array search key & value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_array_search' ) ) {
  function sk_array_search( $array, $key, $value ) {

    $results = array();

    if ( is_array( $array ) ) {
      if ( isset( $array[$key] ) && $array[$key] == $value ) {
        $results[] = $array;
      }

      foreach ( $array as $sub_array ) {
        $results = array_merge( $results, sk_array_search( $sub_array, $key, $value ) );
      }

    }

    return $results;

  }
}

/**
 *
 * Load options fields
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_load_option_fields' ) ) {
  function sk_load_option_fields() {
    global $skelet_path;
   
    $located_fields = array();
    foreach ( glob( $skelet_path["dir"] .'skelet/fields/*/*.php' ) as $sk_field ) {
      $located_fields[] = basename( $sk_field );
      sk_locate_template( str_replace(  $skelet_path["dir"].'skelet/', '', $sk_field ) ,$skelet_path);
    }

    $override_name = apply_filters( 'sk_framework_override', 'sk-framework-override' );
    $override_dir  = get_template_directory() .'/skelet/'. $override_name .'/fields';
    if( is_dir( $override_dir ) ) {

      foreach ( glob( $override_dir .'/*/*.php' ) as $override_field ) {

        if( ! in_array( basename( $override_field ), $located_fields ) ) {

          sk_locate_template( str_replace(  $skelet_path["dir"] .'-override', '', $override_field ) );

        }

      }

    }

    do_action( 'sk_load_option_fields' );

  }
}



/**
 * @package skelet
 */

/**
 * Gets options
 * 
 * Return all paf options array
 * If $option_id is set, the function will that option current value
 * If not find it will return it's default value if it's defined
 * 
 * @param string $option_id
 * @return mixed The paf option value
 */
if(!function_exists("paf")){
function paf( $option_id  = '' ) {
  global $skelet_path;
  $paf = get_option( $skelet_path["option"], array() );

  if( strlen( $option_id ) ) {
    if( isset( $paf[ $option_id ] ) ) {
      return $paf[ $option_id ];
    } else {
      $def = paf_d( $option_id );
      return K::get_var( 'default', $def );
    }
  } else {
    return $paf;
  }
}
}

/**
 * Get option definition
 */
if(!function_exists("paf_d")){
function paf_d( $option_id ){
  
  global $paf_options;
  
  return K::get_var( $option_id, $paf_options, FALSE );
}
}
if(!function_exists("paf_print_option")){

  function paf_print_option( $option_id, $alt = array() ) {
    
    if ( $alt ) {
      $option = $alt;
    } else {
      global $paf_page_options;
      $option = $paf_page_options[ $option_id ];
    }

    $option = paf_option_prepare( $option, $option_id );
    $option_type = K::get_var( 'type', $option, 'text' );

    // Determine the option callback function
    $callback = 'paf_print_option_type_' . $option_type;
    if( ! function_exists( $callback ) ) {
      $callback = 'paf_print_option_type_not_implemented';
    }

    // Sort option parameters for a better display when using 'description' = '~'
    ksort( $option );

    /**
     * Call the function coresponding to the option, 
     * e.g. paf_print_option_type_text or paf_print_option_type_media
     */
    call_user_func( $callback, array( $option_id => $option ) );
  }
}

if(!function_exists("paf_option_return_title")){

  function paf_option_return_title( $option_def ) {

    $option_id = key( $option_def );
    $option = $option_def[ $option_id ];

    $title = K::get_var( 'title', $option, $option_id );
    $subtitle = K::get_var( 'subtitle', $option );

    $return = 
      $title 
      . ( $subtitle
        ? '<br /><span style="font-weight: normal; font-style: italic; font-size: .9em;">' . $subtitle . '</span>'
        : '' 
      )
    ;

    return $return;
  }
}
if(!function_exists("paf_option_return_format")){

  function paf_option_return_format( $option_type = 'input' ) {

    switch ( $option_type ) {
      case 'media':
        return '<table class="form-table"><tbody><tr><th scope="row">%s</th><td>:input%s<br />%s</td></tr></tbody></table>';
      case 'input':
      case 'select':
      case 'textarea':
      default:
        return '<table class="form-table"><tbody><tr><th scope="row">%s</th><td>:' . $option_type . '<br />%s</td></tr></tbody></table>' ;
    }
  }
}

/**
 * Prepare option
 * 
 * Change different option attributes to a suitable format
 */
if(!function_exists("paf_option_prepare")){

function paf_option_prepare( $option, $option_id = null ) {

  // Add type if not specified
  if( ! isset( $option[ 'type' ] ) ) {
    $option[ 'type' ] = 'text';
  }

  // Format selected as an array
  if( isset( $option[ 'selected' ] ) ) {
    $option[ 'selected' ] = K::get_var( 'selected', $option, array() );
    if ( ! is_array( $option[ 'selected' ] ) ) {
      $option[ 'selected' ] = explode( ',', $option[ 'selected' ] );
    }
  }

  // Format default as an array
  if( in_array( $option[ 'type' ], array( 'select', 'radio', 'checkbox' ) ) ) {
    if( isset( $option[ 'default' ] ) ) {
      $option[ 'default' ] = K::get_var( 'default', $option, array() );
      if ( ! is_array( $option[ 'default' ] ) ) {
        $option[ 'default' ] = explode( ',', $option[ 'default' ] );
      }
    }
  }

  // Add code for generating the option is the description is "~"
  if( $option_id ) {
    if( '~' === K::get_var( 'description', $option ) ) {
      $option[ 'description' ] = paf_option_return_dump( $option_id );
    }
  }

  return $option;
}
}
/**
  * Generate formatted and syntax highlighted dump
  */
if(!function_exists("paf_option_return_dump")){
 
function paf_option_return_dump( $option_id ) {

  global $paf_options;
  $option = $paf_options[ $option_id ];
  ksort( $option );

  array_walk_recursive( $option, 'paf_htmlspecialchars_recursive' );

  $dump = K::wrap(
      "\$options[ '$option_id' ] = "
      . var_export( $option, true )
      . ';'
    , array(
      'class' => 'php paf-code-block',
    )
    , array(
      'html_before' => '<pre>',
      'html_after' => '</pre>',
      'in' => 'code',
      'return' => true,
    )
  );

  // Remove white space before 'array ('
  $dump = preg_replace( '/=>\s+array \(/s', '=> array (', $dump );

  // Replace 2 spaces with 4 spaces
  $pattern = "/((?:  )+)(\d+|'|array|\))/";
  $dump = preg_replace( $pattern, '\1\1\2', $dump );

  return $dump;
}
}

if(!function_exists("paf_print_option_type_text")){

function paf_print_option_type_text( $option_def ) {

  $option_id = key( $option_def );
  $option = $option_def[ $option_id ];

  K::input( 'paf[' . $option_id . ']'
    , array(
      'class' => 'regular-text',
      'placeholder' => K::get_var( 'placeholder', $option ),
      'value' => isset( $option[ 'value' ] )
        ? $option[ 'value' ]
        : paf( $option_id )
      ,
      'data-paf-conditions' => K::get_var( 'conditions', $option )
        ? urlencode( json_encode( K::get_var( 'conditions', $option ), JSON_FORCE_OBJECT ) )
        : null
      ,
      'data-paf-default' => K::get_var( 'default', $option ),
    )
    , array(
      'colorpicker' => K::get_var( 'colorpicker', $option, FALSE ),
      'format' => sprintf( 
        paf_option_return_format()
        , paf_option_return_title( $option_def )
        , K::get_var( 'description', $option, '' )
      )
    )
  );
}
}

if(!function_exists("paf_print_option_type_textarea")){

function paf_print_option_type_textarea( $option_def ) {

  $option_id = key( $option_def );
  $option = $option_def[ $option_id ];

  if( '~' === K::get_var( 'description', $option ) ) {
    $option[ 'description' ] = paf_option_return_dump( $option_id );
  }

  $style = '';
  foreach( array( 'height', 'width' ) as $prop ) {
    $style .= K::get_var( $prop, $option )
      ? sprintf( '%:%;', $prop, $option[ $prop ] )
      : ''
    ;
  }

  K::textarea( 'paf[' . $option_id . ']'
    , array(
      'class' => K::get_var( 'cols', $option ) ? '' : 'large-text',
      'cols' => K::get_var( 'cols', $option ),
      'rows' => K::get_var( 'rows', $option ),
      'style' => $style,
      'data-paf-conditions' => K::get_var( 'conditions', $option )
        ? urlencode( json_encode( K::get_var( 'conditions', $option ), JSON_FORCE_OBJECT ) )
        : null
      ,
      'data-paf-default' => K::get_var( 'default', $option ),
    )
    , array(
      'value' => isset( $option[ 'value' ] )
        ? $option[ 'value' ]
        : paf( $option_id )
      ,
      'editor' => K::get_var( 'editor', $option, FALSE ),
      'editor_height' => K::get_var( 'editor_height', $option ),
      'format' => sprintf( 
        paf_option_return_format( 'textarea' )
        , paf_option_return_title( $option_def )
        , K::get_var( 'description', $option, '' )
      ),
      'media_buttons' => K::get_var( 'media_buttons', $option, TRUE ),
      'teeny' => K::get_var( 'teeny', $option ),
      'textarea_rows' => K::get_var( 'textarea_rows', $option, 20 ),
    )
  );
}
}

if(!function_exists("paf_print_option_type_select")){

function paf_print_option_type_select( $option_def ) {

  $option_id = key( $option_def );
  $option = $option_def[ $option_id ];

  if( '~' === K::get_var( 'description', $option ) ) {
    $option[ 'description' ] = paf_option_return_dump( $option_id );
  }

  $is_radio = 'radio' === $option[ 'type' ];
  $is_checkbox = 'checkbox' === $option[ 'type' ];
  $is_select = 'select' === $option[ 'type' ];
  $is_multiple = $is_checkbox || K::get_var( 'multiple', $option );

  // Enqueue select 2
  if( ! $is_checkbox && ! $is_radio ) {
    $protocol = is_ssl() ? 'https' : 'http';
    wp_enqueue_script( 'select2', $protocol . '://cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.js' );
    wp_enqueue_style( 'select2', $protocol . '://cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.css' );
  }

  $options = array();
  switch ( K::get_var( 'options', $option, array() ) ) {
    case 'posts':
      $posts = query_posts( K::get_var( 'args', $option, '' ) );
      foreach ( $posts as $post ) {
        $options[ $post->ID ] = $post->post_title;
      }
      if ( $is_select && ! $is_multiple ) {
        $options = array( '' ) + $options;
      }
      break;
    case 'terms':
      $taxonomies = K::get_var( 'taxonomies', $option, 'category,post_tag,link_category,post_format' );
      if ( ! is_array( $taxonomies ) ) {
        $taxonomies = explode( ',', $taxonomies );
      }
      $args = K::get_var( 'args', $option, array() );
      $terms = get_terms( $taxonomies, $args );
      foreach ( $terms as $term ) {
        $options[ $term->term_id ] = $term->name;
      }
      if ( $is_select && ! $is_multiple ) {
        $options = array( '' ) + $options;
      }
      break;
    default:
      $options = K::get_var( 'options', $option, array() );
      break;
  }

  // Add an empty option to prevent auto-selecting the first radio
  if( $is_radio ) {
    $options = array( '__none__' => '' ) + $options;
  }

  // Escape HTML
  foreach ( $options as $k => $v ) {
    $options[ $k ] = htmlspecialchars( $v );
  }

  K::select( 'paf[' . $option_id . ']'
    , array(
      'class' => 'paf-option-type-' . $option[ 'type' ],
      'data-paf-separator' => K::get_var( 'separator', $option, '<br />' ),
      'multiple' => $is_multiple,
      'style' => 'min-width: 25em;',
      'data-paf-conditions' => K::get_var( 'conditions', $option )
        ? urlencode( json_encode( K::get_var( 'conditions', $option ), JSON_FORCE_OBJECT ) )
        : null
      ,
      'data-paf-default' => K::get_var( 'default', $option )
        ? urlencode( json_encode( K::get_var( 'default', $option ), JSON_FORCE_OBJECT ) )
        : null
      ,
    )
    , array(
      'options' => $options,
      'selected' => isset( $option[ 'selected' ] )
        ? $option[ 'selected' ]
        : paf( $option_id )
      ,
      'format' => sprintf( 
        paf_option_return_format( 'select' )
        , paf_option_return_title( $option_def )
        , K::get_var( 'description', $option, '' )
      )
    )
  );
}

}

if(!function_exists("paf_print_option_type_radio")){

function paf_print_option_type_radio( $option_def ) {

  return paf_print_option_type_select( $option_def );
}
}


if(!function_exists("paf_print_option_type_checkbox")){

function paf_print_option_type_checkbox( $option_def ) {  

  return paf_print_option_type_select( $option_def );
}
}

if(!function_exists("paf_print_option_type_media")){

function paf_print_option_type_media( $option_def ) {

  $option_id = key( $option_def );
  $option = $option_def[ $option_id ];

  if( '~' === K::get_var( 'description', $option ) ) {
    $option[ 'description' ] = paf_option_return_dump( $option_id );
  }

  $button_text = K::get_var( 'button_text', $option, __( 'Select media' ) );

  // Output
  K::input( 'paf[' . $option_id . ']'
    , array(
      'class' => 'paf-option-type-media regular-text',
      'placeholder' => K::get_var( 'placeholder', $option ),
      'value' => isset( $option[ 'value' ] )
        ? $option[ 'value' ]
        : paf( $option_id )
      ,
      'data-paf-conditions' => K::get_var( 'conditions', $option )
        ? urlencode( json_encode( K::get_var( 'conditions', $option ), JSON_FORCE_OBJECT ) )
        : null
      ,
      'data-paf-default' => K::get_var( 'default', $option ),
    )
    , array(
      'format' => sprintf( 
        paf_option_return_format( 'media' )
        , paf_option_return_title( $option_def )
        , '<a class="button">' . $button_text . '</a>'
        , K::get_var( 'description', $option, '' )
      )
    )
  );
}
}

if(!function_exists("paf_print_option_type_not_implemented")){

function paf_print_option_type_not_implemented( $option_def ) {

  $option_id = key( $option_def );
  $option = $option_def[ $option_id ];

  if( '~' === K::get_var( 'description', $option ) ) {
    $option[ 'description' ] = paf_option_return_dump( $option_id );
  }

  K::input( 'paf[' . $option_id . ']'
    , array(
      'value' => K::get_var( 'value', $option, '' ),
    )
    , array(
      'format' => sprintf( 
        paf_option_return_format()
        , paf_option_return_title( $option_def )
        , sprintf(
          '<p class="description"><span class="dashicons dashicons-no"></span> ' . __( 'The option type <code>%s</code> is not yet implemented' ) . '</p>'
          , $option[ 'type' ]
        )
        , K::get_var( 'description', $option, '' )
      )
    )
  );

}

}

if(!class_exists("K")){

/**
 * The k framework
 * 
 * @author Nabil Kadimi <nabil@kadimi.com>
 * @version 1.0.4
 * @package k_framework
 */
class K {

  /**
   * Gets a variable 
   */
  static function get_var( $name, $array = null, $default = null ) {

    if( is_null( $array ) ) {
      $array = $GLOBALS;
    }
    if( is_array( $array ) && array_key_exists( $name, $array ) ) {
      return $array[ $name ];
    } else {
      return $default;
    }
  }

  /**
   * Prints or returns an input field
   */
  static function input( $name ) {

    // $params    
    if( func_num_args() > 1 ) {
      $params = func_get_arg(1);
    }
    if( empty( $params ) ) {
      $params = array();
    }

    // $args
    if( func_num_args() > 2 ) {
      $args = func_get_arg(2);
    }
    if( empty( $args ) ) {
      $args = array();
    }

    // Load defaults    
    $params += array(
      'type' => 'text',
      'id' => '',
      'value' => ''
    );

    // Add name
    $params[ 'name' ] = $name;

    // Build the input field html
    $input = sprintf( '<input %s/>', K::params_str( $params ) );

    // Format
    if( ! empty ( $args[ 'format' ] ) ) {
      $input = str_replace(
        array( ':input', ':name', ':id', ':value' ),
        array( $input, $name, $params[ 'id'], $params[ 'value'] ),
        $args[ 'format' ]
      );
    }

    // Add default color picker
    if(
      ! empty ( $args[ 'colorpicker' ] )
      || (
        'text' === $params[ 'type' ]
        && preg_match( '/_color\]?$/', $name) 
        && empty( $args[ 'nocolorpicker' ] ) 
      )
    ) {
      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_script( 'wp-color-picker' );
      ob_start();
      ?>
      <script>
        jQuery( 'document' ).ready( function($) {
          $( '[name="<?php echo $name; ?>"]' ).wpColorPicker();
        });
      </script>
      <?php
      $input .= ob_get_clean();
    }

    // Print or return the input field HTML
    if( ! empty( $args[ 'return' ] ) ) {
      return $input;
    } else {
      echo $input;
    }
  }

  /**
   * Prints or returns an input field
   */
  static function textarea( $name ) {

    // $params
    if( func_num_args() > 1 ) {
      $params = func_get_arg(1);
    }
    if( ! is_array( $params ) ) {
      $params = array();
    }

    // $args
    if( func_num_args() > 2 ) {
      $args = func_get_arg(2);
    }
    if( ! is_array( $args ) ) {
      $args = array();
    }

    // Load defaults
    $params += array(
      'id' => '',
    );

    // Add name
    $params[ 'name' ] = $name;

    // Set $value
    $value = empty( $args[ 'value' ] ) ? '' : $args[ 'value' ];

    // Build textarea html
    if( K::get_var( 'editor', $args ) ) {
      // Remove the name since it's attached to the editor
      $params_for_editor = $params;
      unset( $params_for_editor[ 'name' ] );
      // Build
      ob_start();
      wp_editor(
        $value,
        str_replace( array( '[', ']' ), '_', $name ) . mt_rand( 100, 999 ),
        array(
          'editor_height' => K::get_var( 'editor_height', $args ),
          'media_buttons' => K::get_var( 'media_buttons', $args, TRUE ),
          'teeny' => K::get_var( 'teeny', $args ),
          'textarea_name' => $name,
          'textarea_rows' => K::get_var( 'textarea_rows', $args, 20 ),
        )
      );
      $textarea = ob_get_clean();
      $textarea = sprintf( '<div %s>%s</div>', K::params_str( $params_for_editor ), $textarea );
    } else {
      $textarea = sprintf( '<textarea %s>%s</textarea>', K::params_str( $params ), $value );
    }

    // Format
    if( ! empty ( $args[ 'format' ] ) ) {
      $textarea = str_replace(
        array( ':textarea', ':value', ':name', ':id' ),
        array( $textarea, $value, $name, $params[ 'id' ] ),
        $args[ 'format' ]
      );
    }

    // Print or return the textarea field HTML
    if( ! empty( $args[ 'return' ] ) ) {
      return $textarea;
    } else {
      echo $textarea;
    }
  }

  /**
   * Prints or returns an dropdown select
   */
  static function select( $name ) {

    // $params    
    if( func_num_args() > 1 ) {
      $params = func_get_arg(1);
    }
    if( empty( $params ) ) {
      $params = array();
    }
    // Load defaults    
    $params += array(
      'id' => '',
    );

    // Sanitize $params[multiple], and Add brackets if the former is true
    if( ! empty( $params[ 'multiple' ] ) ) {
      $params[ 'multiple' ] = 'multiple';
      $name .= '[]';
    }

    // Add name
    $params[ 'name' ] = $name;

    // $args
    if( func_num_args() > 2 ) {
      $args = func_get_arg(2);
    }
    if( empty( $args ) ) {
      $args = array();
    }
    $args += array(
      'default' => '',
      'options' => array(),
      'html_before' => '',
      'html_after' => '',
      'selected' => '',
    );

    // Make 'selected' an array
    if( $selected = $args[ 'selected' ] ) {
      if( ! is_array( $selected ) ) {
        $selected = array( $selected );
      }
    }

    // Use 'default' is 'selected' is empty
    if( ! $selected ) {
      $selected = array( $args[ 'default' ] );
    }

    // Build options
    $options = '';
    foreach ( $args[ 'options' ] as $value => $label ) {
      $options .= K::wrap(
        $label
        , array(
          'value' => $value,
          'selected' => ( in_array( $value, $selected ) ) 
            ? 'selected'
            : null
          ,
        )
        , array(
          'in' => 'option',
          'return' => true,
        )
      );
    }

    // Build the input field html
    $select = sprintf( '%s<select %s>%s</select>%s', $args[ 'html_before' ], K::params_str( $params ), $options, $args[ 'html_after' ] );

    // Format
    if( ! empty ( $args[ 'format' ] ) ) {
      $select = str_replace(
        array( ':select', ':name', ':id' ),
        array( $select, $name, $params[ 'id'] ),
        $args[ 'format' ]
      );
    }

    // Print or return the input field HTML
    if( ! empty( $args[ 'return' ] ) ) {
      return $select;
    } else {
      echo $select;
    }
  }

  /**
   * Prints or returns an input field
   * 
   * @param array $controls The array of controls
   */
  static function fieldset( $legend, $controls = array() ) {

    // $params    
    if( func_num_args() > 2 ) {
      $params = func_get_arg(2);
    }
    if( empty( $params ) ) {
      $params = array();
    }

    // $args
    if( func_num_args() > 3 ) {
      $args = func_get_arg(3);
    }
    if( empty( $args ) ) {
      $args = array();
    }

    // Inner HTML placeholder
    $innerHTML = '';

    // Put controls in placehoder
    foreach( $controls as $control ) {

      // Fill params if needed
      $control[2] = ! empty( $control[2] ) ? $control[2] : array();

      // Set $args['return'] to false
      if( empty( $control[3] ) ) {
        $control[3] = array();
      }
      $control[3][ 'return' ] = true;

      // Get control HTML
      $innerHTML .= call_user_func(
        /* Callback */     'K::' . $control[0],
        /* Name/content */ $control[1],
        /* Params*/        $control[2],
        /* Args */         $control[3]
      );
    }

    // Prepare HTML
    $HTML = str_replace(
      array( ':legend', ':controls', ':parameters' ),
      array( 
        ! empty( $legend ) ? $legend : '',
        $innerHTML,
        K::params_str( $params )
      ),
      '<fieldset :parameters><legend>:legend</legend>:controls</fieldset>'
    );

    // Print or return the input field HTML
    if( ! empty ( $args[ 'return' ] ) ) {
      return $HTML;
    } else {
      echo $HTML;
    }
  }

  /**
   * Wraps given input in an html tag
   */
  static function wrap( $content = '' ) {

    // $params    
    if( func_num_args() > 1 ) {
      $params = func_get_arg(1);
    }
    if( empty( $params ) ) {
      $params = array();
    }

    // $args
    if( func_num_args() > 2 ) {
      $args = func_get_arg(2);
    }
    if( empty( $args ) ) {
      $args = array();
    }
    $args += array(
      'in' => 'div',
      'html_before' => '',
      'html_after' => '',
    );

    // Build the input field html
    $html = sprintf( '%s<%s %s>%s</%s>%s',
      $args[ 'html_before' ],
      $args[ 'in' ],
      K::params_str( $params ),
      $content,
      $args[ 'in' ],
      $args[ 'html_after' ]
    );

    // Print or return the input field HTML
    if( ! empty( $args[ 'return' ] ) ) {
      return $html;
    } else {
      echo $html;
    }
  }

  /**
   * Prepares an array of params and their values to be added to an html element
   */
  static function params_str( $params ) {
    $params_str = '';
    foreach( $params as $parameter => $value ) {
      if( strlen( $value ) ) {
        $params_str .= sprintf( ' %s="%s"', $parameter, $value);
      }
    }
    return $params_str;
  }
}

add_action( 'in_admin_footer', 'k_scripts' );
function k_scripts() {
  ?>
  <style>
    fieldset.k {
      border: solid 1px lightgray;
      margin-bottom: 1em;
      padding-left: .5em;
      padding-right: .5em;
    }
    fieldset.k legend {
      background: white;
      padding: .25em .5em;
      box-shadow: 0 0 3px silver;
      transition: all .5s;
    }
    fieldset.k legend.highlighted {
      background: lightgray;
      box-shadow: 0 0 1px black;
    }
    fieldset.k.expanded legend {
      font-weight: bold;
    }
    fieldset.k.collapsible legend {
      cursor: pointer;
    }
    fieldset.k.collapsed :not(legend) {
      display: none;
    }
    fieldset.k.collapsed {
      display: inline;
      border: none;
    }
  </style>
  <script>
    jQuery( document ).ready( function( $ ) {
      $( '.collapsible legend' ).click( function (){
        $this = $( this );
        $fieldset = $this.parent();
        // Collapse all except target
        $fieldset.parent()
          .find( 'fieldset' )
          .not( $fieldset )
          .removeClass( 'expanded' )
          .addClass( 'collapsed' )
          .trigger( 'collapse' );
        // Toggle target
        $fieldset.toggleClass( 'collapsed' );
        // Add expanded class to non-collpased and vice-versa, then trigger events
        if ( $fieldset.hasClass('collapsed') ) {
          $fieldset.removeClass( 'expanded' );
          $fieldset.trigger( 'collapse' );
        } else {
          $fieldset.addClass( 'expanded' );
          $fieldset.trigger( 'expand' );
        }
      } );
    } );
  </script>
  <?php
}
}

if(!function_exists("pakb_load_file")){
function pakb_load_file($filename){
    ob_start();
    include $filename;
    return ob_get_clean();
}
}

/**
 * Apply prefixes to options
 */
if(!function_exists("sk_apply_prefix")){
  function sk_apply_prefix($option = array()){
      global $skelet_path;

       
          $arr_option = array();
          foreach ($option as $key => $value) {
            $new_prefix = "";
            
            if($key == "name" || $key == "id"){
            
              $new_prefix = $skelet_path["prefix"].'_'.$value;
            
            }elseif($key == "settings" || $key == "fields" || $key == "sections" ||  $key == "shortcodes"){
            
              $arr_settings = array();
              foreach ($value as $key_settings => $val_settings) {
            
                  $arr_sets = array();
                    foreach($val_settings as $vkey => $vval){
                      
                      if($vkey == "settings" || $vkey == "fields" || $vkey == "sections" ||  $vkey == "shortcodes"){
                      
                         $arr_vvfields = array();
                         foreach ($vval as $vvkey => $vvval) {
                           
                             if(isset($vvval["id"])){
                              
                              $vvval["id"] = $skelet_path["prefix"].'_'.$vvval["id"];
                             }else if(isset($vvval["name"])){
                              
                              $vvval["name"] = $skelet_path["prefix"].'_'.$vvval["name"];
                             }
                           
                              array_push($arr_vvfields,$vvval);
                         }
                         $arr_sets[$vkey] = $arr_vvfields;
                      
                      }else if($vkey == "name" || $vkey == "id"){
                      $arr_sets[$vkey] = $skelet_path["prefix"].'_'.$vval;
                    }else{
                      $arr_sets[$vkey] = $vval;
                    }
                  
                  }

                array_push($arr_settings,$arr_sets);
              }
              if(!empty($arr_settings)){
                $new_prefix = $arr_settings;
              }
            }else{
              $new_prefix = $value;
            }
            $arr_option[$key] = $new_prefix;
          }
         
        return $arr_option;
    }
}

/**
 * Apply prefix to shortcodes
 */

if(!function_exists("sk_shortcode_apply_prefix")){
  function sk_shortcode_apply_prefix($option = array()){
      global $skelet_path;

       
          $arr_option = array();
          foreach ($option as $key => $value) {
            $new_prefix = "";
            
            if($key == "name" || $key == "id"){
            
              $new_prefix = $skelet_path["prefix"].'_'.$value;
            
            }elseif($key == "settings" || $key == "fields" || $key == "sections" ||  $key == "shortcodes"){
            
              $arr_settings = array();
              foreach ($value as $key_settings => $val_settings) {
            
                  $arr_sets = array();
                    foreach($val_settings as $vkey => $vval){
                      
                      if($vkey == "settings" || $vkey == "fields" || $vkey == "sections" ||  $vkey == "shortcodes"){
                      
                         $arr_vvfields = array();
                         foreach ($vval as $vvkey => $vvval) {
                           
                             if(isset($vvval["id"])){
                              
                             // $vvval["id"] = $skelet_path["prefix"].'_'.$vvval["id"];
                             }else if(isset($vvval["name"])){
                              
                              $vvval["name"] = $skelet_path["prefix"].'_'.$vvval["name"];
                             }
                           
                              array_push($arr_vvfields,$vvval);
                         }
                         $arr_sets[$vkey] = $arr_vvfields;
                      
                      }else if($vkey == "name" || $vkey == "id"){
                      $arr_sets[$vkey] = $skelet_path["prefix"].'_'.$vval;
                    }else{
                      $arr_sets[$vkey] = $vval;
                    }
                  
                  }

                array_push($arr_settings,$arr_sets);
              }
              if(!empty($arr_settings)){
                $new_prefix = $arr_settings;
              }
            }else{
              $new_prefix = $value;
            }
            $arr_option[$key] = $new_prefix;
          }
         
        return $arr_option;
    }
}