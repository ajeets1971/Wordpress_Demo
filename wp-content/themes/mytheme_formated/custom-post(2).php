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
	my_pagination();	 
	get_sidebar();
	get_footer();
?>