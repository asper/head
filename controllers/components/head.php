<?php
/**
 * Set header configuration in the controller
 * 
 * @see Head.HeadHelper
 * @author "Asper"
 */

App::import('Lib', 'Head.Head');
config('Lib', 'Head.head');

class HeadComponent extends Object {
	
	public function __call($method, $arguments){
		call_user_func_array(array('Head', $method), $arguments);
	}
	
	public function beforeRender(&$controller){
		$controller->helpers[] = 'Head.Head';
	}
	
}