<?php
/**
 * Mytheme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mytheme
 */

if ( ! function_exists( 'mytheme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function mytheme_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Mytheme, use a find and replace
	 * to change 'mytheme' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'mytheme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'mytheme' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'mytheme_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'mytheme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mytheme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mytheme_content_width', 640 );
}
add_action( 'after_setup_theme', 'mytheme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mytheme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'mytheme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'mytheme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'mytheme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mytheme_scripts() {
	wp_enqueue_style( 'mytheme-style', get_stylesheet_uri() );

	wp_enqueue_script( 'mytheme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'mytheme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mytheme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

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
	function my_pagination() {
		global $wp_query;

		$big = 999999999; // need an unlikely integer
		
		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages
		) );
	}
?>
