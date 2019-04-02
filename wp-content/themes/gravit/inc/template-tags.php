<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package gravit
 */

if ( ! function_exists( 'gravit_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @return void
 */
function gravit_paging_nav() {

	if( is_singular() )
		return;

	/** Stop execution if there's only 1 page */
	if( $GLOBALS['wp_query']->max_num_pages <= 1 )
		return;

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $GLOBALS['wp_query']->max_num_pages );

	/**	Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;

	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="navigation"><ul>' . "\n";

	/**	Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<li>%s</li>' . "\n", get_previous_posts_link('<i class="fa fa-angle-double-left"></i>') );

	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) )
			echo '<li>...</li>';
	}

	/**	Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/**	Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo '<li>...</li>' . "\n";

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/**	Next Post Link */
	if ( get_next_posts_link('<i class="fa fa-angle-double-right"></i>') )
		printf( '<li>%s</li>' . "\n", get_next_posts_link('<i class="fa fa-angle-double-right"></i>') );

	echo '</ul></div>' . "\n";
}
endif;

if ( ! function_exists( 'gravit_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function gravit_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'gravit' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '<i class="fa fa-pencil-square-o"></i> Edit', 'gravit' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					
					<?php if ( 0 != $args['avatar_size'] ) { echo get_avatar( $comment, $args['avatar_size'] ); } ?>

					<div class="comment-name">
						<?php printf( __( '%s', 'gravit' ), sprintf( '<span class="fn">%s</span>', get_comment_author_link() ) ); ?>
						<div class="author-star">
							<i class="fa fa-star"></i>
						</div>
					</div>
					
					<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'gravit' ), get_comment_date(), get_comment_time() ); ?>
						</time>
					</a>
					<?php edit_comment_link( __( '| Edit', 'gravit' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-metadata -->

				</div><!-- .comment-author -->

				<div class="comment-content">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->				

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'gravit' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->			

			<?php
				comment_reply_link( array_merge( $args, array(
					'add_below' => 'div-comment',
					'depth'     => $depth,
					'max_depth' => $args['max_depth'],
					'before'    => '<div class="reply">',
					'after'     => '</div>',
				) ) );
			?>

		</article><!-- .comment-body -->
	<?php
	endif;
}
endif; // ends check for gravit_comment()

if ( ! function_exists( 'gravit_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post. (date, comments and sticky)
 */
function gravit_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	if ( is_sticky() ) { 
		echo '<span class="sticky-meta"><i class="fa fa-exclamation-circle"></i> ' . __('Sticky','gravit') .'</span>';
	}

	if ( !is_single( ) ) {
		printf( __( '<span class="posted-on">%1$s</span>', 'gravit' ),
			sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
				esc_url( get_permalink() ),
				$time_string
			),
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			)
		);

	} else { /* don't display permalink on single post view */
		printf( __( '<span class="posted-on">%1$s</span>', 'gravit' ),
			sprintf( '%2$s',
				esc_url( get_permalink() ),
				$time_string
			),
			sprintf( '<span class="author vcard">%%2$s</span>',
				esc_html( get_the_author() )
			)
		);
	}

	if ( comments_open() ) :
		echo '<span class="sep-meta"></span> <span class="comments-meta">';				
		echo comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'gravit' ) . '</span>', __( '1 Reply', 'gravit' ), __( '% Replies', 'gravit' ) ) . '</span>';
	endif; // comments_open();

}
endif;

/**
 * Returns true if a blog has more than 1 category.
 */
function gravit_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so gravit_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so gravit_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in gravit_categorized_blog.
 */
function gravit_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'gravit_category_transient_flusher' );
add_action( 'save_post',     'gravit_category_transient_flusher' );
