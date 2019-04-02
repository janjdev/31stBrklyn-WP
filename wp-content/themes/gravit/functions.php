<?php
/**
 * gravit functions and definitions
 *
 * @package gravit
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1000; /* pixels */
}

if ( ! function_exists( 'gravit_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function gravit_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on gravit, use a find and replace
	 * to change 'gravit' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'gravit', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'gravit' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'quote', 'link', 'status', 'audio', 'chat' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'gravit_custom_background_args', array(
	'default-color' => 'ffffff',
	'default-image' => '',
	) ) );

	// Add Featured Post Image Support //
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1920, 9999, true ); // default Post Thumbnail dimensions 

	// Setup the WordPress core custom Header Image feature.	
	$defaults = array(
	'flex-height' => true,
	'height' => 99,
	'flex-width' => true,
	'width' => 300,
	'default-image' => trailingslashit( get_template_directory_uri() ) . 'images/logo.png',
	'header-text' => false,
	);
	add_theme_support( 'custom-header', $defaults );

}
endif; // gravit_setup
add_action( 'after_setup_theme', 'gravit_setup' );

/**
 * Register widgetized area.
 */
function gravit_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'gravit' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'gravit_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function gravit_scripts() {
	wp_enqueue_style( 'gravit-style', get_stylesheet_uri() );

	wp_enqueue_script( 'jquery' ); // Load Wordpress jQuery

	wp_enqueue_style( 'gravit-google-font', '//fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic|Open+Sans:400|Roboto|Lustria:400,700');

	wp_enqueue_style( 'gravit-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );

	wp_enqueue_script( 'gravit-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'gravit-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { // just load it when we need it
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gravit_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/*  Add responsive container to videos
/* ------------------------------------ */ 
function gravit_embed_html( $html ) {

	//looks for an iFrame on the page
		$pattern = '/<iframe.*?src=".*?(vimeo|youtu\.?be).*?".*?<\/iframe>/';
		preg_match_all($pattern, $html, $matches);
	
		foreach ($matches[0] as $match) {
			// iFrame found, now we wrap it in a DIV...
			$wrappedframe = '<div class="video-wrapper">'. $match .'</div>';
	
			// Swap out the original with our now-encased video
			$html = str_replace($match, $wrappedframe, $html);
		}

	return $html;
}
add_filter( 'embed_oembed_html', 'gravit_embed_html', 10, 3 );
add_filter( 'video_embed_html', 'gravit_embed_html' ); // Jetpack


/* Filter the content of chat posts. */
add_filter( 'the_content', 'gravit_format_chat_content' );

/* Auto-add paragraphs to the chat text. */
add_filter( 'gravit_post_format_chat_text', 'wpautop' );

/* Handling for Chat Post Format
/* Link: http://justintadlock.com/archives/2012/08/21/post-formats-chat
/* ------------------------------------ *
 * @author David Chandra
 * @link http://www.turtlepod.org
 * @author Justin Tadlock
 * @link http://justintadlock.com
 * @copyright Copyright (c) 2012
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link http://justintadlock.com/archives/2012/08/21/post-formats-chat
 *
 * @global array $_post_format_chat_ids An array of IDs for the chat rows based on the author.
 * @param string $content The content of the post.
 * @return string $chat_output The formatted content of the post.
 */

function gravit_format_chat_content( $content ) {
	global $_post_format_chat_ids;

	/* If this is not a 'chat' post, return the content. */
	if ( !has_post_format( 'chat' ) )
		return $content;

	/* Set the global variable of speaker IDs to a new, empty array for this chat. */
	$_post_format_chat_ids = array();

	/* Allow the separator (separator for speaker/text) to be filtered. */
	$separator = apply_filters( 'gravit_post_format_chat_separator', ':' );

	/* Open the chat transcript div and give it a unique ID based on the post ID. */
	$chat_output = "\n\t\t\t" . '<div id="chat-transcript-' . esc_attr( get_the_ID() ) . '" class="chat-transcript">';

	/* Split the content to get individual chat rows. */
	$chat_rows = preg_split( "/(\r?\n)+|(<br\s*\/?>\s*)+/", $content );

	/* Loop through each row and format the output. */
	foreach ( $chat_rows as $chat_row ) {

		/* If a speaker is found, create a new chat row with speaker and text. */
		if ( strpos( $chat_row, $separator ) ) {

			/* Split the chat row into author/text. */
			$chat_row_split = explode( $separator, trim( $chat_row ), 2 );

			/* Get the chat author and strip tags. */
			$chat_author = strip_tags( trim( $chat_row_split[0] ) );

			/* Get the chat text. */
			$chat_text = trim( $chat_row_split[1] );

			/* Get the chat row ID (based on chat author) to give a specific class to each row for styling. */
			$speaker_id = gravit_format_chat_row_id( $chat_author );

			/* Open the chat row. */
			$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

			/* Add the chat row author. */
			$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-author ' . sanitize_html_class( strtolower( "chat-author-{$chat_author}" ) ) . ' vcard"><cite class="fn">' . apply_filters( 'gravit_post_format_chat_author', $chat_author, $speaker_id ) . '</cite>' . $separator . '</div>';

			/* Add the chat row text. */
			$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'gravit_post_format_chat_text', $chat_text, $chat_author, $speaker_id ) ) . '</div>';

			/* Close the chat row. */
			$chat_output .= "\n\t\t\t\t" . '</div><!-- .chat-row -->';
		}

		/**
		 * If no author is found, assume this is a separate paragraph of text that belongs to the
		 * previous speaker and label it as such, but let's still create a new row.
		 */
		else {

			/* Make sure we have text. */
			if ( !empty( $chat_row ) ) {

				/* Open the chat row. */
				$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

				/* Don't add a chat row author.  The label for the previous row should suffice. */

				/* Add the chat row text. */
				$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'gravit_post_format_chat_text', $chat_row, $chat_author, $speaker_id ) ) . '</div>';

				/* Close the chat row. */
				$chat_output .= "\n\t\t\t</div><!-- .chat-row -->";
			}
		}
	}

	/* Close the chat transcript div. */
	$chat_output .= "\n\t\t\t</div><!-- .chat-transcript -->\n";

	/* Return the chat content and apply filters for developers. */
	return apply_filters( 'gravit_post_format_chat_content', $chat_output );
}

