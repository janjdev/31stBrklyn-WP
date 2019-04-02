<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package gravit
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<div class="description-wrapper">
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'gravit' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</div>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>

				<?php if (($wp_query->current_post + 1) < ($wp_query->post_count)) {
   					echo '<div class="post-item-divider"><i class="fa fa-angle-up"></i><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i><i class="fa fa-angle-down"></i></div>';
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
