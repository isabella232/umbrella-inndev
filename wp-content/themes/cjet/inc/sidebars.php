<?php
function cjet_register_sidebars() {
	register_sidebar( array(
		'name' 			=> __( 'Homepage Callout', 'cjet' ),
		'id' 			=> 'homepage-callout',
		'description' 	=> __( 'Homepage Callout Section', 'cjet' ),
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );

	register_sidebar( array(
		'name'			=> __( 'Guide Sidebar Below Table of Contents', 'cjet' ),
		'id' 			=> 'guide-sidebar-below-toc',
		'description' 	=> __( 'Widget area for Guides sidebar. This will appear below the table of contents.', 'cjet' ),
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );
}
add_action( 'widgets_init', 'cjet_register_sidebars' );
