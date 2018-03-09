<?php
/**
 * Template for displaying search forms
 */
?>

<form role="search" method="get" class="search-form form-inline" action="<?php echo esc_url( home_url( '/' ) ) ?>">
	<div class="form-group mx-sm-2">
		<label>
			<span class="sr-only"><?php echo _x( 'Search for:', 'label', 'faster' ) ?></span>
		</label>
		<input type="search" class="search-field form-control" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'faster' ) ?>" value="<?php echo get_search_query() ?>" name="s" />
	</div>
	<input type="submit" class="btn btn-sm btn-outline-info search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'faster' ) ?>" />
</form>