<?php get_header() ?>

<div class="container clearfix">

    <div class=clearfix>
        <h6 class="head-title left"><?php the_title() ?></h6>
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
                $featured_query = new WP_Query(array('posts_per_page' => 1, 'meta_key' => '_compass_featured_post', 'meta_value' => 'on'));
                if ($featured_query->have_posts()) while ($featured_query->have_posts()) : $featured_query->the_post();
            ?>
    
            <article class="featured-article clearfix">
                <h6 class=featured-title>FEATURED ARTICLE: <strong><?php the_title() ?></strong></h6>
    
                <?php the_post_thumbnail('thumb', array('class' => 'alignright')) ?>
    
                <?php
                    global $more;
                    $more = 0;
                    the_content('');
                ?>
    
                <a href="<?php the_permalink() ?>" title="Read more and hand out some grades" class=right>
                    Read more and hand out some grades.
                </a>
            </article>
            <?php endwhile; ?>

        </section>
    
        <section class=search-box>

            <h6 class=padded-title>SEARCH myMoralCompass.com</h6>
            <form action="" method=get class="search-form standout clearfix">
                <input type=text name=s id=s placeholder="Enter Keyword" class=left>
                <input type=submit value=Search class=left>
            </form>

        </section>
    
        <section class=author-box>

            <h6 class="padded-title featured-title">ABOUT THE AUTHOR: <strong><?php the_author_meta('display_name') ?></strong></h6>
            <div class=standout>
                <p class=no-bottom>
                    <?php the_author_meta('description') ?>
                </p>
            </div>

        </section>
        
    </div>

</div>
<?php get_footer() ?>