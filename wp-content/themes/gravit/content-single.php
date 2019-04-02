<?php
/**
 * The template used for displaying single content in single.php
 * @package gravit
 */
?>

	<?php
	/* Include the Post-Format-specific template for the content.
	* If you want to override this in a child theme, then include a file
	* called content-___.php (where ___ is the Post Format name) and that will be used instead.
	*/
	get_template_part( 'content', get_post_format() );
	?>	

<div class="entry-meta additional">
	<?php
	/* translators: used between list items */
	$category_list = get_the_category_list( __( ' ', 'gravit' ) );
	/* translators: used between list items */
	$tag_list = get_the_tag_list( '', __( ' ', 'gravit' ) );
	if ( ! gravit_categorized_blog() ) {
		// This blog only has 1 category so we just need to worry about tags in the meta text
		if ( '' != $tag_list ) {
			$meta_text = __( 'Tags: <span class="sep">%2$s</span>', 'gravit' );
		} else {
			$meta_text = __( '', 'gravit' );
		}
		
	} else {
		// But this blog has loads of categories so we should probably display them here
		if ( '' != $tag_list ) {
			$meta_text = __( 'Categories: <span class="sep">%1$s</span> <div class="sep-line">Tags: <span class="sep">%2$s</span></div>', 'gravit' );
		} else {
			$meta_text = __( 'Categories: <span class="sep">%1$s</span>', 'gravit' );
		}
	} // end check for categories on this blog
	printf(
		$meta_text,
		$category_list,
		$tag_list,
		get_permalink()
	);
	?>
</div><!-- .entry-meta additional -->

<?php if ( get_the_author_meta('description') ) { /* just display written by section if there is a author bio/description */ ?> 
	<footer class="single-footer">
		<div class="byline">
			<div class="bio-avatar">
				<a title="More from <?php echo the_author_meta('display_name'); ?>" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?></a>
			</div>

			<div class="bio">
				<div class="intro">
					<?php _e( 'Written by', 'gravit' ); ?> <a title="More from <?php echo the_author_meta('display_name'); ?>" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>"><?php echo the_author_meta('display_name'); ?></a>
				</div>
				<?php echo the_author_meta('description'); ?> 
			</div>
		</div><!-- .byline -->
	</footer><!-- .single-footer -->
<?php } ?>