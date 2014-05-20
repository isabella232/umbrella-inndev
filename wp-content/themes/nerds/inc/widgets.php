<?php

class NerdsRosterWidget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'nerds_roster_widget', // Base ID
			'Nerds Roster Widget', // Name
			array( 'description' => 'Display a list of INN Technology staff members with photos and bios.' ) // Args
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];
		if (!empty($title))
			echo $args['before_title'] . $title . $args['after_title'];

		$users = get_users(array('blog_id' => get_current_blog_id()));
		$markup = '<ul id="nerds-roster">';
		foreach ($users as $user) {
			$avatar = get_avatar($user->ID, '65');
			$author_url = get_author_posts_url($user->ID);
			$twitter = get_user_meta($user->ID, 'twitter', true);
			$twitter = (!empty($twitter))? $twitter : $author_url;
			$job_title = get_user_meta($user->ID, 'job_title', true);

			$user_posts_link = '';
			if (count_user_posts($user->ID) > 0)
				$user_posts_link = "<p><a href=\"$author_url\">{$user->first_name}'s posts</a></p>";

			$markup .= <<<EOD
<li>
	<div>
		<a href="$twitter">
			$avatar
			<span class="nerd-name">{$user->display_name}</span>
		</a>
		<p>$job_title<p>
		$user_posts_link
	</div>
</li>
EOD;
		}
		$markup .= '</ul>';
		echo $markup;
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		if (isset($instance['title']))
			$title = $instance[ 'title' ];
		else
			$title = 'INN News Nerds';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title']))? strip_tags($new_instance['title']) : '';
		return $instance;
	}


}

add_action('widgets_init', function(){
     register_widget('NerdsRosterWidget');
});
