<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * PressApps Class
 * A helper class for general options pages and routing
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
 class PressApps{

		function __construct(){
			if(is_admin()){
			 	//add_action( 'admin_header', array("PressApps","load_header") );
			 	add_action( 'admin_footer', array("PressApps","load_footer") );

			}
		}

		public function load_header(){
				wp_enqueue_style( 'pressapps', plugin_dir_url(__FILE__ )."assets/css/pressapps.css" );
				wp_enqueue_script( 'pressapps', plugin_dir_url(__FILE__ )."assets/js/pressapps.js"  , array(), '1.0.0', true );
		} 

		public function load_footer(){
				wp_enqueue_style( 'pressapps', plugin_dir_url(__FILE__ )."assets/css/pressapps.css" );
				wp_enqueue_script( 'pressapps', plugin_dir_url(__FILE__ )."assets/js/pressapps.js"  , array(), '1.0.0', true );
		} 		

 		public static function route($page = ''){
 			   if($page == "pa-menu-support"){
 			   		 include_once wp_normalize_path(plugin_dir_path(__FILE__ ) .'/template/support.php');
 			   }else if($page == "pa-menu-product"){
 			   		 include_once wp_normalize_path(plugin_dir_path(__FILE__ ) .'/template/products.php');
 			   }else if($page == "pa-menu-services"){
 			   		 include_once wp_normalize_path(plugin_dir_path(__FILE__ ) .'/template/services.php');
 			   }
 		}

 }


?>