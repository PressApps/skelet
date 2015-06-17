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

	}
}