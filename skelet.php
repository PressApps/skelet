<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 * Skelet Framework
 * @version 1.0.0 
 * @author pressapps <support@pressapps.co>
 * 
 */
 global $skelet_paths,$skelet_path;

// Widget should be included on widgets init action.
include_once wp_normalize_path(plugin_dir_path(__FILE__ ).'/classes/widget.class.php');
include_once wp_normalize_path(dirname( __DIR__ ) .'/admin/options/widget.config.php');
 

if(! class_exists( 'Skelet_LoadConfig' ) ){
    class Skelet_LoadConfig{
            public static function instance(){
                    global $skelet_paths,$skelet_path;
                   
                    // active modules
                    defined( 'SK_ACTIVE_FRAMEWORK' )  or  define( 'SK_ACTIVE_FRAMEWORK',  true );
                    defined( 'SK_ACTIVE_METABOX'   )  or  define( 'SK_ACTIVE_METABOX',    true );
                    defined( 'SK_ACTIVE_SHORTCODE' )  or  define( 'SK_ACTIVE_SHORTCODE',  true );
                    defined( 'SK_ACTIVE_CUSTOMIZE' )  or  define( 'SK_ACTIVE_CUSTOMIZE',  true );
                    defined( 'SK_ACTIVE_WIDGET'    )  or  define( 'SK_ACTIVE_WIDGET',     true );
                    defined( 'SK_ACTIVE_TAXONOMY'  )  or  define( 'SK_ACTIVE_TAXONOMY',   true );
                   
                   foreach ($skelet_paths as $path) {

                         // ------------------------------------------------------------------------------------------------
                            include_once wp_normalize_path(dirname( __FILE__ ) .'/path.php');
                         // ------------------------------------------------------------------------------------------------
                        
                         $skelet_path = $path;
                        // helpers
                        sk_locate_template ( 'functions/deprecated.php'     ,$skelet_path);
                        sk_locate_template ( 'functions/helpers.php'        ,$skelet_path);
                        sk_locate_template ( 'functions/actions.php'        ,$skelet_path);
                        sk_locate_template ( 'functions/enqueue.php'        ,$skelet_path);
                        sk_locate_template ( 'functions/sanitize.php'       ,$skelet_path);
                        sk_locate_template ( 'functions/validate.php'       ,$skelet_path);
                        sk_locate_template ( 'functions/customize.php'       ,$skelet_path);

                        // classes
                        sk_locate_template ( 'classes/abstract.class.php'   ,$skelet_path);
                        sk_locate_template ( 'classes/options.class.php'    ,$skelet_path);
                        sk_locate_template ( 'classes/framework.class.php'  ,$skelet_path);
                        sk_locate_template ( 'classes/metabox.class.php'    ,$skelet_path);
                        sk_locate_template ( 'classes/shortcode.class.php'  ,$skelet_path);
                        sk_locate_template ( 'classes/customize.class.php'  ,$skelet_path);
                        sk_locate_template ( 'classes/taxonomy.class.php'   ,$skelet_path);
                      
                        // configs
                        sk_locate_template ( '../../includes/admin/options/framework.config.php'  ,$skelet_path);
                        sk_locate_template ( '../../includes/admin/options/metabox.config.php'    ,$skelet_path);
                        sk_locate_template ( '../../includes/admin/options/shortcode.config.php'  ,$skelet_path);
                        sk_locate_template ( '../../includes/admin/options/customize.config.php'  ,$skelet_path);
                        sk_locate_template ( '../../includes/admin/options/taxonomy.config.php'  ,$skelet_path);
                   
                    }

            }
    }


     add_action("init",array('Skelet_LoadConfig','instance'),10);

}