<?php

include_once get_template_directory() . '/homepages/homepage-class.php';

class ImpaqHomepageLayout extends Homepage {
  var $name = 'Impaq.Me Homepage Layout';
  var $description = 'Custom homepage layout for impaqme.org.';

  function __construct($options=array()) {
    $defaults = array(
      'template' => get_stylesheet_directory() . '/homepages/templates/impaq.php',
      'assets' => array(
	  		array('impaq', get_stylesheet_directory_uri() . '/homepages/assets/css/impaq.css', array()),
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

	register_homepage_layout('ImpaqHomepageLayout');

}
add_action('init', 'inn_custom_homepage_layouts', 10);
