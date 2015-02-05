<?php

get_header();

// get the equivalent term in the project taxonomy associated with this post
$queried_object = get_queried_object();
$term = get_term_by( 'slug', $queried_object->post_name, 'pauinn_project_tax' );
$term_id = $term->term_id;
?>
<div class="project-nav span2">
	<h3>Programs</h3>
	<?php
		$terms = get_terms( 'pauinn_project_tax', array( 'hide_empty' => false ) );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		    echo '<ul>';
		    foreach ( $terms as $term ) {
			    $term_link = '/project/' . $term->slug;
		    	echo '<li><a href="' . $term_link . '">' . $term->name . '</a></li>';
		    }
		    echo '</ul>';
		}
	?>
</div>

<div id="content" class="span10" role="main">
	<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'partials/content', 'page' );
		endwhile;
	?>

	<div class="bottom row-fluid">
		<div class="span8">
			<h3>Latest News</h3>
			<?php
				$args = array(
					'post_type' => 'post',
					'tax_query' => array(
						array(
							'taxonomy' => 'pauinn_project_tax',
							'field'    => 'slug',
							'terms'    => $post->post_name,
						),
					),
				);
				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					echo '<ul>';
		        	while ( $query->have_posts() ) : $query->the_post();
						echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
					endwhile;
					echo '</ul>';
				}
			?>
		</div>
		<div class="span4">
			<h3>Participating Members</h3>
			<?php
				// this doesn't work yet
				$args = array(
					'fields' => 'all_with_meta',
					'role' => 'Member',
					'meta_key' => 'pauinn_project_tax',
					'meta_value' => $term_id,
				);
				$user_query = new WP_User_Query( $args );

				// User Loop
				if ( ! empty( $user_query->results ) ) {
					foreach ( $user_query->results as $user ) {
						//var_dump($user);
						echo '<p>' . $user->display_name . '</p>';
					}
				} else {
					echo 'No users found.';
				}
			?>
		</div>
	</div>
</div>

<?php

get_footer();