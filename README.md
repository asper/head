HEAD PLUGIN 
===========

This plugin helps you to configure your layouts' head from your controller or your view.

	// In controller
	$this->Head->title($post['Post']['title']);
	// In view
	$this->Head->css($path);
	// For a full list of available methods @see libs/head.php

It also automatically loads javascript and css assets based on a simple directory structure.

Installation
------------

### Copy files

#### Git

	git submodule add git://... plugins/head
	git submodule init
	git submodule update

#### Manual

Download the archive, copy the "head" folder to your "app/plugins" folder

### Create the conf file

Copy the file "plugins/head/config/head.php.default" to "config/head.php"

### Head component

Add the Head component to your AppController

	class AppController extends Controller {
		var $components = array('Head.Head');
		//...
	}

### Layout

Replace all the content of the head tag by :

	<?php echo $head->render($title_for_layout, $scripts_for_layout); ?>) :

Example :
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $head->render($title_for_layout, $scripts_for_layout); ?>
	</head>
	<body>
		...
	</body>
	</html>
	  
Configuration
-------------

### Default configuration

You can configure site wide settings in config/head.php

Example :

	Configure::write('Head.YourConfigKey', 
		array(
			'charset' => 'utf-8',
			'title' => array(
				'options' => array(
					'suffix' => ' - My Website' # Will add " - My Website" at the end of $title_for_layout
				)
			),
			'css' => array(
				array(
					'path' => 'my_css_file'
				)
			)
		)
	);

### Multiple configurations

In case you need to use different Head configurations, use Configure::write('Head.useConfig', 'YourConfigKey'); for example in your Conrtoller

And then store the configuration in config/head.php as follows :

	Configure::write('Head.YourConfigKey', 
		array(
			'charset' => 'utf-8',
			'title' => array(
				'options' => array(
					'suffix' => ' - My alternative Website'
				)
			)
		)
	);


### On demand configuration

In your controller or view : $this->Head->css('cake.generic');
	
Available methods
-----------------

	/**
	 * Sets the page title
	 * @param 	string	$content
	 * @param 	array	$options
	 */
	title($content = null, $options = array());

	/**
	 * Adds a javascript link
	 * @see 	JavascriptHelper::link
	 * @param	string	$url
	 * @param 	bool	$inline
	 */
	js($url, $inline = true);
	
	/**
	 * Javascript Code Block
	 * @see 	JavascriptHelper::codeBlock
	 * @param 	string	$script
	 * @param 	array	$options
	 */
	jsBlock($script = null, $options = array());

	/**
	 * Adds a script to the mootools domready instance
	 * @param 	string	$script
	 * @param 	array	$options
	 */
	domReady($script = null, $options = array(), $top = false);

	/**
	 * Adds a css file
	 * @see 	HtmlHelper::css
	 * @param 	mixed	$path
	 * @param 	string	$rel
	 * @param 	array	$htmlAttributes
	 * @param 	bool	$inline
	 */
	css($path, $rel = null, $htmlAttributes = array(), $inline = true);

	/**
	 * CSS Code Block
	 * @see		HtmlHelper::style
	 * @param 	string	$name	A valid CSS property name (eg: #menu, .light, div#menu li:hover ...)
	 * @param 	array	$css	A set of CSS rules eg : array( 'background' =>'#fff', 'font-weight' => 'bold' ) 
	 */
	cssRule($name = null, $css = array());

	/**
	 * Adds a css file with IE conditional comments
	 * @see 	HtmlHelper::css
	 * @param 	mixed	$path
	 * @param 	mixed	$version A number (6, 7, 8...) A string (<7, >6...) 
	 * @param 	string	$rel
	 * @param 	array	$htmlAttributes
	 * @param 	bool	$inline
	 */
	ieCss($path, $version = 'all' , $rel = null, $htmlAttributes = array(), $inline = true);

	/**
	 * Adds metatags
	 * @see 	HtmlHelper::meta
	 * @param 	string	$type
	 * @param 	mixed	$url
	 * @param 	array	$attributes
	 * @param 	bool	$inline
	 */
	meta($type, $url = null, $attributes = array(), $inline = true);

	/**
	 * Sets the charset
	 * @param	string 	$charset
	 */
	charset($charset = null);

	/**
	 * Gets the configuration
	 * @return array
	 */
	getConfig();

Requirements
------------

- PHP 5.3
- CakePHP 1.3

Files
-----

- **libs/head.php** _Head class_ : Main class, where the values are set
- **controllers/components/head.php** _Head component_ : Wrapper for the Head vendor class
- **views/helpers/head.php** _Head helper_ : Wrapper for the Head vendor class + display
- **config/head.php** _Head configuration file_ : this is where you set the JS & CSS displayed on all the pages	  

Todo
----

- Automatic metatags setting
- Support of multiple libraries for domready
	