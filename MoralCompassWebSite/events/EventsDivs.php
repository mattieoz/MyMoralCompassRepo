<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../include/dbdefs.php';
require_once '../include/topicselection.class.php';


// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$topic=$_GET["topic"];
$wp_user_id = $_GET["wp_user_id"];
$initial_state = $_GET["state"];
$submitMyScorecard = $_POST["submitMyScorecard"];


$con = mysql_connect(DBHOST, DBUSER, DBPASSWORD);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

$introSelection = -1;
$articleSelection = -1;
$scoreCardSelection = -1;
$linksSelection = -1;
$numberOfResources = 0;

mysql_select_db(DBSCHEMA, $con);

if ($submitMyScorecard)
{
	//foreach ($_POST as $key => $value){
	//	echo "key: " . $key . "   value: " . $value;
	//}

	for ($i=1; $i<=8; $i++)
	{
		$scoreItem = new ScoreItem();
		$scoreItem->id = $_POST["myscorecard_record" . $i];
		$scoreItem->originalScoreId = $_POST["myscorecard_scoreid" . $i];
		$scoreItem->score = $_POST["myscorecard_userrating" . $i];
		$scoreItem->description = $_POST["myscorecard_description" . $i];
		$arrScoreItems[$i-1] = $scoreItem;
		//echo "Scorecard item " . $i . " is " . $arrScoreItems[$i-1]->id . " " . $arrScoreItems[$i-1]->originalScoreId . " " . $arrScoreItems[$i-1]->score . " " . $arrScoreItems[$i-1]->description . "<br/>";
		if (!empty($scoreItem->id) )
		{
			if ($scoreItem->id == -1)
			{
				$sql = "Insert into user_score (user_id, score_id, user_rating, description, approval_status) values ('" . $wp_user_id . "', '" . $scoreItem->originalScoreId .
					"', '" . $scoreItem->score . "', '" . mysql_real_escape_string($scoreItem->description) . "', '1')";
			}
			else
			{
				$sql = "Update user_score set user_rating = '" . $scoreItem->score . "', description = '" . mysql_real_escape_string($scoreItem->description) . 
					"' where id = " . $scoreItem->id;
			}
			$result = mysql_query($sql);
			if ($result != 1)
			{
				echo "Error inserting user_score --- Result = " . $result . "  error: " . mysql_error();
				echo "SQL is " . $sql;
				exit();
			}

		}
	}
}

$sql = "Select * from topic where topic.short_name = '".$topic ."'";
//echo $sql;
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$title = $row['title'];
echo "Title is " . $title;
$sql="SELECT * FROM selection inner join topic on selection.topic=topic.id WHERE topic.short_name = '".$topic."'";

