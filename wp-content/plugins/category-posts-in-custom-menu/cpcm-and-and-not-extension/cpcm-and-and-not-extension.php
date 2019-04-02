<?php
/*
    Plugin Name: CPCM Extension AND/AND NOT category
    Plugin URI: http://blog.dianakoenraadt.nl/en/category-posts-in-custom-menu/
    Description: Sample extension, at the request of joah.nl
    Version: 0.1
    Author: Diana Koenraadt
    Author URI: http://www.dianakoenraadt.nl
    License: GPL2
*/

/*  Copyright 2016 Diana Koenraadt (email : dev7 at dianakoenraadt dot nl)

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

/*
* Custom fields to add to the Category Posts in Custom Menu container in Appearance > Menus
*/
function cpcm_and_and_not_custom_fields($item_id, $item)
{	
	$item_id = esc_attr( $item->ID );
		
	?>
		<p class="field-and-category description description-thin">
			<label for="edit-menu-item-and-category-<?php echo $item_id; ?>">
				<?php _e( 'AND category slugs (space separated)' ); ?><br />
				<input type="text" id="edit-menu-item-and-category-<?php echo $item_id; ?>" class="widefat code edit-menu-item-and-category" name="menu-item-and-category[<?php echo $item_id; ?>]" value="<?php echo get_post_meta($item_id, "_and_category", true); ?>" />
			</label>
		</p>
		<p class="field-and-not-category description description-thin">
			<label for="edit-menu-item-and-not-category-<?php echo $item_id; ?>">
				<?php _e( 'AND NOT category slugs (space separated)' ); ?><br />
				<input type="text" id="edit-menu-item-and-not-category-<?php echo $item_id; ?>" class="widefat code edit-menu-item-and-not-category" name="menu-item-and-not-category[<?php echo $item_id; ?>]" value="<?php echo get_post_meta($item_id, "_and_not_category", true); ?>" />
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
function cpcm_and_and_not_filter_posts_query($query_arr, $menu_item)
{
	// Override the taxonomy query with a custom query, if applicable
	$and_category = get_post_meta($menu_item->db_id, "_and_category", true); 
	if (!empty($and_category))
	{
		// Append AND clause for each category in this clause (main plugin already sets relation to AND)
		$and_cats = explode(" ", $and_category);
		foreach($and_cats as $key => $value)
		{
			$query_arr['tax_query'][] = array(
							'taxonomy' => 'category',
							'field' => 'slug',
							'terms' => $value
						);				
		}
	}
	$and_not_category = get_post_meta($menu_item->db_id, "_and_not_category", true); 
	if (!empty($and_not_category))
	{
		// Append AND NOT clause for this category (main plugin already sets relation to AND)
		$and_not_cats = explode(" ", $and_not_category);
		foreach($and_not_cats as $key => $value)
		{
			$query_arr['tax_query'][] = array(
							'taxonomy' => 'category',
							'field' => 'slug',
							'terms' => $value,
							'operator' => 'NOT IN'
						);			
		}		
	}
	
	return $query_arr;
}

/*
* Process when the user clicks "Save Menu" and store the values for your new fields
*/
function cpcm_and_and_not_update_nav_menu_item($menu_id = 0, $menu_item_db_id = 0, $menu_item_data = array()) 
{
	// Only inspect the values if the $_POST variable contains data (the wp_update_nav_menu_item filter is applied in three other places, without a $_POST action)
	if ( ! empty( $_POST['menu-item-db-id'] ) ) {
		update_post_meta( $menu_item_db_id, '_and_category', ( !isset($_POST['menu-item-and-category'][$menu_item_db_id]) ? '' : $_POST['menu-item-and-category'][$menu_item_db_id] ) );
		update_post_meta( $menu_item_db_id, '_and_not_category', ( !isset($_POST['menu-item-and-not-category'][$menu_item_db_id]) ? '' : $_POST['menu-item-and-not-category'][$menu_item_db_id] ) );
	}
}

/*
* Be sure to delete your custom CPCM fields when your extension plugin is uninstalled
*/
function cpcm_and_and_not_plugin_uninstall() {
	delete_post_meta_by_key($nav_menu_item->db_id, '_and_category');
	delete_post_meta_by_key($nav_menu_item->db_id, '_and_not_category');
}

// Don't forget to register the required actions, filter and uninstall hook.
add_action( 'cpcm_custom_fields', 'cpcm_and_and_not_custom_fields', 10, 2 );
add_filter( 'cpcm_filter_posts_query', 'cpcm_and_and_not_filter_posts_query', 10, 2 );
add_action( 'wp_update_nav_menu_item', 'cpcm_and_and_not_update_nav_menu_item', 1, 3 );  
register_uninstall_hook( __FILE__, 'cpcm_and_and_not_plugin_uninstall' );
