## Skelet Framework

### Introduction

Skelet is a framework for creating WordPress plugins, it eases the creation of advanced option pages, shortcodes and WordPress editor buttons.

######Contents

* [Installation](https://bitbucket.org/guerillaio/plugin-boilerplate/overview#installation)

### Installation
------------

Let's assume that you want to use Skelet Framework in the plugin-boilerplate.

* Download & extract a copy of [Plugin Boilerplate](http://wppb.me) in `wp-content/plugins/` & pull a copy of [Skelet Framework](https://bitbucket.org/guerillaio/skelet/src/16c9cabdaef3281adaa33c8440ecb7df206963da/?at=develop) from the repository and drop the folder `/skelet` in `plugin-boilerplate/admin/`
* In the `/plugin-boilerplate/` directory, open the plugin main file and add the following codes
```PHP

/*----------------------------------------------------------------- */
/* Skelet Config Path
/*----------------------------------------------------------------- */

$skelet_paths[] = array(
	'prefix'	  => 'pabpdemo',
	'dir'		  => wp_normalize_path(  plugin_dir_path( __FILE__ ).'/admin/' ),
	'uri' 		  => plugin_dir_url( __FILE__ ).'/admin/skelet',
);


/*----------------------------------------------------------------- */
/* Load Skelet Framework
/*----------------------------------------------------------------- */
if(! class_exists( 'Skelet_LoadConfig' ) ) 
		include_once dirname( __FILE__ ) .'/admin/skelet/skelet.php';
```

 after this line.
```PHP 
if ( ! defined( 'WPINC' ) ) {
	die;
}
```