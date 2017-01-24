 <?php /* Template Name: Pages - My Theme */ 

    $pages = get_pages(); 
    foreach ( $pages as $page ) {
        echo '<li><a href="'. get_page_link( $page->ID ) .'">'.$page->post_title.'</a></li>';
    }
?>