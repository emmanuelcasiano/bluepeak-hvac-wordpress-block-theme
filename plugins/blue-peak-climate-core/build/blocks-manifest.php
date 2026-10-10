<?php
// This file is generated. Do not modify it manually.
return array(
	'example' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'blue-peak-climate-core/example',
		'version' => '0.1.0',
		'title' => 'Example Notice',
		'category' => 'widgets',
		'icon' => 'megaphone',
		'description' => 'Blue Peak Climate dynamic block. Rename or delete it when you add your first real block.',
		'attributes' => array(
			'message' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'supports' => array(
			'html' => false,
			'color' => array(
				'background' => true,
				'text' => true
			),
			'spacing' => array(
				'padding' => true
			)
		),
		'textdomain' => 'blue-peak-climate-core',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	)
);
