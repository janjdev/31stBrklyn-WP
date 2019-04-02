<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package gravit
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<div class="description-wrapper">
					<h1 class="page-title">
						<?php
							if ( is_category() ) :
								single_cat_title();

							elseif ( is_tag() ) :
								single_tag_title();

							elseif ( is_author() ) :
								printf( __( 'Author: %s', 'gravit' ), '<span class="vcard">' . get_the_author() . '</span>' );

							elseif ( is_day() ) :
								printf( __( 'Day: %s', 'gravit' ), '<span>' . get_the_date() . '</span>' );

							elseif ( is_month() ) :
								printf( __( 'Month: %s', 'gravit' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'gravit' ) ) . '</span>' );

							elseif ( is_year() ) :
								printf( __( 'Year: %s', 'gravit' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'gravit' ) ) . '</span>' );

							elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
								_e( 'Asides', 'gravit' );

							elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
								_e( 'Galleries', 'gravit');

							elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
								_e( 'Images', 'gravit');

							elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
								_e( 'Videos', 'gravit' );

							elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
								_e( 'Quotes', 'gravit' );

							elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
								_e( 'Links', 'gravit' );

							elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
								_e( 'Statuses', 'gravit' );

							elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
								_e( 'Audios', 'gravit' );

							elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
								_e( 'Chats', 'gravit' );

							else :
								_e( 'Archives', 'gravit' );

							endif;
						?>
					</h1>
				</div>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php if (($wp_query->current_post + 1) < ($wp_query->post_count)) { /* just add divider between posts */
 				echo '<div class="post-item-divider"><i class="fa fa-angle-up"></i><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i><i class="fa fa-angle-down"></i></div>';
			} ?>

			<?php endwhile; ?>

			<?php gravit_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
