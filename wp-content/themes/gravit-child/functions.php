<?php
function add_custom_types_to_tax( $query ) {
   if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
     // Get all your post types
     $post_types = get_post_types();
     $query->set( 'post_type', $post_types );
    return $query;
   }
}
add_filter( 'pre_get_posts', 'add_custom_types_to_tax' );

register_nav_menus( array(

		'artist' => __( 'Artist Menu', 'gravit-child'),

		'programs' => __('Programming Menu', 'gravit-child'),

		'program_schedule' => __('Program Schedule Menu', 'gravit-child'),

		'projects' => __('Projects Menu', 'gravit-child'),

	) );





function brklyn_widgets_init() {

	register_sidebar( array(

		'name'          => __( 'SidebarRight', 'gravit-child' ),

		'id'            => 'sidebar-2',

		'before_widget' => '<aside id="%1$s" class="widget %2$s">',

		'after_widget'  => '</aside>',

		'before_title'  => '<h1 class="widget-title">',

		'after_title'   => '</h1>',

	) );

	register_sidebar( array(

		'name'          => __( 'SidebarNews', 'gravit-child' ),

		'id'            => 'sidebar-3',

		'before_widget' => '<aside id="%1$s" class="widget %2$s">',

		'after_widget'  => '</aside>',

		'before_title'  => '<h1 class="widget-title">',

		'after_title'   => '</h1>',

	) );

	register_sidebar( array(

		'name'          => __( 'SidebarWhatsup', 'gravit-child' ),

		'id'            => 'sidebar-4',

		'before_widget' => '<aside id="%1$s" class="widget %2$s">',

		'after_widget'  => '</aside>',

		'before_title'  => '<h1 class="widget-title">',

		'after_title'   => '</h1>',

	) );

	register_sidebar( array(

		'name'          => __( 'Sidebarartist', 'gravit-child' ),

		'id'            => 'sidebar-5',

		'before_widget' => '<aside id="%1$s" class="widget %2$s">',

		'after_widget'  => '</aside>',

		'before_title'  => '<h1 class="widget-title">',

		'after_title'   => '</h1>',

	) );

	register_sidebar( array(

		'name'          => __( 'Sidebarprogram', 'gravit-child' ),

		'id'            => 'sidebar-6',

		'before_widget' => '<aside id="%1$s" class="widget %2$s">',

		'after_widget'  => '</aside>',

		'before_title'  => '<h1 class="widget-title">',

		'after_title'   => '</h1>',

	) );

	register_sidebar( array(

		'name'          => __( 'SidebarContact', 'brklyn-child' ),

		'id'            => 'sidebar-7',

		'before_widget' => '<aside id="%1$s" class="widget %2$s">',

		'after_widget'  => '</aside>',

		'before_title'  => '<h1 class="widget-title">',

		'after_title'   => '</h1>',

	) );

	register_sidebar( array(

		'name'          => __( 'SidebarAbout', 'brklyn-child' ),

		'id'            => 'sidebar-8',

		'before_widget' => '<aside id="%1$s" class="widget %2$s">',

		'after_widget'  => '</aside>',

		'before_title'  => '<h1 class="widget-title">',

		'after_title'   => '</h1>',

	) );

	register_sidebar( array(

		'name'          => __( 'SidebarFooter', 'brklyn-child' ),

		'id'            => 'sidebar-9',

		'before_widget' => '<aside id="%1$s" class="widget %2$s">',

		'after_widget'  => '</aside>',

		'before_title'  => '<h1 class="widget-title">',

		'after_title'   => '</h1>',

	) );

}

add_action( 'widgets_init', 'brklyn_widgets_init' );



/**

 * Enqueue scripts and styles.

 */





function my_theme_enqueue_styles() {



    $parent_style = 'parent-style';



    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );

    wp_enqueue_style( 'child-style',

        get_stylesheet_directory_uri() . '/style.css',

        array( $parent_style )

    );

}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );



function brklyn_theme_scripts(){

	wp_enqueue_script('jquery');



	wp_enqueue_style('brklyn_google_font', '//fonts.googleapis.com/css?family=Open+Sans:300');

	wp_enqueue_style('sweet', get_stylesheet_directory_uri() . '/css/sweetalert.css');

	wp_enqueue_style('brklyn_google_logo_font', '//fonts.googleapis.com/css?family=Dosis:400,800');
	wp_enqueue_style('fontAwesomeBootStrap', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
	wp_enqueue_style('31stBrklyn_font', 'http://31stbrklyn.com/wp-content/themes/gravit-child/fonts/font.css');




	wp_enqueue_style( 'grid', get_stylesheet_directory_uri() . '/css/grid.css' );



	// wp_enqueue_style( 'style', get_stylesheet_directory() );



	wp_enqueue_style('form', get_stylesheet_directory_uri() . '/css/form.css' );



	wp_enqueue_style('notify', get_stylesheet_directory_uri() . '/css/notifyme.css' );


	wp_enqueue_style('flexslider', get_stylesheet_directory_uri() . '/css/flexslider.css' );
	

	wp_enqueue_script('gravit-form-contact', get_stylesheet_directory_uri() . '/js/form.js', array(), '20162007', true );



	wp_enqueue_script('classie', get_stylesheet_directory_uri() . '/js/classie.js', array(), '20162107', true);



	wp_enqueue_script('gravit-clipboard', get_stylesheet_directory_uri() . '/js/clipboard.min.js', array(), '20160701', true);



	wp_enqueue_script('map', get_stylesheet_directory_uri() . '/js/map.js', array(), '20160721', true);

	wp_enqueue_script('slider', get_stylesheet_directory_uri() . '/js/jquery.flexslider-min.js', array(), '20160905', true);


	wp_enqueue_script('main', get_stylesheet_directory_uri() . '/js/main.js', array(), '20160723', true);


	 wp_enqueue_script('notifyme', get_stylesheet_directory_uri() . '/js/notifyme.js', array(), '20160724', true);

	
	 wp_enqueue_script('notifyingMe', get_stylesheet_directory_uri() . '/js/notifyingMe.js', array(), '20160722', true);
    	wp_add_inline_script( 'notifyingMe', 'jQuery("#the_form").notifyingMe();' );
    	
    wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js');

    wp_enqueue_script('sweetalert', get_stylesheet_directory_uri() . '/js/sweetalert.min.js', array(), '20170801', true);

}

add_action('wp_enqueue_scripts', 'brklyn_theme_scripts');

/*Remonve pages from search results*/

function searchfilter($query) {
 if ($query->is_search ) {
    $query->set('post_type',array('post','programming_projects', 'associate_artist'));
  }
return $query;
}
add_filter('pre_get_posts','searchfilter');


add_filter('posts_orderby', 'group_by_post_type', 10, 2);
function group_by_post_type($orderby, $query) {
    global $wpdb;
    if ($query->is_search) {
        return $wpdb->posts . '.post_type DESC';
    }
    // provide a default fallback return if the above condition is not true
    return $orderby;
}

class Menu_With_Description extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';     
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes; 
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"'; 
        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>'; 
        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : ''; 
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '<br /><span class="sub"><p>' . $item->description . '</p></span>';
		$item_output .= '</a>';
        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}
?>