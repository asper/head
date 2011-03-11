<?php
/**
 * Easy header configuration
 * 
 * @author "Asper"
 */

App::import('vendor', 'Head.head');

class HeadHelper extends AppHelper {
	
	var $helpers = array('Html', 'Javascript');
	
	/**
	 * Html Helper
	 * @var HtmlHelper $Html
	 */
	var $Html;
	
	/**
	 * Javascript Helper
	 * @var JavascriptHelper $Javascript
	 */
	var $Javascript;
		
	public function __call($method, $arguments){
		call_user_func_array(array('Head', $method), $arguments);
	}
	
	/**
	 * Renders the head
	 * @param	string	$title_for_layout
	 * @param	string	$scripts_for_layout
	 * @return 	string
	 */
	public function render($title_for_layout = null, $scripts_for_layout = null){	
		
		$r = "\r\n"; $t = "\t";
		
		// Auto load JS & CSS
		$this->__autoload();
		
		// Configuration
		extract(Head::getConfig());		
		
		// Charset
		$out = $this->Html->charset($charset).$r;
		
		// Title
		if(empty($title['content'])) $title['content'] = $title_for_layout;
		$out .= $t.'<title>'.$title['options']['prefix'].$title['content'].$title['options']['suffix'].'</title>'.$r;
		
		// Metas
		foreach($meta as $args){
			$args = am( array( 'type' => null, 'url' => null, 'attributes' => array(), 'inline' => true ), $args);
			if($args['type']) $out .= $t.$this->Html->meta($args['type'], $args['url'], $args['attributes'], $args['inline']).$r;
		}		

		// CSS
		foreach($css as $args){
			$args = am( array( 'path' => null, 'rel' => null, 'htmlAttributes' => array(), 'inline' => true ), $args);
			if($args['path']) $out .= $t.$this->Html->css($args['path'], $args['rel'], $args['htmlAttributes'], $args['inline']).$r;
		}
		
		// Inline CSS
		if(!empty($cssRules)){
			$out .= $t.'<style type="text/css">'.$r;
			$out .= $t.'<!--'.$r;		
			foreach($cssRules as $args){
				$args = am( array( 'name' => null, 'css' => array() ), $args);
				if($args['name'] && !empty($args['css'])) $out .= $t.$t.$args['name'].' { '.$this->Html->style($args['css'], true).' }'.$r;
			}	
			$out .= $t.'-->'.$r;
			$out .= $t.'</style>'.$r;
		}
		
		// IE CSS
		if(!empty($ieCss)){
			foreach($ieCss as $version => $stylesheets){
				$firstLetter = substr($version, 0, 1);
				
				$out .= $t.'<!--[if '; 
				switch($firstLetter){
					case '<' :
						$out .= 'lte ';
						break;
					case '>' :
						$out .= 'gte ';
						break;
					case '=' :
					default :
						$out .= '';
						break;
				}
				
				$out .= 'IE '.str_replace(array('<','>','=', 'all'), '', $version).']>'.$r;
				foreach($stylesheets as $args){
					$args = am( array( 'path' => null, 'rel' => null, 'htmlAttributes' => array(), 'inline' => true ), $args);
					if($args['path']) $out .= $t.$t.$this->Html->css($args['path'], $args['rel'], $args['htmlAttributes'], $args['inline']).$r;
				}
				
				$out .= $t.'<![endif]-->'.$r;										
			}				
		}
		
		// JS
		foreach($js as $args){
			$args = am( array( 'url' => null, 'inline' => true ), $args);
			if($args['url']) $out .= $t.$this->Javascript->link($args['url'], $args['inline']).$r;
		}
		
		// DomReady JS
		if(!empty($domReady)){
			$domReadyString = 'window.addEvent(\'domready\', function(){';
			foreach($domReady as $args){
				$args = am( array( 'script' => null, 'options' => array() ), $args);
				if($args['script']) $domReadyString .= $args['script'];
			}
			$domReadyString .= '});';
			$out .= $this->Javascript->codeBlock(trim(str_replace(array("\r\n", "\n\r", "\n", "\r", "\t"), '', $domReadyString)));
		}
		
		// Inline JS
		if(!empty($jsBlock)){
			$jsBlockString = '';
			foreach($jsBlock as $args){
				$args = am( array( 'script' => null, 'options' => array() ), $args);
				if($args['script']) $jsBlockString .= $args['script'].$r.$r;
			}
			$out .= $this->Javascript->codeBlock(trim(str_replace(array("\r\n", "\n\r", "\n", "\r", "\t"), '', $jsBlockString)));
		}
		
		// Scripts for layout
		$out .= $scripts_for_layout.$r;
		
		return $out;
	}
	
