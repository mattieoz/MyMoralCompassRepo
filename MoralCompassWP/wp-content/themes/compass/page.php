<?php get_header() ?>

<div class="container clearfix">

    <div class=clearfix>
        <h6 class="head-title left"><?php the_title() ?></h6>
    </div>
    
    <div class="content left">

        <?php if (have_posts()) while (have_posts()) : the_post(); ?>
        <article class=article-content>

            <div class=article-text>
                <?php 
                    the_content();
                ?>
            </div>

        </article>
        <?php endwhile; ?>

    </div>

</div>
<?php get_footer() ?>