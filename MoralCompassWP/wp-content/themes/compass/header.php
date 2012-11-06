<!DOCTYPE xhtml>
<html <?php language_attributes(); ?> class=no-js>
		<?php global $template; ?>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel=pingback href="<?php bloginfo( 'pingback_url' ); ?>"/>
        <link rel=profile href="http://gmpg.org/xfn/11"/>
        <meta name=viewport content="width=device-width"/>
        <title>
        <?php
            if ( is_home() || is_front_page() ) { bloginfo('name'); echo ' | '; bloginfo('description'); }
            elseif ( is_search() ) { bloginfo('name'); echo ' | Results for: ' . wp_specialchars($s); }
            elseif ( is_404() ) { bloginfo('name'); echo ' | Not found'; }
            else { bloginfo('name'); wp_title(' | '); }
        ?>
        </title>
        <?php
            if (is_singular() && get_option('thread_comments'))
                wp_enqueue_script('comment-reply'); 

            wp_head();
        ?>
        <link rel=stylesheet href="<?php bloginfo('stylesheet_url'); ?>"/>
        <link rel=stylesheet href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700">
        <script src="<?php echo get_template_directory_uri(); ?>/js/functions.js" type="text/javascript"></script>

        <!--[if lt IE 8]> -->
        <!-- <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script> -->
        <!-- <![endif]-->
    </head>
    <body <?php body_class(); ?>>
        
        <div class=wrapper>
            
            <header class=site-header>
            <?php 
            	$thePermalink = get_permalink();
            	$salesPitch = false;
            	if (strpos($thePermalink, "sales-pitch") != false)
            		$salesPitch = true;
            ?>
		    <?php if ($template == "WRITING" || $salesPitch == true) : ?>
                <a href="<?php echo get_bloginfo('url') . '/index.php/educational-product' ?>" class="logo ir left">
                    <?php bloginfo('name') ?>
                </a>
		    <?php else: ?> 
                <a href="<?php bloginfo('url') ?>" class="logo ir left">
                    <?php bloginfo('name') ?>
                </a>
			<?php endif ?>
                <nav class="main-menu-container right">
                    <?php
                    if (is_single()) : 
                        wp_nav_menu( array( 'container' => '', 'theme_location' => 'article-menu', 'depth' => 1 ) );
                    else :
                        wp_nav_menu( array( 'container' => '', 'theme_location' => 'main-menu', 'depth' => 1 ) );
                    endif;
                    ?>
        
                </nav>
			    <div class="little-search-box">
					<form role="search" method="post" id="searchform" action="/index.php/search-results">
			        <input type=submit value="Search" class=left>
					<input type=text name=s id=s placeholder="Enter Keyword" class=left>
					</form>
				</div>
                
            </header>