	/**
	 * Autoloading of CSS and JS
	 * 
	 * Will search css and js files based on a defined structure :
	 * _app
	 * |_plugins
	 *   |_*plugin
	 *     |_css
	 * 	     |_*controller
	 *         -*controller.css
	 *         -*action.css
	 *         -*action_id.css  
	 *     |_js
	 *       |_*controller
	 *         -*controller.js
	 *         -*action.js
	 *         -*action_id.js
	 * |_webroot
	 *   |_css
	 * 	   |_*controller
	 *       -*controller.css
	 *       -*action.css
	 *       -*action_id.css  
	 *   |_js
	 *     |_*controller
	 *       -*controller.js
	 *       -*action.js
	 *       -*action_id.js
	 *   |_themed
	 *     |_*theme
	 *       |_css
	 * 	       |_*controller
	 *           -*controller.css
	 *           -*action.css
	 *           -*action_id.css  
	 *       |_js
	 *         |_*controller
	 *           -*controller.js
	 *           -*action.js
	 *           -*action_id.js       
	 *       |_plugins
	 *         |_*plugin
	 *           |_css
	 * 	           |_*controller
	 *               -*controller.css
	 *               -*action.css
	 *               -*action_id.css  
	 *           |_js
	 *             |_*controller
	 *               -*controller.js
	 *               -*action.js
	 *               -*action_id.js
	 *
	 * @link http://bakery.cakephp.org/articles/view/nicehead-helper-with-autoloading-of-javascript-and-css
	 * @return unknown_type
	 */
	private function __autoload(){
		$theme = $this->themeWeb;
		$plugin = $this->params['plugin'];
		$controller = $this->params['controller'];
		$action = $this->params['action'];
		$id = isset($this->params['pass'][0]) && is_int($this->params['pass'][0]) ? $this->params['pass'][0] : null;
		$models = $this->params['models'];

		$files = array(
			'js' => array(),
			'css' => array()
		);
		
		
		$folders[] = array(APP.'webroot'.DS, '/');
		if($theme) $folders[] = array(APP.'webroot'.DS.'themed'.DS.$theme.DS, '/themed/'.$theme.'/');
		if($plugin) $folders[] = array(APP.'plugins'.DS.$plugin.DS.'vendors'.DS, '/'.$plugin.'/');
		if($theme && $plugin) $folders[] = array(APP.'webroot'.DS.'themed'.DS.$theme.DS.'plugins'.DS.$plugin.DS, '/themed/'.$theme.'/plugins/'.$plugin.'/');
		
		$types = array('css', 'js');
		foreach($types as $type){
			foreach($folders as $folder){
				$file_paths = array(
					$type.DS.$controller.DS.$controller.'.'.$type,
					$type.DS.$controller.DS.$action.'.'.$type
				);
				if($id) $file_paths[] = $type.DS.$controller.DS.$action.'_'.$id.'.'.$type;
				
				foreach($file_paths as $file_path){
					if(file_exists($folder[0].$file_path)){
						$this->{$type}($folder[1].str_replace(DS, '/', $file_path));
					}
				}
					
			}	
		}
		
	}
		
}