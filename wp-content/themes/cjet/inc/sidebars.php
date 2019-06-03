<?php
function cjet_register_sidebars() {
	register_sidebar( array(
		'name' 			=> __( 'Homepage Top', 'cjet' ),
		'id' 			=> 'homepage-top',
		'description' 	=> __( '', 'cjet' ),
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h2 class="">',
		'after_title' 	=> '</h2>',
	) );

	register_sidebar( array(
		'name' 			=> __( 'Homepage Topics', 'cjet' ),
		'id' 			=> 'homepage-topics',
		'description' 	=> __( 'This is the cluster of items on the bottom of the homepage.', 'cjet' ),
		'before_widget' => '<div id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</div aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );

	register_sidebar( array(
		'name'			=> __( 'Guide Sidebar Below Table of Contents', 'cjet' ),
		'id' 			=> 'guide-sidebar-below-toc',
		'description' 	=> __( 'Widget area for Guides sidebar. This will appear below the table of contents.', 'cjet' ),
	) );
}
add_action( 'widgets_init', 'cjet_register_sidebars' );
