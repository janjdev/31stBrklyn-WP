<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package gravit
 */

/**
 * Get our wp_nav_menu() fallback, wp_pag_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function gravit_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'gravit_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function gravit_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'gravit_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function gravit_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() ) {
		return $title;
	}

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$sit_edescription = get_bloginfo( 'description', 'display' );
	if ( $sit_edescription && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $sit_edescription";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'gravit' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'gravit_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call th_epost() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function gravit_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'gravit_setup_author' );

/**
 * Adding Page Meta Box for Social Media Profiles
 */
 
add_action( 'add_meta_boxes', 'gravit_meta_box_add' );  
function gravit_meta_box_add() {  
    add_meta_box( 'social', __( 'Social Profiles', 'gravit' ), 'gravit_meta_box', 'page', 'normal', 'high' );  
}  
  
function gravit_meta_box() {  
    _e( 'If you are using the About Me Template enter your social profile links here.', 'gravit' );     

 	global $post;  
	wp_nonce_field( 'gravit_meta_box_nonce', 'meta_box_nonce' ); 
	?>  
	<p>  
	    <label for="gravit_facebook"><?php _e( 'Facebook', 'gravit' );  ?></label><br />  
	    <input type="text" name="gravit_facebook" id="gravit_facebook" value="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_facebook', true ) ); ?>" />  
	</p>
	<p>  
	    <label for="gravit_twitter"><?php _e( 'Twitter', 'gravit' );  ?></label><br />   
	    <input type="text" name="gravit_twitter" id="gravit_twitter" value="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_twitter', true ) ); ?>" />  
	</p>
	<p>  
	    <label for="gravit_google-plus"><?php _e( 'Google+', 'gravit' );  ?></label><br /> 
	    <input type="text" name="gravit_google-plus" id="gravit_google-plus" value="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_google-plus', true ) ); ?>" />  
	</p>
	<p>  
	    <label for="gravit_linkedin"><?php _e( 'LinkedIn', 'gravit' );  ?></label><br />   
	    <input type="text" name="gravit_linkedin" id="gravit_linkedin" value="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_linkedin', true ) ); ?>" />  
	</p>
	<p>  
	    <label for="gravit_youtube"><?php _e( 'YouTube', 'gravit' );  ?></label><br />   
	    <input type="text" name="gravit_youtube" id="gravit_youtube" value="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_youtube', true ) ); ?>" />  
	</p>
	<p>  
	    <label for="gravit_instagram"><?php _e( 'Instagram', 'gravit' );  ?></label><br />  
	    <input type="text" name="gravit_instagram" id="gravit_instagram" value="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_instagram', true ) ); ?>" />  
	</p>
	<p>  
	    <label for="gravit_pinterest"><?php _e( 'Pinterest', 'gravit' );  ?></label><br />  
	    <input type="text" name="gravit_pinterest" id="gravit_pinterest" value="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_pinterest', true ) ); ?>" />  
	</p>


<?php }  

add_action( 'save_post', 'gravit_meta_box_save' );  
function gravit_meta_box_save( $post_id )  
{  
	global $post;
    // Bail if we're doing an auto save  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
     
    // if our nonce isn't there, or we can't verify it, bail 
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'gravit_meta_box_nonce' ) ) return; 
     
    // if our current user can't edit this post, bail  
    if ( ! current_user_can( 'edit_post', $post->ID ) )
    return;

    // Make sure your data is set before trying to save it  
    if( isset( $_POST['gravit_facebook'] ) )  {
        update_post_meta( $post_id, 'gravit_facebook', $_POST['gravit_facebook'] );  
    }

      if( isset( $_POST['gravit_twitter'] ) )  {
        update_post_meta( $post_id, 'gravit_twitter', $_POST['gravit_twitter'] );  
    }

      if( isset( $_POST['gravit_google-plus'] ) )  {
        update_post_meta( $post_id, 'gravit_google-plus', $_POST['gravit_google-plus'] );  
    }

      if( isset( $_POST['gravit_linkedin'] ) )  {
        update_post_meta( $post_id, 'gravit_linkedin', $_POST['gravit_linkedin'] );  
    }

      if( isset( $_POST['gravit_youtube'] ) )  {
        update_post_meta( $post_id, 'gravit_youtube', $_POST['gravit_youtube'] );  
    }

       if( isset( $_POST['gravit_instagram'] ) )  {
        update_post_meta( $post_id, 'gravit_instagram', $_POST['gravit_instagram'] );  
    }  

       if( isset( $_POST['gravit_pinterest'] ) )  {
        update_post_meta( $post_id, 'gravit_pinterest', $_POST['gravit_pinterest'] );  
    }            
} ?>