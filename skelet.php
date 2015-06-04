<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 * Skelet Framework
 * @version 1.0.0 
 * @author pressapps <support@pressapps.co>
 * 
 */
 global $skelet_paths,$skelet_path;
                   
if(! class_exists( 'Skelet_LoadConfig' ) ){
    class Skelet_LoadConfig{
            public static function instance(){
                    global $skelet_paths,$skelet_path;
                   
                    // active modules
                    defined( 'CS_ACTIVE_FRAMEWORK' )  or  define( 'CS_ACTIVE_FRAMEWORK',  true );
                    defined( 'CS_ACTIVE_METABOX'   )  or  define( 'CS_ACTIVE_METABOX',    true );
                    defined( 'CS_ACTIVE_SHORTCODE' )  or  define( 'CS_ACTIVE_SHORTCODE',  true );
                    defined( 'CS_ACTIVE_CUSTOMIZE' )  or  define( 'CS_ACTIVE_CUSTOMIZE',  true );
                   
                   foreach ($skelet_paths as $path) {

                         // ------------------------------------------------------------------------------------------------
                            include_once wp_normalize_path(dirname( __FILE__ ) .'/path.php');
                         // ------------------------------------------------------------------------------------------------
                        
                         $skelet_path = $path;
                        // helpers
                        cs_locate_template ( 'functions/deprecated.php'     ,$skelet_path);
                        cs_locate_template ( 'functions/helpers.php'        ,$skelet_path);
                        cs_locate_template ( 'functions/actions.php'        ,$skelet_path);
                        cs_locate_template ( 'functions/enqueue.php'        ,$skelet_path);
                        cs_locate_template ( 'functions/sanitize.php'       ,$skelet_path);
                        cs_locate_template ( 'functions/validate.php'       ,$skelet_path);
                        cs_locate_template ( 'functions/customize.php'       ,$skelet_path);

                        // classes
                        cs_locate_template ( 'classes/abstract.class.php'   ,$skelet_path);
                        cs_locate_template ( 'classes/options.class.php'    ,$skelet_path);
                        cs_locate_template ( 'classes/framework.class.php'  ,$skelet_path);
                        cs_locate_template ( 'classes/metabox.class.php'    ,$skelet_path);
                        cs_locate_template ( 'classes/shortcode.class.php'  ,$skelet_path);
                        cs_locate_template ( 'classes/customize.class.php'  ,$skelet_path);
                        cs_locate_template ( 'classes/widget.class.php'     ,$skelet_path);
                        cs_locate_template ( 'classes/taxonomy.class.php'   ,$skelet_path);
                      
                        // configs
                        cs_locate_template ( '../../includes/admin/options/framework.config.php'  ,$skelet_path);
                        cs_locate_template ( '../../includes/admin/options/metabox.config.php'    ,$skelet_path);
                        cs_locate_template ( '../../includes/admin/options/shortcode.config.php'  ,$skelet_path);
                        cs_locate_template ( '../../includes/admin/options/customize.config.php'  ,$skelet_path);
                        cs_locate_template ( '../../includes/admin/options/widget.config.php'  ,$skelet_path);
                        cs_locate_template ( '../../includes/admin/options/taxonomy.config.php'  ,$skelet_path);
                   
                    }

            }
    }


     add_action("init",array('Skelet_LoadConfig','instance'),10);

}