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
  class SkeletFramework_Template extends SkeletFramework_Abstract{

      private $options = array();

  		function __construct($options = array()){
        
        $this->options = $options;
  			
        add_filter("template_include",array($this,"template_include"));
  			add_filter('pre_get_posts',	  array($this,'pre_get_posts'   ));
  		}
  		public static function instance($options = array()){
  				
          if(!empty($options)){
           
            new self($options);
    			}
  		}

  		public function template_include($template){
  			global $wp_query;

        foreach ($this->post_types() as $type) {
         if(isset($wp_query->{$type}) && $wp_query->{$type}){
            //  var_dump($type);
          }
        }
      
          //var_dump( $this->options);
  				
  				return $template;
  		}

  		public function pre_get_posts($query){

  				//var_dump($query);

  				return $query;
  		}
  }
}
?>