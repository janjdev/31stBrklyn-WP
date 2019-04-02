<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package gravit
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php // check if the post has a Post Thumbnail assigned to it.
		if ( has_post_thumbnail() ) { ?>
			<div class="thumbnail-wrap">
		  		<?php the_post_thumbnail('post-thumbnail', array('style' => 'post-thumbnail')); ?>
			</div>
	<?php } ?>

	<header class="entry-header"> 
	     <h1 class="entry-title"><?php the_title(); ?></h1> 
	</header><!-- .entry-header --> 

	<div class="entry-content">		
		<?php the_content(); ?>
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
