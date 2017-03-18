<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Framework Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework")){
  class SkeletFramework extends SkeletFramework_Abstract {

    public static $skelet_unique = "";
    /**
     *
     * option database/data name
     * @access public
     * @var string
     *
     */
    public $unique = array();

    /**
     *
     * settings
     * @access public
     * @var array
     *
     */
    public $settings = array();


    /**
     *
     * first_settings
     * @access public
     * @var array
     *
     */
    public $first_settings = array();

    /**
     *
     * options tab
     * @access public
     * @var array
     *
     */
    public $options = array();

    /**
     *
     * options section
     * @access public
     * @var array
     *
     */
    public $sections = array();

    /**
     *
     * options store
     * @access public
     * @var array
     *
     */
    public $get_option = array();

    /**
     *
     * instance
     * @access private
     * @var class
     *
     */
    private static $instance = null;

    // run framework construct
    public function __construct( $settings, $options, $path ) {

      $options = $this->apply_prefix($options);

      self::$skelet_unique = $path["option"];
      $this->unique  = $path["option"];

      $this->settings = apply_filters( 'sk_framework_settings', $settings );
      $this->options  = apply_filters( 'sk_framework_options', $options );
      if( ! empty( $this->options ) ) {
        $this->sections   = $this->get_sections();
        $this->get_option = get_option( self::$skelet_unique );
        $this->addAction( 'admin_init', 'settings_api' );
        $this->addAction( 'admin_menu', 'admin_menu' );
        $this->addAction( 'admin_menu', 'remove_menu', 100);
        $this->addAction( 'wp_ajax_sk-export-options', 'export' );
      }

    }



    // instance
    public static function instance( $settings = array(), $options = array() ) {
      global $skelet_path;

      self::$skelet_unique = $skelet_path["option"];
      //if ( is_null( self::$instance ) && SK_ACTIVE_FRAMEWORK ) {
        self::$instance = new self( $settings, $options, $skelet_path );
      //}

      return self::$instance;
    }

    // get sections
    public function get_sections() {

      $sections = array();

      foreach ( $this->options as $key => $value ) {

        if( isset( $value['sections'] ) ) {

          foreach ( $value['sections'] as $section ) {

            if( isset( $section['fields'] ) ) {
              $sections[] = $section;
            }

          }

        } else {

          if( isset( $value['fields'] ) ) {
            $sections[] = $value;
          }

        }

      }

      return $sections;

    }

    // wp settings api
    public function settings_api() {

      $defaults = array();

      foreach( $this->sections as $section ) {

        register_setting( $this->unique .'_group', $this->unique, array( &$this,'validate_save' ) );

        if( isset( $section['fields'] ) ) {

          add_settings_section( $section['name'] .'_section', $section['title'], '', $section['name'] .'_section_group' );

          foreach( $section['fields'] as $field_key => $field ) {

            add_settings_field( $field_key .'_field', '', array( &$this, 'field_callback' ), $section['name'] .'_section_group', $section['name'] .'_section', $field );

            // set default option if isset
            if( isset( $field['default'] ) ) {
              $defaults[$field['id']] = $field['default'];
              if( ! empty( $this->get_option ) && ! isset( $this->get_option[$field['id']] ) ) {
                $this->get_option[$field['id']] = $field['default'];
              }
            }

          }
        }

      }

      // set default variable if empty options and not empty defaults
      if( empty( $this->get_option )  && ! empty( $defaults ) ) {
        update_option( $this->unique, $defaults );
        $this->get_option = $defaults;
      }

    }

    // section fields validate in save
    public function validate_save( $request ) {

      $add_errors = array();
      $section_id = ( isset( $_POST['sk_section_id'] ) ) ? $_POST['sk_section_id'] : '';

      // ignore nonce requests
      if( isset( $request['_nonce'] ) ) { unset( $request['_nonce'] ); }

      // import
      if ( isset( $request['import'] ) && ! empty( $request['import'] ) ) {
        $decode_string = sk_decode_string( $request['import'] );
        if( is_array( $decode_string ) ) {
          return $decode_string;
        }
        $add_errors[] = $this->add_settings_error( __( 'Success. Imported backup options.', SK_TEXTDOMAIN ), 'updated' );
      }

      // reset all options
      if ( isset( $request['resetall'] ) ) {
        $add_errors[] = $this->add_settings_error( __( 'Default options restored.', SK_TEXTDOMAIN ), 'updated' );
        return;
      }

      // reset only section
      if ( isset( $request['reset'] ) && ! empty( $section_id ) ) {
        foreach ( $this->sections as $value ) {
          if( $value['name'] == $section_id ) {
            foreach ( $value['fields'] as $field ) {
              if( isset( $field['id'] ) ) {
                if( isset( $field['default'] ) ) {
                  $request[$field['id']] = $field['default'];
                } else {
                  unset( $request[$field['id']] );
                }
              }
            }
          }
        }
        $add_errors[] = $this->add_settings_error( __( 'Default options restored for only this section.', SK_TEXTDOMAIN ), 'updated' );
      }

      // option sanitize and validate
      foreach( $this->sections as $section ) {
        if( isset( $section['fields'] ) ) {
          foreach( $section['fields'] as $field ) {

            // ignore santize and validate if element multilangual
            if ( isset( $field['type'] ) && ! isset( $field['multilang'] ) && isset( $field['id'] ) ) {

              // sanitize options
              $request_value = isset( $request[$field['id']] ) ? $request[$field['id']] : '';
              $sanitize_type = $field['type'];

              if( isset( $field['sanitize'] ) ) {
                $sanitize_type = ( $field['sanitize'] !== false ) ? $field['sanitize'] : false;
              }

              if( $sanitize_type !== false && has_filter( 'sk_sanitize_'. $sanitize_type ) ) {
                $request[$field['id']] = apply_filters( 'sk_sanitize_' . $sanitize_type, $request_value, $field, $section['fields'] );
              }

              // validate options
              if ( isset( $field['validate'] ) && has_filter( 'sk_validate_'. $field['validate'] ) ) {

                $validate = apply_filters( 'sk_validate_' . $field['validate'], $request_value, $field, $section['fields'] );

                if( ! empty( $validate ) ) {
                  $add_errors[] = $this->add_settings_error( $validate, 'error', $field['id'] );
                  $request[$field['id']] = ( isset( $this->get_option[$field['id']] ) ) ? $this->get_option[$field['id']] : '';
                }

              }

            }

            if( ! isset( $field['id'] ) || empty( $request[$field['id']] ) ) {
              continue;
            }

          }
        }
      }

      $request = apply_filters( 'sk_validate_save', $request );

      // set transient
      $transient_time = ( sk_language_defaults() !== false ) ? 30 : 10;
      set_transient( 'sk-framework-transient', array( 'errors' => $add_errors, 'section_id' => $section_id ), $transient_time );

      return $request;
    }

    // field callback classes
    public function field_callback( $field ) {
      $value = ( isset( $field['id'] ) && isset( $this->get_option[$field['id']] ) ) ? $this->get_option[$field['id']] : '';
      echo sk_add_element( $field, $value, $this->unique );
    }

    // settings sections
    public function do_settings_sections( $page ) {

      global $wp_settings_sections, $wp_settings_fields;

      if ( ! isset( $wp_settings_sections[$page] ) ){
        return;
      }

      foreach ( $wp_settings_sections[$page] as $section ) {

        if ( $section['callback'] ){
          call_user_func( $section['callback'], $section );
        }

        if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) ){
          continue;
        }

        $this->do_settings_fields( $page, $section['id'] );

      }

    }

    // settings fields
    public function do_settings_fields( $page, $section ) {

      global $wp_settings_fields;

      if ( ! isset( $wp_settings_fields[$page][$section] ) ) {
        return;
      }

      foreach ( $wp_settings_fields[$page][$section] as $field ) {
        call_user_func($field['callback'], $field['args']);
      }

    }

    public function add_settings_error( $message, $type = 'error', $id = 'global' ) {
      return array( 'setting' => 'sk-errors', 'code' => $id, 'message' => $message, 'type' => $type );
    }

    // adding option page
    public function admin_menu() {

      $defaults_menu_args = array(
        'menu_parent'     => '',
        'menu_title'      => '',
        'menu_type'       => '',
        'menu_slug'       => '',
        'menu_icon'       => '',
        'menu_capability' => 'manage_options',
        'menu_position'   => null,
        'menu_parent_page'=> false
      );


      $args = wp_parse_args( $this->settings, $defaults_menu_args );

      if(!defined( 'SK_PARENT_MENU' )){
        $set_parent_slug = isset($args["menu_slug"])?$args["menu_slug"]:"pa-main-menu";
        define( 'SK_PARENT_MENU',$set_parent_slug."_");
        call_user_func("add_menu_page", "PressApps", "PressApps", "pa-nonexistent-capability", SK_PARENT_MENU, null, $args['menu_icon'], $args['menu_position'] );
      }


      if( $args['menu_type'] == 'add_submenu_page' ) {
        call_user_func( $args['menu_type'], SK_PARENT_MENU, $args['menu_title'], $args['menu_title'], $args['menu_capability'], $args['menu_slug'], array( &$this, 'admin_page' ) );
      }

    }

    //remove seklet menu
    public function remove_menu() {
      if( is_multisite() ) {
        remove_submenu_page( SK_PARENT_MENU, SK_PARENT_MENU );
      }
    }

    // option page html output
    public function admin_page() {

      $transient  = get_transient( 'sk-framework-transient' );
      $has_nav    = ( count( $this->options ) <= 1 ) ? ' sk-show-all' : '';
      $section_id = ( ! empty( $transient['section_id'] ) ) ? $transient['section_id'] : $this->sections[0]['name'];
      $section_id = ( isset( $_GET['sk-section'] ) ) ? esc_attr( $_GET['sk-section'] ) : $section_id;

      echo '<div class="sk-framework sk-option-framework">';

        echo '<form method="post" action="options.php" enctype="multipart/form-data" id="csframework_form">';
        echo '<input type="hidden" class="sk-reset" name="sk_section_id" value="'. $section_id .'" />';

        if( $this->settings['ajax_save'] !== true && ! empty( $transient['errors'] ) ) {

          global $sk_errors;

          $sk_errors = $transient['errors'];

          if ( ! empty( $sk_errors ) ) {
            foreach ( $sk_errors as $error ) {
              if( in_array( $error['setting'], array( 'general', 'sk-errors' ) ) ) {
                echo '<div class="sk-settings-error '. $error['type'] .'">';
                echo '<p><strong>'. $error['message'] .'</strong></p>';
                echo '</div>';
              }
            }
          }

        }

        settings_fields( $this->unique. '_group' );

        echo '<header class="sk-header">';
        echo '<h1>';

        echo sprintf('%s',
            isset( $this->settings['header_title'])?
            //$this->settings['header_title']." <small>".$this->settings['current_version']."</small>":"Skelet Framework <small>by PressApps</small>");
            $this->settings['header_title']:"Skelet Framework");
        echo '</h1>';
        //echo '<p class="feedback"><a target="_blank" href="http://pressapps.co/feedback/">Feedback</a></p>';
        echo '<fieldset>';
        echo ( $this->settings['ajax_save'] === true ) ? '<span id="sk-save-ajax">'. __( 'Settings saved.', SK_TEXTDOMAIN ) .'</span>' : '';
        submit_button( __( 'Save Changes', SK_TEXTDOMAIN ), 'sk-save', 'save', false, array( 'data-ajax' => $this->settings['ajax_save'], 'data-save' => __( 'Saving...', SK_TEXTDOMAIN ) ) );
        submit_button( __( 'Restore', SK_TEXTDOMAIN ), 'secondary sk-restore sk-reset-confirm', $this->unique .'[reset]', false );
        echo '</fieldset>';
        //echo ( empty( $has_nav ) ) ? '<a href="#" class="sk-expand-all"><i class="fa fa-eye-slash"></i> '. __( 'show all options', SK_TEXTDOMAIN ) .'</a>' : '';
        echo '<div class="clear"></div>';
        echo '</header>'; // end .sk-header

        echo '<div class="sk-body'. $has_nav .'">';

          echo '<div class="sk-nav">';

            echo '<ul>';
            foreach ( $this->options as $key => $tab ) {

              if( ( isset( $tab['sections'] ) ) ) {

                $tab_active   = sk_array_search( $tab['sections'], 'name', $section_id );
                $active_style = ( ! empty( $tab_active ) ) ? ' style="display: block;"' : '';
                $active_list  = ( ! empty( $tab_active ) ) ? ' sk-tab-active' : '';
                $tab_icon     = ( ! empty( $tab['icon'] ) ) ? '<i class="sk-icon '. $tab['icon'] .'"></i>' : '';

                echo '<li class="sk-sub'. $active_list .'">';

                  echo '<a href="#" class="sk-arrow">'. $tab_icon . $tab['title'] .'</a>';

                  echo '<ul'. $active_style .'>';
                  foreach ( $tab['sections'] as $tab_section ) {

                    $active_tab = ( $section_id == $tab_section['name'] ) ? ' class="sk-section-active"' : '';
                    $icon = ( ! empty( $tab_section['icon'] ) ) ? '<i class="sk-icon '. $tab_section['icon'] .'"></i>' : '';

                    echo '<li><a href="#"'. $active_tab .' data-section="'. $tab_section['name'] .'">'. $icon . $tab_section['title'] .'</a></li>';

                  }
                  echo '</ul>';

                echo '</li>';

              } else {

                $icon = ( ! empty( $tab['icon'] ) ) ? '<i class="sk-icon '. $tab['icon'] .'"></i>' : '';

                if( isset( $tab['fields'] ) ) {

                  $active_list = ( $section_id == $tab['name'] ) ? ' class="sk-section-active"' : '';
                  echo '<li><a href="#"'. $active_list .' data-section="'. $tab['name'] .'">'. $icon . $tab['title'] .'</a></li>';

                } else {

                  echo '<li><div class="sk-seperator">'. $icon . $tab['title'] .'</div></li>';

                }

              }

            }
            echo '</ul>';

          echo '</div>'; // end .sk-nav

          echo '<div class="sk-content">';

            echo '<div class="sk-sections">';

            foreach( $this->sections as $section ) {

              if( isset( $section['fields'] ) ) {

                $active_content = ( $section_id == $section['name'] ) ? ' style="display: block;"' : '';
                echo '<div id="sk-tab-'. $section['name'] .'" class="sk-section"'. $active_content .'>';
                echo ( isset( $section['title'] ) && empty( $has_nav ) ) ? '<div class="sk-section-title"><h3>'. $section['title'] .'</h3></div>' : '';
                $this->do_settings_sections( $section['name'] . '_section_group' );
                echo '</div>';

              }

            }

            echo '</div>'; // end .sk-sections

            echo '<div class="clear"></div>';

          echo '</div>'; // end .sk-content

          echo '<div class="sk-nav-background"></div>';

        echo '</div>'; // end .sk-body

        echo '</form>'; // end form

        echo '<div class="clear"></div>';

      echo '</div>'; // end .sk-framework

      global $skelet_path;
      echo '<footer class="sk-footer">';
        echo '<img src="' . $skelet_path["uri"] . '/assets/images/madeby.png">';
        echo '<a target="_blank" href="http://pressapps.co/"><img src="' . $skelet_path["uri"] . '/assets/images/pressapps.png"></a>';
      echo '</footer>'; // end .sk-footer

    }

    // export options
    public function export() {

      header('Content-Type: plain/text');
      header('Content-disposition: attachment; filename=backup-options-'. gmdate( 'd-m-Y' ) .'.txt');
      header('Content-Transfer-Encoding: binary');
      header('Pragma: no-cache');
      header('Expires: 0');

      echo sk_encode_string( get_option( self::$skelet_unique ) );

      die();

    }

  }
}
