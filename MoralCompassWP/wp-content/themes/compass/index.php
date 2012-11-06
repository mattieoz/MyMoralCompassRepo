<?php 

error_reporting(E_ERROR | E_WARNING | E_PARSE);

include(ABSPATH . 'wp-content/php/coresearch.php');
?>



<?php get_header() ?>

<div class="container clearfix">

    <div class=clearfix>
        <h6 class="head-title left">Search Results</h6>
    </div>
    
    <?php get_sidebar(); ?>
    
    <div class="content left">
    
        <article class=article-content>

            <div class=article-text>
                <?php
                	if (count($searchResults) == 1)
                		$resultsStr = "1 matching result";
                	else
                		$resultsStr = count($searchResults) . " matching results";
                    echo "Your search for <b>" . $searchString . "</b> found " . $resultsStr . ".<br/><br/>";
                    foreach ($searchResults as $sr)
                    {
                    	echo "<a href='" . $sr->shortName . "'>" . $sr->title . "</a><br/>";
                    	echo $sr->text . "<br/><br/>";
                    }
                ?>
            </div>

        </article>
        </div>

</div>
<?php get_footer() ?>