/**
 * This function returns an ID based on the provided chat author name.  It keeps these IDs in a global 
 * array and makes sure we have a unique set of IDs.  The purpose of this function is to provide an "ID"
 * that will be used in an HTML class for individual chat rows so they can be styled.  So, speaker "John" 
 * will always have the same class each time he speaks.  And, speaker "Mary" will have a different class 
 * from "John" but will have the same class each time she speaks.
 *
 * @author David Chandra
 * @link http://www.turtlepod.org
 * @author Justin Tadlock
 * @link http://justintadlock.com
 * @copyright Copyright (c) 2012
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link http://justintadlock.com/archives/2012/08/21/post-formats-chat
 *
 * @global array $_post_format_chat_ids An array of IDs for the chat rows based on the author.
 * @param string $chat_author Author of the current chat row.
 * @return int The ID for the chat row based on the author.
 */
function gravit_format_chat_row_id( $chat_author ) {
	global $_post_format_chat_ids;

	/* Let's sanitize the chat author to avoid craziness and differences like "John" and "john". */
	$chat_author = strtolower( strip_tags( $chat_author ) );

	/* Add the chat author to the array. */
	$_post_format_chat_ids[] = $chat_author;

	/* Make sure the array only holds unique values. */
	$_post_format_chat_ids = array_unique( $_post_format_chat_ids );

	/* Return the array key for the chat author and add "1" to avoid an ID of "0". */
	return absint( array_search( $chat_author, $_post_format_chat_ids ) ) + 1;
}

//modify comment form fields
function gravit_comment_form_fields($fields) {

	//required variables for changing the fields value
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields['author'] = '<p class="comment-form-author">' . '<label for="author">' . __( 'Name (required)', 'gravit' ) . '</label> ' .
	( $req ? '<span class="required">*</span>' : '' ) .
	'<input id="author" name="author" type="text" placeholder="' . __( 'Name (required)', 'gravit' ) . '" value="' .
	esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
	$fields['email'] = '<p class="comment-form-email"><label for="email">' . __( 'Email (required)', 'gravit' ) . '</label> ' .
	( $req ? '<span class="required">*</span>' : '' ) .
	'<input id="email" name="email" type="text" placeholder="' . __( 'Email (required)', 'gravit' ) . '" value="' .
	esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
	$fields['url'] = '<p class="comment-form-url"><label for="url">' . __( 'Website', 'gravit' ) . '</label>' .
	'<input id="url" name="url" type="text" placeholder="' . __( 'Website', 'gravit' ) . '" value="' .
	esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';
	return $fields;
}
 
