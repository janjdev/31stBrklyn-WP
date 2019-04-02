<?php
/**
 * @package gravit
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php // check if the post has a Post Thumbnail assigned to it.
		if ( has_post_thumbnail() ) { ?>
			<div class="thumbnail-wrap">
				<?php if( !is_single() ) { ?>
					 <a href="<?php the_permalink(); ?>">
			  			 <?php the_post_thumbnail('post-thumbnail', array('style' => 'post-thumbnail')); ?>
			  		 </a>
			  	<?php } else { ?>
			  		<?php the_post_thumbnail('post-thumbnail', array('style' => 'post-thumbnail')); ?>
			  	<?php } ?>
	  		</div>
	<?php } ?>

	<header class="entry-header">

		<div class="post-symbol">
			<i title="<?php echo _e('Standard Post', 'gravit') ?>" class="fa fa-thumb-tack"></i>
		</div>

		<h1 class="entry-title">
			<?php if( !is_single() ) { ?>
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			<?php } else { ?>
				<?php the_title(); ?>
			<?php } ?>
		</h1>
		
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php gravit_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav"><i class="fa fa-chevron-circle-right"></i></span>', 'gravit' ) ); ?>
		<?php wp_link_pages( array(
				'before' => '<div class="page-links"><div class="intro">Pages:</div>',
				'after'  => '</div>',
				'nextpagelink'     => __( '<i class="fa fa-angle-double-right"></i>' ),
				'previouspagelink' => __( '<i class="fa fa-angle-double-left"></i>' ),
				'link_before'      => '<span>',
				'link_after'       => '</span>',
		) ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
