<?php

include_once get_template_directory() . '/homepages/homepage-class.php';

class EditorialHomepageLayout extends Homepage {
  var $name = 'INN Editorial Site Homepage Layout';
  var $description = 'Custom homepage layout for the INN editorial projects site.';

  function __construct($options=array()) {
    $defaults = array(
      'template' => get_stylesheet_directory() . '/homepages/templates/editorial.php',
      'assets' => array(
	  		array('editorial', get_stylesheet_directory_uri() . '/homepages/assets/css/editorial.css', array()),
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
		'Slider'
	);

	foreach ($unregister as $layout)
		unregister_homepage_layout($layout);

	register_homepage_layout('EditorialHomepageLayout');

}
add_action('init', 'inn_custom_homepage_layouts', 10);
