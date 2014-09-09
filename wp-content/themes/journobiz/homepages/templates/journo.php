<?php

global $shown_ids;

$bigStoryPost = largo_home_single_top();
$featuredStories = largo_home_featured_stories();
$shown_ids[] = $bigStoryPost->ID; //don't repeat the current post

?>
<div id="homepage-featured" class="row-fluid clearfix">
	<div class="home-single span9">
		<div class="home-top">
			<a href="<?php echo esc_attr(get_permalink($bigStoryPost->ID)); ?>">
				<?php echo get_the_post_thumbnail($bigStoryPost->ID, 'full'); ?>
			</a>
			<div id="dark-top">
				<?php echo $bigStory; // Big story zone ?>
			</div>
		</div>
	</div>
	<div class="span3">
		<?php $substories = largo_get_featured_posts( array(
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'prominence',
					'field' 	=> 'slug',
					'terms' 	=> 'homepage-featured'
				)
			),
			'showposts'		=> 3,
			'post__not_in' 	=> $shown_ids
		) );
		if ( $substories->have_posts() ) :
			while ( $substories->have_posts() ) : $substories->the_post(); $shown_ids[] = get_the_ID();
		?>
				<div class="story">
					<a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail(get_the_id(), 'large'); ?></a>
			        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			    </div>
		<?php
			endwhile;
		endif; // end more featured posts ?>
	</div>
</div>
<div id="homepage-bottom">
	<div class="span9">
	<?php
			$args = array(
				'paged'					=> $paged,
				'post_status'			=> 'publish',
				'posts_per_page'		=> 10,
				'post__not_in' 			=> $shown_ids,
				);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) : $query->the_post();
					//if the post is in the array of post IDs already on this page, skip it. Just a double-check
					if ( in_array( get_the_ID(), $shown_ids ) ) {
						continue;
					} else {
						$ids[] = get_the_ID();
						get_template_part( 'partials/content', 'home' );
					}
				endwhile;
				//largo_content_nav( 'nav-below' );
			} else {
				get_template_part( 'partials/content', 'not-found' );
			}
	?>
	</div>
	<div class="span3">
		<h2>INNovation Fund</h2>
		<?php dynamic_sidebar( 'sidebar-home' ); ?>
	</div>
</div>