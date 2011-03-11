<?php
App::import('core', 'set');

/**
 * This clas enables head configuration from the HeadComponent and the HeadHelper
 * 
 * @author "Asper"
 */

class Head {
	
	private static $instance;
	
/**
 * Configuration holder (do not edit this var, edit '/plugins/head/config/head.php' instead)
 * @var array
 */
	private $__config = array(
		'charset' => 'utf-8',
		'title' => array(
			'content' => null,
			'options' => array(
				'prefix' => null,
				'suffix' => null 
			)
		),
		'meta' => array(),
		'css' => array(),
		'cssRules' => array(),
		'ieCss' => array(),
		'js' => array(),
		'jsBlock' => array(),
		'domReady' => array()
	);
	
/**
 * Loads the configuration
 * @return unknown_type
 */
	private function __construct(){
		include(APP.'plugins'.DS.'head'.DS.'config'.DS.'head.php');
		$useConfig = Configure::read('Head.useConfig');
		$useConfig = $useConfig ? $useConfig : 'default';	
		$this->__config = Set::merge($this->__config, Configure::read('Head.'.$useConfig));
	}
	
/**
 * Singleton
 * @return unknown_type
 */
    public static function instance() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

/**
 * Sets the page title
 * @param 	string	$content
 * @param 	array	$options
 */
	public function title($content = null, $options = array()){
		$head = Head::instance();
		$head->__config['title'] = array(
			'content' => $content,
			'options' => am($this->__config['title']['options'], $options)
		);
	}

/**
 * Adds a javascript link
 * @see 	JavascriptHelper::link
 * @param	string	$url
 * @param 	bool	$inline
 */
	public function js($url, $inline = true){
		$head = Head::instance();
		$head->__config['js'][] = array( 'url' => $url, 'inline' => $inline);
	}
	
/**
 * Javascript Code Block
 * @see 	JavascriptHelper::codeBlock
 * @param 	string	$script
 * @param 	array	$options
 */
	public function jsBlock($script = null, $options = array()){
		$head = Head::instance();
		$head->__config['jsBlock'][] = array( 'script' => $script, 'options' => $options);
	}

/**
 * Adds a script to the mootools domready instance
 * @param 	string	$script
 * @param 	array	$options
 */
	public function domReady($script = null, $options = array(), $top = false){
		$head = Head::instance();
		if($top){
			array_unshift($head->__config['domReady'], array( 'script' => $script, 'options' => $options));
		}
		else{
			$head->__config['domReady'][] = array( 'script' => $script, 'options' => $options);
		}
		
	}

/**
 * Adds a css file
 * @see 	HtmlHelper::css
 * @param 	mixed	$path
 * @param 	string	$rel
 * @param 	array	$htmlAttributes
 * @param 	bool	$inline
 */
	public function css($path, $rel = null, $htmlAttributes = array(), $inline = true){
		$head = Head::instance();
		$head->__config['css'][] = array( 'path' => $path, 'rel' => $rel, 'htmlAttributes' => $htmlAttributes, 'inline' => $inline);
	}

/**
 * CSS Code Block
 * @see		HtmlHelper::style
 * @param 	string	$name	A valid CSS property name (eg: #menu, .light, div#menu li:hover ...)
 * @param 	array	$css	A set of CSS rules eg : array( 'background' =>'#fff', 'font-weight' => 'bold' ) 
 */
	public function cssRule($name = null, $css = array()){
		$head = Head::instance();
		$head->__config['cssRules'][] = array( 'name' => $name, 'css' => $css);
	}

/**
 * Adds a css file with IE conditional comments
 * @see 	HtmlHelper::css
 * @param 	mixed	$path
 * @param 	mixed	$version A number (6, 7, 8...) A string (<7, >6...) 
 * @param 	string	$rel
 * @param 	array	$htmlAttributes
 * @param 	bool	$inline
 */
	public function ieCss($path, $version = 'all' , $rel = null, $htmlAttributes = array(), $inline = true){
		$head = Head::instance();
		$head->__config['ieCss'][$version][] = array( 'path' => $path, 'rel' => $rel, 'htmlAttributes' => $htmlAttributes, 'inline' => $inline);
	}

/**
 * Adds metatags
 * @see 	HtmlHelper::meta
 * @param 	string	$type
 * @param 	mixed	$url
 * @param 	array	$attributes
 * @param 	bool	$inline
 */
	public function meta($type, $url = null, $attributes = array(), $inline = true){
		$head = Head::instance();
		$head->__config['meta'][] = array('type' => $type, 'url' => $url, 'attributes' => $attributes, 'inline' => $inline);
	}

/**
 * Sets the charset
 * @param	string 	$charset
 */
	public function charset($charset = null){
		$head = Head::instance();
		$head->__config['charset'] = $charset;
	}

/**
 * Gets the configuration
 * @return array
 */
	public function getConfig(){
		$head = Head::instance();
		return $head->__config;
	}
		
}