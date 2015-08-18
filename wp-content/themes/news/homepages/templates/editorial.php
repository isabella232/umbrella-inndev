<div id="content">
	<?php
    	switch_to_blog( 1 ); // network content is on the main blog because it depends on the member profiles

    	$args = array(
    		'post_type'			=> 'network_content',
			'posts_per_page'	=> 10
    	);
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
                get_template_part( 'partials/content', 'network-content' );
			endwhile;
        } else {
            get_template_part( 'partials/content', 'not-found' );
        }
    ?>
</div>