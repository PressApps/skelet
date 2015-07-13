<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Abstract Class
 * A helper class for action and filter hooks
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Abstract")){
	abstract class SkeletFramework_Abstract {

		public function __construct() {}

		public function addAction( $hook, $function_to_add, $priority = 30, $accepted_args = 1 ) {
		   
		   	 add_action( $hook, array( &$this, $function_to_add), $priority, $accepted_args );
		}

		public function addFilter( $tag, $function_to_add, $priority = 30, $accepted_args = 1 ) {
		   		
		   		 add_action( $tag, array( &$this, $function_to_add), $priority, $accepted_args );
		}

		public function post_types(){
		  	 
			  	$arr_post_types = array(
			  			 'is_single',
						 'is_preview',
						 'is_page',
						 'is_archive',
						 'is_date',
						 'is_year',
						 'is_month',
						 'is_day',
						 'is_time',
						 'is_author',
						 'is_category',
						 'is_tag',
						 'is_tax',
						 'is_search',
						 'is_feed',
						 'is_comment_feed',
						 'is_trackback',
						 'is_home',
						 'is_404',
						 'is_comments_popup',
						 'is_paged',
						 'is_admin',
						 'is_attachment',
						 'is_singular',
						 'is_robots',
						 'is_posts_page',
						 'is_post_type_archive');
			  	return $arr_post_types;
		}

		public function get_dummy_post_data($args){
			    
			    return array_merge(array(
			        'ID'                    => 0,
			        'post_status'           => 'publish',
			        'post_author'           => 0,
			        'post_parent'           => 0,
			        'post_type'             => 'page',
			        'post_date'             => 0,
			        'post_date_gmt'         => 0,
			        'post_modified'         => 0,
			        'post_modified_gmt'     => 0,
			        'post_content'          => '',
			        'post_title'            => '',
			        'post_excerpt'          => '',
			        'post_content_filtered' => '',
			        'post_mime_type'        => '',
			        'post_password'         => '',
			        'post_name'             => '',
			        'guid'                  => '',
			        'menu_order'            => 0,
			        'pinged'                => '',
			        'to_ping'               => '',
			        'ping_status'           => '',
			        'comment_status'        => 'closed',
			        'comment_count'         => 0,
			        'filter'                => 'raw',

			        

			    ),$args);
		}

		public function apply_prefix($options = array()){
			global $skelet_path;

				$arr_options = array();
				foreach ($options as $option) {

					$arr_option = array();
					foreach ($option as $key => $value) {
						$new_prefix = "";
						
						if($key == "name" || $key == "id"){
						
							$new_prefix = $skelet_path["prefix"].'_'.$value;
						
						}elseif($key == "settings" || $key == "fields" || $key == "sections" ||  $key == "shortcodes"){
						
							$arr_settings = array();
							foreach ($value as $key_settings => $val_settings) {
						
									$arr_sets = array();
								    foreach($val_settings as $vkey => $vval){
								    	
								    	if($vkey == "fields"){
								    	
								    		 $arr_vvfields = array();
								    		 foreach ($vval as $vvkey => $vvval) {
								    		   
								    		     if(isset($vvval["id"])){
								    		     	
								    		     	$vvval["id"] = $skelet_path["prefix"].'_'.$vvval["id"];
								    		     }
								    		   
								    		      array_push($arr_vvfields,$vvval);
								    		 }
								    		 $arr_sets[$vkey] = $arr_vvfields;
								    	
								    	}else if($vkey == "name" || $vkey == "id"){
											$arr_sets[$vkey] = $skelet_path["prefix"].'_'.$vval;
										}else{
											$arr_sets[$vkey] = $vval;
										}
									
									}

								array_push($arr_settings,$arr_sets);
							}
							if(!empty($arr_settings)){
								$new_prefix = $arr_settings;
							}
						}else{
							$new_prefix = $value;
						}
						$arr_option[$key] = $new_prefix;
					}
					array_push($arr_options, $arr_option);
				}
				
				return $arr_options;
		}

	}
}