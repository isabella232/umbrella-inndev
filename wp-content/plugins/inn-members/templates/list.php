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

		if ( isset( $_GET['state'] ) ) {
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

			$count = 0;

			foreach ( $users->results as $user ) {

				$class = "inn_member directory";
				if ( $count % 2 == 0 ) $class .= " count-2";
				if ( $count % 4 == 0 ) $class .= " count-4";

				echo '<article id="post-' . $user->ID . '" class="' . $class . '">';

				include('member.php');

				echo '</article>';

				$count++;
			}

		}

	?>
