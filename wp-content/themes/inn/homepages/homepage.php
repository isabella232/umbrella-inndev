<?php

include_once get_template_directory() . '/homepages/homepage-class.php';

class INNHomepageLayout extends Homepage {
  var $name = 'INN Main Site Homepage Layout';
  var $description = 'Custom homepage layout for the main INN site.';

  function __construct($options=array()) {
    $defaults = array(
      'template' => get_stylesheet_directory() . '/homepages/templates/inn.php',
      'assets' => array(
	  		array('inn', get_stylesheet_directory_uri() . '/homepages/assets/css/inn.css', array()),
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

	register_homepage_layout('INNHomepageLayout');

}
add_action('init', 'inn_custom_homepage_layouts', 10);
