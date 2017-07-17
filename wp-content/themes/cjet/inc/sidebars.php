<?php
function cjet_register_sidebars() {
	register_sidebar( array(
		'name' 			=> __( 'Homepage Boxes', 'cjet' ),
		'id' 			=> 'homepage-boxes',
		'description' 	=> __( 'This is the array of boxes on the homepage. You should use textwidgets here, where every textwidget contains within an &lt;a&gt; an &lt;h3&gt; and a &lt;p&gt;.', 'cjet' ),
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );
}
add_action( 'widgets_init', 'cjet_register_sidebars' );
