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
			$twitter = get_user_meta($user->ID, 'twitter', true);
			$author_url = ($twitter)? $twitter : get_author_posts_url($user->ID, $user->display_name);
			$job_title = get_user_meta($user->ID, 'job_title', true);

			$markup .= <<<EOD
<li>
	<div>
		<a href="$author_url">
			$avatar
			<span class="nerd-name">{$user->display_name}</span>
		</a>
		<p>$job_title<p>
		<p><a href="$author_url">{$user->first_name}'s posts</a></p>
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
