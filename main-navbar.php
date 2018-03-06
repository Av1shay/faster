<?php
/**
 * Navbar content based on Bootstrap's navbar template.
 *
 * @see https://v4-alpha.getbootstrap.com/components/navbar/
 * @package faster
 */

$show_nav_search = get_option('faster_show_nav_search');
if ( $show_nav_search ) $mr_auto = 'mr-auto';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="container-fluid">
		<?php if ( is_front_page() && is_home() ) echo '<h1 class="site-title">'; ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand" rel="home">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				bloginfo( 'name' );
			}
			?>
		</a>
		<?php if ( is_front_page() && is_home() ) echo '</h1>'; ?>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<?php
			wp_nav_menu(array(
				'location' => 'primary-menu',
				'container' => false,
				'menu_class' => 'navbar-nav ' . $mr_auto,
				'walker' => new WP_Bootstrap_Navwalker()
			));

			if ( $show_nav_search ) : ?>
                <form id="primary-menu-search" class="form-inline my-2 my-lg-0" action="<?php echo esc_url( home_url( '/' ) ) ?>">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" value="<?php echo get_search_query() ?>" name="s" />
                    <input type="submit" class="btn btn-sm btn-outline-info" value="<?php echo esc_attr_x('Search', 'submit button', 'faster') ?>"/>
                </form>
            <?php endif; ?>
		</div>
	</div>
</nav>
