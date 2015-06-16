<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * CPT Class
 * A helper class for action and filter hooks
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_CPT")){
	class SkeletFramework_CPT {

		protected $options = array();

		function __construct($options = array()){
			$this->options = $options;
			add_action("init", array($this,"register"));
		}
		public static function instance($options = array()){
					new self($options);
		}

		public function register(){
				$cpts = $this->options;

				foreach ($cpts as &$cpt) {
					if(isset($cpt["cpt_slug"]) && !empty($cpt["cpt_slug"]) &&
						isset($cpt["cpt"]) && !empty($cpt["cpt"])){

						$this->post_type($cpt);

						if(isset($cpt["cpt_parent_slug"]) && !empty($cpt["cpt_parent_slug"]) &&
							isset($cpt["cpt_taxonomy"]) && !empty($cpt["cpt_taxonomy"])){
							$cpt["cpt_slug"] = $cpt["cpt_parent_slug"];
							$this->taxonomy($cpt);
						}

						if(isset($cpt["cpt_parent_slug"]) && !empty($cpt["cpt_parent_slug"]) &&
							isset($cpt["cpt_tags"]) && !empty($cpt["cpt_tags"])){
							$cpt["cpt_slug"] = $cpt["cpt_parent_slug"];
							$this->tags($cpt);
						}
					}else if(isset($cpt["cpt_parent_slug"]) && !empty($cpt["cpt_parent_slug"]) &&
							isset($cpt["cpt_taxonomy"]) && !empty($cpt["cpt_taxonomy"])){
							$cpt["cpt_slug"] = $cpt["cpt_parent_slug"];
							$this->taxonomy($cpt);

					}else if(isset($cpt["cpt_parent_slug"]) && !empty($cpt["cpt_parent_slug"]) &&
							isset($cpt["cpt_tags"]) && !empty($cpt["cpt_tags"])){
							$cpt["cpt_slug"] = $cpt["cpt_parent_slug"];
							$this->tags($cpt);
					}
					
				}


		}

		public function post_type($cpt = array()){
			global $wp_rewrite;
			register_post_type( $cpt["cpt_slug"] , $cpt["cpt"] );

		}

		public function taxonomy($cpt = array()){
			global $wp_rewrite;
			register_taxonomy( $cpt["cpt_parent_slug"].'_category', $cpt["cpt_parent_slug"], $cpt["cpt_taxonomy"]);
	
		}

		public function tags($cpt = array()){
			global $wp_rewrite;
			register_taxonomy( $cpt["cpt_parent_slug"].'_tags', $cpt["cpt_parent_slug"], $cpt["cpt_tags"]);
	
		}


	}

}