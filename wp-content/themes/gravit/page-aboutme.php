<?php
/*
Template Name: About Me
Description: A Page to tell your visitors something about you.
*/

get_header();

if (have_posts()) : while (have_posts()) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="about-me">
	<?php // check if the post has a Post Thumbnail assigned to it.
		if ( has_post_thumbnail() ) { ?>
			<div class="thumbnail-wrap">
			  		<?php the_post_thumbnail('post-thumbnail'); ?>
			</div>
	<?php } ?>

		<header class="entry-header">
			<div class="bio-avatar">
				<span><a title="<?php _e('Show Posts from ', 'gravit'); echo the_author_meta('display_name'); ?>" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?></a></span>
			</div>

			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->

		<div class="entry-content">		

			<?php the_content(); ?>

			<div class="social">		 		
		 		<?php /* Social Profile Links */
		 		if ( get_post_meta( $post->ID, 'gravit_facebook', true ) ) { ?>
					<a title="<?php _e('Facebook', 'gravit') ?>" href="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_facebook', true ) ); ?>"><i class="fa fa-facebook"></i></a>
				<?php } ?>

				<?php if ( get_post_meta( $post->ID, 'gravit_twitter', true ) ) { ?>
					<a title="<?php _e('Twitter', 'gravit') ?>" href="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_twitter', true ) ); ?>"><i class="fa fa-twitter"></i></a>
				<?php } ?>

				<?php if ( get_post_meta( $post->ID, 'gravit_google-plus', true ) ) { ?>
					<a title="<?php _e('Google+', 'gravit') ?>" href="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_google-plus', true ) ); ?>"><i class="fa fa-google-plus"></i></a>
				<?php } ?>

				<?php if ( get_post_meta( $post->ID, 'gravit_linkedin', true ) ) { ?>
					<a title="<?php _e('LinkedIn', 'gravit') ?>" href="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_linkedin', true ) ); ?>"><i class="fa fa-linkedin"></i></a>
				<?php } ?>

				<?php if ( get_post_meta( $post->ID, 'gravit_youtube', true ) ) { ?>
					<a title="<?php _e('YouTube', 'gravit') ?>" href="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_youtube', true ) ); ?>"><i class="fa fa-youtube"></i></a>
				<?php } ?>

				<?php if ( get_post_meta( $post->ID, 'gravit_instagram', true ) ) { ?>
					<a title="<?php _e('Instagram', 'gravit') ?>" href="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_instagram', true ) ); ?>"><i class="fa fa-instagram"></i></a>
				<?php } ?>

				<?php if ( get_post_meta( $post->ID, 'gravit_pinterest', true ) ) { ?>
					<a title="<?php _e('Pinterest', 'gravit') ?>" href="<?php echo esc_attr( get_post_meta( $post->ID, 'gravit_pinterest', true ) ); ?>"><i class="fa fa-pinterest"></i></a>
				<?php } ?>
			</div><!-- .social -->

		</div><!-- .entry-content -->
	</div><!-- .about-me -->
</article><!-- #post-## -->

<?php endwhile; endif; 
get_footer(); ?>
