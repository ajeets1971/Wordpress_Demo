<?php
/**
 * Template Name: My First Custom page template 
 */
 
get_header(); ?>
 
<div id="main-content" class="main-content">
 
<?php
	if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
?>
 
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
				
		<?php
			$custom_posts = new WP_Query( array(
				'order_by' => 'title',
				'order'    => 'asc'
			));
			if ( $custom_posts->have_posts() ) :

				while ( $custom_posts->have_posts() ) : $custom_posts->the_post();

					get_template_part( 'content', get_post_format() );

				endwhile;

				twentyfourteen_paging_nav();

			else :

				get_template_part( 'content', 'none' );

			endif;
		?>
			
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->
 
<?php
get_sidebar();
get_footer();