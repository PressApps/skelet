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

		public function get($option_id = ''){
				if(empty($this->prefix)){
						return 'Prefix not set. <em>new Skelet("your_prefix");</em>'; 
				}
				$pao = get_option($this->prefix.'_options', array() );
				$option_id = $this->prefix.'_'.$option_id;
				if( strlen( $option_id ) ) {
				    if( isset( $pao[ $option_id ] ) ) {
				      return $pao[ $option_id ];
				    } 
				} else {
				    return $pao;
				}
		}

	}
}

/*$p = new Skelet('pabp');
echo $p->get("background_1");
die(1);*/