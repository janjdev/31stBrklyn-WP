<?php
/*
    Plugin Name: Sample CPCM Extension
    Plugin URI: http://blog.dianakoenraadt.nl/en/category-posts-in-custom-menu/
    Description: This plugin shows how to extend Category Posts in Custom Menu with your own options
    Version: 0.1
    Author: Diana Koenraadt
    Author URI: http://www.dianakoenraadt.nl
    License: GPL2
*/

/*  Copyright 2015 Diana Koenraadt (email : dev7 at dianakoenraadt dot nl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/* ================= The following four functions show how to extend the backend with your own controls and use them in the posts query ================= */

/*
* Custom fields to add to the Category Posts in Custom Menu container in Appearance > Menus
*/
function my_cpcm_custom_fields($item_id, $item)
{	
	$item_id = esc_attr( $item->ID );
		
	?>
		<p class="field-excludeposts description description-wide">
			<label for="edit-menu-item-excludeposts-<?php echo $item_id; ?>">
				<?php _e( 'Exclude Posts by Post ID' ); ?><br />
				<input type="text" id="edit-menu-item-excludeposts-<?php echo $item_id; ?>" class="widefat code edit-menu-item-excludeposts" name="menu-item-excludeposts[<?php echo $item_id; ?>]" value="<?php echo get_post_meta($item_id, "_excludeposts", true); ?>" />
			</label>
		</p>
	<?php
}

/*
* This filter is called when the menu_item is of taxonomy type and the "Create submenu containing links to posts in this category" is checked
* Here, you can add or modify the posts query to your liking.
*
* query_arr: The original get_posts query, as built by CPCM
* menu_item: The nav_menu_item for which the query is being built. Guaranteed to have type 'taxonomy' and to have been checked as 'Replace with posts in the taxonomy'.
*
* Returns: the modified query-arr
*/
function my_cpcm_filter_posts_query($query_arr, $menu_item)
{
	$query_arr['exclude'] = get_post_meta($menu_item->db_id, "_excludeposts", true); // default value of '' excludes no posts
	return $query_arr;
}

/*
* Process when the user clicks "Save Menu" and store the values for your new fields
*/
function sample_update_nav_menu_item($menu_id = 0, $menu_item_db_id = 0, $menu_item_data = array()) 
{
	// Only inspect the values if the $_POST variable contains data (the wp_update_nav_menu_item filter is applied in three other places, without a $_POST action)
	if ( ! empty( $_POST['menu-item-db-id'] ) ) {
		update_post_meta( $menu_item_db_id, '_excludeposts', ( !isset($_POST['menu-item-excludeposts'][$menu_item_db_id]) ? '' : $_POST['menu-item-excludeposts'][$menu_item_db_id] ) );
	}
}

/*
* Be sure to delete your custom CPCM fields when your extension plugin is uninstalled
*/
function sample_plugin_uninstall() {
	delete_post_meta_by_key($nav_menu_item->db_id, '_excludeposts');
}

// Don't forget to register the required actions, filter and uninstall hook.
add_action( 'cpcm_custom_fields', 'my_cpcm_custom_fields', 10, 2 );
add_filter( 'cpcm_filter_posts_query', 'my_cpcm_filter_posts_query', 10, 2 );
add_action( 'wp_update_nav_menu_item', 'sample_update_nav_menu_item', 1, 3 );  
register_uninstall_hook( __FILE__, 'sample_plugin_uninstall' );

/* ================= The following function shows how to add your own custom wildcards to the plugin ================= */

/*
* Called after all default CPCM wildcards have been processed.
*
* string: The post title string as processed so far.
* post: The post whose title string it concerns.
*
* Returns: The modified string
*/
function my_cpcm_replace_user_wildcards( $string, $post )
{
	return str_replace( "%hello_world" , "Hello, World!", $string);
}

// Don't forget to register the required filter.
add_filter( 'cpcm_replace_user_wildcards', 'my_cpcm_replace_user_wildcards', 10, 2);

/* ================= The following function shows how to plug-in your own menu-modifying function ================= */

/*
* Called after CPCM and other plugins have modified the menu.
*/
function my_nav_menu_objects( $sorted_menu_items, $args ) 
{
	// Annotate all menu items with children with a CSS class 'menu-item-has-children'
	$menu_items_with_children = array();
	
	foreach ( (array) $sorted_menu_items as $key => $menu_item ) 
	{
		if ($menu_item->menu_item_parent)
		{
			$menu_items_with_children[$menu_item->menu_item_parent] = true;
		}
	}
	
	foreach ( (array) $sorted_menu_items as $key => $menu_item ) 
	{
		if (isset($menu_items_with_children[$menu_item->db_id]))
		{
			$menu_item->classes = array_merge( $menu_item->classes, array("menu-item-has-children") );
		}
	}
	
	return $sorted_menu_items;
}

// Don't forget to register the required filter. 
add_filter( 'wp_nav_menu_objects', 'my_nav_menu_objects', 10 /* LAST! */, 2);