<?php

/**

*

*

*Template Name: About 31st & Brklyn

*Description: Another format for the about page

*

*/

?>



<?php get_header()?>
<div class="container about_page">
	<div class="twelve columns about_content">
		<aside class="three columns"><?php get_sidebar('about'); ?>
      <br>
      <h1 class="widget-title">MORE</h1>
      <div class="widget-text">
        <ul class="about_nav">
          <li><a href="<?php echo get_post_meta($post->ID, 'founder-site', true); ?>"><?php echo get_post_meta($post->ID, 'founder-name', true); ?></a></li>
          <!-- <li><a href="">Our Place</a></li> -->
        </ul>
      </div>
      <br>
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

        <span class="fa fa-phone"></span> <span class="celly"><a href="tel: <?php echo get_post_meta($id, 'phone', true); ?>"> <?php echo get_post_meta($id, 'phone', true); ?></a></span>

        <br><br>

        <a class="email" href="mailto:<?php echo get_post_meta($id, 'email', true); ?>"><span class="fa fa-rocket">  Email</span></a><br><br>
       
        <a class="fb" href="<?php echo get_post_meta($id, 'facebook', true); ?>"><span class="fa fa-facebook"></span>  Facebook</a><br><hr>
         <?php

        wp_reset_postdata();  // IMPORTANT - reset the $post object so the rest of the page works correctly ?>  


      </div>

    </aside>
		<div class="nine columns statements">			
			<p><?php echo get_post_meta($post->ID, 'slogan', true); ?></p>
		  <div class="bottom">	
		    <section id="about" class="twelve columns">
          <div class="headline">
            <span class="fa fa-quote-left"></span>
		<?php if ( get_bloginfo( 'description' ) ) { ?>	
          <h3><?php bloginfo( 'description' ); ?></h3>
          <?php } ?>
          </div>
          <div class="mission"><?php  if (have_posts()) : ?>
             <?php while (have_posts()) : ?>
            <?php  the_post(); ?>
            <?php   the_content(); ?>
            <?php endwhile; ?>
            <?php endif; ?>
          </div>
        </section>
	    </div>
    </div>
  </div>
</div>
<?php get_footer() ?>	











   

