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
		 		'url'   => 'http://investigativenewsnetwork.org'
		 	),
		 	array(
		 		'id' 	=> 2,
		 		'class' => 'largo',
		 		'url'   => 'http://largoproject.org'
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

	echo '<h3>More sites from the Investigative News Network</h3>';
	echo '<ul class="sites-list">';

	foreach ( $sites as $site ) {

		// don't include the site I'm currently on
		if ( $this_blog_id != $site['id'] ) {

			switch_to_blog( $site['id'] );

			// we still have a couple sites that aren't live, so we should link to their current home instead
			if ( isset( $site['url'] ) ) {
				$url = $site['url'];
			} else {
				$url = get_bloginfo('url');
			}

			printf('<li class="%s"><a href="%s"><strong>%s</strong></a><span class="dash"> - </span><span class="tagline">%s</span></li>',
				$site['class'],
				$url,
				get_bloginfo('name'),
				get_bloginfo('description')
			);
		}
	}

	echo '</ul>';

	restore_current_blog();
	?>
</div>
