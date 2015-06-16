<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Template Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Template")){
  class SkeletFramework_Template {
  		function __construct(){
  			add_filter("template_include",array($this,"template_include"));
  			add_filter('pre_get_posts',	  array($this,'pre_get_posts'   ));
  		}
  		public static function instance($options = array()){
  				new self();
  				//var_dump($options);
  		}

  		public function template_include($template){
  			global $wp_query, $post;

  				
  				return $template;
  		}

  		public function pre_get_posts($query){

  				//var_dump($query);

  				return $query;
  		}
  }
}
?>