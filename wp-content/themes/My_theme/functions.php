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
	    add_meta_box( 'book_review_meta_box',
	        'Book Details',
	        'display_book_review_meta_box',
	        'book', 'normal', 'high'
	    );
	}
	function display_book_review_meta_box( $book ) {
    // Retrieve current name of the Director and Movie Rating based on review ID
	    $Book_name = esc_html( get_post_meta( $book->ID, 'Book_name', true ) );
	    $Author_name = esc_html( get_post_meta( $book->ID, 'Author_name', true ) );
	    $Book_rating = intval( get_post_meta( $book->ID, 'Book_rating', true ) );
	    wp_nonce_field( 'save_book_details', 'book_nonce' );
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
	    </table>
	    <?php
	}
	add_action( 'save_post', 'save_post_meta_book' );
	function save_post_meta_book( $id )
	{
	    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	     
	    if( !isset( $_POST[book_nonce] ) || !wp_verify_nonce( $_POST['book_nonce'], 'save_book_details' ) ) return;
	     
	    if( !current_user_can( 'edit_post' ) ) return;
	     
	    $allowed = array(
	        'p' => array()
	    );
	     
	    if( isset( $_POST['Book_name'] ) )
	        update_post_meta( $id, 'Book_name', wp_kses( $_POST['Book_name'], $allowed ) );
	     
	    if( isset( $_POST['Author_name'] ) )
	        update_post_meta( $id, 'Author_name', esc_attr( strip_tags( $_POST['Author_name'] ) ) );
	         
	    if( isset( $_POST['Book_rating'] ) )
	        update_post_meta( $id, 'Book_rating', esc_attr( strip_tags( $_POST['Book_rating'] ) ) );
	     
	}

	add_filter( 'the_content', 'cd_display_quote' );
	function cd_display_quote( $content )
	{   
	    if( !is_single() ) return $content;
	    global $post;	     
	    $book = get_post_meta( $post->ID, 'Book_name', true );
	    if( empty( $quote ) ) return $content;
	    $author = get_post_meta( $post->ID, 'Author_name', true );
	    $rating = get_post_meta( $post->ID, 'Book_rating', true );
	    $out = '<blockquote>' . $book;
	    if( !empty( $author ) )
	    {
	        $out .= '<p class="Author-name">-' . $author;
	        if( !empty( $Rating ) )
	            $out .= ' (' . $Rating . ')';
	        $out .= '</p>';       
	    }
	    $out .= '</blockquote>';
	    return $out . $content;
	}

	function arphabet_widgets_init() {

		register_sidebar( array(
			'name'          => 'Home right sidebar',
			'id'            => 'home_right_1',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="rounded">',
			'after_title'   => '</h2>',
		) );

	}
	add_action( 'widgets_init', 'arphabet_widgets_init' );

	function wpgyan_widgets_init() {

	register_sidebar( array(
		'name' => 'Header Sidebar',
		'id' => 'header_sidebar',
		'before_widget' => '<div id="wpgyan-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="rounded">',
		'after_title' => '</h2>',
	) );
	}
	add_action( 'widgets_init', 'wpgyan_widgets_init' );

	function get_Pages_Shortcode(){
		$pages = get_pages(); 
	    foreach ( $pages as $page ) {
	        echo '<br><li><a href="'. get_page_link( $page->ID ) .'">'.$page->post_title.'</a></li>';
	    }
	}

	add_shortcode( 'getPagesShortcode', 'get_Pages_Shortcode' );
?>