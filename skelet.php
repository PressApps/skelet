<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 * Skelet Framework
 * @version 1.0.0 
 * @author pressapps <support@pressapps.co>
 * 
 */
// ------------------------------------------------------------------------------------------------
include_once dirname( __FILE__ ) .'/path.php';
// ------------------------------------------------------------------------------------------------

if( ! function_exists( 'skelet_framework_init' ) && ! class_exists( 'CSFramework' ) ) {
  function skelet_framework_init() {

    // active modules
    defined( 'CS_ACTIVE_FRAMEWORK' )  or  define( 'CS_ACTIVE_FRAMEWORK',  true );
    defined( 'CS_ACTIVE_METABOX'   )  or  define( 'CS_ACTIVE_METABOX',    true );
    defined( 'CS_ACTIVE_SHORTCODE' )  or  define( 'CS_ACTIVE_SHORTCODE',  true );
    defined( 'CS_ACTIVE_CUSTOMIZE' )  or  define( 'CS_ACTIVE_CUSTOMIZE',  true );

    // helpers
    cs_locate_template ( 'functions/deprecated.php'     );
    cs_locate_template ( 'functions/helpers.php'        );
    cs_locate_template ( 'functions/actions.php'        );
    cs_locate_template ( 'functions/enqueue.php'        );
    cs_locate_template ( 'functions/sanitize.php'       );
    cs_locate_template ( 'functions/validate.php'       );

    // classes
    cs_locate_template ( 'classes/abstract.class.php'   );
    cs_locate_template ( 'classes/options.class.php'    );
    cs_locate_template ( 'classes/framework.class.php'  );
    cs_locate_template ( 'classes/metabox.class.php'    );
    cs_locate_template ( 'classes/shortcode.class.php'  );
    cs_locate_template ( 'classes/customize.class.php'  );

    // configs
    cs_locate_template ( '../../includes/admin/options/framework.config.php'  );
    cs_locate_template ( '../../includes/admin/options/metabox.config.php'    );
    cs_locate_template ( '../../includes/admin/options/shortcode.config.php'  );
    cs_locate_template ( '../../includes/admin/options/customize.config.php'  );
    
  }
  add_action( 'init', 'skelet_framework_init', 10 );
}