$result = mysql_query($sql);
$selectionIndex = 0;
while($row = mysql_fetch_array($result))
  {
  	$aSelection = new Selection();
	// row[0] yields id of selection
	$aSelection->id = $row[0];
	$aSelection->type = $row['type'];
	$aSelection->article = nl2br($row['article_text']);
	//$aSelection->article = mb_convert_encoding($aSelection->article, "latin1");
	$aSelection->thumbnail = $row['thumbnail_image'];
	$aSelection->image = $row['graphic_image'];
	$aSelection->breadcrumb = nl2br($row['breadcrumb']);
	$aSelection->videoEmbedCode = $row['video_embed_code'];
	$aSelection->videoURL = $row['video_url'];

	$topicSelections[$selectionIndex] = $aSelection;
	switch ($aSelection->type)
	{
	case Selection::INTRO:
		$introSelection = $selectionIndex;
		break;
	case Selection::ARTICLE:
		$articleSelection = $selectionIndex;
		break;
	case Selection::VIDEO:
		$resourceSelections[$numberOfResources] = $selectionIndex;
		$resourceHtml[$numberOfResources] = $aSelection->videoEmbedCode;
		//$resourceHtml[$numberOfResources] = str_replace("\"", "\\\"", $resourceHtml[$numberOfResources]);
		$numberOfResources = $numberOfResources + 1;
		break;
	case Selection::GRAPHIC:
		$resourceSelections[$numberOfResources] = $selectionIndex;
		$resourceHtml[$numberOfResources] = "<img src='../images/" . $topic . "/" . $aSelection->image . "' border='0' />";
		$resourceHtml[$numberOfResources] = str_replace("\"", "\\\"", $resourceHtml[$numberOfResources]);
		$numberOfResources = $numberOfResources + 1;
		break;
	case Selection::SCORECARD:
		$scoreCardSelection = $selectionIndex;
		break;
	case Selection::LINKS:
		$linksSelection = $selectionIndex;
		break;
	default:
		break;
	}
	$selectionIndex = $selectionIndex + 1;
  }
  $numberOfScores = 0;
  $numberOfUserScores = 0;
  if ($scoreCardSelection != -1)
  {
	$sql = "SELECT * from score where score.selection = " . $topicSelections[$scoreCardSelection]->id;
	//echo $sql;

	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result))
	  {
		$aScore = new ScoreItem();
		$aScore->id = $row['id'];
		$aScore->who = $row['who'];
		$aScore->score = $row['score'];
		$aScore->description = $row['description'];
		$scoreCardItems[$numberOfScores] = $aScore;
		$numberOfScores = $numberOfScores + 1;
	  }
	  
	if (isset($wp_user_id) && $wp_user_id > 0)
	{
		$sql = "select us.id, sc.id, us.user_id, sc.who, us.user_rating, us.description, us.approval_status from user_score us " .
			"inner join score sc on us.score_id=sc.id where us.user_id=" . $wp_user_id . " and sc.selection= " . $topicSelections[$scoreCardSelection]->id;
		//echo $sql;
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result))
		{
			$aScore = new ScoreItem();
			$aScore->id = $row[0];
			$aScore->originalScoreId = $row[1];
			$aScore->who = $row['who'];
			$aScore->score = $row['user_rating'];
			$aScore->description = $row['description'];
			$userScoreCardItems[$numberOfUserScores] = $aScore;
			$numberOfUserScores = $numberOfUserScores + 1;
		}
		//echo "Number of user scores is " . $numberOfUserScores . " user score card item 0 id is: " . $userScoreCardItems[0]->id;
		if ($numberOfUserScores == 0)
		{
			while ($numberOfUserScores < $numberOfScores)
			{
				$aScore = new ScoreItem();
				$aScore->id = -1;
				$aScore->originalScoreId = $scoreCardItems[$numberOfUserScores]->id;
				$aScore->who = $scoreCardItems[$numberOfUserScores]->who;
				$aScore->score = -1;
				$aScore->description = "";
				$userScoreCardItems[$numberOfUserScores] = $aScore;
				$numberOfUserScores = $numberOfUserScores + 1;
			}
		}
	}	
  }
  
mysql_close($con);

function userScoresAreEntered()
{
	global $userScoreCardItems, $numberOfUserScores;
	if ($numberOfUserScores == 0 || $userScoreCardItems[0]->id == -1)
	{
		return false;
	}
	else
	{
		return true;
	}
}

?>

<title><?=$title?></title>
  <meta name="generator" content="Amaya, see http://www.w3.org/Amaya/" />
  <style type="text/css">
<!--
.style1, body {
	font-size: 14px;
	font-family:Arial, Helvetica, sans-serif;
}
.hidden { display: none; }
.visible { display: block; }
textarea.orange-scrollbar {scrollbar-base-color:orange;}
textarea.red-scrollbar {scrollbar-base-color:red;}
textarea {
    resize: none;
}
-->
  </style>
<script language="javascript">
function makeVisible(tag, vis) {
	var element = document.getElementById(tag);
	if (element)
	{
		if (vis)
			element.className = "visible";
		else
			element.className = "hidden";
	}
}

