<?php
/**
 * Handle meta boxes.
 *
 * @package faster
 */

/**
 * Main function to add meta boxes.
 */
function faster_add_metaboxes(){
	add_meta_box('faster_post_visibility',
		__('Visibility', 'faster'),
		'faster_post_visibility_ops',
		'post',
		'side',
		'low'
	);
}
add_action('add_meta_boxes', 'faster_add_metaboxes');

/**
 * Display post visibility fields.
 *
 * @param $post WP_Post
 */
function faster_post_visibility_ops($post){
	wp_nonce_field( 'faster_save_post_visibility_ops', 'faster_save_post_visibility' );
	$post_visibility = get_post_meta($post->ID,'faster_post_visibility', true);
	$selected_roles = get_post_meta($post->ID,'faster_post_visibility_roles', true) ? unserialize(get_post_meta($post->ID,'faster_post_visibility_roles', true)) : false;
	?>
	<p><small><?php _e('Who can see this post?', 'faster') ?></small></p>
	<input id="visibility-all" type="radio" name="faster_post_visibility" <?php checked($post_visibility, 'visibility_all'); ?> value="visibility_all"/>
	<label for="visibility-all"><?php _e('All', 'faster'); ?></label><br/>
	<input id="visibility-logged-in" type="radio" name="faster_post_visibility" <?php checked($post_visibility, 'visibility_logged_in'); ?> value="visibility_logged_in"/>
	<label for="visibility-logged-in"><?php _e('Logged-In Users', 'faster'); ?></label><br/>
	<input id="visibility-define-roles" type="radio" name="faster_post_visibility" <?php checked($post_visibility, 'visibility_define_roles'); ?> value="visibility_define_roles"/>
	<label for="visibility-define-roles"><?php _e('Define Roles', 'faster'); ?></label><br/>
	<label for="visibility-roles" class="screen-reader-shortcut"><?php _e('Roles', 'faster'); ?></label>
	<select id="visibility-roles" name="visibility_roles[]" class="hide" multiple style="min-width: 210px; min-height: 115px;">
		<?php faster_wp_dropdown_roles($selected_roles) ?>
	</select>
	<?php
}

/**
 * Save post visibility values.
 *
 * @param $post_id
 */
function faster_save_post_visibility_ops($post_id){
	if ( ! isset($_POST['faster_save_post_visibility']) ) {
		return;
	}
	$nonce = $_POST['faster_save_post_visibility'];
	if ( ! wp_verify_nonce($nonce, 'faster_save_post_visibility_ops') ) {
		return;
	}
	if ( (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) || ! current_user_can('edit_post') ) {
		return;
	}

	if ( ! isset($_POST['faster_post_visibility']) ) { // set default
		update_post_meta($post_id, 'faster_post_visibility', 'visibility_all');
	} else {
		update_post_meta( $post_id, 'faster_post_visibility', $_POST['faster_post_visibility'] );
		if ( $_POST['faster_post_visibility'] == 'visibility_define_roles' ) {
			update_post_meta( $post_id, 'faster_post_visibility_roles', serialize($_POST['visibility_roles']) );
		}
	}
}
add_action('save_post', 'faster_save_post_visibility_ops');