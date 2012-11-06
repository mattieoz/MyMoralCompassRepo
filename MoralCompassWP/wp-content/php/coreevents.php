<?php
//require_once('/wp-content/php/dbdefs.php');
//require_once('/wp-content/php/topicselection.class.php');

require_once(ABSPATH . 'wp-content/php/dbdefs.php');
require_once(ABSPATH . 'wp-content/php/topicselection.class.php'); 
require_once(ABSPATH . 'wp-content/php/util.php');



//error_reporting(E_ERROR | E_WARNING | E_PARSE);

$template = "MORALCOMPASS";
//$topic=$_GET["topic"];
$title = get_the_title();
$underscoreLocation = strpos($title, "_");
$templateStr = "";
if ($underscoreLocation == false)
{
	$topic = $title;
}
else
{
	$topic = substr($title, 0, $underscoreLocation);
	$templateStr = substr($title, $underscoreLocation+1);
	if ($templateStr == "WR")
		$template = "WRITING";
}
//echo "Topic is : " . $topic . "  template is: " . $template . "  templateStr: " . $templateStr;
//echo "\nunderscoreLocaton: " . $underscoreLocation;
//exit();

$current_user = wp_get_current_user();
$wp_user_id = $current_user->ID;
$initial_state = $_GET["state"];
$submitMyScorecard = $_POST["submitMyScorecard"];
$submitMyWriting = $_POST["submitMyWriting"];
if (isset($_POST["student_user_id"]))
	$currentStudentId = $_POST["student_user_id"];
else
	$currentStudentId = -1;

$con = mysql_connect(DBHOST, DBUSER, DBPASSWORD);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

$introSelection = -1;
$articleSelection = -1;
$scoreCardSelection = -1;
$linksSelection = -1;
$instructionsSelection = -1;
$featuredGraphicSelection = -1;
$featuredGraphicHtml = "";
$numberOfWritingPrompts = 0;
$numberOfResources = 0;

mysql_select_db(DBSCHEMA, $con);
if ($submitMyWriting)
{
	// I'd like to find another way other than hard-coding to 8
	for ($i=1; $i<=8; $i++)
	{
		$writingItem = new WritingItem();
		$writingItem->id = $_POST["mywriting_id" . $i];
		$writingItem->selectionId = $_POST["mywriting_selectionid" . $i];
		$writingItem->text = $_POST["mywriting_text" . $i];
		if (!empty($writingItem->id) )
		{
			if ($writingItem->id == -1)
			{
				$sql = "Insert into user_writing (user_id, selection_id, text, approval_status) values ('" . $wp_user_id . "','" . $writingItem->selectionId .
					"', '" . mysql_real_escape_string($writingItem->text) . "', '1')";
			}
			else
			{
				$sql = "Update user_writing set text = '" . mysql_real_escape_string($writingItem->text)  . 
					"' where id = " . $writingItem->id;
				
			}
			//echo $sql;
			$result = mysql_query($sql);
			if ($result != 1)
			{
				echo "Error inserting user writing --- Result = " . $result . "  error: " . mysql_error();
				echo "SQL is " . $sql;
				exit();
			}
		}
	}
	//exit();
}

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
$authorId = $row['author'];
$authorDisplayName = get_the_author_meta( "display_name", $authorId );
//echo "Title is " . $title;
$sql="SELECT * FROM selection sl inner join topic tp on sl.topic=tp.id WHERE tp.short_name = '".$topic."' order by sl.sequence";

