<?php
/**
 * Template Name: Member list
 * Template for membership archive
 */
get_header();
?>

<div id="content" class="stories span12" role="main">
	<header class="archive-background clearfix">
	<?php
		while ( have_posts() ) : the_post(); ?>
			<h1 class="page-title"><?php the_title(); ?></h1>
			<div class="archive-description">
				<?php the_content(); ?>
				<?php

		endwhile;

				wp_nav_menu( array(
					'theme_location' => 'membership',
					'container' => false,
					'menu_class' => 'members-menu',
					'depth' => 1)
				);
			?>
		</div>
	</header>

	<?php
		//map, abstracted in inn_members.php
		inn_member_map();
	?>

	<div class="member-nav">
		<label><?php _e('Filter List By: ', 'inn'); ?></label>
		<?php
			//all these are abstracted in inn_members.php
			inn_member_alpha_links();
			inn_member_categories_list();
			inn_member_states_list();
		?>
	</div>
	<?php
		//custom query to pull members by alpha and/or page here...
		//pagination inactive for now
		$user_query = array(
			'role' => 'Member',
			'fields' => 'all_with_meta',
			'number' => 9999, //get_option('posts_per_page', 10),
			'offset' => 0, //(get_query_var('paged')) ? get_query_var('paged') * get_option('posts_per_page', 10) : 0;
			'orderby' => 'display_name',
		);

		if ( isset($_GET['letter']) ) {
			$user_query['user_starts_with'] = $_GET['letter'];
		}

		if ( isset( $_GET['focus']) ) {
			$user_query['meta_query'] = array(
				'relation' => 'AND',
				array(
					'compare' => 'LIKE',
					'key' => INN_MEMBER_TAXONOMY,
					'value' => inn_prep_user_term( $_GET['focus'], INN_MEMBER_TAXONOMY )
				)
			);
		}

		if ( isset( $_GET['state']) ) {
			if ( $_GET['state'] == 'intl' ) {

				$user_query['meta_query'] = array(
					'relation' => 'AND',
					array(
						'compare' => '!=',
						'key' => 'paupress_address_country_1',
						'value' => 'US'
					)
				);


			} else {

				$user_query['meta_query'] = array(
					'relation' => 'AND',
					array(
						'compare' => '=',
						'key' => 'paupress_address_state_1',
						'value' => $_GET['state']
					)
				);


			}

		}

		$users = new WP_User_Query( $user_query );

		if ( !empty( $users->results ) ) {

			foreach ( $users->results as $user ) {
				include(locate_template('content-member.php'));
			}

			/* pagination inactive for now
			?>
			<nav id="nav-below" class="pager post-nav">
				<div class="next"><?php next_posts_link( __( 'Next Page &rarr;', 'largo' ) ); ?></div>
				<div class="previous"><?php previous_posts_link( __( '&larr; Previous Page', 'largo' ) ); ?></div>

			</nav><!-- .post-nav -->

			<?php
			*/
		}

	?>

</div><!--#content-->
<?php get_footer(); ?>