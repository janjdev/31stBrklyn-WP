<?php

/*

Template Name: Single-Programming_Project

Description: Layout for individual Project page

*/

?>



<?php 

get_header(); ?>



<div id="post-<?php the_ID(); ?>" class="container content"> 

    <!-- Main Content -->  

      <section id="associate" class="twelve columns">

        <aside id="leftsidebar"> <div class="three columns <?php echo wp_strip_all_tags( get_the_category_list() ) ?>"><?php $walker = new Menu_With_Description; ?>
      <?php wp_nav_menu( array( 'theme_location' => 'projects','menu_class' => 'nav-menu', 'walker' => $walker ) ); ?></div>

        </aside>
      <div id="mycontent" class="nine columns">
        <div class="twelve columns top_wrap">

          <div class="twelve columns headline">

            <h6 id="title" class="associate_artist_title">

                         <?php  $my_post_meta = get_post_meta($post->ID, 'title', true); ?>

        <?php if ( ! empty ( $my_post_meta ) ): ?>



              <?php echo get_post_meta($post->ID, 'title', true); ?>

            <?php else :?> <?php echo apply_filters( 'the_title', get_the_title() ); ?>

                <?php endif; ?>

            </h6>

          <?php  if (have_posts()) : ?>

            <?php while (have_posts()) : ?>

            <?php  the_post(); ?>

            <?php  $my_post_meta = get_post_meta($post->ID, 'events_date', true); ?>

        <?php if ( ! empty ( $my_post_meta ) ): ?>



              <?php echo get_post_meta($post->ID, 'events_date', true); ?>

            <?php else :?>  

            <?php $post_date = the_date('Y', '<p class="post_date">', '</p>', FALSE); echo $post_date; ?>

             <?php endif; ?>

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

            

         <div class="twelve columns proj_bottom">         

          <div class="nine columns pro_details"> 

          <?php  if (have_posts()) : ?>

  <?php while (have_posts()) : ?>

    <?php  the_post(); ?>

      <?php   the_content(); ?>

  <?php endwhile; ?>

<?php endif; ?>           

          </div>

           <div class="three columns categories">

            <?php echo get_the_category_list(', '); ?>

          </div>

          <div class="three columns categories">

            <?php echo get_the_tag_list('<p>Tags:<br> ',', ','</p>'); ?>

          </div>

          <div class="three columns categories"> 

            <?php

          $cat = get_the_category_list(', ');

          $cat = strip_tags($cat);

          $cats = explode(',', $cat);

          $mycat = array_pop($cats);

          $type = 'programming_projects';

    $args=array(

      'post_type' => $type,

      'post_status' => 'publish',

      'posts_per_page' => 3,

      'caller_get_posts'=> 1,

      'category_name' => $mycat,

      'post__not_in' => array($post->ID, )

      );

        $my_query = null;

    $my_query = new WP_Query($args);

    if( $my_query->have_posts()  ) : ?>

         <p>Related:</p>

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
  </div> 
   <div class="four columns program_details">

          <?php  $my_post_meta = get_post_meta($post->ID, 'title', true); ?>

        <?php if ( ! empty ( $my_post_meta ) ): ?>



              <span><?php echo get_post_meta($post->ID, 'title', true); ?></span>

            <?php else :?> 

            <span><?php echo apply_filters( 'the_title', get_the_title() ); ?></span>

                <?php endif; ?>
    </div>    
           

        </section>

    </div>









<?php get_footer(); ?>









