<?php

add_action( 'show_user_profile', 'more_profile_info' );
add_action( 'edit_user_profile', 'more_profile_info' );
function more_profile_info($user) { ?>
	<h3>More profile information</h3>
	<table class="form-table">
		<tr>
			<th><label for="job_title">Job title</label></th>
			<td>
				<input type="text" name="job_title" id="job_title" value="<?php echo esc_attr( get_the_author_meta( 'job_title', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your job title.</span>
			</td>
		</tr>
		<tr>
			<th><label for="staff_widget">Staff Widget</label></th>
			<td>
				<input type="checkbox" name="hide" id="hide" value="no" <?php if (esc_attr( get_the_author_meta( "hdie", $user->ID )) == "yes") echo "checked"; ?> /><label for="hide"><?php _e("Hide in Widget"); ?></label><br />
				<input type="checkbox" name="emeritus" id="emeritus" value="no" <?php if (esc_attr( get_the_author_meta( "emeritus", $user->ID )) == "yes") echo "checked"; ?> /><label for="emeritus"><?php _e("Emeritus?"); ?></label><br />
				<input type="checkbox" name="honorary" id="honorary" value="no" <?php if (esc_attr( get_the_author_meta( "honorary", $user->ID )) == "yes") echo "checked"; ?> /><label for="honorary"><?php _e("Honorary?"); ?></label>
			</td>
		</tr>
	</table>
<?php }

add_action('personal_options_update', 'save_more_profile_info');
add_action('edit_user_profile_update', 'save_more_profile_info');
function save_more_profile_info($user_id) {
    if (!current_user_can('edit_user', $user_id )) { return false; }
    update_user_meta($user_id, 'job_title', $_POST['job_title']);
    update_user_meta($user_id, 'hide', $_POST['hide']);
    update_user_meta($user_id, 'emeritus', $_POST['emeritus']);
    update_user_meta($user_id, 'honorary', $_POST['honorary']);
}