function setState(param) {
	for (i=1;i<=6;i++)
	{	
		var rsrcName = "divResource" + i;
		makeVisible(rsrcName, false);
	}
	if (param == 1)
	{
		makeVisible("divIntro", true);
		makeVisible("divFullArticle", false);
		makeVisible("divScoreCard", true);
		makeVisible("divMyScoreCard", false);
		makeVisible("divResourceThumbnails", true);
		makeVisible("divLinks", true);
		location.href = "#";
	}
	else if (param == 2)
	{
		makeVisible("divIntro", false);
		makeVisible("divFullArticle", true);
		makeVisible("divScoreCard", true);
		makeVisible("divMyScoreCard", false);
		makeVisible("divResourceThumbnails", true);
		makeVisible("divLinks", true);
		location.href = "#";
	}
	else if (param == 3)
	{
		location.href = "#scorecard";
	}
	else if (param == 4)
	{
		makeVisible("divIntro", true);
		makeVisible("divFullArticle", false);
		makeVisible("divScoreCard", true);
		makeVisible("divMyScoreCard", true);
		makeVisible("divResourceThumbnails", true);
		makeVisible("divLinks", true);
		location.href = "#myscorecard";
	}
	else if (param == 5)
	{
		location.href = "#resources";
	}
	else if (param == 6)
	{
		location.href = "#links";
	}
}

var divCurrentlyPlayingVideo = "";

function setResource(rsrc) {
	var divRsrc = rsrc + 1;
	
	makeVisible("divIntro", true);
	makeVisible("divFullArticle", false);
	makeVisible("divScoreCard", true);
	makeVisible("divMyScoreCard", false);
	makeVisible("divResourceThumbnails", true);
	makeVisible("divLinks", true);
	if (divCurrentlyPlayingVideo != "")
	{
		toggleVideo(divCurrentlyPlayingVideo, 'hide');
		divCurrentlyPlayingVideo = "";
	}
	for (i=1;i<=6;i++)
	{	
		var rsrcName = "divResource" + i;
		if (i == divRsrc)
		{
			makeVisible(rsrcName, true);
			divCurrentlyPlayingVideo = rsrcName;
		}
		else
			makeVisible(rsrcName, false);
	}
} 

function toggleVideo(videoDivId, state)
{
    var div = document.getElementById(videoDivId);
	if (div.getElementsByTagName("iframe")[0] == undefined)
		return;
    var iframe = div.getElementsByTagName("iframe")[0].contentWindow;
	//div.style.display = state == 'hide' ? 'none' : '';
	func = (state == 'hide' ? 'pauseVideo' : 'playVideo');
	iframe.postMessage('{"event":"command","func":"' + func + '","args":""}','*');
}
</script>

  
</head>

<body onload="javascript:setState(1)">

<p align="left">&nbsp;</p>
<table width="1050" border="0" cellpadding="10">
  <tr>
  <td>&nbsp;</td>
  <td>
  <h1 align="center"><?=$title?></h1>
  </td>
  <tr>
  	<td width="250" valign="top">
    <a href="../EventsMenu.html">Events Menu</a><br/><br/>
    <a href="javascript:setState(1);">Show Intro</a><br/>
    <a href="javascript:setState(2);">Show Article</a><br/>
    <a href="javascript:setState(3);">Show Scorecard</a><br/>
    <a href="javascript:setState(5);">Show Resources</a><br/>
    <a href="javascript:setState(6);">Show Links</a><br/>
    My Scorecard<br/>
    <?php if (!isset($wp_user_id) || $wp_user_id <= 0) {?>
    Not logged in.  <a href="">Log in</a>
    <?php } else if (!userScoresAreEntered()) { ?>
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
    <?
		echo "<p align='left'>" . $topicSelections[$introSelection]->article . "</p>";
		echo "<a href='javascript:setState(2)'>Read more</a><br/><br/>"
	?>
    </div>
    <div id="divFullArticle" class="hidden" style="overflow:auto;height:400px;">
    <a name="article"></a> 
    <?
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
    <h3>Matt's Scorecard<hr/></h3>
    <table border='1'>
    <?
			foreach ($scoreCardItems as $scoreCardItem)
			{
				echo "<tr>";
				echo "<td width='150'>" . $scoreCardItem->who . "</td>";
				echo "<td width='40'>" . $scoreCardItem->scoreToGrade() . "</td>";
				echo "<td>" . $scoreCardItem->description . "</td>";
				echo "</tr>";
			}
	?>
    </table>
    </div>
    <div id="divResourceThumbnails" class="hidden">
    <a name="resources"></a> 
    <?
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
    <?
		echo "<h3>Bibliography and Links<hr/></h3>";
		echo "<p align='left'>" . $topicSelections[$linksSelection]->article . "</p>";
	?>
    </div>
    
    </td>
  </tr>
</table>
<p align="left">&nbsp; </p>
<p></p>

</body>
</html>
