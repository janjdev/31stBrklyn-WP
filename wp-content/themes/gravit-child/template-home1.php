<?php

/*

Template Name: Home2

Description: Alternative home page with featured content

*/

?>







<?php get_header("header2"); ?>



<!-- start container -->

<div id="primary" class="content-area container">

	<main id="main" class="site-main twelve columns" role="main">

		<aside id="leftaside" class="three columns"><?php get_sidebar('sidebar-1'); ?>

			<h1 class="widget-title">Find Us</h1>

			<div class="widget-text">
				<?php $page = get_page_by_title( 'Contact' ); 
						global $id;
						$id = $page->ID;
					?>

				<p><?php echo get_post_meta($id, 'address_street', true); ?></p>

                <p><?php echo get_post_meta($id, 'address_city_st_zip', true); ?></p>
               
				<hr>

				<div class="map" role="button" type="button"><span class="fa fa-map-marker"></span> Map</div>

				<div id="map"><span id="close" class="fa fa-minus-square"></span><div id="map_brklyn"></div> </div>

				<hr>
				<a class="celly" href="tel: <?php echo get_post_meta($id, 'phone', true); ?>"> <span class="fa fa-phone bar-icons"></span><?php echo get_post_meta($id, 'phone', true); ?></a>

		        <br><br>

		        <a class="email" href="mailto:<?php echo get_post_meta($id, 'email', true); ?>"><span class="fa fa-envelope bar-icons"></span>  Email</a><br><br>
		       
		        <a class="fb" href="<?php echo get_post_meta($id, 'facebook', true); ?>"><span class="fa fa-facebook bar-icon"></span>  Facebook</a><br><hr>
		         <?php

		        wp_reset_postdata();  // IMPORTANT - reset the $post object so the rest of the page works correctly ?>  


			</div>



		</aside>

		<div class="six columns post_content">

				<div class="body_feature">

				<?php



				 global $FIH, $FIB;	

					$post_object = get_field('body_featured_post');

					$FIB = get_field('body_featured_post');

					$FIH = get_field('featured_post_header');





						if( $post_object ): 

							// override $post

						$post = $post_object; 

						setup_postdata( $post, $FIH, $FIB ); 

				?>

				<div class="post" id="<?php echo ($post->ID) ?>"><!--id of the featured post-->

					<a href="<?php the_permalink() ?>">

					<span class="project">	

					<img class="feature" src="<?php the_post_thumbnail_url('full'); ?>">

					</span>

					<div class="description">

						<h2><?php the_title();?></h2>

						<p>Upcoming Events</p> 

						<p><?php echo get_post_meta($post->ID, 'events_date', true); ?></p>

					</div>

					</a>	

				</div>
				<?php

wp_reset_postdata();  // IMPORTANT - reset the $post object so the rest of the page works correctly
?>	
	

<?php endif; ?>

			</div>

		</div>

		<aside id="rightaside" class="three columns">

		<?php $page = get_page_by_title( 'About 31st & BRKLYN' ); 

			setup_postdata( $page );

		 $the_excerpt = $page->post_excerpt;

		 ?>		
			<h4 class="about-title"><a href="<?php the_permalink(get_page_by_title('About 31st & BRKLYN'))?>"> About Us</a></h4>
			<div class="flexslider">
				<ul class="slides">
					<li class="slide1">
						<span class="about_excerpt"><?php echo get_the_excerpt(); ?></span> <span>...</span>
						<?php wp_reset_postdata();  // IMPORTANT - reset the $post object so the rest of the page works correctly ?>	
					</li>
					<li class="slide2"><span>We are committed to our mission to Engage, Influence, and Build Community</span></li>		
					<li class="slide3"><span>With a focus on artists of color we provide affordable venue rental space for community organizations, collectives, and small-scale markets.</span></li>
				</ul>
			</div>
		</aside>

		

			

				<div class="projects nine columns">
					<h4 class="program-title"> Current Programming</h4>


					<?php

		$FIH_id = $FIH->ID;

		$FIB_id = $FIB->ID;						

		$type = 'programming_projects';

		$args=array(

		  'post_type' => $type,

		  'post_status' => 'publish',

		  'posts_per_page' => -1,

		  'caller_get_posts'=> 1,

		  'category'         => 'current_proograms',

		  'post__not_in' => array($FIH_id, $FIB_id)

		  );

				$my_query = null;

		$my_query = new WP_Query($args);

		if( $my_query->have_posts() ) {

  		while ($my_query->have_posts()) : $my_query->the_post(); ?> 	  	  	



	          <div class="current-projects ">

	            <a class="proj_link" href="<?php the_permalink() ?>">

	              <span class="project">

	              	<?php if ( has_post_thumbnail() ) : ?>

	                <img class="feature" src="<?php the_post_thumbnail_url('full'); ?>"/><?php endif; ?>

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

				 	<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>

				

		

		

		

			

	</main><!-- #main -->

</div><!-- #primary -->					



<?php get_footer(); ?>









		