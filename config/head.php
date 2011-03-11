<?php
/**
 * Site-wide head configuration
 */

Configure::write('Head.default', 
	array(
		'charset' => 'utf-8',
		'title' => array(
			'content' => null,
			'options' => array(
				'prefix' => null,
				'suffix' => null 
			)
		),
		'meta' => array(
			array(
				'type' => 'icon',
				'url' => '/img/favicon.ico'
			)
		),
		'css' => array(
			array('path' => 'cake.generic')
		),
		'cssRules' => array(),
		'ieCss' => array(),
		'js' => array(),
		'jsBlock' => array(),
		'domReady' => array()
	)
);