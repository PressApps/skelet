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

      private $templates = array();
      

      function __construct($options = array()){
        $options = $this->apply_prefix($options);
     
        $this->options = $options;

          foreach ($options as $tpl) {
              foreach ($tpl as $key => $value) {
                  foreach($value as $is => $cpt){
                      if($is == "if"){
                            $this->templates[$key] = array();
                            foreach(array_keys($cpt) as $c){
                               // array_push(  $this->templates[$key], call_user_func($c));
                                $this->templates[$key][$c] = $c;
                            }

                            foreach ($cpt as $k => $v ) {
                              $this->templates[$key][$k] = $v;
                                  foreach($v as $vv){
                                  //  array_push( $this->templates[$key], call_user_func_array($k,array($vv)));
                                    array_push($this->templates[$key][$k],$vv);
                                  }
                                  $this->templates[$key][$k] = array_unique($this->templates[$key][$k]);
                            }
                       }
                  }
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

        $arr_current_type = array();
        
        foreach ($this->post_types() as $type) {
         if(isset($wp_query->{$type}) && $wp_query->{$type}){
              array_push( $arr_current_type, $wp_query->{$type});
          }
        }

        $current_post_type = get_post_type($post);
         if(in_array($current_post_type,$this->config_post_types)){
             //return $this->get_template($current_post_type) ? : $template;
             if ( $this->get_template($current_post_type) ) {
                 return $this->get_template($current_post_type);
             } else {
                 return $template;
             }
         }

         foreach ($this->templates as $tpl => $page) {
            if(in_array(array_keys($page), $arr_current_type)){
                  //return $this->get_template($tpl) ?: $template;
                if ( $this->get_template($tpl) ) {
                    return $this->get_template($tpl);
                } else {
                    return $template;
                }
            }
         }
          
          return $template;
      }

      public function pre_get_posts($query){

       
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
                          global $skelet_template;
                          $skelet_template = $plugin_tpl_dir.$value["template"].".php";
                          // include template controller/filter
                          if(isset($value["controller"]) &&
                                file_exists($skelet_template)){
                              include_once($plugin_tpl_ctr.$value["controller"].".php");
                          }
                          return $skelet_template;
                       }
                    }
                }

            }
              return false;
      }
  }
}
?>