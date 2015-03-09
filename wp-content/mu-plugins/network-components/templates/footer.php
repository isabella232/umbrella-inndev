<div id="network-footer">
	<?php
		$this_blog_id = get_current_blog_id();

		/**
		 * 1 - main site
		 * 2 - largo
		 * 3 - newstraining.org
		 * 4 - impaqme.org
		 * 5 - journo.biz
		 * 6 - INNinvestigations.org
		 * 7 - nerds
		 */
		 $sites = array(
		 	array(
		 		'id' 	=> 1,
		 		'class' => 'inn',
		 	),
		 	array(
		 		'id' 	=> 2,
		 		'class' => 'largo',
		 	),
		 	array(
		 		'id' 	=> 3,
		 		'class' => 'newstraining'
		 	),
		 	array(
		 		'id' 	=> 4,
		 		'class' => 'impaq'
		 	),
		 	array(
		 		'id' 	=> 5,
		 		'class' => 'journo'
		 	),
		 	array(
		 		'id' 	=> 7,
		 		'class' => 'nerds'
		 	)
		 );

	echo '<h3>More sites from INN</h3>';
	echo '<ul class="sites-list">';

	foreach ( $sites as $site ) {

		// don't include the site I'm currently on
		if ( $this_blog_id != $site['id'] ) {

			switch_to_blog( $site['id'] );

			printf('<li class="%s"><a href="%s"><strong>%s</strong></a><span class="dash"> - </span><span class="tagline">%s</span></li>',
				$site['class'],
				get_bloginfo('url'),
				get_bloginfo('name'),
				get_bloginfo('description')
			);
		}
	}

	echo '</ul>';

	switch_to_blog( $this_blog_id );
	?>
</div>
