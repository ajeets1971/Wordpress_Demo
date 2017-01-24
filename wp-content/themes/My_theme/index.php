<?php get_header(); 
$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
$query_args = array(
  'post_type' => 'post',
  'posts_per_page' => 1,
  'paged' => $paged
);
$the_query = new WP_Query( $query_args );
 if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); // run the loop ?>
    <h1><?php echo the_title(); ?></h1>
      <?php the_excerpt(); ?>
<?php endwhile; ?>

<?php if ($the_query->max_num_pages > 1) { // check if the max number of pages is greater than 1  ?>
      <?php echo get_next_posts_link( 'Older Entries', $the_query->max_num_pages ); // display older posts link ?>
      <?php echo get_previous_posts_link( 'Newer Entries' , $the_query->max_num_pages); // display newer posts link ?>
<?php } ?>

<?php else: ?>
    <h1>Sorry...</h1>
    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif;
get_footer(); ?>
