 <?php /* Template Name: Custom Post(2) - My Theme */ 
	get_header();
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$query_args = array(
	  'post_type' => 'book',
	  'posts_per_page' => 2,
	  'paged' => $paged
	);
	$the_query = get_posts($query_args);
	$posts = array(); 
	foreach($the_query as $post)
	{
		$posts[] += $post->ID;
	  	echo "<h1>" . $post->post_title . "</h1><br>";
	  	echo "<p>" . $post->post_content . "</p><br>";
	} 
	/*<?php if ($the_query->max_num_pages > 1) { // check if the max number of pages is greater than 1  ?>
	      <?php echo get_next_posts_link( 'Older Entries', $the_query->max_num_pages ); // display older posts link ?>
	      <?php echo get_previous_posts_link( 'Newer Entries' , $the_query->max_num_pages); // display newer posts link 
	      wp_reset_postdata();?>
	<?php } ?>

	<?php else: ?>
	    <h1>Sorry...</h1>
	    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php endif;*/
	$current = array_search( get_the_ID(), $posts );
	$prevID = $posts[$current-1];
	$nextID = $posts[$current+1];
	?>

	<div class="navigation">
	<?php if ( !empty( $prevID ) ): ?>
	<div class="alignleft">
	<a href="<?php echo get_permalink( $prevID ); ?>"
	  title="<?php echo get_the_title( $prevID ); ?>">Previous</a>
	</div>
	<?php endif;
	if ( !empty( $nextID ) ): ?>
	<div class="alignright">
	<a href="<?php echo get_permalink( $nextID ); ?>" 
	 title="<?php echo get_the_title( $nextID ); ?>">Next</a>
	</div>
	<?php endif; ?>
	</div><!-- .navigation -->
<?php	 
get_sidebar();
get_footer();
?>