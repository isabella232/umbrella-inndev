<?php

include_once get_template_directory() . '/homepages/homepage-class.php';

class JournoHomepageLayout extends Homepage {
  var $name = 'Journo.biz Custom Homepage Layout';
  var $description = 'Custom homepage layout for journo.biz.';

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
    $this->init();
  }

  public function bigStory() {
	return homepage_big_story_headline();
  }

  public function init($options=array()) {
	$this->prominenceTerms = array(
		array(
			'name' => __('Top Story', 'largo'),
			'description' 	=> __('If you are using the Newspaper or Carousel optional homepage layout, add this label to a post to make it the top story on the homepage', 'largo'),
		    'slug' 			=> 'top-story'
		),
		array(
			'name' => __('Homepage Featured', 'largo'),
			'description' => __('If you are using the Newspaper or Carousel optional homepage layout, add this label to posts to display them in the featured area on the homepage.', 'largo'),
			'slug' => 'homepage-featured'
		)
	);
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
