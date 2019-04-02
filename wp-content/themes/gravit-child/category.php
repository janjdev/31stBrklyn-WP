<?php
/*
	/**

 * The template for displaying Category pages.

 *

 * Learn more: http://codex.wordpress.org/Template_Hierarchy

 *

 * @package gravit-child

 */
	?>

<?php get_header() ?>


<div class="container">



	<div class="twelve columns">

		<aside id="leftsidebar" class="three columns current_proograms">

			<?php get_sidebar('programs'); ?>

		</aside>



		<div id="mycontent" class="nine columns">

		<div class="page_title"><h2 class="cat_title">Category: <?php echo single_cat_title(); ?></h2></div>	



		<?php if(have_posts() ) {

  		while (have_posts()) : the_post(); ?> 	  	  	

		<div class="current-projects ">            

            <a class="proj_link" href="<?php the_permalink() ?>">

              <span class="project">

              	<?php if ( has_post_thumbnail() ) : ?>

                <img class="feature" src="<?php the_post_thumbnail_url('full'); ?>"><?php endif; ?>

              </span>

              <div class="description"><h2><?php the_title(); ?></h2></div>

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



<?php get_footer(); ?>	
