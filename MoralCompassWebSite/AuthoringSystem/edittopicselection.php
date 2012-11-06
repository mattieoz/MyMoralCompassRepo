<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Topic Selection</title>
<?php

require_once '../include/dbdefs.php';
require_once '../include/topic.class.php';
require_once '../include/topicselection.class.php';

$con = mysql_connect(DBHOST, DBUSER, DBPASSWORD);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db(DBSCHEMA, $con);

$currentTopic = "";
if(isset($_POST["submitToDatabase"]))
{
	$currentTopic = $_POST["currentTopic"];	
	$editRecord = $_POST["editRecord"];
	$article = $_POST["article"];
	$thumbnail = $_POST["thumbnail"];
	$image = $_POST["image"];
	$breadcrumb = $_POST["breadcrumb"];
	$thumbnail = $_POST["thumbnail"];
	$videoEmbedCode = $_POST["videoEmbedCode"];
	$selectionType = $_POST["selectionType"];
	$topicId = $_POST["topicId"];
	if ($selectionType == Selection::SCORECARD)
	{
		for ($i=1; $i<=8; $i++)
		{
			$scoreItem = new ScoreItem();
			$scoreItem->id = $_POST["scorecard_id" . $i];
			$scoreItem->who = $_POST["scorecard_who" . $i];
			$scoreItem->score = $_POST["scorecard_score" . $i];
			$scoreItem->description = $_POST["scorecard_description" . $i];
			$arrScoreItems[$i-1] = $scoreItem;
			//echo "Scorecard item " . $i . " is " . $arrScoreItems[$i-1]->id . " " . $arrScoreItems[$i-1]->who . " " . $arrScoreItems[$i-1]->score . " " . $arrScoreItems[$i-1]->description;
		}
	}
	
	if ($editRecord != "-1")
	{
		$sql = "Update selection set article_text='" . mysql_real_escape_string($article) . "', thumbnail_image='" . $thumbnail . 
			"', graphic_image='" . $image . "', breadcrumb='" . mysql_real_escape_string($breadcrumb) . "', video_embed_code='" . 
			mysql_real_escape_string($videoEmbedCode) . "' where id = '" . $editRecord . "'";
		$result = mysql_query($sql);
		if ($result != 1)
		{
			echo "Error updating selection --- Result = " . $result . "  error: " . mysql_error();
			echo "SQL is " . $sql;
			exit();
		}
		if ($selectionType == Selection::SCORECARD)
		{
			//echo "Scorecard code for updating";
			for ($i=1; $i<=8; $i++)
			{
				echo "Scorecard item " . $i . " is " . $arrScoreItems[$i-1]->id . " " . $arrScoreItems[$i-1]->who . " " . $arrScoreItems[$i-1]->score . " " . $arrScoreItems[$i-1]->description;
				if ($arrScoreItems[$i-1]->id == -1 && $arrScoreItems[$i-1]->who != "")
				{
					// insert new record
					$sql = "Insert into score (who, score, description, selection) values('" . $arrScoreItems[$i-1]->who . "', '" . $arrScoreItems[$i-1]->score . "', '" .
						mysql_real_escape_string($arrScoreItems[$i-1]->description) . "', '" . $editRecord . "')";
					echo $sql;
					$result = mysql_query($sql);
				}
				else if ($arrScoreItems[$i-1]->id != -1 && $arrScoreItems[$i-1]->who != "")
				{
					$sql = "UPDATE score SET who='" . $arrScoreItems[$i-1]->who . "', score='" . $arrScoreItems[$i-1]->score . "', description='" .
						mysql_real_escape_string($arrScoreItems[$i-1]->description) . "' WHERE id=" . $arrScoreItems[$i-1]->id;
					echo $sql;
					$result = mysql_query($sql);
					// update existing record
				}
				else if ($arrScoreItems[$i-1]->id != -1 && $arrScoreItems[$i-1]->who == "")
				{
					// delete existing record
					$sql = "delete from score where id = " . $arrScoreItems[$i-1]->id;
					echo $sql;
					$result = mysql_query($sql);
				}
				else
					echo "Doing nothing";
					// otherwise do nothing, no existing record and nothing was entered
					$result = 1;			
				if ($result != 1)
				{
					echo "Error inserting score --- Result = " . $result . "  error: " . mysql_error();
					echo "SQL is " . $sql;
					exit();
				}
			}
			//exit();
		}
	}
	else
	{
		$sql = "Insert into selection (topic, type, article_text, thumbnail_image, graphic_image, breadcrumb, video_embed_code) values(" .
			$topicId . ", " . $selectionType . ", '" . mysql_real_escape_string($article) . "', '" . $thumbnail . "', '" . $image . "', '" .
			mysql_real_escape_string($breadcrumb) . "', '" . mysql_real_escape_string($videoEmbedCode) . "')";
		$result = mysql_query($sql);
		if ($result != 1)
		{
			echo "Error inserting selection --- Result = " . $result . "  error: " . mysql_error();
			echo "SQL is " . $sql;
			exit();
		}
		$editRecord = mysql_insert_id();
		if ($selectionType == Selection::SCORECARD)
		{
			echo "Selection type is scorecard\n";
			for ($i=1; $i<=8; $i++)
			{
				echo "Scorecard item " . $i . " is " . $arrScoreItems[$i-1]->id . " " . $arrScoreItems[$i-1]->who . " " . $arrScoreItems[$i-1]->score . " " . $arrScoreItems[$i-1]->description;
				if ($arrScoreItems[$i-1]->who != "")
				{
					$sql = "Insert into score (who, score, description, selection) values('" . $arrScoreItems[$i-1]->who . "', '" . $arrScoreItems[$i-1]->score . "', '" .
						mysql_real_escape_string($arrScoreItems[$i-1]->description) . "', '" . $editRecord . "')";
					echo $sql;
					$result = mysql_query($sql);
					if ($result != 1)
					{
						echo "Error inserting score --- Result = " . $result . "  error: " . mysql_error();
						echo "SQL is " . $sql;
						exit();
					}
				}
			}
			//exit();
		}
		
	}
	header("Location: topicselections.php?currentTopic=" . $currentTopic);
}

