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
	),
	'gallery-filter' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'bpch/gallery-filter',
		'version' => '0.1.0',
		'title' => 'Gallery filter',
		'category' => 'design',
		'icon' => 'images-alt2',
		'description' => 'Filter chips for the photos inside it. Each photo needs the class bpch-gallery__item and bpch-gallery__item--cooling (or heating, crew, comfort).',
		'keywords' => array(
			'gallery',
			'filter',
			'photos'
		),
		'supports' => array(
			'html' => false,
			'interactivity' => true,
			'customClassName' => false
		),
		'textdomain' => 'blue-peak-climate-core',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScriptModule' => 'file:./view.js'
	),
	'review-carousel' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'bpch/review-carousel',
		'version' => '0.1.0',
		'title' => 'Review carousel',
		'category' => 'design',
		'icon' => 'format-quote',
		'description' => 'Shows the newest published reviews one at a time, with dots, arrows and an automatic rotation that can be paused. Made for a dark band.',
		'keywords' => array(
			'reviews',
			'testimonials',
			'slider'
		),
		'supports' => array(
			'html' => false,
			'interactivity' => true,
			'customClassName' => false
		),
		'textdomain' => 'blue-peak-climate-core',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScriptModule' => 'file:./view.js'
	),
	'symptom-picker' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'bpch/symptom-picker',
		'version' => '0.1.0',
		'title' => 'Symptom picker',
		'category' => 'design',
		'icon' => 'editor-help',
		'description' => 'Five tabs (warm air, no heat, breaker trips, water leaking, strange noises), each with likely causes, what we would do, a tip and call-to-action buttons. The text is edited in the plugin code.',
		'keywords' => array(
			'symptoms',
			'tabs',
			'quick check'
		),
		'supports' => array(
			'html' => false,
			'interactivity' => true,
			'customClassName' => false
		),
		'textdomain' => 'blue-peak-climate-core',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScriptModule' => 'file:./view.js'
	)
);
