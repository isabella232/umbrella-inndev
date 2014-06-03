<?php
/**
 * CJET site front page, ultimate we'll want to port this into Largo's homepage template system I guess... but it's pretty significantly different.
 */
get_header();

$courses_parent = get_page_by_path('courses');
$guides_parent = get_page_by_path('guides');

$courses = get_pages( array(
	'sort_column' => 'menu_order',
	'parent' => $courses_parent->ID
));

$guides = get_pages( array(
	'sort_column' => 'post_date',
	'sort_order' => 'DESC',
	'parent' => $guides_parent->ID
));
?>

<div id="logo">
	<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php esc_attr( get_bloginfo('name') ); ?>" />
</div>

<section id="courses">
	<h1><?php _e("Online Courses", 'cjet'); ?></h1>
	<ul>
	<?php
	foreach ( $courses as $course ) :	?>
		<li><article>
			<?php echo apply_filters('the_content', get_the_post_thumbnail( $course->ID, 'medium' ) ); ?>
			<h4><a href="<?php echo get_permalink( $course->ID ); ?>" title="Permalink to <?php echo esc_attr( $course->post_title ); ?>"><?php echo $course->post_title; ?></a></h4>
			<p><?php echo $course->post_excerpt; ?></p>
		</article></li>
		<?php
		endforeach;
	?>
	</ul>
</section><!-- #courses -->

<section id="extras">
	<?php dynamic_sidebar( 'homepage-bottom' ); ?>
</section>

<section id="guides">
	<h1><?php _e('Guides', 'cjet'); ?></h1>
	<p class="description"><?php
		if ( of_get_option('cjet_guides_intro') ) {
			echo of_get_option('cjet_guides_intro');
		} else {
			_e('Edit this description in the Theme Options for CJET', 'cjet');
		}
	?></p>
	<ul>
	<?php
		foreach ( $guides as $guide ) : ?>
		<li><article>
			<?php echo apply_filters('the_content', get_the_post_thumbnail( $guide->ID, 'medium' ) ); ?>
			<h4><a href="<?php echo get_permalink( $guide->ID ); ?>" title="Permalink to <?php echo esc_attr( $guide->post_title ); ?>"><?php echo $guide->post_title; ?></a></h4>
			<p><?php echo $guide->post_excerpt; ?></p>
		</article></li>
		<?php
		endforeach;
	?>
	</ul>
</section><!-- #guides -->

<?php get_footer(); ?>