if ($currentTopic == "")
	$currentTopic = $_GET["currentTopic"];
$mode = $_GET["mode"];
$currentRecord = $_GET["record"];

$sql = "Select * from topic where short_name='" . $currentTopic ."'";
//echo $sql;
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$atopic = new topicSpec();
$atopic->id = $row['id'];
$atopic->shortName = $row['short_name'];
$atopic->title = $row['title'];


if ($currentRecord != '')
{
	$sql = "Select * from selection where id=" . $currentRecord ;
	//echo $sql;
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	
	$aSelection = new Selection();
	// row[0] yields id of selection
	$aSelection->id = $row[0];
	$aSelection->type = $row['type'];
	$aSelection->article = $row['article_text'];
	$aSelection->thumbnail = $row['thumbnail_image'];
	$aSelection->image = $row['graphic_image'];
	$aSelection->breadcrumb = $row['breadcrumb'];
	$aSelection->videoEmbedCode = $row['video_embed_code'];
	$aSelection->videoURL = $row['video_url'];
	
	$numberOfScoreItems = 0;
	if ($aSelection->type == Selection::SCORECARD)
	{
		$sql = "Select * from score where selection=" . $currentRecord;
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result))
		{
			$aScoreItem = new ScoreItem();
			$aScoreItem->id = $row[0];
			$aScoreItem->who = $row['who'];
			$aScoreItem->score = $row['score'];
			$aScoreItem->description = $row['description'];
			$scoreItems[$numberOfScoreItems] = $aScoreItem;
			$numberOfScoreItems = $numberOfScoreItems + 1;
		}

	}
	
}
else
{
	$numberOfScoreItems = 0;
	$aSelection = new Selection();
	if (isset($_GET["selectionType"]))
		$aSelection->type = $_GET["selectionType"];
	else
		$aSelection->type = Selection::INTRO;
}	
mysql_close($con);
?>
<style type="text/css">
textarea.orange-scrollbar {scrollbar-base-color:orange;}
textarea.red-scrollbar {scrollbar-base-color:red;}
</style>

<script type="text/javascript">
 function selectTypeChange(selectObj, currentTopic) { 
 // get the index of the selected option 
 var idx = selectObj.selectedIndex; 
 // get the value of the selected option 
 var which = selectObj.options[idx].value; 
 var newURL = "edittopicselection.php?currentTopic=" + currentTopic + "&mode=add&selectionType=" + which;
 location.href = newURL;
}
</script>
</head>

