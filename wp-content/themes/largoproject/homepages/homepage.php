<?php

include_once get_template_directory() . '/homepages/homepage-class.php';

class LargoHomepageLayout extends Homepage {
	var $name = 'Largo (Project Site) Homepage Layout';
	var $description = 'Custom homepage layout for the Largo project site.';

	function __construct($options=array()) {
		$defaults = array(
			'template' => get_stylesheet_directory() . '/homepages/templates/largo.php',
			'assets' => array(
				array('editorial', get_stylesheet_directory_uri() . '/homepages/assets/css/largo.css', array()),
			)
		);
	$options = array_merge($defaults, $options);
		$this->init();
		$this->load($options);
	}
}

function inn_custom_homepage_layouts() {
	$unregister = array(
		'HomepageBlog',
		'HomepageSingle',
		'HomepageSingleWithFeatured',
		'HomepageSingleWithSeriesStories',
		'TopStories',
		'Slider',
		'LegacyThreeColumn'
	);

	foreach ($unregister as $layout)
		unregister_homepage_layout($layout);

	register_homepage_layout('LargoHomepageLayout');

}
add_action('init', 'inn_custom_homepage_layouts', 10);
