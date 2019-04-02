<?php

/*
  /**

 * The template for displaying tag pages.

 *

 * Learn more: http://codex.wordpress.org/Template_Hierarchy

 *

 * @package gravit-child

 */


?>



<?php 

get_header(); ?>



<div id="tags_page" class="container content"> 

    <!-- Main Content -->  

      <section id="associate" class="twelve columns">

        <aside id="leftsidebar" class="three columns <?php echo wp_strip_all_tags( get_the_category_list() ) ?>"><?php get_sidebar('programs')?> <div ></div>

          <!-- <div class="current"><a href="/programming">Current</a></div>

          <div class="furture"><a href="">Future</a></div>

          <div class="past"><a href="">Past</a></div> -->

        </aside>

       <div id="post-<?php the_ID(); ?>" class="nine columns">
          <div class="page_title"><h2 class="cat_title">Tag: <?php echo single_tag_title(); ?></h2></div>  
        
        <?php  if (have_posts()) : ?>

            <?php while (have_posts()) : ?>

            <?php  the_post(); ?>    
        <div  class="twelve columns top_wrap">

          <div class="twelve columns headline">

            <h6 id="title" class="associate_artist_title">

                         <?php  $my_post_meta = get_post_meta($post->ID, 'title', true); ?>

        <?php if ( ! empty ( $my_post_meta ) ): ?>

              <?php echo get_post_meta($post->ID, 'title', true); ?>

            <?php else :?> <?php echo apply_filters( 'the_title', get_the_title() ); ?>

                <?php endif; ?>

            </h6>

            <?php  $my_post_meta = get_post_meta($post->ID, 'events_date', true); ?>

            <?php if ( ! empty ( $my_post_meta ) ): ?>



              <?php echo get_post_meta($post->ID, 'events_date', true); ?>

            <?php else :?>  

            <?php $post_date = the_date('Y', '<p class="post_date">', '</p>', FALSE); echo $post_date; ?>

             <?php endif; ?>
          </div>               
          <div class=" twelve columns thumb">
            <a  href="<?php the_permalink() ?>">
          <?php if( get_field('featured_img') ): ?>

           <img class="thumbnail" src="<?php the_field('featured_img'); ?>">

          <?php elseif ( has_post_thumbnail() ) : ?>

            <img class="thumbnail" src="<?php the_post_thumbnail_url('full'); ?>">

          <?php endif; ?>
        </a>
      </div>

    </div>             
    <div class="twelve columns proj_bottom">         
      <div class="offset-by-three columns three columns categories">

            <p>Categories: <br> <?php echo get_the_category_list(', '); ?> </p>

        </div>

        <div class="offset-by-three columns three columns categories">

            <?php echo get_the_tag_list('<p>Tags:<br> ',', ','</p>'); ?>

        </div>
   </div>
   <div><p class="logo_font divider">z</p></div> 
   <?php endwhile; 

  wp_reset_query();  // Restore global post data stomped by the_post().

  ?>        

  <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
<?php endif; ?>    
  </div> 
       

                

          <!-- <div class="col soc">

            <a class="soc_link tum" href="#"></a>

            <a class="soc_link yt" href="#"></a>

            <a class="soc_link tw" href="#"></a>

          </div> -->

        </section>

    </div>









<?php get_footer(); ?>









