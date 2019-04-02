<?php

/**

 * The template for displaying search forms in gravit

 *

 * @package gravit

 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">

	<label>

		<span class="screen-reader-text"><?php _ex( 'Search for:', 'categories', 'gravit' ); ?></span>

		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'gravit' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s"><button class="fa fa-search" type="submit" role="button" value="<?php echo esc_attr_x( 'Search', 'submit button', 'gravit' ); ?>"></button>

	</label>

	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'gravit' ); ?>">

</form>

