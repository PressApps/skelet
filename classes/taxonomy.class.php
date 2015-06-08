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
    public $options = array();

    /**
     *
     * instance
     * @access private
     * @var class
     *
     */
    private static $instance = null;

    // run taxonomy construct
    public function __construct( $options ){

      $this->options = apply_filters( 'sk_taxonomy_options', $options );
     
     if( ! empty( $this->options ) ) {
        $taxonomy_options = $this->options;
        foreach($taxonomy_options as $key => $val){
          $taxonomy = $val["taxonomy"]; 
          

          //$this->addAction( $taxonomy.'_edit_form_fields', 'edit_taxonomy_box',90,2 );
          $this->addAction( 'edit_'.$taxonomy.'_form ', 'edit_taxonomy_box',90,2 );
          $this->addAction( 'edited_'.$taxonomy, 'edited_taxonomy_box' , 10, 2);

          $this->addAction( $taxonomy.'_add_form_fields', 'add_taxonomy_box',10,2);
          $this->addAction( 'created_'.$taxonomy, 'save_extra_fields' , 10, 2);
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

    public function edit_taxonomy_box($post, $aa){

      if(empty($this->options)) 
          return false;

      foreach($this->options as $key => $val){
        if($val["taxonomy"] == $post->taxonomy){
          $term_id = $post->term_id;
          $unique = "_skelet_".$val["taxonomy"]."_".$term_id;
          $meta_value = get_option( $unique );
          foreach ( $val['fields'] as $field_key => $field ) {

                    $default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
                    $elem_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
                    $elem_value = ( is_array( $meta_value ) && isset( $meta_value[$elem_id] ) ) ? $meta_value[$elem_id] : $default;
                    echo sk_add_element( $field, $elem_value, $unique );

          }
        }
      }
        
    }

    public function edited_taxonomy_box($post, $taxonomy){
        
       
                $taxonomy = $_POST['taxonomy'];
                  $term_id = $_POST['tag_ID'];
            $skelet_fields = "_skelet_".$taxonomy.'_'.$term_id;
            
            update_option($skelet_fields,$_POST[$skelet_fields]); 
    }

    public function add_taxonomy_box($taxonomy){
        

         if(empty($this->options)) 
          return false;

          foreach($this->options as $key => $val){
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

    public function save_extra_fields($tag_ID){
      

            $taxonomy = $_POST['taxonomy'];
             $term_id = $_POST['term_id'];
            $skelet_fields = "_skelet_".$taxonomy;
            $skelet_option = "_skelet_".$taxonomy.'_'.$tag_ID;

            update_option($skelet_option,$_POST[$skelet_fields]); 
     
    }


  }

}