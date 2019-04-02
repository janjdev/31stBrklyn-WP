<?php
/*
    Plugin Name: Category Posts in Custom Menu
    Plugin URI: http://blog.dianakoenraadt.nl/en/category-posts-in-custom-menu/
    Description: This plugin replaces selected Category links / Post Tag links / Custom taxonomy links in a Custom Menu by a list of their posts/pages.
    Version: 1.2.3
    Author: Diana Koenraadt
    Author URI: http://www.dianakoenraadt.nl
    License: GPL2
*/

/*  Copyright 2012 Diana Koenraadt (email : dev7 at dianakoenraadt dot nl)

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

require_once( ABSPATH . 'wp-admin/includes/nav-menu.php' ); // Load all the nav menu interface functions

// Use version_compare because closures are not supported in PHP 5.2 or below
// http://wordpress.org/support/topic/unexpected-t_function-1
if (version_compare(phpversion(), '5.3.0') == -1)
{
	require('cpcm-functions52.php');	
}
else
{
	require('cpcm-functions.php');		
}

new CPCM_Manager;

class CPCM_Manager 
{

	// Added plugin options (source: http://wordpress.stackexchange.com/a/49797 with many thanks to stackexchange user onetrickpony)
    const OPTION_NAME = 'cpcm_options';
	const VERSION = '1.2.0';
	
	protected $options  = null;
	protected $defaults = array('version'     => self::VERSION);
				
	function __construct()
	{
		add_action( 'admin_enqueue_scripts', array( &$this, 'cpmp_wp_admin_nav_menus_css' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'cpmp_wp_admin_nav_menus_js' ) );
        add_filter( 'wp_edit_nav_menu_walker', array( &$this, 'cpcm_edit_nav_menu_walker' ), 1, 2 );
        add_filter( 'wp_nav_menu_objects', array( &$this, 'cpcm_replace_taxonomy_by_posts' ), 1, 2 );
        add_action( 'wp_update_nav_menu_item', array( &$this, 'cpcm_update_nav_menu_item' ), 1, 3 );  
		
		add_action('wp_nav_menu_item_custom_fields', array( &$this, 'cpcm_wp_nav_menu_item_custom_fields'), 10, 4 );

	} // function

	function CPCM_Manager()
	{
		$this->getOptions();
		$this->__construct();

	} // function
	
	// Update from no versioning or version 1.1 to 1.2.0
	private function Update120()
	{
		/*
		* Upgrade to 1.2.0
		* Update all custom fields: They should have a starting underscore
		*/
		$args = array('post_type' => 'nav_menu_item');
		$all_nav_menu_items = get_posts($args);
		
		foreach( $all_nav_menu_items as $nav_menu_item) 
		{
			$cpcm_unfold = get_post_meta($nav_menu_item->ID, "cpcm-unfold", true);
			if ($cpcm_unfold !== '')
			{
				update_post_meta( $nav_menu_item->ID, "_cpcm-unfold", $cpcm_unfold );
			}
			$cpcm_orderby = get_post_meta($nav_menu_item->ID, "cpcm-orderby", true);
			if ($cpcm_orderby !== '')
			{
				update_post_meta( $nav_menu_item->ID, "_cpcm-orderby", $cpcm_orderby );
			}
			$cpcm_order = get_post_meta($nav_menu_item->ID, "cpcm-order", true);
			if ($cpcm_order !== '')
			{
				update_post_meta( $nav_menu_item->ID, "_cpcm-order", $cpcm_order );
			}
			$cpcm_item_count = get_post_meta($nav_menu_item->ID, "cpcm-item-count", true);
			if ($cpcm_item_count !== '')
			{
				update_post_meta( $nav_menu_item->ID, "_cpcm-item-count", $cpcm_item_count );
			}
			$cpcm_item_skip = get_post_meta($nav_menu_item->ID, "cpcm-item-skip", true);
			if ($cpcm_item_skip !== '')
			{
				update_post_meta( $nav_menu_item->ID, "_cpcm-item-skip", $cpcm_item_skip );
			}
			$cpcm_item_titles = get_post_meta($nav_menu_item->ID, "cpcm-item-titles", true);
			if ($cpcm_item_titles !== '')
			{
				update_post_meta( $nav_menu_item->ID, "_cpcm-item-titles", $cpcm_item_titles );
			}
			$cpcm_remove_original_item = get_post_meta($nav_menu_item->ID, "cpcm-remove-original-item", true);
			if ($cpcm_remove_original_item !== '')
			{
				update_post_meta( $nav_menu_item->ID, "_cpcm-remove-original-item", $cpcm_remove_original_item );
			}
			$cpcm_subcategories = get_post_meta($nav_menu_item->ID, "cpcm-subcategories", true);
			if ($cpcm_subcategories !== '')
			{
				update_post_meta( $nav_menu_item->ID, "_cpcm-subcategories", $cpcm_subcategories );
			}
		}
		
		// Delete old custom fields
		delete_post_meta_by_key("cpcm-unfold");
		delete_post_meta_by_key("cpcm-orderby");
		delete_post_meta_by_key("cpcm-order");
		delete_post_meta_by_key("cpcm-item-count");
		delete_post_meta_by_key("cpcm-item-skip");
		delete_post_meta_by_key("cpcm-item-titles");
		delete_post_meta_by_key("cpcm-remove-original-item");
		delete_post_meta_by_key("cpcm-subcategories");
		/* End upgrade to 1.2.0 */
	}
	
	private function getOptions()
	{			
		// already did the checks
		if(isset($this->options))
		{
			return $this->options;    
		}

		// first call, get the options
		$options = get_option(self::OPTION_NAME);

		// options exist
		if($options !== false)
		{
			$version_11 = version_compare($options['version'], "1.1", "==");
			$new_version = version_compare($options['version'], self::VERSION, '<');
			$desync = array_diff_key($this->defaults, $options) !== array_diff_key($options, $this->defaults);

			// update options if version changed, or we have missing/extra (out of sync) option entries 
			if($new_version || $desync)
			{
				// I made a mistake in version 1.1, resulting in a version number but no upgrade performed.
				// If user comes from version 1.1, perform upgrade after all.
				if ($version_11)
				{
					$this->Update120();
				}
				
				$new_options = array();

				// check for new options and set defaults if necessary
				foreach($this->defaults as $option => $value)
				$new_options[$option] = isset($options[$option]) ? $options[$option] : $value;        				
				
				// update version info
				$new_options['version'] = self::VERSION;

				update_option(self::OPTION_NAME, $new_options);
				$this->options = $new_options;  
			}
			else // no update required
			{
				$this->options = $options;     
			}
		}
		else // either new install or version from before versioning existed 
		{			
			$this->Update120(); // update to first version with (proper) versioning
			
			update_option(self::OPTION_NAME, $this->defaults);
			$this->options = $this->defaults; 
		}

		return $this->options;
	}
	
	static function startsWith($haystack, $needle) 
	{
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}
	
	static function endsWith($haystack, $needle) 
	{
		// search forward starting from end minus needle length characters
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}
	
	static function cpmp_uninstall() 
	{
		// We're uninstalling, so delete all custom fields on nav_menu_items that the CPCM plugin added	
		delete_post_meta_by_key($nav_menu_item->db_id, '_cpcm-unfold');
		delete_post_meta_by_key($nav_menu_item->db_id, '_cpcm-orderby');
		delete_post_meta_by_key($nav_menu_item->db_id, '_cpcm-order');
		delete_post_meta_by_key($nav_menu_item->db_id, '_cpcm-item-count');
		delete_post_meta_by_key($nav_menu_item->db_id, '_cpcm-item-skip');
		delete_post_meta_by_key($nav_menu_item->db_id, '_cpcm-item-titles');
		delete_post_meta_by_key($nav_menu_item->db_id, '_cpcm-remove-original-item');
		delete_post_meta_by_key($nav_menu_item->db_id, '_cpcm-subcategories');
	} // function

	/* 
	* Add JS for div.cpmp-description to nav-menus.php
	*/
	function cpmp_wp_admin_nav_menus_js($hook)
	{
		// Check the hook so that the .js is only added to the .php file where we need it
		if( 'nav-menus.php' != $hook )
				return;
		wp_register_script( 'cpmp_wp_admin_nav_menus_js', plugins_url( 'cpmp_wp_admin_nav_menus.js' , __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'cpmp_wp_admin_nav_menus_js' );
	} // function

	/* 
	* Add CSS for div.cpmp-description to nav-menus.php
	*/
	function cpmp_wp_admin_nav_menus_css($hook)
	{
		// Check the hook so that the .css is only added to the .php file where we need it
		if( 'nav-menus.php' != $hook )
				return;
		wp_register_style( 'cpmp_wp_admin_nav_menus_css', plugins_url( 'cpmp_wp_admin_nav_menus.css' , __FILE__ ) );
		wp_enqueue_style( 'cpmp_wp_admin_nav_menus_css' );
	} // function

	/*
	* Extend Walker_Nav_Menu_Edit and use the extended class (CPCM_Walker_Nav_Menu_Edit) to add controls to nav-menus.php, 
	* specifically a div is added with class="cpmp-description". Everything else in this extended class is unchanged with 
	* respect to the parent class.
	*
	* Note that this extension of Walker_Nav_Menu_Edit is required because there are no hooks in its start_el method.
	* If hooks are provided in later versions of wordpress, the plugin needs to be updated to use these hooks, that would be 
	* much better.
	*/
	function cpcm_edit_nav_menu_walker( $walker_name, $menu_id ) 
	{
		if ( class_exists ( 'CPCM_Walker_Nav_Menu_Edit' ) ) 
		{
				return 'CPCM_Walker_Nav_Menu_Edit';
		}
		return 'Walker_Nav_Menu_Edit';
	} // function

	function replace_placeholders( $post, $string )
	{		
		$userdata = get_userdata($post->post_author);
		$string = str_replace( "%post_author", 	$userdata ? $userdata->data->display_name : '', $string);

		$thumb_image = wp_get_attachment_thumb_url( get_post_thumbnail_id($post->ID) );
		$string = str_replace( "%post_feat_image_thumb", 	$thumb_image, $string); // deprecated
		$string = str_replace( "%post_featured_image_thumb_url", 	$thumb_image, $string);
		if (trim($thumb_image) == true)
		{
			$string = str_replace( "%post_featured_image_thumb", 	"<img src=\"" . $thumb_image . "\" />", $string);
		}
		
		$featured_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		$string = str_replace( "%post_feat_image", 	$featured_image, $string); // deprecated
		$string = str_replace( "%post_featured_image_url", 	$featured_image, $string);
		if (trim($featured_image) == true)
		{
			$string = str_replace( "%post_featured_image", 	"<img src=\"" . $featured_image . "\" />", $string);
		}

		$string = str_replace( "%post_title", 	$post->post_title, 	$string);
		$string = str_replace( "%post_excerpt", 	$post->post_excerpt, 	$string);
		$string = str_replace( "%post_url", 	get_permalink($post->ID), 	$string);
		$string = str_replace( "%post_status", 	$post->post_status, 	$string);
		$string = str_replace( "%post_comment_count", 	$post->comment_count, 	$string);

		$string = replace_dates($post, $string);

		// Process custom fields last, so that users cannot override the built-in options like %post_featured_image with a custom field named "featured_image"	
		// As reported here https://wordpress.org/support/topic/category-lastes-post-with-images?replies=7#post-7052239
		$custom_field_keys = get_post_custom_keys($post->ID);
		foreach ( (array)$custom_field_keys as $key => $value ) 
		{
			$valuet = trim($value);
			if ( '_' == $valuet{0} )
			continue;
			$meta = get_post_meta($post->ID, $valuet, true);
			$valuet_str = str_replace(' ', '_', $valuet);
			// Check if post_myfield occurs
			if (substr_count($string, "%post_" . $valuet_str) > 0)
			{
				if (is_string($meta))
				{
					$string = str_replace( "%post_" . $valuet_str, $meta, $string);
				}
			}
		}
		
		// Allow users to plug-in their own wildcards
		// To facilitate https://wordpress.org/support/topic/show-category-post_category?replies=3
		if (has_filter('cpcm_replace_user_wildcards'))
		{
			$string = apply_filters( 'cpcm_replace_user_wildcards', $string, $post );			
		}
		
		// Remove remaining %post_ occurrences.
		$pattern = "/" . "((\((?P<lbrack>(\S*))))?" . "\%post_[-\w]*(?P<brackets>(\(((?P<inner>[^\(\)]*)|(?P>brackets))\)))" . "(((?P<rbrack>(\S*))\)))?" . "/";
		$string = preg_replace($pattern, '', $string);
		
		$pattern = "/%post_[-\w]*(?P<brackets>(\(((?P<inner>[^\(\)]*)|(?P>brackets))\)))?/";
		$string = preg_replace($pattern, '', $string);			
		
		$pattern = "/%post_[-\w]*(\([-\w]*\))?/"; 
		$string = preg_replace($pattern, '', $string);
		
		return $string;
	}
	
	/* 
	* Build the menu structure for display: Augment taxonomies (category, tags or custom taxonomies) that have been marked as such, by their posts. Optionally: remove original menu item.
	*/
	function cpcm_replace_taxonomy_by_posts( $sorted_menu_items, $args ) 
	{		
		$this->getOptions();
		
		$result = array();    
		$inc = 0;
		
		$menu_item_parent_map = array(); // Holds, for each menu item I that was removed, a link to the item that should become the new parent P of menu items under I
		foreach ( (array) $sorted_menu_items as $key => $menu_item ) 
		{		
			$menu_item->menu_order = $menu_item->menu_order + $inc;
		
			// Augment taxonomy object with a list of its posts: Append posts to $result
			// Optional: Remove the taxonomy object/original menu item itself.
			if ( $menu_item->type == 'taxonomy' && (get_post_meta($menu_item->db_id, "_cpcm-unfold", true) == '1')) 
			{					
				$query_arr = array();

				// Example:  Array ( [0] => Array ( [taxonomy] => category [field] => id [terms] => 3 ) ), i.e. get a category by id, where id = 3
				$query_arr['tax_query'] = array(
					'relation' => 'AND',
					array(
						'taxonomy'=>$menu_item->object,
						'field'=>'id',
						'terms'=>$menu_item->object_id
					)
				);
				
				$subcategory_behavior = get_post_meta($menu_item->db_id, "_cpcm-subcategories", true);
				switch ($subcategory_behavior) 
				{
					case "exclude": 
					
						// Subcategories (subtaxonomies) should be excluded, so append a query to tax_query that does exactly that
						$category_children = array_diff(get_term_children($menu_item->object_id, $menu_item->object),array(""));
						
						if (!empty($category_children))
						{												
							$query_arr['tax_query'][] = 
								array(
										'taxonomy'=>$menu_item->object,
										'terms' => $category_children,      
										'field' => 'id',
										'operator' => 'NOT IN' 
									);
						}
						break;
					case "flatten": /* No additional filtering */
					default: break;
				}
				
				// If _cpcm-unfold is true, the following custom fields exist:
				$query_arr['order'] = get_post_meta($menu_item->db_id, "_cpcm-order", true);
				$query_arr['orderby'] = get_post_meta($menu_item->db_id, "_cpcm-orderby", true);
				$query_arr['numberposts'] = get_post_meta($menu_item->db_id, "_cpcm-item-count", true); // default value of -1 returns all posts
				$query_arr['offset'] = get_post_meta($menu_item->db_id, "_cpcm-item-skip", true); // default value of 0 skips no posts
				$query_arr['posts_per_page'] = $query_arr['numberposts'] != '-1' ? get_post_meta($menu_item->db_id, "_cpcm-item-count", true) : 100000; // http://wordpress.stackexchange.com/a/13376 posts_per_page does not accept -1, but is a required argument to make offset work. So, whenever numberposts is positive, use that. Otherwise, use the proposed workaround on the SO topic.
				
				// Support for custom post types
				$tag = get_taxonomy($menu_item->object);
				$query_arr['post_type'] = $tag->object_type;
				
				// Allow plugin extensions that further modify the query
				if (has_filter('cpcm_filter_posts_query'))
				{
					$query_arr = apply_filters( 'cpcm_filter_posts_query', $query_arr, $menu_item );					
				}
				 
				$posts = get_posts( $query_arr );
				
				// Decide whether the original item needs to be preserved.
				$remove_original_item = get_post_meta($menu_item->db_id, "_cpcm-remove-original-item", true);
				$menu_item_parent = $menu_item->menu_item_parent;
				switch ($remove_original_item) 
				{
					case "always":
						if (empty($posts))
						{
							$inc -= 1;
							$menu_item_parent_map[$menu_item->db_id] = $menu_item->menu_item_parent;
						}
						else if (count($posts) == 1)
						{
							// If the menu-item should be removed, but it has exactly one post, then use this post as new parent for any menu items down the line.
							// Because we can't use posts as menu items (they don't have a db_id), reuse the menu_item object and transfer the post properties to the menu_item in the foreach loop
							// See {note 1} in foreach
							array_push($result,$menu_item);
						}
						else
						{
							$inc -= 1;
							$menu_item_parent_map[$menu_item->db_id] = $menu_item->menu_item_parent;
						}
						break;
					case "only if empty":
						if (empty($posts))
						{
							$inc -= 1;
							$menu_item_parent_map[$menu_item->db_id] = $menu_item->menu_item_parent;
						}
						else 
						{
							array_push($result,$menu_item);
						}
						break;
					case "never":
						array_push($result,$menu_item);
						break;
				}
				
				if (is_numeric($query_arr['numberposts']) && $query_arr['numberposts'] == '0')
				{
					continue;
				}
				
				// Set the menu_item_parent for the menu_item: If the parent item was removed, go up a level
				$current_parent_id = $menu_item->menu_item_parent;
				while (array_key_exists(strval($current_parent_id), $menu_item_parent_map) == 1)
				{
					$current_parent_id = $menu_item_parent_map[$current_parent_id];
				}
				$menu_item->menu_item_parent = $current_parent_id;

				foreach( (array) $posts as $pkey => $post ) 
				{
					$post = wp_setup_nav_menu_item( $post );
					
					// Set the menu_item_parent for the post: If the parent item was removed, go up a level
					$current_parent_id = $menu_item->db_id;
					while (array_key_exists(strval($current_parent_id), $menu_item_parent_map) == 1)
					{
						$current_parent_id = $menu_item_parent_map[$current_parent_id];
					}
					$post->menu_item_parent = $current_parent_id;

					// Transfer properties from the old menu item to the new one
					$post->target = $menu_item->target;
					//$post->classes = $menu_item->classes; // Don't copy the classes, because this will also copy the 'active' CSS class too all siblings of the selected menu item. http://wordpress.org/support/topic/active-css-class
					$post->classes = array_merge( $post->classes, (array) get_post_meta($menu_item->db_id, "_menu_item_classes", true) ); // copy custom css classes that the user specified under "CSS Classes (optional)"
					
					$post->xfn = $menu_item->xfn;
					$post->description = $menu_item->description;

					// Set the title of the new menu item
					$post->title = get_post_meta($menu_item->db_id, "_cpcm-item-titles", true);

					// Replace the placeholders in the title by the properties of the post
					$post->title = $this->replace_placeholders($post, $post->title);

					$inc += 1;
					
					$post->menu_order = $menu_item->menu_order + $inc;
				}
				
				// Solve https://wordpress.org/support/topic/works-with-41-as-far-as-i-can-tell?replies=5, regenerate all classes for the posts, and copy those classes to the menu_item that we're reusing.
				// Extend the items with classes.
				_wp_menu_item_classes_by_context( $posts );
				
				// Decorate the posts with the required data for a menu-item.
				if (count($posts) == 1 && $remove_original_item == "always")
				{
					// {note 1}
					// Do not use the post, but re-use the menu item instead.
					$menu_item->title = get_post_meta($menu_item->db_id, "_cpcm-item-titles", true);
					$menu_item->title = $this->replace_placeholders($post, $menu_item->title);
					$menu_item->url = get_permalink($post->ID);
					$menu_item->classes = $posts[0]->classes;
				}
				else
				{
					// Append the new menu_items to the menu array that we're building.
					$result = array_merge( $result, $posts );
				}
			}
			else 
			{
				// Other objects may have a parent that has been removed by cpcm. Fix that here.
				// Set the menu_item_parent for the menu_item: If the parent item was removed, go up a level
				$current_parent_id = $menu_item->menu_item_parent;
				while (array_key_exists(strval($current_parent_id), $menu_item_parent_map) == 1)
				{
					$current_parent_id = $menu_item_parent_map[$current_parent_id];
				}
				$menu_item->menu_item_parent = $current_parent_id;
				
				// Treat other objects as usual, but note that the position 
				// of elements in the array changes.
				array_push($result,$menu_item);
			}
		}

		unset( $sorted_menu_items );
		unset( $menu_item_parent_map );
		
		// Apply _wp_menu_item_classes_by_context not only to the $posts array, but to the whole result array so that the classes for the original menu items are regenerated as well. Solves: http://wordpress.org/support/topic/issue-with-default-wordpress-sidebar-menu and http://wordpress.org/support/topic/menu-do-not-include-the-current-menu-parent-class
		_wp_menu_item_classes_by_context( $result );
		
		return $result;
	} // function

	function __empty($string)
	{ 
		$string = trim($string); 
		if(!is_numeric($string)) return empty($string); 
		return FALSE; 
	}
 
	/*
	* Store the entered data in nav-menus.php by inspecting the $_POST variable again.
	*/
	function cpcm_update_nav_menu_item( $menu_id = 0, $menu_item_db_id = 0, $menu_item_data = array() ) 
	{
		// Only inspect the values if the $_POST variable contains data (the wp_update_nav_menu_item filter is applied in three other places, without a $_POST action)
		if ( ! empty( $_POST['menu-item-db-id'] ) ) 
		{
			// Only process nav_menu_items that actually had the CPCM checkbox option
			if ($menu_item_data['menu-item-type'] == 'taxonomy')
			{
				update_post_meta( $menu_item_db_id, '_cpcm-unfold', (!empty( $_POST['menu-item-cpcm-unfold'][$menu_item_db_id]) ) );
				update_post_meta( $menu_item_db_id, '_cpcm-orderby', (empty( $_POST['menu-item-cpcm-orderby'][$menu_item_db_id]) ? "none" : $_POST['menu-item-cpcm-orderby'][$menu_item_db_id]) );
				update_post_meta( $menu_item_db_id, '_cpcm-order', (empty( $_POST['menu-item-cpcm-order'][$menu_item_db_id]) ? "DESC" : $_POST['menu-item-cpcm-order'][$menu_item_db_id]) );
				update_post_meta( $menu_item_db_id, '_cpcm-item-count', (int) ($this->__empty( $_POST['menu-item-cpcm-item-count'][$menu_item_db_id]) ? "-1" : $_POST['menu-item-cpcm-item-count'][$menu_item_db_id]) );
				update_post_meta( $menu_item_db_id, '_cpcm-item-skip', (int) ($this->__empty( $_POST['menu-item-cpcm-item-skip'][$menu_item_db_id]) ? "0" : $_POST['menu-item-cpcm-item-skip'][$menu_item_db_id]) );
				update_post_meta( $menu_item_db_id, '_cpcm-item-titles', (empty( $_POST['menu-item-cpcm-item-titles'][$menu_item_db_id]) ? "%post_title" : $_POST['menu-item-cpcm-item-titles'][$menu_item_db_id]) );
				update_post_meta( $menu_item_db_id, '_cpcm-remove-original-item', (empty( $_POST['menu-item-cpcm-remove-original-item'][$menu_item_db_id]) ? "always" : $_POST['menu-item-cpcm-remove-original-item'][$menu_item_db_id]) );
				update_post_meta( $menu_item_db_id, '_cpcm-subcategories', (empty( $_POST['menu-item-cpcm-subcategories'][$menu_item_db_id]) ? "flatten" : $_POST['menu-item-cpcm-subcategories'][$menu_item_db_id]) );
			}
		} // if 
	} // function

	
	function cpcm_wp_nav_menu_item_custom_fields( $item_id, $item, $depth, $args) 
	{		
		$this->getOptions();
		$item_id = esc_attr( $item->ID );
		 
		/* BEGIN CATEGORY POSTS IN CUSTOM MENU */ 
		if( $item->type == 'taxonomy' ) : ?>
			<div class="cpmp-description">
				<p class="field-cpcm-unfold description description-wide">
					<label for="edit-menu-item-cpcm-unfold-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-cpcm-unfold-<?php echo $item_id; ?>" class="edit-menu-item-cpcm-unfold" name="menu-item-cpcm-unfold[<?php echo $item_id; ?>]" <?php checked( get_post_meta($item_id, "_cpcm-unfold", true), true )  ?> /> Create submenu containing links to posts<?php if ('Category' == $item->type_label) echo ' in this category'; else if (('Tag' == $item->type_label) || ('Post Tag' == $item->type_label)) echo ' with this tag'; else echo ' in this taxonomy'; ?>.
					</label>
				</p>
				<p class="field-cpcm-item-count description description-thin">
					<label for="edit-menu-item-cpcm-item-count-<?php echo $item_id; ?>">
						<?php _e( 'Number of Posts' ); ?><br />
						<input type="text" id="edit-menu-item-cpcm-item-count-<?php echo $item_id; ?>" class="widefat code edit-menu-item-cpcm-item-count" name="menu-item-cpcm-item-count[<?php echo $item_id; ?>]" value="<?php $item_count = get_post_meta($item_id, "_cpcm-item-count", true); echo $item_count != '' ? $item_count : '-1'; ?>" />
					</label>
				</p>
				<p class="field-cpcm-item-skip description description-thin">
					<label for="edit-menu-item-cpcm-item-skip-<?php echo $item_id; ?>">
						<?php _e( 'Skip Posts' ); ?><br />
						<input type="text" id="edit-menu-item-cpcm-item-skip-<?php echo $item_id; ?>" class="widefat code edit-menu-item-cpcm-item-skip" name="menu-item-cpcm-item-skip[<?php echo $item_id; ?>]" value="<?php $item_skip = get_post_meta($item_id, "_cpcm-item-skip", true); echo $item_skip != '' ? $item_skip : '0'; ?>" />
					</label>
				</p>
				<p class="field-cpcm-orderby description description-thin">
					<label for="edit-menu-item-cpcm-orderby-<?php echo $item_id; ?>">
						<?php _e( 'Order By' ); ?><br />
						<select id="edit-menu-item-cpcm-orderby-<?php echo $item_id; ?>" class="widefat edit-menu-item-cpcm-orderby" name="menu-item-cpcm-orderby[<?php echo $item_id; ?>]">
							<option value="none" <?php selected( get_post_meta($item_id, "_cpcm-orderby", true), "none" )  ?>><?php _e('None'); ?></option>
							<option value="ID" <?php selected( get_post_meta($item_id, "_cpcm-orderby", true), "ID" )  ?>><?php _e('ID'); ?></option>
							<option value="author" <?php selected( get_post_meta($item_id, "_cpcm-orderby", true), "author" )  ?>><?php _e('Author'); ?></option>
							<option value="title" <?php selected( get_post_meta($item_id, "_cpcm-orderby", true), "title" )  ?>><?php _e('Title'); ?></option>
							<option value="date" <?php selected( get_post_meta($item_id, "_cpcm-orderby", true), "date" )  ?>><?php _e('Date'); ?></option>
							<option value="modified" <?php selected( get_post_meta($item_id, "_cpcm-orderby", true), "modified" )  ?>><?php _e('Last Modified'); ?></option>
							<option value="parent" <?php selected( get_post_meta($item_id, "_cpcm-orderby", true), "parent" )  ?>><?php _e('Post/Page Parent ID'); ?></option>
							<option value="rand" <?php selected( get_post_meta($item_id, "_cpcm-orderby", true), "rand" )  ?>><?php _e('Random Order'); ?></option>
							<option value="comment_count" <?php selected( get_post_meta($item_id, "_cpcm-orderby", true), "comment_count" )  ?>><?php _e('Number of Comments'); ?></option>
							<option value="menu_order" <?php selected( get_post_meta($item_id, "_cpcm-orderby", true), "menu_order" ) ?>><?php _e('Menu Order'); ?></option>
						</select>
					</label>
				</p>
				<p class="field-cpcm-order description description-thin">
					<label for="edit-menu-item-cpcm-order-<?php echo $item_id; ?>">
						<?php _e( 'Order' ); ?><br />
						<select id="edit-menu-item-cpcm-order-<?php echo $item_id; ?>" class="widefat edit-menu-item-cpcm-order" name="menu-item-cpcm-order[<?php echo $item_id; ?>]">
							<option value="DESC" <?php selected( get_post_meta($item_id, "_cpcm-order", true), "DESC" )  ?>><?php _e('Descending'); ?></option>
							<option value="ASC" <?php selected( get_post_meta($item_id, "_cpcm-order", true), "ASC" )  ?>><?php _e('Ascending'); ?></option>
						</select>
					</label>
				</p>
				
				<?php if (is_taxonomy_hierarchical($item->object)) { ?>
					<p class="field-cpcm-remove-original-item description description-thin">
				<?php } else { ?>
					<p class="field-cpcm-remove-original-item description description-wide">
				<?php } ?>
						<label for="edit-menu-item-cpcm-remove-original-item-<?php echo $item_id; ?>">
							<?php _e( 'Remove original menu item' ); ?><br />
							<select id="edit-menu-item-cpcm-remove-original-item-<?php echo $item_id; ?>" class="widefat edit-menu-item-cpcm-remove-original-item" name="menu-item-cpcm-remove-original-item[<?php echo $item_id; ?>]">
								<option value="always" <?php selected( get_post_meta($item_id, "_cpcm-remove-original-item", true), "always" )  ?>><?php _e('Always'); ?></option>
								<option value="only if empty" <?php selected( get_post_meta($item_id, "_cpcm-remove-original-item", true), "only if empty" )  ?>><?php _e('Only if there are no posts'); ?></option>
								<option value="never" <?php selected( get_post_meta($item_id, "_cpcm-remove-original-item", true), "never" )  ?>><?php _e('Never'); ?></option>
							</select>
						</label>
					</p>
				<?php if (is_taxonomy_hierarchical($item->object)) { ?>
					<p class="field-cpcm-subcategories description description-thin">
						<label for="edit-menu-item-cpcm-subcategories-<?php echo $item_id; ?>">					
							<?php 
								if ('category' == $item->object) 
								{
									_e( 'Subcategory posts' ); 
								}
								else 
								{
									_e( 'Subtaxonomy posts' );	
								} ?><br />					
							<select id="edit-menu-item-cpcm-subcategories-<?php echo $item_id; ?>" class="widefat edit-menu-item-cpcm-subcategories" name="menu-item-cpcm-subcategories[<?php echo $item_id; ?>]">
								<option value="flatten" <?php selected( get_post_meta($item_id, "_cpcm-subcategories", true), "flatten" )  ?>><?php _e('Include'); ?></option>
								<option value="exclude" <?php selected( get_post_meta($item_id, "_cpcm-subcategories", true), "exclude" )  ?>><?php _e('Exclude'); ?></option>
							</select>
						</label>
					</p>
				<?php } ?>
								
				<?php
					do_action('cpcm_custom_fields', $item_id, $item );
				?>
				
				<p class="field-cpcm-item-titles description description-wide">
					<label for="edit-menu-item-cpcm-item-titles-<?php echo $item_id; ?>">
						<?php _e( 'Post Navigation Label' ); ?><br />
						<textarea id="edit-menu-item-cpcm-item-titles-<?php echo $item_id; ?>" class="widefat code edit-menu-item-cpcm-item-titles" name="menu-item-cpcm-item-titles[<?php echo $item_id; ?>]" rows="4"><?php $item_titles = get_post_meta($item_id, "_cpcm-item-titles", true); echo $item_titles != '' ? esc_attr( $item_titles ) : '%post_title' ?></textarea>
						<span class="description"><?php _e('The navigation label for generated post links may be customized using wildcars such as: %post_title, %post_author, %post_my_field (for custom field \'my field\' or \'my_field\'). See documentation.'); ?></span>
					</label>
				</p>
				
			</div>		
			
		<?php endif; 
		/* CATEGORY POSTS IN CUSTOM MENU END */		
	}
} // class

class CPCM_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit  
{
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) 
	{

		$item_output = '';
		parent::start_el( $item_output, $item, $depth, $args, $id );

		$position = '<p class="field-move';		
		global $wp_version;
		if ( $wp_version >= 4.7 ) 
		{
			$position = '<fieldset class="field-move';
		}

		$extra = $this->get_fields( $item, $depth, $args, $id );
		
		 $output .= str_replace( $position, $extra . $position, $item_output );
	} // function
	
	function get_fields( $item, $depth, $args = array(), $id = 0 ) 
	{
		ob_start();

		// conform to https://core.trac.wordpress.org/attachment/ticket/14414/nav_menu_custom_fields.patch
		do_action( 'wp_nav_menu_item_custom_fields', $id, $item, $depth, $args );
				
		return ob_get_clean();
	}
} // class

// Register the uninstall hook. Should be done after the class has been defined.
register_uninstall_hook( __FILE__, array( 'CPCM_Manager', 'cpmp_uninstall' ) );

?>
