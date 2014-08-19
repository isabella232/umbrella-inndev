<?php

include_once get_template_directory() . '/homepages/homepage-class.php';

class JournoHomepageLayout extends Homepage {
  var $name = 'Journo.biz Custom Homepage Layout';
  var $description = 'Custom homepage layout for journo.biz.';

  var $sidebars = array(
	'Home Bottom Left', 'Home Bottom Center', 'Home Bottom Right'
  );

  function __construct($options=array()) {
    $defaults = array(
      'template' => get_stylesheet_directory() . '/homepages/templates/journo.php',
      'assets' => array(
	  		array('journo', get_stylesheet_directory_uri() . '/homepages/assets/css/journo.css', array()),
	  		array('journo', get_stylesheet_directory_uri() . '/homepages/assets/js/journo.js', array('jquery'))
	  	)
    );
    $options = array_merge($defaults, $options);
    $this->load($options);
  }

  public function viewToggle() {
	return homepage_view_toggle();
  }

  public function bigStory() {
	return homepage_big_story_headline();
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

	register_homepage_layout('JournoHomepageLayout');

}
add_action('init', 'inn_custom_homepage_layouts', 0);
