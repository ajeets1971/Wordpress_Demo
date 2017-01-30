<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>
<head>
    <meta charset="UTF-8" />
    <title>Home - HealthyLife</title>
    <link rel="stylesheet" type="text/css" href="http://localhost/wordpress/wp-content/themes/My_theme/css/style.css" />
    <!--[if IE 9]>
        <link rel="stylesheet" type="text/css" href="css/ie9.css" />
    <![endif]-->
    <!--[if IE 7]>
        <link rel="stylesheet" type="text/css" href="css/ie7.css" />
    <![endif]-->
    <!--[if IE 6]>
        <link rel="stylesheet" type="text/css" href="css/ie6.css" />
    <![endif]-->
</head>
<body>
    <div id="page">
        <div id="header">
            <div>
                <a href="index.html"><img src="http://localhost/wordpress/wp-content/themes/My_theme/images/logo.gif" alt="Logo" /></a>
            </div>
            <ul>
                <!-- <li class="first current"><a href="http://localhost/wordpress/all-post/">All Post</a></li>
                <li><a href="http://localhost/wordpress/custom-post/">Custom Post</a></li>
                <li><a href="about.html">About us</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li class="last"><a href="contact.html">Contact Us</a></li> -->
            

            <?php
                $pages = get_pages(); 
                foreach ( $pages as $page ) {
                    if($page->post_title != "Sample Page" && $page->post_title != "Pages" )
                        echo '<li><a  class="first current" href="'.get_page_link( $page->ID ).'">'.$page->post_title.'</a></li>';
                }
            ?>
            </ul>
        </div>