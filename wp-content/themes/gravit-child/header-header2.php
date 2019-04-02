<?php

/**

 * Template Name: header2

 *

 * Alternative herader to match the alternative home page (home2)

 *

 * 

 */

?><!DOCTYPE html>

<html <?php language_attributes(); ?>>
<!--[if IE 8 ]>    <html dir="ltr" lang="en-US" class="no-js ie8 oldie"> <![endif]-->

<head>

<meta charset="<?php bloginfo( 'charset' ); ?>">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?php wp_title( '|', true, 'right' ); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11">

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<link href='https://fonts.googleapis.com/css?family=Raleway:300,400,600,800' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">



<script src="https://use.typekit.net/hqc3sbr.js"></script>

<script>try{Typekit.load({ async: true });}catch(e){}</script>



<?php wp_head(); ?>

</head>



<body <?php body_class(); ?>>



<div id="page" class="hfeed site">

	<?php do_action( 'before' ); ?>

	<header id="masthead" class="site-header" role="banner">

		<!-- The Modal -->
		<div id="myModal" class="modal">

		  <!-- Modal content -->
		  <div class="modal-content">
		    <span class="close">&times;</span>
		    <?php $link = get_field('header_announcement_link');
				if(!empty($link)): ?>
			<a href="<?php the_field('header_announcement_link'); ?>">
			<?php endif; ?>	
			    <?php $image = get_field('header_announcement_image');
				if( !empty($image) ): 	?>		
				<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
			</a>
			<?php endif; ?>
		  </div>

		</div>

		<div class="site-branding">

			<?php //show title or Header image

			if ( get_header_image() ) { 

				echo '<a href="'. home_url() .'"><img alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" title="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="header-image" src="' . esc_url( get_header_image() ) . '" /></a>';

			} else { ?>

				<h1 class="site-title"><a title="<?php bloginfo( 'name' ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>

			<?php } ?>			

			

			

			</div>	  	



			<nav id="site-navigation" class="main-navigation" role="navigation">



				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

				

			</nav><!-- #site-navigation -->





	<div class="container">
		<div class="menu-mobile">

			<div class="site-branding-mobile">

						<?php //show title or Header image

						if ( get_header_image() ) { 

							echo '<a href="'. home_url() .'"><img alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" title="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="header-image" src="' . esc_url( get_header_image() ) . '" /></a>';

						} else { ?>

							<h1 class="site-title"><a title="<?php bloginfo( 'name' ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>

						<?php } ?>

					

			</div>



	  				<div id="menu-toggle">

	  					<i class="fa fa-bars"></i>

   					</div>



	  				<nav id="site-navigation-mobile" class="main-navigation" role="navigation">



						<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

				

					</nav><!-- #site-navigation-mobile -->
					
			</div>			
	  	</div>

	  			
		





	  	<?php



				$post_object = get_field('featured_post_header');



					if( $post_object ): 

						// override $post

					$post = $post_object; 

					setup_postdata( $post );



			?>
			<div id="featured_img" class="">	
	  			<a href="<?php the_permalink() ?>"> 
	  				<div id="featured">
	  					<div class="feature" id="feature-1">			
	  						<img src="<?php the_post_thumbnail_url('full'); ?>"><!--Post image link -->
	  						<h5>Current Programming</h5>
	  						<h3><span><?php the_title();?></span></h3>
	  						<h4><?php echo get_post_meta($post->ID, 'events_date', true); ?></h4>		
							<div class="bg_overlay"></div>
						</div>
						<div class="featured-nav">	</div>
					</div>
				</a>
			</div>	
			<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
				<?php endif; ?>		

		



	</header><!-- #masthead -->



	<div id="content" class="site-content">