$result = mysql_query($sql);
$selectionIndex = 0;
while($row = mysql_fetch_array($result))
  {
  	$aSelection = new Selection();
	// row[0] yields id of selection
	$aSelection->id = $row[0];
	$aSelection->type = $row['type'];
	$aSelection->article = convertUtf8(nl2br($row['article_text']));
	$aSelection->thumbnail = $row['thumbnail_image'];
	$aSelection->image = $row['graphic_image'];
	$aSelection->breadcrumb = convertUtf8(nl2br($row['breadcrumb']));
	$aSelection->videoEmbedCode = $row['video_embed_code'];
	$aSelection->videoURL = $row['video_url'];
	$aSelection->size = $row['size'];
	$topicSelections[$selectionIndex] = $aSelection;
	switch ($aSelection->type)
	{
	case Selection::INTRO:
		$introSelection = $selectionIndex;
		break;
	case Selection::INSTRUCTIONS:
		$instructionsSelection = $selectionIndex;
		break;
	case Selection::FEATUREDGRAPHIC:
		$featuredGraphicSelection = $selectionIndex;
		$featuredGraphicHtml = "<img src='" . ABSURL . "wp-content/images/" . $topic . "/" . $aSelection->image . "' border='0' />";
		$featuredGraphicHtml = str_replace("\"", "\\\"", $featuredGraphicHtml);
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
		$resourceHtml[$numberOfResources] = "<img src='" . ABSURL . "wp-content/images/" . $topic . "/" . $aSelection->image . "' border='0' />";
		$resourceHtml[$numberOfResources] = str_replace("\"", "\\\"", $resourceHtml[$numberOfResources]);
		$numberOfResources = $numberOfResources + 1;
		break;
	case Selection::SCORECARD:
		$scoreCardSelection = $selectionIndex;
		break;
	case Selection::LINKS:
		$linksSelection = $selectionIndex;
		break;
	case Selection::WRITINGPROMPT:
		$writingPromptSelections[$numberOfWritingPrompts] = $selectionIndex;
		$numberOfWritingPrompts = $numberOfWritingPrompts + 1;
		break;
	default:
		break;
	}
	$selectionIndex = $selectionIndex + 1;
  }
  $numberOfSelections = $selectionIndex;

  $numberOfScores = 0;
  $numberOfUserScores = 0;
  
  $numberOfUserWritingItems = 0;
  
  if ($numberOfWritingPrompts != 0)
  {
  	if (loggedInAsTeacher())
  		$userWritingId = $currentStudentId;
  	else
  		$userWritingId = $wp_user_id;
	if ($userWritingId > 0)
	{
		$inString = "(";
		for ($i=0;$i<$numberOfWritingPrompts;$i++)
		{
			$inString = $inString . $writingPromptSelections[$i];
			if ($i < $numberOfWritingPrompts - 1)
				$inString = $inString . ", ";
		}
		$inString = $inString . ")";
		$sql = "select uw.id, uw.selection_id, uw.user_id, uw.text, uw.approval_status from user_writing uw " .
			"where uw.user_id=" . $userWritingId . " and uw.selection_id in " . $inString;
		//echo $sql;
		$result = mysql_query($sql);
		//echo "<br/>After sql error is " . mysql_error();
		while($row = mysql_fetch_array($result))
		{
			$aWriting = new WritingItem();
			$aWriting->id = $row['id'];
			$aWriting->selectionId = $row['selection_id'];
			$aWriting->userId = $row['user_id'];
			$aWriting->text = $row['text'];
			$userWritingItems[$numberOfUserWritingItems] = $aWriting;
			$numberOfUserWritingItems = $numberOfUserWritingItems + 1; 
		}
		for ($i=0;$i<$numberOfWritingPrompts;$i++)
		{
			if (findUserWritingIndexForSelection($writingPromptSelections[$i]) == -1)
			{
				$aWriting = new WritingItem();
				$aWriting->id = -1;
				$aWriting->selectionId = $writingPromptSelections[$i];
				$aWriting->userId = $wp_user_id;
				$aWriting->text = "";
				$userWritingItems[$numberOfUserWritingItems] = $aWriting;
				$numberOfUserWritingItems = $numberOfUserWritingItems + 1; 
			}
		}
		

		/*
		for ($i=0;$i<$numberOfWritingPrompts;$i++)
		{
			$selectionIndex = findUserWritingIndexForSelection($writingPromptSelections[$i]);
			echo "<br/>Writing prompt selection index is " . $writingPromptSelections[$i];
			echo "<br/>Writing prompt " . $i . " selection index is " . $selectionIndex;
			echo "<br/>prompt is " . $topicSelections[$writingPromptSelections[$i]]->article;
			echo "<br/>id is " . $userWritingItems[$selectionIndex]->id;
			echo "<br/>user writing is " . $userWritingItems[$selectionIndex]->text;
		}
		exit();
		*/
  	  }
  }

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
		$aScore->description = convertUtf8($row['description']);
		$scoreCardItems[$numberOfScores] = $aScore;
		$numberOfScores = $numberOfScores + 1;
	  }
	if (isset($wp_user_id) && $wp_user_id > 0)
	{
		$sql = "select us.id, sc.id, us.user_id, sc.who, us.user_rating, us.description, us.approval_status from user_score us " .
			"inner join score sc on us.score_id=sc.id where us.user_id=" . $wp_user_id . " and sc.selection= " . $topicSelections[$scoreCardSelection]->id;
		//echo $sql;
		$result = mysql_query($sql);
		//echo "<br/>After sql error is " . mysql_error();
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
		//echo "Number of user scores is " . $numberOfUserScores;
		//if ($numberOfUserScores > 0)
		//	echo " user score card item 0 id is: " . $userScoreCardItems[0]->id;
		
		// set up user scores but have them as blank
		// system will still see them as not entered
	}
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
	//echo "Number of user scores is " . $numberOfUserScores;
	//if ($numberOfUserScores > 0)
	//{
	//	echo " user score card item 0 id is: " . $userScoreCardItems[0]->id;
	//	echo " originalScoreId: " . $userScoreCardItems[0]->originalScoreId;
	//	echo " scoreCardItems id is " . $scoreCardItems[0]->id;
	//}
	//exit();
  }

mysql_close($con);

function userScoresAreEntered()
{
	global $userScoreCardItems, $numberOfUserScores;
	//echo "numberOfUserScores is " . $numberOfUserScores;
	//echo "userScoreCardItems[0]->id is " . $userScoreCardItems[0]->id;
	if ($numberOfUserScores == 0 || $userScoreCardItems[0]->id == -1)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function findUserWritingIndexForSelection($selectionId)
{
	global $userWritingItems, $numberOfUserWritingItems;
	for ($i=0;$i<$numberOfUserWritingItems;$i++)
	{
		if ($userWritingItems[$i]->selectionId == $selectionId)
			return $i;
	}
	return -1;
}

function loggedInAsTeacher()
{
	global $current_user;
	get_currentuserinfo();
	if ($current_user->user_login == "Teacher")
		return true;
	else
		return false;
}


?>
