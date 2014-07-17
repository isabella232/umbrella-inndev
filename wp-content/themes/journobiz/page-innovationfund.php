<?php
/**
 * Template Name: Innovation Fund
 * Template for the Innovation Fund page, which includes a list of grantees
 */
get_header();
?>

<div id="grantee-content" class="span12" role="main">
	<?php
		while ( have_posts() ) : the_post();
		global $post;
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'hnews item' ); ?> itemscope itemtype="http://schema.org/Article">
		<header>
			<div class="img-container"><?php the_post_thumbnail('full'); ?></div>
			<div class="info span5">
	 			<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
		 			<?php largo_excerpt( $post, 3, false ); ?>
			</div>
	 		<meta itemprop="description" content="<?php echo strip_tags(largo_excerpt( $post, 5, false, '', false ) ); ?>" />
	 		<meta itemprop="datePublished" content="<?php echo get_the_date( 'c' ); ?>" />
	 		<meta itemprop="dateModified" content="<?php echo get_the_modified_date( 'c' ); ?>" />
	 		<?php
	 			if ( has_post_thumbnail( $post->ID ) ) {
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
					echo '<meta itemprop="image" content="' . $image[0] . '" />';
				}
	 		?>
		</header><!-- / entry header -->

		<?php
			// fetch child pages of the current page. Loop thru and show them, querying Round terms for grantees to include based on the given Page's assigned rounds
			$children = new WP_Query( array(
				'post_type' => 'page',
				'post_parent' => get_the_ID(),
				'post_status' => array('publish','future'),
				'orderby' => 'menu_order'
			) );

			if ( $children->have_posts() ): ?>
				<ul class="funding-rounds">
				<?php

				while ( $children->have_posts() ) :
					$children->the_post();
					global $more;
					$more = 0;
					$round_live = ( get_post_status() == 'publish' ) ? true : false ;
					if ( $round_live ) : ?>
					<li class="round active">
						<article>
							<header>
								<h1><?php the_title(); ?></h1>
							</header>
							<div class="intro">
								<?php // largo_excerpt( $post, 3, true, __('Read More', 'journobiz'), true, false, false ); ?>
								<?php the_content( __('Read More', 'journobiz') ); ?>
							</div>
								<?php
									// get grantees belonging to the round(s) this child page is assigned to

									$rounds = wp_get_post_terms( get_the_ID(), 'round', array('fields' => 'ids') );
									$grantees = new WP_Query( array(
										'post_type' => 'grantee',
										'post_status' => 'publish',
										'orderby' => 'title',
										'tax_query' => array(
											array(
												'taxonomy' => 'round',
												'field' => 'id',
												'terms' => $rounds
											)
										)
									) );

									if ( $grantees->have_posts() ) :
										$round_post = $post; ?>
										<div class="grantee-list"><ul>
									<?php
									while ( $grantees->have_posts() ) : $grantees->the_post();
										$details = get_post_meta( get_the_ID(), 'grantee_details', true );
									?>
										<li>
											<?php the_post_thumbnail( 'thumbnail' ); ?>
											<div>
												<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
												<p><?php echo $details['org-name']; ?></p>
												<p><?php echo __('Award Amount: ', 'journobiz') . $details['award-amount']; ?></p>
											</div>
										</li>
									<?php
									endwhile;
									setup_postdata( $round_post );
									$post = $round_post;
									endif;
								?></ul>
							</div>
						</article>
					</li>
					<?php else : ?>
					<li class="round future">
						<header>
							<h1><?php echo str_replace("Round ", "", get_the_title() ); ?></h1>
						</header>
						<div class="intro">
							<?php //largo_excerpt( get_the_ID(), 1, false ); ?>
							<?php the_content( __('Read More', 'journobiz') ); ?>
						</div>
					</li>
			<?php
					endif;
				endwhile;
				wp_reset_postdata(); ?>
				</ul>
		<?php
			endif;
		endwhile;
		?>

</div><!--#content-->
<?php get_footer(); ?>