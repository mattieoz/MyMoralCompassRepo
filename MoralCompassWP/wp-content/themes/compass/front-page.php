<?php 

error_reporting(E_ERROR | E_WARNING | E_PARSE);

include(ABSPATH . 'wp-content/php/coretimeperiod.php');
?>
<?php get_header() ?>

<div class="container clearfix">

    <div class=clearfix>
        <h6 class="head-title left">INDEX OF ARTICLES</h6>
    </div>
    
    <?php get_sidebar(); ?>
    
    <div class="content left">
    
        <section class=top-content>
            <?php if (have_posts()) while (have_posts()) : the_post(); ?>
            <article id="<?php the_ID() ?>" class="home-article serif">
                <?php the_content() ?>
            </article>
            <?php endwhile; ?>
    
            <?php
                if ($featuredTopic != null):
            ?>
    
            <article class="featured-article clearfix">
                <h6 class=featured-title>FEATURED ARTICLE: <strong><?php echo $featuredTopic->title ?></strong></h6>
    			
                <?php echo "<a href='index.php/" . $featuredTopic->shortName . "'>
					<img class='aligncenter wp-post-image' src='" . ABSURL . "wp-content/images/" . 
                	$featuredTopic->shortName . "/" . $featuredImage . "' alt='' /></a>" ?>
    			
    			<br/>
    			<p>
                <?php
                	echo $featuredCaption;
                    //global $more;
                    //$more = 0;
                    //the_content('');
                ?>
    			</p>
    			
                <a href="index.php/<?php echo $featuredTopic->shortName ?>" title="Read more and hand out some grades" class=right>
                    Read more and hand out some grades.
                </a>
                
            </article>
            <?php endif;// endwhile; ?>

        </section>
    
        <section class=search-box>

            <h6 class=padded-title>SEARCH myMoralCompass.com</h6>
			<form role="search" method="post" class="search-form standout clearfix" id="searchform" action="index.php/search-results">
                <input type=text name=s id=s placeholder="Enter Keyword" class=left>
                <input type=submit value=Search class=left>
            </form>

        </section>
	<!--     
        <section class=author-box>

            <h6 class="padded-title featured-title">ABOUT THE AUTHOR: <strong><?php the_author_meta('display_name', 1) ?></strong></h6>
            <div class=standout>
                <p class=no-bottom>
                    <?php the_author_meta('description', 1) ?>
                </p>
            </div>

        </section>
        
    -->
     </div>

</div>
<?php get_footer() ?>