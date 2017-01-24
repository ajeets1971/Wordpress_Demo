<?php /*Template Name: Books*/

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
	            <td style="width: 100%">Movie Director</td>
	            <td><input type="text" size="80" name="movie_review_director_name" value="<?php echo $movie_director; ?>" /></td>
	        </tr>
	        <tr>
	            <td style="width: 150px">Movie Rating</td>
	            <td>
	                <select style="width: 100px" name="movie_review_rating">
	                <?php
	                // Generate all items of drop-down list
	                for ( $rating = 5; $rating >= 1; $rating -- ) {
	                ?>
	                    <option value="<?php echo $rating; ?>" <?php echo selected( $rating, $movie_rating ); ?>>
	                    <?php echo $rating; ?> stars <?php } ?>
	                </select>
	            </td>
	        </tr>
	    </table>
	    <?php
	}
 ?>