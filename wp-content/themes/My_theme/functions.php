<?php
	add_action( 'init', 'My_First_Custom_Post_type' );

	function My_First_Custom_Post_type() {
		$labels = array(
			'name' => 'Books'
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'book' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => null,
			//'taxonomies' 			 => array('post_tag','category'),
	 		'supports'           => array( 'title' )
		);

		register_post_type( 'book', $args );

		$labels = array(
	    'name' => 'Topics'
	  	);   
	 
	  register_taxonomy('topics','book', array(
	    'hierarchical' => true,
	    'labels' => $labels,
	    'show_ui' => true,
	    'show_admin_column' => true,
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'topic' ),
	  ));

	}
	add_action( 'admin_init', 'my_admin' );
	
	function my_admin() {
	    add_meta_box( 'movie_review_meta_box',
	        'Book Details',
	        'display_movie_review_meta_box',
	        'book', 'normal', 'high'
	    );
	}
	function display_movie_review_meta_box( $movie_review ) {
    // Retrieve current name of the Director and Movie Rating based on review ID
	    $movie_director = esc_html( get_post_meta( $movie_review->ID, 'movie_director', true ) );
	    $movie_rating = intval( get_post_meta( $movie_review->ID, 'movie_rating', true ) );
	    ?>
	    <table>
	        <tr>
	            <td style="width: 100%">Book Name</td>
	            <td><input type="text" size="80" name="Book_name" value="<?php echo $Book_name; ?>" /></td>
	        </tr>
	        <tr>
	            <td style="width: 100%">Author</td>
	            <td><input type="text" size="80" name="Author_name" value="<?php echo $Author_name; ?>" /></td>
	        </tr>
	        <tr>
	            <td style="width: 150px">Book Rating</td>
	            <td>
	                <select style="width: 100px" name="Book_rating">
	                <?php
	                // Generate all items of drop-down list
	                for ( $rating = 5; $rating >= 1; $rating -- ) {
	                ?>
	                    <option value="<?php echo $rating; ?>" <?php echo selected( $rating, $Book_rating ); ?>>
	                    <?php echo $rating; ?> stars <?php } ?>
	                </select>
	            </td>
	        </tr>
	        <tr>
	            <td style="width: 100%" colspan="2" align="center">
	            <input type="submit" name="submit_book" id="submit_book" /></td>
	        </tr>
	    </table>
	    <?php
	}
?>

<style type="text/css">
	#submit_book{
		width: 200px;
		height: 40px;
		border-radius: 5px;
	}
</style>