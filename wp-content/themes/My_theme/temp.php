<?php 
$args = array(
    'post_type' => 'post'
);
$the_query = new WP_Query( $args ); 
 if ( $the_query->have_posts() ) :

    while ( $the_query->have_posts() ) : $the_query->the_post();?> 
      <h2><?php the_title(); ?></h2>
    <?php endwhile;
  wp_reset_postdata();

 else : ?>
  <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>


<?php get_header(); 
$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
$query_args = array(
  'post_type' => 'post',
  'posts_per_page' => 2,
  'paged' => $paged
);
$the_query = new WP_Query( $query_args );
 if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); // run the loop ?>
    <h1><?php echo the_title(); ?></h1>
      <?php the_excerpt(); ?>
<?php endwhile; 

previous_posts_link('&laquo; Newer posts');
next_posts_link( 'Older posts &raquo;', $query->max_num_pages );
wp_reset_postdata();
 else: ?>
    <h1>Sorry...</h1>
    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif;
get_footer(); ?>