add_filter('comment_form_default_fields','gravit_comment_form_fields');

/* Changes Gallery Shortcode Default Size to Medium */
function gravit_gallery_atts( $out, $pairs, $atts ) {
   
    $atts = shortcode_atts( array(
        'size' => 'gallery',
         ), $atts );

    $out['size'] = $atts['size'];

    return $out;

}
add_filter( 'shortcode_atts_gallery', 'gravit_gallery_atts', 10, 3 );

/* Add Editor Style */
add_action( 'init', 'gravit_add_editor_styles' );
/**
 * Apply theme's stylesheet to the visual editor.
 */
function gravit_add_editor_styles() {

    add_editor_style( get_stylesheet_uri() ); 

    /* add google fonts to editor */
    $font_url = '//fonts.googleapis.com/css?family=PT+Sans:400,700|Open+Sans|Lustria|Raleway';
    add_editor_style( str_replace( ',', '%2C', $font_url ) );
}

/* customize css */
function gravit_styles_method() {
	
	$footer_text_color = get_option('footer_text_color', '#808080');
	$icon_color = get_option('icon_color', '#EF3636');
	$footer_link_color = get_option('footer_link_color', '#c2c2c2');
	$title_text_color = get_option('title_text_color', '#4B4A47');
	$about_page_color = get_option('about_page_color', '#FFFFFF');

    $custom_css = "
        .site-info {
            color: {$footer_text_color};
        }      

		.post-symbol, .post-symbol a, #menu-toggle { 
			color: {$icon_color}!important;
		}
        
		.site-info a {
			color: {$footer_link_color};
		}

        .site-title a {
        color: {$title_text_color}; 
    	}

        .about-me {
        	background-color: {$about_page_color}; 
        }";

       if (false === get_theme_mod('show_icons')) {
       		$custom_css .=	"
       			.post-symbol {
       				display: none !important;
       			}
       		";
       	}

       	if (true == get_theme_mod('remove_space')) {
       		$custom_css .=	"
				article:first-child {
  					margin-top:  20px !important;
				}
       		";
       	}

    wp_add_inline_style( 'gravit-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'gravit_styles_method' );


/* Display a update notice that can be dismissed */
add_action('admin_notices', 'gravit_update_2notice');
function gravit_update_2notice() {
	global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'gravit_ignore_update_2notice') ) {
        echo '<div class="updated"><p>';
        printf(__('<p><strong>Gravit has been updated to 1.6! Fixing a minor bug and introducing a new feature. Get the update notes <a target="_blank" href="https://twitter.com/anbianio/status/565163135813033984">here.</a></strong></p><a class="skip button-primary" href="%1$s">Hide Notice</a>'), '?gravit_nag_update_2ignore=0');
        echo "</p></div>";
	}
}
add_action('admin_init', 'gravit_nag_update_2ignore');
function gravit_nag_update_2ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['gravit_nag_update_2ignore']) && '0' == $_GET['gravit_nag_update_2ignore'] ) {
             add_user_meta($user_id, 'gravit_ignore_update_2notice', 'true', true);
	}
}

/* Display a notice that can be dismissed */
add_action('admin_notices', 'gravit_admin_notice');
function gravit_admin_notice() {
	global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'gravit_ignore_notice') ) {
        echo '<div class="updated"><p>';
        printf(__('<p><strong>Thank you for using Gravit!</strong> Get some inspiration about what you can do with gravit from our <a target="_blank" href="http://gravit.tryand.net/">preview site</a>.<br />
    <strong>If you are enjoying gravit you might want to check out our other themes at <a target="_blank" href="https://anbian.io/">anbian.io</a></strong></p>  <a class="skip button-primary" href="%1$s">Hide Notice</a>'), '?gravit_nag_ignore=0');
        echo "</p></div>";
	}
}
add_action('admin_init', 'gravit_nag_ignore');
function gravit_nag_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['gravit_nag_ignore']) && '0' == $_GET['gravit_nag_ignore'] ) {
             add_user_meta($user_id, 'gravit_ignore_notice', 'true', true);
	}
}