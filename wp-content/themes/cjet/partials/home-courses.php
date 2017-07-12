<?php

$courses_parent = get_page_by_path('courses');
$courses = get_pages( array(
	'sort_column' => 'menu_order',
	'parent' => $courses_parent->ID
));

?>
<section id="courses">
	<h1><?php _e("Online Courses", 'cjet'); ?></h1>
	<p class="description"><?php
		if ( of_get_option('cjet_courses_intro') ) {
			echo of_get_option('cjet_courses_intro');
		} else {
			_e('Edit this description under Appearance > Theme Options.', 'cjet');
		}
	?></p>
	<ul>
	<?php
		foreach ( $courses as $course ) :	?>
			<li>
				<article>
					<a href="<?php echo get_permalink( $course->ID ); ?>" title="Permalink to <?php echo esc_attr( $course->post_title ); ?>"><?php echo apply_filters('the_content', get_the_post_thumbnail( $course->ID, 'large' ) ); ?></a>
					<h4><a href="<?php echo get_permalink( $course->ID ); ?>" title="Permalink to <?php echo esc_attr( $course->post_title ); ?>"><?php echo $course->post_title; ?></a></h4>
					<p><?php echo $course->post_excerpt; ?></p>
				</article>
			</li>
			<?php
		endforeach;
	?>
	</ul>
</section><!-- #courses -->


