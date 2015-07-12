<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Framework constants
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
defined( 'SK_VERSION' )     or  define( 'SK_VERSION',     '1.0.0' );
defined( 'SK_TEXTDOMAIN' )  or  define( 'SK_TEXTDOMAIN',  'pressapps' );
defined( 'SK_OPTION' )      or  define( 'SK_OPTION',      '_sk_options' );
defined( 'SK_CUSTOMIZE' )   or  define( 'SK_CUSTOMIZE',   '_sk_customize_options' );

  global $skelet_path;
   
/**
 *
 * Framework locate template and override files
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'sk_locate_template' ) ) {
  function sk_locate_template( $template_name = '', $path = array('dir' => '', 'uri' => '','basename' =>'') ) {
    
    
    $located      = '';
    $override     = apply_filters( 'sk_framework_override', 'skelet-override' );
    $dir_plugin   = $path['dir'];
    $dir_theme    = get_template_directory();
    $dir_child    = get_stylesheet_directory();
    $dir_override = '/'. $override .'/'. $template_name;
    $dir_template = $path['basename'] .'/'. $template_name;

    // child theme override
    $child_force_overide    = $dir_child . $dir_override;
    $child_normal_override  = $dir_child . $dir_template;

    // theme override paths
    $theme_force_override   = $dir_theme . $dir_override;
    $theme_normal_override  = $dir_theme . $dir_template;

    // plugin override
    $plugin_force_override  = $dir_plugin . $dir_override;
    $plugin_normal_override = $dir_plugin . $dir_template;
    $is_located = false;

/*     var_dump(array( 
        $child_force_overide."=".(file_exists($child_force_overide)?true:false),
        $child_normal_override."=".(file_exists($child_normal_override)?true:false),
        $theme_force_override."=".(file_exists($theme_force_override)?true:false),
        $theme_normal_override."=".(file_exists($theme_normal_override)?true:false),
        $plugin_force_override."=".(file_exists($plugin_force_override)?true:false),
        $plugin_normal_override."=".(file_exists($plugin_normal_override)?true:false)
      ));
     die(1);*/

    if ( file_exists( $child_force_overide ) ) {

      $located = $child_force_overide;
       $is_located = true;

    } else if ( file_exists( $child_normal_override ) ) {

      $located = $child_normal_override;
       $is_located = true;

    } else if ( file_exists( $theme_force_override ) ) {

      $located = $theme_force_override;
       $is_located = true;

    } else if ( file_exists( $theme_normal_override ) ) {

      $located = $theme_normal_override;
       $is_located = true;

    } else if ( file_exists( $plugin_force_override ) ) {

      $located =  $plugin_force_override;
       $is_located = true;

    } else if ( file_exists( $plugin_normal_override ) ) {

      $located =  $plugin_normal_override;
       $is_located = true;
    }
   
 
    $located = apply_filters( 'sk_locate_template', $located, $template_name );

    if ( ! empty( $located ) ) {
      load_template( $located, true );

    
    }

    return $located;

  }
}

