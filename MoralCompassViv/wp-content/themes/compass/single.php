<?php get_header() ?>

<div class="container clearfix">

    <div class=clearfix>
        <h6 class="head-title left"><?php the_title() ?></h6>
    </div>
    
    <?php get_sidebar('article'); ?>
    
    <div class="content left">

        <?php if (have_posts()) while (have_posts()) : the_post(); ?>
        <article class=article-content>

            <?php if (has_post_thumbnail()) : ?>
            <figure class="article-thumb right">
                <?php the_post_thumbnail() ?>
                <figcaption>
                    <?php the_post_thumbnail_caption(); ?>
                </figcaption>
            </figure>
            <?php endif; ?>

            <div class=article-text>
                <h6>INTRODUCTION</h6>
                <?php 
                    global $more;
                    $more = 0;
                    the_content('Read more');
                ?>
            </div>

        </article>
        <?php endwhile; ?>

        <div id=your-score-area></div>

        <section class=scorecard>
            <h6 class="featured-title padded-title"><?php the_author_meta('first_name') ?>&rsquo;S SCORECARD</h6>
            
            <div class=scores>
                <table cellpadding=0 cellspacing=0 class="score-table no-bottom">
                    <tr>
                        <th class=header>
                            GW Bush
                        </th>
                        <td class=grade>
                            <div class="letter d">D</div>
                        </td>
                        <td class=description>
                            <div class=description-text>
                                I&rsquo;m sure it wasn&rsquo;t his idea to run the Horton he went along with it.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class=header>
                            Dukakis
                        </th>
                        <td class=grade>
                            <div class="letter b">B</div>
                        </td>
                        <td class=description>
                            <div class=description-text>
                                Perhaps Dukakis should get a better grade for taking the high road.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class=header>
                            Lee Atwater
                        </th>
                        <td class=grade>
                            <div class="letter f">F</div>
                        </td>
                        <td class=description>
                            <div class=description-text>
                                Atwater would do anything to win an election for his candidate the Horton ads he found a way to appeal to the public’s worst racist.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class=header>
                            Willie Horton
                        </th>
                        <td class=grade>
                            <div class="letter f">F</div>
                        </td>
                        <td class=description>
                            <div class=description-text>
                                Horton was a violent criminal who no doubt had no morals whatsoever. Is it even relevant to the issue? Atwater would have found another violent black criminal to hang on Dukakis.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class=header>
                            American Public
                        </th>
                        <td class=grade>
                            <div class="letter d">D</div>
                        </td>
                        <td class=description>
                            <div class=description-text>
                                Horton was a violent criminal who no doubt had no morals whatsoever. Is it even relevant to the issue? Atwater would have found another violent black criminal to hang on Dukakis.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class=caption>
                            These are the scores given by the author of this site, <?php the_author_meta('display_name') ?>.<br>
                            <a href="#" title="What score would you give?">What score would you give?</a> 
                        </td>
                    </tr>
                </table>    
            </div>
            
        </section>

        <section class=resources>
            <h6 class=padded-title>RESOURCES</h6>

            <div class=media-area>
                <ul class="media-list clearfix">
                    <li class=left>
                        <figure>
                            <img src="<?php bloginfo('template_url') ?>/img/willie-horton.jpg" alt="">
                            <figcaption>
                                The original Willie Horton attack ad.<br>
                                <span class=media-meta>Video:</span> 00:30 
                            </figcaption>
                        </figure>
                    </li>
                    <li class=left>
                        <figure>
                            <img src="<?php bloginfo('template_url') ?>/img/revolving-door.jpg" alt="">
                            <figcaption>
                                The revolving door: The response ad.<br>
                                <span class=media-meta>Video:</span> 2:56 
                            </figcaption>
                        </figure>
                    </li>
                    <li class=left>
                        <figure>
                            <img src="<?php bloginfo('template_url') ?>/img/sample.jpg" alt="">
                            <figcaption>
                                Lorem ipsum dolor sit amet, consectetur<br>
                                <span class=media-meta>Image:</span> 300 x 1000px 
                            </figcaption>
                        </figure>
                    </li>
                </ul>
                <a href="#" class="arrow next"></a>
            </div>
        </section>

        <?php comments_template(); ?>

        <?php if (get_post_meta(get_the_ID(), '_compass_sources')) : ?>
        <section class=sources>
            <div class=padded-title>
                <h6 class=underline-title>BIBLIOGRAPHY AND LINKS</h6>
                <?php echo wpautop(get_post_meta(get_the_ID(), '_compass_sources', true)); ?>
            </div>
        </section>
        <?php endif; ?>

    </div>

</div>
<?php get_footer() ?>