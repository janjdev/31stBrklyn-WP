<?php

/*

Template Name: Single_Associate_Artists

Description: Layout for individual Artist page

*/

?>



<?php 

get_header(); ?>



<div id="post-<?php the_ID(); ?>" class="container content"> 

    <!-- Main Content -->     

      <section id="associate" class="twelve columns">

        <aside id="leftsidebar" class="three columns"><?php get_sidebar('artist') ?></aside>

        <div class="nine columns top_wrap">

          <div class="twelve columns headline">

            <h6 class="associate_artist_title">

                         <?php  $my_post_meta = get_post_meta($post->ID, 'title', true); ?>

        <?php if ( ! empty ( $my_post_meta ) ): ?>



              <?php echo get_post_meta($post->ID, 'title', true); ?>

            <?php else :?> <?php echo apply_filters( 'the_title', get_the_title() ); ?>

                <?php endif; ?>

            </h6>

          <?php  if (have_posts()) : ?>

            <?php while (have_posts()) : ?>

            <?php  the_post(); ?>  

            <?php $post_date = the_date('Y', '<p class="post_date">', '</p>', FALSE); echo $post_date; ?>

              <?php endwhile; ?>

          <?php endif; ?>  

       </div>     

           

           



              <div class=" twelve columns thumb">

    	

                <?php if( get_field('featured_img') ): ?>



                  <img class="thumbnail" src="<?php the_field('featured_img'); ?>">



                <?php elseif ( has_post_thumbnail() ) : ?>



                  <img class="thumbnail" src="<?php the_post_thumbnail_url('full'); ?>">



                <?php endif; ?>

            </div>

          </div> 

             

         <div class="twelve columns">

         <div class="three columns artist_bio">

          <?php  $my_post_meta = get_post_meta($post->ID, 'title', true); ?>

        <?php if ( ! empty ( $my_post_meta ) ): ?>



              <span><?php echo get_post_meta($post->ID, 'title', true); ?></span>

            <?php else :?> 

            <span><?php echo apply_filters( 'the_title', get_the_title() ); ?></span>

                <?php endif; ?><span>'s Bio</span></div>  

          <div class="six columns bio"> 

          <?php  if (have_posts()) : ?>

  <?php while (have_posts()) : ?>

    <?php  the_post(); ?>

      <?php   the_content(); ?>

  <?php endwhile; ?>

<?php endif; ?>           

          </div>

           <div class="three columns categories">

            <p><?php echo get_the_category_list(', '); ?></p>

          </div>

          <div class="three columns categories">

            <?php echo get_the_tag_list('<p>Tags:<br> ',', ','</p>'); ?>

          </div>

          <div class="three columns categories">  
             <?php
          $type = 'associate_artist';

    $args=array(

      'post_type' => $type,

      'post_status' => 'publish',

      'posts_per_page' => 3,

      'caller_get_posts'=> 1,

      'post__not_in' => array($post->ID, )

      );

        $my_query = null;

    $my_query = new WP_Query($args);

    if( $my_query->have_posts()  ) : ?>

              <p>Related:</p><br>

               <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>           

        <div class="related_post">

          <a href="<?php the_permalink() ?>">

            <?php if ( has_post_thumbnail() ) : ?>

            <figure>

              <img class="feature" src="<?php the_post_thumbnail_url('full'); ?>">

              <figcaption><?php the_title(); ?></figcaption>

              

            </figure>

            <?php endif; ?>

          </a>

        </div>

      <?php

    endwhile;

  

  wp_reset_query();  // Restore global post data stomped by the_post().

  ?>        
          

          </div>
  <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>

        <?php endif; ?>        

        </div>   

              

          <div class="col soc">

            <a class="soc_link tum" href="#"></a>

            <a class="soc_link yt" href="#"></a>

            <a class="soc_link tw" href="#"></a>

          </div>

        </section>

    </div>









<?php get_footer(); ?>









