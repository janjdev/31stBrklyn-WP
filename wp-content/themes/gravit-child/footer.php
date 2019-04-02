<?php

/**

 * The template for displaying the footer.

 *

 * Contains the closing of the #content div and all content after

 *

 * @package gravit

 */

?>

	</div><!-- #content -->

          <div class="footer <?php if (is_single()) : ?><?php echo wp_strip_all_tags( get_the_category_list() ) ?><?php endif; ?>">
                <hr>
               <div class="container">
          	<footer id="colophon" class=" twelve columns site-footer" role="contentinfo">
          		 <div class="cont_deets">
                    <div class="twelve columns site_name">
                    	<?php $site_title = get_bloginfo('name'); 
                    		  $site_url = get_bloginfo('url');
                    		  $email = get_bloginfo('admin_email');	?>
                    <p><a href="<?php echo "$site_url"; ?>">31st <span class="logo_font">z</span> BRKLYN</a></p>
                   
                    </div>
                    <div class="footer_text">
                    <div id="footerBar"><?php get_sidebar('footer') ?></div>
                    	<?php $page = get_page_by_title( 'Contact' ); 
          			setup_postdata( $page );
          			?>
                    <div id="addressBlock"><p><?php echo get_post_meta($page->ID, 'address_street', true); ?><br><?php echo get_post_meta($page->ID, 'address_city_st_zip', true); ?><br><a href="mailto:<?php echo "$email";  ?>">@31st <span class="logo_font">z</span>BRKLYN</a><br><a href="<?php echo get_post_meta($id, 'facebook', true); ?>"><span class="fa fa-facebook"></span>  31st<span class="logo_font">z</span>BRKLYN</a></p>
                         <div class="mapfooter" role="button" type="button"><span class="fa fa-map-marker"></span> Map</div>

                              <div id="footermap"><span id="footerclose" class="fa fa-minus-square"></span><div id="footer_map_brklyn"></div> </div>
                    </div>          
                    <?php

          wp_reset_postdata();  // IMPORTANT - reset the $post object so the rest of the page works correctly



          ?>		
                    </div>
                  </div>
          	</footer><!-- #colophon -->
          </div>
     </div>     	

</div><!-- #page -->



<?php wp_footer(); ?>



</body>

</html>