/**
 *
 * Get option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_get_option' ) ) {
  function sk_get_option( $option_name = '', $default = '' ) {

    $options = apply_filters( 'sk_get_option', get_option( SK_OPTION ), $option_name, $default );

    if( ! empty( $option_name ) && ! empty( $options[$option_name] ) ) {
      return $options[$option_name];
    } else {
      return ( ! empty( $default ) ) ? $default : null;
    }

  }
}

/**
 *
 * Set option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_set_option' ) ) {
  function sk_set_option( $option_name = '', $new_value = '' ) {

    $options = apply_filters( 'sk_set_option', get_option( SK_OPTION ), $option_name, $new_value );

    if( ! empty( $option_name ) ) {
      $options[$option_name] = $new_value;
      update_option( SK_OPTION, $options );
    }

  }
}

/**
 *
 * Get all option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_get_all_option' ) ) {
  function sk_get_all_option() {
    return get_option( SK_OPTION );
  }
}

/**
 *
 * Multi language value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_get_multilang_option' ) ) {
  function sk_get_multilang_option( $option_name = '', $default = '' ) {

    $value     = sk_get_option( $option_name, $default );
    $languages = sk_language_defaults();
    $default   = $languages['default'];
    $current   = $languages['current'];

    if ( is_array( $value ) && is_array( $languages ) && isset( $value[$current] ) ) {
      return  $value[$current];
    } else if ( $default != $current ) {
      return  '';
    }

    return $value;

  }
}

/**
 *
 * Get customize option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_get_customize_option' ) ) {
  function sk_get_customize_option( $option_name = '', $default = '' ) {

    $options = apply_filters( 'sk_get_customize_option', get_option( SK_CUSTOMIZE ), $option_name, $default );

    if( ! empty( $option_name ) && ! empty( $options[$option_name] ) ) {
      return $options[$option_name];
    } else {
      return ( ! empty( $default ) ) ? $default : null;
    }

  }
}

/**
 *
 * Set customize option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_set_customize_option' ) ) {
  function sk_set_customize_option( $option_name = '', $new_value = '' ) {

    $options = apply_filters( 'sk_set_customize_option', get_option( SK_CUSTOMIZE ), $option_name, $new_value );

    if( ! empty( $option_name ) ) {
      $options[$option_name] = $new_value;
      update_option( SK_CUSTOMIZE, $options );
    }

  }
}

/**
 *
 * Get all customize option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_get_all_customize_option' ) ) {
  function sk_get_all_customize_option() {
    return get_option( SK_CUSTOMIZE );
  }
}

/**
 *
 * WPML plugin is activated
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_is_wpml_activated' ) ) {
  function sk_is_wpml_activated() {
    if ( class_exists( 'SitePress' ) ) { return true; } else { return false; }
  }
}

/**
 *
 * qTranslate plugin is activated
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_is_qtranslate_activated' ) ) {
  function sk_is_qtranslate_activated() {
    if ( function_exists( 'qtrans_getSortedLanguages' ) ) { return true; } else { return false; }
  }
}

/**
 *
 * Polylang plugin is activated
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_is_polylang_activated' ) ) {
  function sk_is_polylang_activated() {
    if ( class_exists( 'Polylang' ) ) { return true; } else { return false; }
  }
}

/**
 *
 * Get language defaults
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sk_language_defaults' ) ) {
  function sk_language_defaults() {

    $multilang = array();

    if( sk_is_wpml_activated() || sk_is_qtranslate_activated() || sk_is_polylang_activated() ) {

      if( sk_is_wpml_activated() ) {

        global $sitepress;
        $multilang['default']   = $sitepress->get_default_language();
        $multilang['current']   = $sitepress->get_current_language();
        $multilang['languages'] = $sitepress->get_active_languages();

      } else if( sk_is_polylang_activated() ) {

        global $polylang;
        $current    = pll_current_language();
        $default    = pll_default_language();
        $current    = ( empty( $current ) ) ? $default : $current;
        $poly_langs = $polylang->model->get_languages_list();
        $languages  = array();

        foreach ( $poly_langs as $p_lang ) {
          $languages[$p_lang->slug] = $p_lang->slug;
        }

        $multilang['default']   = $default;
        $multilang['current']   = $current;
        $multilang['languages'] = $languages;

      } else if( sk_is_qtranslate_activated() ) {

        global $q_config;
        $multilang['default']   = $q_config['default_language'];
        $multilang['current']   = $q_config['language'];
        $multilang['languages'] = array_flip( qtrans_getSortedLanguages() );

      }

    }

    $multilang = apply_filters( 'sk_language_defaults', $multilang );

    return ( ! empty( $multilang ) ) ? $multilang : false;

  }
}

/**
 *
 * Get locate for load textdomain
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if(!function_exists("sk_get_locale")){
  function sk_get_locale() {

    global $locale, $wp_local_package;

    if ( isset( $locale ) ) {
      return apply_filters( 'locale', $locale );
    }

    if ( isset( $wp_local_package ) ) {
      $locale = $wp_local_package;
    }

    if ( defined( 'WPLANG' ) ) {
      $locale = WPLANG;
    }

    if ( is_multisite() ) {

      if ( defined( 'WP_INSTALLING' ) || ( false === $ms_locale = get_option( 'WPLANG' ) ) ) {
        $ms_locale = get_site_option( 'WPLANG' );
      }

      if ( $ms_locale !== false ) {
        $locale = $ms_locale;
      }

    } else {

      $db_locale = get_option( 'WPLANG' );

      if ( $db_locale !== false ) {
        $locale = $db_locale;
      }

    }

    if ( empty( $locale ) ) {
      $locale = 'en_US';
    }

    return apply_filters( 'locale', $locale );

  }
}

/**
 *
 * Framework load text domain
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
load_textdomain( SK_TEXTDOMAIN, $skelet_path['dir'] . '/languages/'. sk_get_locale() .'.mo' );
