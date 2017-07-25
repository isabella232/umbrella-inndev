<?php
/**
 * homepage section partial for:
 * - full-width photo background
 * - site search form
 *
 * The photo should be as low-bandwidth as possible, possibly served responsibly with viewport items.
 * We did this in Chicago Reporter with a post ID and the function cr_ph_header_tag( $post_id ): https://github.com/INN/umbrella-chicagoreporter/blob/master/wp-content/themes/chicago-reporter/single-photo-header.php
 * That function output a header tag with a specific ID and an inline style targeting that ID, using media queries to replicate <img srcset=""> tags in CSS: https://github.com/INN/umbrella-chicagoreporter/blob/master/wp-content/themes/chicago-reporter/inc/photo-header-template.php#L18
 *
 * for now, we'll avoid doing that, unless this needs to be a user-uploaded image.
 */

$url = get_stylesheet_directory_uri();

?>
<style type="text/css">
	@media (min-width: 0px) and (max-width: 360px) {
		#home-search {
			background-image: url("<?php echo $url; ?>/img/search-360x360.jpg");
		}
	}
	@media (min-width: 361px) and (max-width: 520px) {
		#home-search {
			background-image: url("<?php echo $url; ?>/img/search-520x520.jpg");
		}
	}
	@media (min-width: 521px) and (max-width: 768px) {
		#home-search {
			background-image: url("<?php echo $url; ?>/img/search-768x768.jpg");
		}
	}
	@media (min-width: 769px) and (max-width: 980px) {
		#home-search {
			background-image: url("<?php echo $url; ?>/img/search-980x980.jpg");
		}
	}
	@media (min-width: 981px) and (max-width: 1170px) {
		#home-search {
			background-image: url("<?php echo $url; ?>/img/search-1170x1170.jpg");
		}
	}
	@media (min-width: 1171px) and (max-width: 2000px) {
		#home-search {
			background-image: url("<?php echo $url; ?>/img/search-2000x2000.jpg");
		}
	}
	@media (min-width: 2001px) {
		#home-search {
			background-image: url("<?php echo $url; ?>/img/search.jpg");
		}
	}
</style>
<section id="home-search">
	<h3>Donec sodales sagittis magna</h3>
	<form class="form-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<div class="input-append">
			<label for="guide-search-form" class="visuallyhidden"><?php _e('Search our resources', 'inn'); ?></label>
			<input name="guide-search-form" type="text" placeholder="<?php _e('Search our resources', 'inn'); ?>" class="searchbox search-query" value="<?php the_search_query(); ?>" name="s" />
			<button type="submit" name="search submit" class="search-submit btn"><?php _e('Search', 'largo'); ?></button>
		</div>
	</form>

	<!-- photo credit for https://unsplash.com/photos/PeUJyoylfe4 as img/search.jpg -->
	<a class="photo-credit visible-desktop" style="" href="https://twitter.com/@LEBertoc" target="_blank" rel="noopener noreferrer" title="Photo Credit: Laura Bertocci">
		<span style="">
			<svg xmlns="http://www.w3.org/2000/svg" style="height:12px;width:auto;position:relative;vertical-align:middle;top:-1px;fill:white;" viewBox="0 0 32 32">
				<title>
					Photo Credit: Laura Bertocci
				</title>
				<path d="M20.8 18.1c0 2.7-2.2 4.8-4.8 4.8s-4.8-2.1-4.8-4.8c0-2.7 2.2-4.8 4.8-4.8 2.7.1 4.8 2.2 4.8 4.8zm11.2-7.4v14.9c0 2.3-1.9 4.3-4.3 4.3h-23.4c-2.4 0-4.3-1.9-4.3-4.3v-15c0-2.3 1.9-4.3 4.3-4.3h3.7l.8-2.3c.4-1.1 1.7-2 2.9-2h8.6c1.2 0 2.5.9 2.9 2l.8 2.4h3.7c2.4 0 4.3 1.9 4.3 4.3zm-8.6 7.5c0-4.1-3.3-7.5-7.5-7.5-4.1 0-7.5 3.4-7.5 7.5s3.3 7.5 7.5 7.5c4.2-.1 7.5-3.4 7.5-7.5z">
				</path>
			</svg>
		</span>
		<span class="visuallyhidden">Photo credit:</span>
		<span >Laura Bertocci</span>
	</a>
</section>
