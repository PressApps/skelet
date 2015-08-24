<?php 

/**
 *
 * Skelet Framework Widget Class
 *
 */

abstract class SkeletWidget_Factory extends WP_Widget {
    /***********************************************************
    **  Abstract Functions
    ***********************************************************/
    /** Return the widget template filter */
    abstract function getTemplate();
    /** Return the pugin id, ie: my-plugin */
    abstract function getID();
    /** Return the pugin name, ie: My Plugin */
    abstract function getName();

    /** Return the form content to show in admin dashboard */
    abstract function getForm($instance);
    /** Return the real widget content */
    abstract function getContent($instance,$args = null);
    /** Return the plugin default options, a name=>value array, ie: array('title'=>'My Plugin Title') */
    abstract function getDefaults();

    /** Title field to retrieve from options */
    function getTitleField(){ return 'title'; }    

    /***********************************************************
    **  Static Functions
    ***********************************************************/

    /** Register the Widget using Wordpress Actions */
    static function init($classname=null,$class_id = null){
          

        if (is_null($classname)){
            $classname=  get_called_class();
        }

        add_action( 'widgets_init', create_function('', 'return register_widget("'.$classname.'");') );
    }

    /***********************************************************
    **  Wordpress Hooks
    ***********************************************************/

    /** Class constructor */
    function SkeletWidget_Factory(){
        $widget_ops = $this->getDefaults();
        $control_ops = array('width' => 400);
        parent::__construct($this->getID(), $this->getName(), $widget_ops, $control_ops);
    }

    /** Admin Dashboard Form */
  function form($instance) {
         $instance = $this->parse_args( $instance );
        echo $this->getForm($instance);
  }

    /** Widget Code */
  function widget($args, $instance) {
        extract($args);
        $tfld = $this->getTitleField();        
        echo $before_widget;
       

        $tpl = $this->getTemplate();
       
        if(!empty( $tpl )){
            if(isset($tpl["walker_class"])){
                  
                  if(isset($tpl["walker_class"]["path"]) && file_exists($tpl["walker_class"]["path"])){
                      include_once($tpl["walker_class"]["path"]);
                  }
                  
                  if(isset($tpl["walker_class"]["name"]) && class_exists($tpl["walker_class"]["name"])){
                        $tpl["id"] = str_replace('-','_',$this->id);
                        $tpl["id_base"] = str_replace('-','_',$this->id_base);
                    new $tpl["walker_class"]["name"]($tpl,$instance);
                  }else{
                      echo "Invalid class name or Walker class not declared.";
                  }
              
            }
        }
       echo $after_widget; 
    }
    /** Called By Wordpress when saving settings */
  function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $new_instance = $this->parse_args( $new_instance );
       
        foreach($new_instance as $k=>$v){
            if(!is_array($v)){
              $instance[$k] = strip_tags($v);
            }else{
              $instance[$k] = $v;
            }
         /*  
            if (empty($instance[$k]) && !empty($def[$k])){
                $instance[$k] = $def[$k];
            }*/
        }
        
        foreach($instance as $k => $v){
          $instance[$k] = $new_instance[$k];
        }
         return $instance;
  }


    /***********************************************************
    **  Utility Functions
    ***********************************************************/

    /** Return an input type=text field ready for admin dashboard */
    function getForm_input($instance, $name, $title=null){
        if (is_null($title)) { $title=ucwords($name); }
        return  '<label for="'.$this->get_field_id($name).'">'.__($title).'</label>'.
                '<input class="widefat" type="text" id="'.$this->get_field_id($name).'" name="'.$this->get_field_name($name).'" value="'.esc_attr($instance[$name]).'"/>';
    }    

    /** Return a textarea ready for admin dashboard */
    function getForm_textarea($instance, $name, $title=null){
        if (is_null($title)) { $title=ucwords($name); }
        return  '<label for="'.$this->get_field_id($name).'">'.__($title).'</label>'.
                '<textarea class="widefat" id="'.$this->get_field_id($name).'" name="'.$this->get_field_name($name).'">'.esc_attr($instance[$name]).'</textarea>';
    }

    /** Simplify wp_parse_args */
    function parse_args($instance){
        return wp_parse_args( (array)$instance, $this->getDefaults());
    }
}


class SkeletFramework_Widget{
      public static function instance($options = array()){


          foreach($options as $option){
              $widget_name = isset($option["title"])?$option["title"]:"";
              $widget_id = isset($option["name"])?str_replace(" ","",str_replace("-","",ucfirst($option["name"]))):"";
              $widget_description = !empty($option["description"])?$option["description"]:"";
              $arr_default_settings = array();
              if(!isset($option["settings"]))
                 $option["settings"] = array();
               
              foreach ($option["settings"] as $key) {
               
                if(isset($key["default"])){
                  $arr_default_settings[$key["name"]] = $key["default"];
                }
              }

               $serialize = serialize($arr_default_settings);
               $serialize_settings = !empty($option["settings"])?serialize($option["settings"]):"";
               $serialize_template = !empty($option["frontend_tpl"])?serialize($option["frontend_tpl"]):"";
               
                eval("
                  if(!class_exists('SkeletFramework_$widget_id')){
                      class SkeletFramework_$widget_id extends SkeletWidget_Factory {
                            function getTemplate(){  return unserialize('$serialize_template');}
                            function getID(){ return '$widget_id'; }
                            function getName(){ return '$widget_name';}
                            function getForm(\$instance){
                                 
                                 \$instance = wp_parse_args( \$instance, unserialize('$serialize'));

                                 \$fields = unserialize('$serialize_settings');
                               
                                 foreach(\$fields as &\$field ){
                                      if(!empty(\$field)){
                                              
                                                
                                                if(isset(\$field['control']['type'])  && \$field['control']['type'] == 'switcher' ){
                                                  \$field['switch_default'] = isset(\$field['default'])?\$field['default']:'';
                                                  \$field['default'] = '';
                                                }

                                              
                                                \$value = isset(\$instance[\$field['name']])? \$instance[\$field['name']] :null;
                                                \$name = isset(\$field['name'])?\$this->get_field_name(\$field['name']):'';

                                                \$field = array_filter(array(
                                                  'id'    => \$name,
                                                  'name'  => \$name,
                                                  'title' => isset(\$field['control']['label'])?\$field['control']['label']:null,
                                                  'info'  => isset(\$field['info'])?\$field['info']:null,
                                                  'default' => !empty(\$field['default'])?\$field['default']:null,
                                                  'type' => isset(\$field['control']['type'])?\$field['control']['type']:null,
                                                  'desc' => isset(\$field['control']['info'])?\$field['control']['info']:null,
                                                  'options' => isset(\$field['control']['options'])?\$field['control']['options']:null
                                                ));
                                                
                                                if(isset(\$field['control']['type'])  && \$field['control']['type'] == 'switcher' ){
                                                 array_push(\$field, array('default' => isset(\$field['switch_default'])?true:false));
                                                }

                                                if(!empty(\$field)){
                                                  echo   sk_add_element( \$field, \$value );
                                                }
                                               

                                      }
                                  }
                            }

                            function getContent(\$instance,\$args = null){

                                
                              
                                return '';
                            }    

                            function getDefaults(){
                                return array(
                                    'title' => __(\$this->getName()),
                                    'description' => __('$widget_description')
                                );
                            }
                      }
                          \$widget = new SkeletFramework_$widget_id();
                          \$widget::init();
                  } ");
            
          }
      } 
}




