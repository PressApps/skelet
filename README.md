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

### Contents

* [Installation and Adding Skelet to a Project](#installation)
* [Configuration Files](#configuration-files)
* [Pulling Values](#pulling-values)
  *	[Options Page fields](#get-options-values)
  * [Post/Page Metaboxes](#get-postpage-meta-values)
  *	[Customize Options](#get-customize-options-values)
  *	[Taxonomy fields](#get-taxonomy-options-values)
  *	[Widget fields](#get-widget-fields-values)
* [Supported Options Fields](#supported-options-fields)
* [Credits & Links](#credits-and-links)
* [Changelog](#changelog)

### Installation
------------

Let's assume that you want to use Skelet Framework in the plugin-boilerplate. Including Skelet has been made easy with the use of composer.

Instructions below assumes that you have already installed composer on your system, if not you can download it [here](https://getcomposer.org/download/).

* Using your terminal type in `cd /path/to/plugin-boilerplate/` - `/path/to/` refers to the exact path of your `plugin-boilerplate`.
* Once your in the `plugin-boilerplate` directory type in `composer install` and you are now ready to configure some files.
* In the `/plugin-boilerplate/includes` directory, open the `class-{plugin-name}-base.php` file and add the following codes in `load_dependencies()`.
```PHP
/**
 * Skelet Config Path
 */

$GLOBALS['skelet_paths'][] = array(
	'prefix'      => 'pakb',
	'dir'         => wp_normalize_path(  plugin_dir_path( dirname( __FILE__ ) ).'includes/' ),
	'uri'         => plugin_dir_url( dirname( __FILE__ ) ).'includes/skelet',
);


/**
	 * Load Skelet Framework
	 */
	if( ! class_exists( 'Skelet_LoadConfig' ) ){
		include_once plugin_dir_path( dirname( __FILE__ ) ) .'includes/skelet/skelet.php';
	}
```

Take Note: the prefix name should be unique per plugin
```PHP
$GLOBALS['skelet_paths'][] = array(
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

/**
 * a option section for options overview  
 */
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

![Alt text](https://raw.githubusercontent.com/pressapps/skelet/doc/doc/images/pressapps-admin-side-menu.png "PressApps Side Menu")

* Your plugin is ready to use some cool features of Skelet. Read the [Configuration Files](#configuration-files).

### Configuration Files
------------
There are 7 configuration files of Skelet that you can add in  `plugin-boilerplate/admin/options` directory.
* [customize.config.php](https://github.com/pressapps/skelet/blob/doc/doc/options/customize.config.php) - Configure custom fields, sections and panels in customizer
* [framework.config.php](https://github.com/pressapps/skelet/blob/doc/doc/options/framework.config.php) - Configure the option page, sections and fields.
* [metabox.config.php](https://github.com/pressapps/skelet/blob/doc/doc/options/metabox.config.php) -  Configure custom fields, sections and panels in post/page.
* [shortcode.config.php](https://github.com/pressapps/skelet/blob/doc/doc/options/shortcode.config.php) -  Configure shortcodes and fields.
* [taxonomy.config.php](https://github.com/pressapps/skelet/blob/doc/doc/options/taxonomy.config.php) - Configure taxonomy & tags extra fields.
* [template.config.php](https://github.com/pressapps/skelet/blob/doc/doc/options/template.config.php) - Override template and queries.
* [widget.config.php](https://github.com/pressapps/skelet/blob/doc/doc/options/widget.config.php) - Configure widgets custom fields, sections and display controller.

For supported options fields, read this [documentation](http://codestarframework.com/documentation/#text).

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
	// get all options values
	var_dump($skelet->get_meta(get_the_ID(),'_custom_page_options'));
	// get specific option value
	var_dump($skelet->get_meta(get_the_ID(),'_custom_page_options','section_1_text'));
	
```
##### Get customize options values
````PHP 
	$skelet = new Skelet("pabp");
	// get all options values
	var_dump(array($skelet->get_customize_option()));
	// get specific option value
	var_dump(array($skelet->get_customize_option('color_option_with_default')));
```
##### Get taxonomy options values
````PHP 
	$skelet = new Skelet("pabp");
	// get all options values
	var_dump($skelet->get_taxonomy('category',50,));
	// get specific option value
	var_dump($skelet->get_taxonomy('category',50,'section_4_text'));
```
##### Get widget fields values
Skelet widget configuration comes up with controller settings to get fields values.
```PHP
 $options[]            = array(
  'name'              => 'skelet_widget_fields_1',
  'title'             => 'Skelet Widget Fields',
  'description'       => 'This is a description',
  'settings'          => array(

    // text
    array(
      'name'          => 'text_1',
      'default'       => 'Skelet widget is awesome',
      'control'       => array(
        'label'       => 'Sample Text Field',
        'type'        => 'text',
      ),
    ),
    // setup widget controller
  "frontend_tpl" => array(
      "wrapper"      => "<div class=\"item-wrapper\">%s</div>",
      "before_item"  => "<div>",
      "after_item"   => "</div>",
      "show_label"   => array(
          "wrapper"  => "<div>%s</div>",
          "before"   => "<label>",
          "after"    => ":</label>  "
      ),
      "walker_class" => array(
            "name"   => "SkeletWidgetWalker",
            "path"   => PABP_PLUGIN_DIR."template/widget/walkers/sk-widget-sample-class.php",
      )
  )

```
Get widget fields values in controller file `sk-widget-sample-class.php`
```PHP
if(!class_exists("SkeletWidgetWalker")){
	class SkeletWidgetWalker{

			public $widget_option_settings = array();

			function __construct($args,$instance){

				global $options;
				
				var_dump($instance["text_1"]);

				
			}
	}
}
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

### Credits and Links
------------
Skelet is based on awesome [Codestar Framework](http://codestarframework.com/), thank you for the great work.

### Changelog
-----------
*	v 1.0.0 - Initial Release


