<?php

/**

 * Template Name: Search Layout

 *

 * Description: Template for search results

 */



get_header(); ?>



	<section id="primary" class="content-area">

		<main id="main" class="site-main" role="main">		
			
			<div class="container">
				<div class="twelve columns">
					<aside class="three columns long"><?php get_sidebar('news'); ?>
						<ul>
							<?php
							global $wp_query;
							$total_results = $wp_query->found_posts;
							global $program_results;
							global $artist_results;
							?>
							<?php if ( have_posts() ) : ?>
							<?php while (have_posts()) : the_post(); ?>
							<?php if (get_post_type($post->ID) == 'programming_projects') {
								$program_results++;
							}
							?>
							<?php if(get_post_type($post->ID) == 'associate_artist'){
								$artist_results++;
							}
							?>
							<?php endwhile; ?>
							<?php endif; ?>							
							<li class="search_link"><a href="#" class="all-results active_result_list">All Results <span>( <?php echo $total_results; ?> )</span></a></li>
						<?php /* Start the Loop */ ?>
							<?php
							 	$last_type="";
							 	$typecount = 0; 
							 if ( have_posts() ) : ?>
							 <?php while ( have_posts() ) : the_post(); ?>
							<?php if($last_type != $post->post_type){
								$typecount = $typecount + 1;
								if($typecount > 1){
									echo '</li>';
								}
								$last_type = $post->post_type;
									switch ($post->post_type) {
										case 'programming_projects':
											$count_posts = count($post);
											echo '<li class="search_link"><a href="#" class="program-results" href="#">Programming<span>('; echo $program_results; echo ')</span></a>';
											break;										
										case 'associate_artist':
											$count_posts = $post->post_type;
											echo '<li class="search_link"><a href="#" class="artist-results" href="#">Associate Artist<span>('; echo $artist_results; echo ')</span></a>';
											break;	
									}
								}  
							?>
						<?php endwhile; ?>
						<?php endif; ?>							
							
							<!--  <li class="search_link"><a class="results_link" href="#">About</a></li>
							 <li class="search_link"><a class="results_link" href="#">Contact</a></li> -->
						</ul>
					</aside>	
					<div class="nine columns">
						<div id="articles">
							<h3 class="section-title">You searched</h3>
							<p>All results for <?php printf(get_search_query() ); ?></p>
							
								
							
						</div>		
						
								
							<div class="twelve columns">
								
															
							<?php
							 	$last_type="";
							 	$typecount = 0; 
							 if ( have_posts() ) : ?>
							 <?php while ( have_posts() ) : the_post(); ?>
							<?php if ($last_type != $post->post_type){
        						$typecount = $typecount + 1;
        						if ($typecount > 1){
            					echo "</ol>"; //close type container
        						} 
        						$last_type = $post->post_type;
							        //open type container
							        switch ($post->post_type) {
							            case 'post':
							                echo "<h2>News Articles</h2><ol>";
							                break;
							            case 'programming_projects':
							                echo "<h2 class='section-title'>Programming</h2><ol class='search_results program-search'>";
							                break;
							            case 'associate_artist':
							                echo "<h2 class='section-title'>Artist</h2><ol class='search_results artist-search'>";
							                break;
							            case 'bootlegs':
							                echo "<h2>Bootlegs</h2><ol>";
							                break;
							            case 'directors':
							                echo "<h2>Directors</h2><ol>";
							                break;
							            //add as many as you need.
							        }
							    } ?>
								<div id="<?php echo $post->ID ?>" class="result">
									<a href="<?php  the_permalink() ?>">
										<h3 class="section-title"><?php the_title(); ?></h3>
										<?php echo wp_strip_all_tags( get_the_content() ) ?>
									</a>
								</div>								
								<?php endwhile; ?>
							</div>
						</div>	
					</div>	
				</div>
				<div class="twelve columns">
					<?php gravit_paging_nav(); ?>
						<?php else : ?>
							<?php get_template_part( 'content', 'none' ); ?>
						<?php endif; ?>
					</div>
			</div><!-- End Container -->	

		</main><!-- #main -->

	</section><!-- #primary -->




<?php get_footer(); ?>

