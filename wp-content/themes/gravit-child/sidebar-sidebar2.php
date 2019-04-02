<?php
/**
*
*
*
* The page for the second sidebar
*
*
*
*/?>

<?php if ( is_active_sidebar( 'sidebar-2' ) ) { ?>
	<div class="sidebar">
		<div id="LeftBar" role="secodary">
			<div class="widget-area">
				<?php do_action( 'before_sidebar' ); ?>

				<?php dynamic_sidebar( 'sidebar-2' ); ?>
				
			</div>
		</div><!-- #LeftBar -->
	</div><!-- .sidebar -->
<?php } // end sidebar widget area ?>