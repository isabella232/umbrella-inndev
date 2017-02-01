<?php
/**
 * Switch to the Nerds site, grab most-recent 3 posts in the "project-largo" tag
 */
?>
<section id="project-updates" class="largo-section">
	<h2>Latest Updates</h2>
		<div class="max-width-container clearfix">
		<?php
			switch_to_blog( 7 );

			$args = array (
				'showposts' => 3,
				'post_status' => 'publish',
				'cat' => 130 // Largo
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					?>
					<div class="update-row">
						<span class="update-data"><?php largo_time(); ?></span>
						<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
					</div>
					<?php
				}
			}

			restore_current_blog();
		?>
		<p class="more"><a href="http://nerds.inn.org/category/largo/">More Project Updates</a></p>
	</div>
</section>
