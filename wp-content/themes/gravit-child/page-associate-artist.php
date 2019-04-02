<?php
/*
Template Name: Artists
Description: Layout for Artist page
*/
?>

<?php 
get_header(); ?>


<div class="container">
	<div class="twelve columns">
		<aside id="leftaside" class="three columns assoc"><?php get_sidebar('artist'); ?></aside>    
	    <div class="nine columns artist_list">
	     	<div class="page_title"><h2><?php echo apply_filters( 'the_title', get_the_title() ); ?></h2></div>
	     	
	     	<div class="twelve columns">
	     		    

		<?php
		$type = 'associate_artist';
		$args=array(
		  'post_type' => $type,
		  'post_status' => 'publish',
		  'posts_per_page' => -1,
		  'caller_get_posts'=> 1
		  );


	  	$my_query = null;
		$my_query = new WP_Query($args);
		if( $my_query->have_posts() ) {
	  		while ($my_query->have_posts()) : $my_query->the_post(); ?>  
	  		  	  	
			        <div class="associate_artist">	
			          <a class"artist_link" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
			            <span class="artist">          
			   		<?php if ( has_post_thumbnail() ) : ?>
			            <img class="artist_img" src="<?php the_post_thumbnail_url('full'); ?>"><?php endif; ?>		            		    	         
			            </span>
			            <div class="excerpt"><?php the_content(); ?> </div>
			            <div class="artist_name">
			              <h2 class="artistName"><?php the_title(); ?></h2>
			            </div>
			          </a>
			        </div>
			      
	<?php
	  endwhile;
	}
	wp_reset_query();  // Restore global post data stomped by the_post().
	?>
				 
				
			</div>
		</div>		
	</div>
</div>

<?php get_footer();?>