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
 
// Custom Post/Taxonomy/Tags Type should be included on widgets init action.
include_once wp_normalize_path(plugin_dir_path(__FILE__ ).'/classes/cpt.class.php');
include_once wp_normalize_path(dirname( __DIR__ ) .'/admin/options/cpt.config.php');
 

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
                    defined( 'SK_ACTIVE_CPT'       )  or  define( 'SK_ACTIVE_CPT',        true );
                    defined( 'SK_ACTIVE_TEMPLATE'  )  or  define( 'SK_ACTIVE_TEMPLATE',   true );
                    
                   
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
                        sk_locate_template ( 'classes/template.class.php'   ,$skelet_path);
                      
                        // configs
                        sk_locate_template ( '../../includes/admin/options/framework.config.php'  ,$skelet_path);
                        sk_locate_template ( '../../includes/admin/options/metabox.config.php'    ,$skelet_path);
                        sk_locate_template ( '../../includes/admin/options/shortcode.config.php'  ,$skelet_path);
                        sk_locate_template ( '../../includes/admin/options/customize.config.php'  ,$skelet_path);
                        sk_locate_template ( '../../includes/admin/options/taxonomy.config.php'  ,$skelet_path);
                        sk_locate_template ( '../../includes/admin/options/template.config.php'  ,$skelet_path);


                       
                   
                    }

            }
    }


     add_action("init",array('Skelet_LoadConfig','instance'),10);

}

if(!class_exists("Skelet_PressApps_Menu")){

  class Skelet_PressApps_Menu{

    public static function pa_main_menu(){
        global $skelet_paths, $submenu;
      

        call_user_func("add_submenu_page", SK_PARENT_MENU, "Support", "Support", "manage_options", "pressapps-support", array("Skelet_PressApps_Menu","get_pa_general_pages"));
        call_user_func("add_submenu_page", SK_PARENT_MENU, "Products", "Products", "manage_options", "pressapps-product", array("Skelet_PressApps_Menu","get_pa_general_pages"));
        call_user_func("add_submenu_page", SK_PARENT_MENU, "Services", "Services", "manage_options", "pressapps-services", array("Skelet_PressApps_Menu","get_pa_general_pages"));
                          
   }
    public static function get_pa_general_pages(){
      if(!class_exists("PressApps"))
      {
            include_once wp_normalize_path(plugin_dir_path(__FILE__ ) .'/pressapps/pressapps.class.php');
            $pa = new PressApps;
            $pa::route(isset($_GET["page"])?$_GET["page"]:"");
      }
    }

  
  }
     add_action("admin_menu",array("Skelet_PressApps_Menu","pa_main_menu"),999999);


}