<body>
<form action="" method="post" name="EditTopicSelection">
<table>
<tr>
<td>Topic</td><td><?=$atopic->title?></td>
</tr>
<tr>
<td width="75">Type</td>
<td width="562">
<input type="hidden" name="topicId" value="<?=$atopic->id?>" />
<?php if ($mode == 'add') { ?>
<select name="selectionType"  onchange="selectTypeChange(this, '<?=$currentTopic?>');">
<?
	foreach ($aSelection->possibleTypes as $k => $v) {
		if ($aSelection->type == $k)
			echo "<option selected value='" . $k . "'>" . $v . "</option>";
		else
			echo "<option value='" . $k . "'>" . $v . "</option>";
	}
?>
</select>
<input type="hidden" name="editRecord" value="-1" />
<?php } else { ?>
<input type="hidden" name="selectionType" value="<?=$aSelection->type?>"  />
<?=$aSelection->topicLabel()?>
<input type="hidden" name="editRecord" value="<?=$currentRecord?>" />
<? } ?>
</td>
</tr>
<tr>
<td>Thumbnail</td>
<td><input type="text" name="thumbnail" size="50" value="<?=$aSelection->thumbnail?>"/></td>
</tr>
<tr>
<td>Breadcrumb</td>
<td><input type="text" name="breadcrumb" maxlength="100" size="100" value="<?=$aSelection->breadcrumb?>"/></td>
</tr>
<?php if ($aSelection->type == Selection::VIDEO) { ?>
<tr>
<td>Video Embed Code</td>
<td><TEXTAREA NAME="videoEmbedCode" class="red-scrollbar" COLS=80 ROWS=12><?=$aSelection->videoEmbedCode ?></TEXTAREA></td>
</tr>
<?php } else if ($aSelection->type == Selection::INTRO) { ?>
<tr>
<td>Intro Text</td>
<td><TEXTAREA NAME="article" class="red-scrollbar" COLS=80 ROWS=12><?=$aSelection->article ?></TEXTAREA></td>
</tr>
<?php } else if ($aSelection->type == Selection::ARTICLE) { ?>
<tr>
<td>Article Text</td>
<td><TEXTAREA NAME="article" class="red-scrollbar" COLS=80 ROWS=25><?=$aSelection->article ?></TEXTAREA></td>
</tr>
<?php } else if ($aSelection->type == Selection::GRAPHIC) { ?>
<tr>
<td>Image</td>
<td><input type="text" name="image" value="<?=$aSelection->image?>" maxlength="100" size="100" value=""/></td>
</tr>
<?php } else if ($aSelection->type == Selection::LINKS) { ?>
<tr>
<td>Links Text</td>
<td><TEXTAREA NAME="article" class="red-scrollbar" COLS=80 ROWS=25><?=$aSelection->article ?></TEXTAREA></td>
</tr>
<?php } else if ($aSelection->type == Selection::SCORECARD) { ?>
<tr>
<td>Scorecard</td>
<td>
<table>
<tr><td>Who</td><td>Score</td><td>Description</td></tr>
<?php 
for ($i=1; $i<=8; $i++) {
?>
<tr>
<?php if ($i - 1 < $numberOfScoreItems) { ?>
<td><input type="text" maxlength="50" size="20" name="scorecard_who<?=$i?>" value="<?=$scoreItems[$i-1]->who ?>"/></td>
<td><input type="text" maxlength="20" size="20" name="scorecard_score<?=$i?>" value="<?=$scoreItems[$i-1]->score ?>"/></td>
<td><TEXTAREA name="scorecard_description<?=$i?>" class="red-scrollbar" COLS=80 ROWS=3><?=$scoreItems[$i-1]->description ?></TEXTAREA></td>
<input type="hidden" name="scorecard_id<?=$i?>" value="<?=$scoreItems[$i-1]->id?>" />
<?php } else { ?>
<td><input type="text" maxlength="50" size="20" name="scorecard_who<?=$i?>" /></td>
<td><input type="text" maxlength="20" size="20" name="scorecard_score<?=$i?>" /></td>
<td><TEXTAREA name="scorecard_description<?=$i?>" class="red-scrollbar" COLS=80 ROWS=3></TEXTAREA></td>
<input type="hidden" name="scorecard_id<?=$i?>" value="-1" />
<?php } ?>
</tr>
<?php } ?>
</table>
</td>
</tr>
<?php } ?>
<tr>
<input type="hidden" name="submitToDatabase" value="1" />
<input type="hidden" name="currentTopic" value="<?=$currentTopic?>" />
<td><input type="submit" value="Submit" /></td>
<td><input type="button" value="Cancel" onClick="location.href='topicSelections.php?currentTopic=<?=$currentTopic?>'" /></td>
</tr>
</table>
</form>
</body>
</html>
