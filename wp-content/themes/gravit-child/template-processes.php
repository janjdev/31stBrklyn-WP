<?php

/*

Template Name: Process-Projects

Description: Layout for Process Project types

*/

?>





<?php 

get_header(); ?>







<div class="container">



	<div class="twelve columns">

		<aside id="leftsidebar" class="three columns current_proograms">

			<?php get_sidebar('programs'); ?>

		</aside>



		<div id="mycontent" class="nine columns">

		<div class="page_title"><h2><?php echo apply_filters( 'the_title', get_the_title() ); ?></h2></div>	



			<?php

		$type = 'programming_projects';

		$args=array(

		  'post_type' => $type,

		  'post_status' => 'publish',

		  'posts_per_page' => -1,

		  'caller_get_posts'=> 1,

		  'category_name' => 'processes'

		  );





			$my_query = null;

		$my_query = new WP_Query($args);

		if( $my_query->have_posts() ) {

  		while ($my_query->have_posts()) : $my_query->the_post(); ?> 	  	  	

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