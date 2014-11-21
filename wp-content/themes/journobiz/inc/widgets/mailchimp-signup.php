<?php

class inn_mailchimp_signup_widget extends WP_Widget {

	function inn_mailchimp_signup_widget() {
		$widget_opts = array(
			'classname' => 'mailchimp-signup-widget',
			'description'=> __('Display a simple mailchimp signup form, perhaps at the bottom of your story pages.', 'largo')
		);
		$this->WP_Widget('mailchimp_signup_widget', __('Simple MailChimp Signup Widget', 'largo'),$widget_opts);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __('Subscribe to our newsletter', 'largo') : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		if ( $instance['cta'] )
			echo '<p>' . $instance['cta'] . '</p>';
		?>
			<form action="//investigativenewsnetwork.us1.list-manage.com/subscribe/post?u=<?php echo $instance['user_id']; ?>&amp;id=<?php echo $instance['form_id']; ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<fieldset>
					<input required type="email" value="" name="EMAIL" class="required email_address" id="mce-EMAIL" placeholder="Email address">
					<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn submit">
					<div style="position: absolute; left: -5000px;"><input type="text" name="<?php echo $instance['user_id']; ?>_<?php echo $instance['form_id']; ?>" tabindex="-1" value=""></div>
					<div class="error"></div>
				</fieldset>
			</form>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cta'] = strip_tags( $new_instance['cta'] );
		$instance['user_id'] = strip_tags( $new_instance['user_id'] );
		$instance['form_id'] = strip_tags( $new_instance['form_id'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' 	=> __('Subscribe to our newsletter', 'largo'),
			'cta' 		=> __('Like our stories? Get weekly updates in your inbox.', 'largo'),
			'user_id' 	=> '81670c9d1b5fbeba1c29f2865',
			'form_id' 	=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'cta' ); ?>"><?php _e( 'Call to action text:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('cta'); ?>" name="<?php echo $this->get_field_name('cta'); ?>" type="text" value="<?php echo $instance['cta']; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'user_id' ); ?>"><?php _e( 'MailChimp User ID:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('user_id'); ?>" name="<?php echo $this->get_field_name('user_id'); ?>" type="text" value="<?php echo $instance['user_id']; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'form_id' ); ?>"><?php _e( 'MailChimp Form ID:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('form_id'); ?>" name="<?php echo $this->get_field_name('form_id'); ?>" type="text" value="<?php echo $instance['form_id']; ?>">
		</p>

		<?php
	}

}