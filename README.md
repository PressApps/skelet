## Skelet Framework

### Introduction

Skelet is a framework for creating WordPress plugins, it eases the creation of:

* Advanced option pages
* Widgets
* Shortcodes
* Taxonomies
* Metaboxes
* WP Customize
* Templating
* Adding custom fields in pages, posts, custom post types, taxonomies, widgets and customizer.

######Contents

* [Installation and Adding Skelet to a Project](#installation)
* [Configuration Files](#configuration-files)
* [Pulling Values](#pulling-values)
* [Supported Options Fields](#supported-options-fields)
* [Debugging](#debugging)
* [Credits & Links](#credits-and-links)
* [Changelog](#changelog)

### Installation
------------

Let's assume that you want to use Skelet Framework in the plugin-boilerplate.

* Download & extract a copy of [Plugin Boilerplate](http://wppb.me) in `wp-content/plugins/` & pull [Skelet Framework](https://github.com/pressapps/skelet) from the repository and drop the folder `/skelet` in `plugin-boilerplate/admin/`
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

Take Note: the prefix name should be unique per plugin
```PHP
$skelet_paths[] = array(
	'prefix'	  => 'your_unique_prefix_name',
	....
);
```
##### Now, let's test the `Skelet` with the plugin boilerplate...
* Create a new folder `options` in the `plugin-boilerplate/admin` directory.
* In `plugin-boilerplate/admin/options`, create a new file `framework.config.php` and add the following codes:
```PHP
$settings      = array(
  'header_title' => 'Plugin BoilerPlate',
  'current_version' => '1.0.0',
  'menu_title' => 'BoilerPlate',
  'menu_type'  => 'add_submenu_page',
  'menu_slug'  => 'pa-boilerplate',
  'ajax_save'  => false,
);

$options = array();

// ----------------------------------------
// a option section for options overview  -
// ----------------------------------------
$options[]      = array(
  'name'        => 'overwiew',
  'title'       => 'Overview',
  'icon'        => 'fa fa-star',

  // begin: fields
  'fields'      => array(

	    // begin: a field
	    array(
	      'id'      => 'text_1',
	      'type'    => 'text',
	      'title'   => 'Text',
	      'default' => 'Hello World!'
	    ),
	    // end: a field

	    array(
	      'id'      => 'textarea_1',
	      'type'    => 'textarea',
	      'title'   => 'Textarea',
	      'default'	=> 'How are you today?',
	      'help'    => 'This option field is useful. You will love it!'
	    )
   )
 );
SkeletFramework::instance( $settings, $options );

```
* Now, let's activate the `plugin-boilerplate` and it should show the `PressApps` in the admin side-menu.
* Your plugin is ready to use some cool features of Skelet. Read the [Configuration Files](#configuration-files).

### Configuration Files
------------
There are 7 configuration files of Skelet that you can add in  `plugin-boilerplate/admin/options` directory.
* `customize.config.php` - Configure custom fields, sections and panels in customizer
* `framework.config.php` - Configure the option page, sections and fields.
* `metabox.config.php` -  Configure custom fields, sections and panels in post/page.
* `shortcode.config.php` -  Configure shortcodes and fields.
* `taxonomy.config.php` - Configure taxonomy & tags extra fields.
* `template.config.php` - Override template and queries.
* `widget.config.php` - Configure widgets custom fields, sections and display controller.

For supported options, read this [documentation](http://codestarframework.com/documentation/#text).

### Pulling Values
------------
##### Get options values
Here's how to pull options values with a plugin prefix name `pabp`:
```PHP
	$skelet = new Skelet('pabp');
	var_dump($skelet->get());
```
You can also get a specific option value by adding the name/id of the option field:
```PHP
	$skelet = new Skelet('pabp');
	var_dump($skelet->get('text_1'));
```
##### Get post/page meta values
Below example, we specify the meta id `_custom_page_options` and get the `section_1_text` field value.
```PHP
	$skelet = new Skelet('pabp');
	var_dump($skelet->get_meta(get_the_ID(),'_custom_page_options','section_1_text'));
	
```
To retrieve all meta fields values, you can try the following:
```PHP
	$skelet = new Skelet('pabp');
	var_dump($skelet->get_meta(get_the_ID(),'_custom_page_options'));
	
```
##### Get customize options values
````PHP 
	$skelet = new Skelet("pabp");
	var_dump(array($skelet->get_customize_option('color_option_with_default')));
```


### Supported Options fields
------------
*	Text
*	Textarea
*	Checkbox
*	Radio
*	Select
*	Number
*	Icons
*	Group
*	Image
*	Upload
*	Gallery
*	Sorter
*	Wysiwyg
*	Switcher
*	Background
*	Color Picker
*	Multi Checkbox
*	Checkbox Image Select
*	Radio Image Select
*	Typography
*	Backup
*	Heading
*	Sub Heading
*	Fieldset
*	Notice
*	and extendable fields

### Debugging
------------

### Credits and Links
------------

### Changelog
-----------
*	v 1.0.0 - Initial Release


