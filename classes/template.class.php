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

      private $config_post_types = array();

      function __construct($options = array()){
        
        $this->options = $options;

        foreach ($options as $tpl) {
          foreach ($tpl as $key => $value) {
             array_push($this->config_post_types, $key);
          }
        }

        add_filter("template_include",array($this,"template_include"));
        add_filter('pre_get_posts',   array($this,'pre_get_posts'   ));
      }
      public static function instance($options = array()){
          
          if(!empty($options)){
           
            new self($options);
          }
      }

      public function template_include($template){
        global $wp_query, $post;

        foreach ($this->post_types() as $type) {
         if(isset($wp_query->{$type}) && $wp_query->{$type}){
            // var_dump($type);
          }
        }

        $current_post_type = get_post_type($post);

         if(in_array($current_post_type,$this->config_post_types)){
              return $this->get_template($current_post_type) ?: $template;
         }
         // var_dump( $this->options );
         // var_dump( $wp_query );
          
          return $template;
      }

      public function pre_get_posts($query){

         //var_dump($query);

          return $query;
      }

      public function get_template($post_type){
          $path = str_replace("includes\skelet\classes/","",plugin_dir_path( __FILE__ ));
          $plugin_tpl_dir = wp_normalize_path($path."/template/");
          $plugin_tpl_ctr = wp_normalize_path($path."/includes/controllers/");

           foreach ($this->options as $tpl) {
               
                foreach ($tpl as $key => $value) {
                    if($key == $post_type){
                       if(isset($value["template"]) && file_exists($plugin_tpl_dir.$value["template"].".php")){
                          // include template controller/filter
                          if(isset($value["controller"]) &&
                                file_exists($plugin_tpl_ctr.$value["controller"].".php")){
                              include_once($plugin_tpl_ctr.$value["controller"].".php");
                          }
                          // return template
                          return $plugin_tpl_dir.$value["template"].".php";
                       }
                    }
                }

            }
              return false;
      }
  }
}
?>