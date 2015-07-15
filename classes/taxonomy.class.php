<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Taxonomy Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Taxonomy")){
  class SkeletFramework_Taxonomy extends SkeletFramework_Abstract{

    /**
     *
     * taxonomy options
     * @access public
     * @var array
     *
     */
    public static $options = array();

    /**
     *
     * instance
     * @access private
     * @var class
     *
     */
    private static $instance = null;

    /**
     *
     * plugin prefix
     * @access private
     * @var prefix
     *
     */
    private static $prefix = null;

    // run taxonomy construct
    public function __construct( $options ){

      self::$options = apply_filters( 'sk_taxonomy_options', $options );
      $this->addAction("admin_init",'load_taxonomy');
    
    }

    public function load_taxonomy(){

        if( ! empty( self::$options ) ) {
        $taxonomy_options = self::$options;
        foreach($taxonomy_options as $key => $val){
          $taxonomy = $val["taxonomy"]; 
          if(isset($_REQUEST["taxonomy"]) && $taxonomy == $_REQUEST["taxonomy"]){

              add_action( 'edited_'.$taxonomy, array('SkeletFramework_Taxonomy','edited_taxonomy_box') );
              add_action( $taxonomy.'_add_form_fields', array('SkeletFramework_Taxonomy','add_taxonomy_box'));
              add_action( 'created_'.$taxonomy, array('SkeletFramework_Taxonomy','save_extra_fields'));
              add_action( $taxonomy.'_edit_form_fields', array('SkeletFramework_Taxonomy','edit_taxonomy_box') , 10, 2 );
            
             
          }
         
        }
      }
      
    }

    // instance
    public static function instance( $options = array() ){
      if ( is_null( self::$instance ) && SK_ACTIVE_TAXONOMY ) {
        self::$instance = new self( $options );
      }
      return self::$instance;
    }

    public static function edit_taxonomy_box($post, $aa){

      if(empty(self::$options)) 
          return false;

    
      $display_elem = "";

      foreach(self::$options as $key => $val){
        if($val["taxonomy"] == $post->taxonomy){
          $term_id = $post->term_id;
          $unique = "_skelet_".$val["taxonomy"]."_".$term_id;
          $meta_value = get_option( $unique );
          foreach ( $val['fields'] as $field_key => $field ) {

                    $default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
                    $elem_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
                    $elem_value = ( is_array( $meta_value ) && isset( $meta_value[$elem_id] ) ) ? $meta_value[$elem_id] : $default;
                    $display_elem .= sk_add_element( $field, $elem_value, $unique );

          }
        }
      }
     echo $display_elem;

    }

    public static function edited_taxonomy_box($post, $taxonomy){
       
                $taxonomy = $_POST['taxonomy'];
                  $term_id = $_POST['tag_ID'];
            $skelet_fields = "_skelet_".$taxonomy.'_'.$term_id;
            
            update_option($skelet_fields,$_POST[$skelet_fields]); 
    }

    public static function add_taxonomy_box($taxonomy){
        global $skelet_path;
       

         if(empty(self::$options)) 
          return false;

          foreach(self::$options as $key => $val){
            if($val["taxonomy"] == $taxonomy){
              foreach ( $val['fields'] as $field_key => $field ) {
                        $unique = "_skelet_".$val["taxonomy"];
                        $default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
                        $elem_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
                        $elem_value =  $default;
                        echo sk_add_element( $field, $elem_value, $unique );

              }
            }
          }
      
    }

    public static function save_extra_fields($tag_ID){
          global $skelet_path;

            $taxonomy = $_POST['taxonomy'];
             $term_id = $_POST['term_id'];
            $skelet_fields = "_skelet_".$taxonomy;
            $skelet_option = "_skelet_".$taxonomy.'_'.$tag_ID;

            update_option($skelet_option,$_POST[$skelet_fields]); 
     
    }


  }

}