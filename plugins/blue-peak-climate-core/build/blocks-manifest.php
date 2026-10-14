<?php
// This file is generated. Do not modify it manually.
return array(
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
