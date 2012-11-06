<?php 
// Report simple running errors

//echo "Error reporting level: " . error_reporting();

include(ABSPATH . 'wp-content/php/coreevents.php');
//include('/wp-content/php/coreevents.php'); 
?>

<?php get_header() ?>



<?php 
//echo "Error reporting level: " . error_reporting();

?>
<div class="container clearfix">

    <div class=clearfix>
        <h6 class="head-title left"><?php echo $title ?></h6>
        <h5 class="right"><?php echo "Author: " . $authorDisplayName ?></h6>
    </div>
    
    <?php get_sidebar('article'); ?>
    
    <div class="content left">

        <?php if (have_posts()) while (have_posts()) : the_post(); ?>
        <article class=article-content>
        
        	<?php if ($template == "WRITING") :?>
        	<div id="divInstructions" class="article-text">
                <h6>INSTRUCTIONS</h6>
                <div id="divInstructionsText" class="instructions-area">
                <?php
					echo "<p align='left'>" . $topicSelections[$instructionsSelection]->article . "</p>";
				?>
				</div>
			</div>
			<?php endif ?>
            <div id="divIntro" class="article-text">
            <?php if ($featuredGraphicSelection != -1) : ?>
            <figure class="article-thumb right">
                <?php echo $featuredGraphicHtml; ?>
                <figcaption>
                    <?php //echo $topicSelections[$featuredGraphicSelection]->article; ?>
                </figcaption>
            </figure>
            <?php endif; ?>

                <h6>INTRODUCTION</h6>
                <?php
			//echo strToHex($scoreCardItems[0]->description);
			echo "<p align='left'>" . $topicSelections[$introSelection]->article . "</p>";
			echo "<a href='javascript:setState(2)'>Full article</a><br/><br/>"

                    //global $more;
                    //$more = 0;
                    //the_content('Read more');
                ?>
            </div>
            
            <div id="divFullArticle" class="hidden">
            	<div class="article-heading">
            	<h6>ARTICLE
				<?php //echo strToHex($topicSelections[$articleSelection]->article); ?>
            	<a href='javascript:setState(1)'>
            	<img class="align-right" src='<?php echo ABSURL ?>wp-content/themes/compass/img/close-button.png'/></a></h6>
            	</div>
                <div id="divArticleText" class="article-area" style="overflow:auto;height:398px;">
				<?php
                    echo "<p align='left'>" . $topicSelections[$articleSelection]->article . "</p>";
                ?>
                </div>
            </div>
        </article>
        <?php endwhile; ?>
        
		<a name="resources"></a> 
        <section class=resources>
            <h6 class=padded-title>RESOURCES (<?php echo $numberOfResources ?>)</h6>

            	<?php
					for ($i=1; $i<=8; $i++)
					{
						if ($i <= $numberOfResources)
							echo "<div align='center' id='divResource" . $i . 
							"' class='hidden'><div align='right'><a href='javascript:setResource(-1)'><img src='" . 
							ABSURL . "wp-content/themes/compass/img/close-button.png'/></a></div><br/>" . $resourceHtml[$i-1] . "</div>";
						else
							echo "<div id='divResource" . $i . "' class='hidden'></div>";
					}
				
				?>
            <div class=media-area id="divResourceThumbnails">
                <?php 
					if ($numberOfResources > 3)
					{
						echo "<div id='prevbutton' class='visible'>";
						echo "<a href='javascript:setVisibleThumbnails(-1, " . $numberOfResources . ");' class='arrow previous'></a>";
						echo "</div>";
					}
				?>
				
                <ul class="media-list clearfix">
                <?php
				$resourceIndex = 0;
				for ($i=0; $i<$numberOfResources; $i++)
				{
					$thumbnailIndex = $i + 1;
					$selectionIndex = $resourceSelections[$i];
					if ($i >= 3)
						echo "<div id='divThumbnail" . $thumbnailIndex . "' class='hidden'>";
					else
						echo "<div id='divThumbnail" . $thumbnailIndex . "' class='visible'>";
                    echo "<li class=left>";
                    echo "<figure>";
					echo "<a href='javascript:setResource(" . $i . ")'><img src='" . ABSURL . "wp-content/images/" . $topic . "/" . $topicSelections[$selectionIndex]->thumbnail . 
						"' width='120' height='90' alt='' /></a>";
                    echo "<figcaption>";
					echo $topicSelections[$selectionIndex]->breadcrumb;
                    echo "</figcaption></figure></li></div>";
				}
				?>
                </ul>
                <?php 
					if ($numberOfResources > 3)
					{
						echo "<div id='prevbutton' class='visible'>";
						echo "<a href='javascript:setVisibleThumbnails(1, " . $numberOfResources . ");' class='arrow next'></a>";
						echo "</div>";
					}
				?>
            </div>
        </section>
        
    <?php if ($template == "WRITING" && $numberOfWritingPrompts > 0 ) : ?>
    
        <h6 class=padded-title>WRITING (<?php echo $numberOfWritingPrompts ?>)
        <?php 
        	if (!is_user_logged_in())
        		echo "    <b>You must be logged in to activate the writing panel</b>";
        	else if (loggedInAsTeacher() && !empty($currentStudentId))
        	{
        		$user_info = get_userdata($currentStudentId);
        		echo "   <b>You are viewing writing from \"" . $user_info->user_login . "\"</b>";
        	}
        ?>
        </h6>
        
        <div id="divWriting" class=scores>
			<form action="" method="post" name="EditMyWriting">
            <table cellpadding=0 cellspacing=0 class="score-table no-bottom">
	    	<?php
				$iUserWritingCount = 1;
				for ($i=0;$i<$numberOfWritingPrompts;$i++)
        		{
        			$userWritingItemIndex = findUserWritingIndexForSelection($writingPromptSelections[$i]);
        			$userWritingItem = $userWritingItems[$userWritingItemIndex];
					echo "<p class=clearfix>";
					echo "<tr>";
					echo "<th class=header>" . $topicSelections[$writingPromptSelections[$i]]->article . "</th>";
					echo "</tr>";
					echo "<tr>";
					if (!is_user_logged_in() || loggedInAsTeacher())
						echo "<td class=description-text><TEXTAREA readonly='readonly' name='mywriting_text" . $iUserWritingCount . "' COLS=78 ROWS=8>" . $userWritingItem->text . "</TEXTAREA></td>";
					else
						echo "<td class=description-text><TEXTAREA name='mywriting_text" . $iUserWritingCount . "' COLS=78 ROWS=8>" . $userWritingItem->text . "</TEXTAREA></td>";
					echo "</tr>";
					echo "<input type='hidden' name='mywriting_id" . $iUserWritingCount . "' value='" . $userWritingItem->id . "'>";
					echo "<input type='hidden' name='mywriting_selectionid" . $iUserWritingCount . "' value='" . $userWritingItem->selectionId . "'>";
					echo "</p>";
					$iUserWritingCount = $iUserWritingCount + 1;
        		}
        	?>
		    <tr>
		    <td >
		    <input type="submit" value="Submit" />
		    <input type="button" value="Cancel" /></td>
		    </tr>
        	</table>
			<input type="hidden" name="submitMyWriting" value="1" />
        	</form>
        </div>
    
    <?php endif; ?>

	<?php if ($template == "MORALCOMPASS" && $userScoreCardItems != null) : ?>
    <div id="divMyScoreCard" class="hidden">
    <a name="myscorecard"></a> 
    <section class=myscorecard>
	<form action="" method="post" name="EditMyScoreCard">
    <h6 class="featured-title padded-title">MY SCORECARD</h6>
    
        <div class=scores>
            <table cellpadding=0 cellspacing=0 class="score-table no-bottom">
    	<?php
			$iScorecardCount = 1;
			foreach ($userScoreCardItems as $scoreCardItem)
			{
				echo "<p class=clearfix>";
				echo "<tr>";
				echo "<th class=header>" . $scoreCardItem->who . "</th>";
				echo "<td class=grade>";
				echo "<select name='myscorecard_userrating" . $iScorecardCount . "' id=grade class=left>";
				//echo "<select name='grade[]' id=grade class=left>";
				echo outputGradeOptions($scoreCardItem->score);
				echo "</select>";
				echo "</td>";
				echo "<td class=description-text><TEXTAREA name='myscorecard_description" . $iScorecardCount . "' COLS=53 ROWS=2>" . $scoreCardItem->description . "</TEXTAREA></td>";
				echo "</tr>";
				echo "<input type='hidden' name='myscorecard_scoreid" . $iScorecardCount . "' value='" . $scoreCardItem->originalScoreId . "'>";
				echo "<input type='hidden' name='myscorecard_record" . $iScorecardCount . "' value='" . $scoreCardItem->id . "'>";
				echo "</p";
				$iScorecardCount = $iScorecardCount + 1;
			}
	?>
    <tr>
    <td colspan="3">
    <input type="submit" value="Submit" />
    <input type="button" value="Cancel" onClick="javascript:setState(1);" /></td>
    </tr>
    </table>
    </div>
	<input type="hidden" name="submitMyScorecard" value="1" />
    </form>
	</section>
	</div>
	<?php endif ?>



	<?php if ($template == "MORALCOMPASS" && $scoreCardItems != null) : ?>

        <div id=your-score-area class=article-text></div>

        <section class=scorecard>
        	<div id="divAuthorScoreCardHeader">
	           	<h6 class="featured-title padded-title"><a href='javascript:setAuthorScoreCardVisible(true)'>
	           	Show <?php the_author_meta('first_name') ?>&rsquo;S SCORECARD</a></h6>
        	</div>
           	<div id="divAuthorScoreCard" class="hidden">
	           	<h6 class="featured-title padded-title"><?php the_author_meta('first_name') ?>&rsquo;S SCORECARD
	           	<a href='javascript:setAuthorScoreCardVisible(false)'>
	           	<img class="align-right" src='<?php echo ABSURL ?>wp-content/themes/compass/img/close-button.png'/></a></h6>
        		<br/>
            
	            <div class=scores>
	                <table cellpadding=0 cellspacing=0 class="score-table no-bottom">
			    <?php
	
				foreach ($scoreCardItems as $scoreCardItem)
				{
					echo "<tr>";
					echo "<th class=header>" . $scoreCardItem->who . "</th>";
					echo "<td class=grade>" . "<div class='" . $scoreCardItem->scoreToClass() . "'>" . $scoreCardItem->scoreToGrade() . "</div></td>";
					echo "<td class=description><div class=description-text>" . $scoreCardItem->description . "</div></td>";
					echo "</tr>";
				}
	
			    ?>
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
        	</div>
            
        </section>
	<?php endif ?>

	<?php if ($template == "MORALCOMPASS") : ?>
        <?php comments_template(); ?>
	<?php endif ?>
	
        <?php if (get_post_meta(get_the_ID(), '_compass_sources')) : ?>
        <section class=sources>
            <div class=padded-title>
                <h6 class=underline-title>BIBLIOGRAPHY AND LINKS</h6>
                <?php echo wpautop(get_post_meta(get_the_ID(), '_compass_sources', true)); ?>
            </div>
        </section>
        <?php endif; ?>
        <section class=sources>
            <div class=padded-title>
                <h6 class=underline-title>BIBLIOGRAPHY AND LINKS</h6>
                <?php echo $topicSelections[$linksSelection]->article; ?>
            </div>
        </section>
    </div>

</div>
<?php get_footer() ?>