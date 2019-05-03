<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Skelet Class
 * A helper class for retrieving option values
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("Skelet")){
	class Skelet {

		 /**
	     *
	     * prefix
	     * @access public
	     * @var string
	     *
	     */
	    public $prefix = '';


		function __construct($prefix = ''){

				if(!empty($prefix)){
					   $this->prefix = $prefix;
				}
			  	
		}

		/**
		 * Get options values
		 * @param  string $option_id 	accepts option name/id
		 * @return array           
		 */
		public function get($option_id = ''){
				
				if(empty($this->prefix)){
						return 'Prefix not set. <em>new Skelet("your_prefix_name");</em>'; 
				}
				
				$pao = get_option($this->prefix.'_options');
				$option_id = $this->prefix.'_'.$option_id;

				if( strlen( $option_id ) && isset( $pao[ $option_id ] ) ) {
					$pao_value = $pao[ $option_id ];
				} else {
					$pao_value = null;
				}

				return $pao_value;
		}

		/**
		 * Retrieve skelet meta values
		 * @param  int $post_id   the current page/post id
		 * @param  string $meta_id 
		 * @param  string $option_id metabox field id/name
		 * @return boolean/array     returns meta data array or boolean false if no data found.
		 */
		public function get_meta($post_id, $meta_id = '', $option_id = ''){

			if(empty($this->prefix)){
				return 'Prefix not set. <em>new Skelet("your_prefix_name");</em>'; 
			}

			if(isset($post_id) && $post_id > 0 ){

				if(!empty( $meta_id ) && empty( $option_id )){
					$meta_data = get_post_meta( $post_id, $this->prefix.'_'.$meta_id, true );
				}

				if(!empty( $meta_id ) && !empty( $option_id )){
					$meta_data = get_post_meta( $post_id, $this->prefix.'_'.$meta_id, true );
					
					if( isset($meta_data[$this->prefix.'_'.$option_id]) ){
						return $meta_data[$this->prefix.'_'.$option_id];
					}
					return false;
				}

				if(empty($meta_data)){
					$meta_data = get_post_meta( $post_id );
				}

				return $meta_data;
			}

			return false;

		}

		/**
		 * Retrieve skelet customize option
		 * @param  string $option_id 
		 * @return boolean/array     returns meta data array or boolean false if no data found.
		 */
		public function get_customize_option( $option_id = ''){
			

			if(empty($this->prefix)){
				return 'Prefix not set. <em>new Skelet("your_prefix_name");</em>'; 
			}

			
				if( empty( $option_id )){
					
					$meta_data = get_option( $this->prefix.'_customize' );

					return $meta_data;
				}

				if(!empty( $option_id )){
					$meta_data = get_option( $this->prefix.'_customize' );
					
					if( isset($meta_data[$this->prefix.'_'.$this->prefix.'_'.$option_id]) ){
						return $meta_data[$this->prefix.'_'.$this->prefix.'_'.$option_id];
					}
				}

			
			return false;

		}

		/**
		 * Get Taxonomy fields values
		 * @param  string  $taxonomy 
		 * @param  integer $tag_id   
		 * @param  string  $field_id 
		 * @return array|bool            
		 */
		public function get_taxonomy($taxonomy = '', $tag_id = 0, $field_id = ''){
				if(empty($this->prefix)){
					return 'Prefix not set. <em>new Skelet("your_prefix_name");</em>'; 
				}
				if( !empty( $taxonomy ) && $tag_id > 0 && empty($field_id)){
					
					$skelet_fields = "_skelet_".$taxonomy.'_'.$tag_id;
            
					$meta_data = get_option( $skelet_fields );

					return $meta_data;
				}

				if( !empty( $taxonomy ) && $tag_id > 0 && !empty($field_id)){
				
					$skelet_fields = "_skelet_".$taxonomy.'_'.$tag_id;
            
					$meta_data = get_option( $skelet_fields );

					if( isset($meta_data[$this->prefix.'_'.$field_id]) ){
						return $meta_data[$this->prefix.'_'.$field_id];
					}
				}

			
			return false;

		}


	}
}

