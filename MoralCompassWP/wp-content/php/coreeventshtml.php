<p align="left">&nbsp;</p>
<table width="1050" border="0" cellpadding="10">
  <tr>
  <td>&nbsp;</td>
  <td>
  <h1 align="center"><?php  echo $title?></h1>
  </td>
  <tr>
  	<td width="250" valign="top">
    <a href="/?page_id=17">Events Menu</a><br/><br/>
    <a href="javascript:setState(1);">Show Intro</a><br/>
    <a href="javascript:setState(2);">Show Article</a><br/>
    <a href="javascript:setState(3);">Show Scorecard</a><br/>
    <a href="javascript:setState(4);">Show Resources</a><br/>
    <a href="javascript:setState(5);">Show Links</a><br/>
    <p/>
    My Scorecard<br />
    <?php if (!isset($wp_user_id) || $wp_user_id <= 0) {
		echo "Not logged in";
		$permalink = get_permalink();
		wp_loginout($permalink);
    } 
	else if ($numberOfUserScores == 0 || $userScoreCardItems[0]->id == -1) { ?>
    No scores entered.  <a href="javascript:setState(4);">Enter my scores</a>
    <?php } else { ?>
    <table border='0'>
    <hr />
    <?
			foreach ($userScoreCardItems as $scoreCardItem)
			{
				echo "<tr>";
				echo "<td width='150'>" . $scoreCardItem->who . "</td>";
				echo "<td width='40'>" . $scoreCardItem->scoreToGrade() . "</td>";
				echo "</tr><tr>";
				echo "<td colspan='2'>" . $scoreCardItem->description . "<hr></td>";
				echo "</tr>";
			}
	?>
    </table>
    <a href="javascript:setState(4);">Change my scores</a>
    <?php } ?>
    
    </td>
    <td width="800" height="725" valign="top">
    <div id="divIntro" class="hidden">
    <a name="intro"></a> 
    <?php
		echo "<p align='left'>" . $topicSelections[$introSelection]->article . "</p>";
		echo "<a href='javascript:setState(2)'>Read more</a><br/><br/>"
	?>
    </div>
    <div id="divFullArticle" class="hidden" style="overflow:auto;height:400px;">
    <a name="article"></a> 
    <?php
		echo "<p align='left'>" . $topicSelections[$articleSelection]->article . "</p>";
	?>
    </div>
    <div id="divMyScoreCard" class="hidden">
    <a name="myscorecard"></a> 
	<form action="" method="post" name="EditMyScoreCard">
    <h3>My Scorecard<hr/></h3>
    <table border='1'>
    <?
			$iScorecardCount = 1;
			foreach ($userScoreCardItems as $scoreCardItem)
			{
				echo "<tr>";
				echo "<td width='150'>" . $scoreCardItem->who . "</td>";
				echo "<td width='40'>";
				echo "<select name='myscorecard_userrating" . $iScorecardCount . "'>";
				echo outputGradeOptions($scoreCardItem->score);
				echo "</select>";
				echo "</td>";
				echo "<td width='400'><TEXTAREA name='myscorecard_description" . $iScorecardCount . "' COLS=75 ROWS=2>" . $scoreCardItem->description . "</TEXTAREA></td>";
				echo "</tr>";
				echo "<input type='hidden' name='myscorecard_scoreid" . $iScorecardCount . "' value='" . $scoreCardItem->originalScoreId . "'>";
				echo "<input type='hidden' name='myscorecard_record" . $iScorecardCount . "' value='" . $scoreCardItem->id . "'>";
				$iScorecardCount = $iScorecardCount + 1;
			}
	?>
    <tr>
    <td colspan="3">
    <input type="submit" value="Submit" />
    <input type="button" value="Cancel" onClick="javascript:setState(1);" /></td>
    </tr>
    </table>
	<input type="hidden" name="submitMyScorecard" value="1" />
    </form>
    </div>
    <div id="divScoreCard" class="hidden">
    <a name="scorecard"></a> 
    <?php
			echo "<h3>Scorecard<hr/></h3>";
			echo "<table border='1'>";
			foreach ($scoreCardItems as $scoreCardItem)
			{
				echo "<tr>";
				echo "<td width='150'>" . $scoreCardItem->who . "</td>";
				echo "<td width='40'>" . $scoreCardItem->scoreToGrade() . "</td>";
				echo "<td>" . $scoreCardItem->description . "</td>";
				echo "</tr>";
			}
			echo "</table>";
	?>
    </div>
    <div id="divResourceThumbnails" class="hidden">
    <a name="resources"></a> 
    <?php
		echo "<h3>Primary Resources<hr/></h3>";
		for ($i=1; $i<=6; $i++)
		{
			if ($i <= $numberOfResources)
				echo "<div id='divResource" . $i . "' class='hidden'><a href='javascript:setResource(-1)'>Close</a><br/>" . $resourceHtml[$i-1] . "</div>";
			else
				echo "<div id='divResource" . $i . "' class='hidden'></div>";
		}
    	echo "<table border='0'>";
		$numberOfRows = ($numberOfResources + 2) / 3;
		$resourceIndex = 0;
		for ($i=0; $i<$numberOfRows; $i++)
		{
			echo "<tr>";
			$columnCount = 0;
			while ($resourceIndex < $numberOfResources && $columnCount < 3)
			{
				echo "<td width='240'>";
				$selectionIndex = $resourceSelections[$resourceIndex];
				echo "<a href='javascript:setResource(" . $resourceIndex . ")'><img src='../images/" . $topic . "/" . $topicSelections[$selectionIndex]->thumbnail . "' alt='' width='120' height='104' align='middle' /></a>";
				echo "<p>";
				echo $topicSelections[$selectionIndex]->topicLabel();
				echo "<p>";
				echo $topicSelections[$selectionIndex]->breadcrumb;
				echo "</td>";
				$resourceIndex++;
				$columnCount++;
			}
			echo "</tr>";
		}
    	echo "</table>";
	?>
    </div>
    <div id="divLinks" class="hidden">
    <a name="links"></a> 
    <?php
		echo "<h3>Bibliography and Links<hr/></h3>";
		echo "<p align='left'>" . $topicSelections[$linksSelection]->article . "</p>";
	?>
    </div>
    
    </td>
  </tr>
</table>
