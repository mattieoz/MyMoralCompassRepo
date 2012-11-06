<?php
require_once 'auth.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Topic Selection</title>
<?php

require_once '../wp-content/php/dbdefs.php';
require_once '../wp-content/php/topic.class.php';
require_once '../wp-content/php/topicselection.class.php';
require_once '../wp-content/php/util.php';

$unmagic = 0;

function uploadFile($myFile, $myTopic)
{
	$uploadDir = "../wp-content/images/" . $myTopic . "/";
    if ($myFile["error"] !== UPLOAD_ERR_OK) {
        echo "<p>An error occurred.</p>";
        exit;
    }
    // ensure a safe filename
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);
    
    /*
    // don't overwrite an existing file
    $i = 0;
    $parts = pathinfo($name);
    while (file_exists(UPLOAD_DIR . $name)) {
        $i++;
        $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
    }
    */
    // preserve file from temporary directory
    $success = move_uploaded_file($myFile["tmp_name"],
        $uploadDir . $name);
    if (!$success) {
        echo "<p>Unable to save file.</p>";
        exit;
    }
    // set proper permissions on the new file
    chmod($uploadDir . $name, 0644);
}

function my_escape($str)
{
	global $unmagic;
	//return $str;
	//echo "Before mysql_real_escape_string str is " . $str . "<br/>";
	//echo "Unmagic is: " . $unmagic . "<br/>";
	//$str = convertUtf8($str);
	if ($unmagic == 0)
		$str = mysql_real_escape_string($str);
	//echo "After mysql_real_escape_string str is " . $str . "<br/>";
	return $str;
}




//echo 'Current PHP version: ' . phpversion();
//if(get_magic_quotes_runtime())
//	echo "Magic quotes are enabled";
//else
//	echo "Magic quotes are disabled";

$con = mysql_connect(DBHOST, DBUSER, DBPASSWORD);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db(DBSCHEMA, $con);

$currentTopic = "";
$editRecord = -1;
if(isset($_POST["submitToDatabase"]))
{
	if (isset($_POST["magicquotecheck"]))
	{
		$magicQuoteCheck = $_POST["magicquotecheck"];
		//echo "Value of magicQuoteCheck is: " . $magicQuoteCheck;
		$positn = strpos($magicQuoteCheck, "'");
		//echo "Position of quote is " . $positn;
		if ($positn == 1)
			$unmagic = 1;
		//echo "Value of unmagic is: " . $unmagic;
	}
	
	
	$submitValue = $_POST["Submit"];
	//echo "Upload is going here on edittopicselection, submitValue is " . $submitValue;
	$myThumbnailFile = $_FILES["mythumbnailfile"];
	$myImageFile = $_FILES["myimagefile"];
	//echo "<br/>Thumbnail file is " . $myThumbnailFile["name"];
	//echo "<br/>Image file is " . $myImageFile["name"];

	
	
	$currentTopic = $_POST["currentTopic"];	
	$editRecord = $_POST["editRecord"];
	$article = $_POST["article"];
	if (!empty($myThumbnailFile["name"]))
	{
		$thumbnail = $myThumbnailFile["name"];
		uploadFile($myThumbnailFile, $currentTopic);
	}
	else
		$thumbnail = $_POST["thumbnail"];
	if (!empty($myImageFile["name"]))
	{
		$image = $myImageFile["name"];
		uploadFile($myImageFile, $currentTopic);
	}
	else
		$image = $_POST["image"];
			
	$breadcrumb = $_POST["breadcrumb"];
	$videoEmbedCode = $_POST["videoEmbedCode"];
	$selectionType = $_POST["selectionType"];
	$topicId = $_POST["topicId"];
	if (!isset($_POST['size']) || empty($_POST['size']))
		$size = '0';
	else
		$size = $_POST['size'];
	if (!isset($_POST['sequence']) || empty($_POST['sequence']))
		$sequence = '0';
	else
		$sequence = $_POST['sequence'];
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
		$sql = "Update selection set article_text='" . my_escape($article) . "', thumbnail_image='" . $thumbnail . 
			"', graphic_image='" . $image . "', breadcrumb='" . my_escape($breadcrumb) . "', video_embed_code='" . 
			my_escape($videoEmbedCode) . "', size='" . $size . "', sequence='" . $sequence . "' where id = '" . $editRecord . "'";
		//echo $sql;
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
				//echo "Scorecard item " . $i . " is " . $arrScoreItems[$i-1]->id . " " . $arrScoreItems[$i-1]->who . " " . $arrScoreItems[$i-1]->score . " " . $arrScoreItems[$i-1]->description;
				if ($arrScoreItems[$i-1]->id == -1 && $arrScoreItems[$i-1]->who != "")
				{
					// insert new record
					$sql = "Insert into score (who, score, description, selection) values('" . $arrScoreItems[$i-1]->who . "', '" . $arrScoreItems[$i-1]->score . "', '" .
						my_escape($arrScoreItems[$i-1]->description) . "', '" . $editRecord . "')";
					//echo $sql;
					$result = mysql_query($sql);
				}
				else if ($arrScoreItems[$i-1]->id != -1 && $arrScoreItems[$i-1]->who != "")
				{
					$sql = "UPDATE score SET who='" . $arrScoreItems[$i-1]->who . "', score='" . $arrScoreItems[$i-1]->score . "', description='" .
						my_escape($arrScoreItems[$i-1]->description) . "' WHERE id=" . $arrScoreItems[$i-1]->id;
					//echo $sql;
					$result = mysql_query($sql);
					// update existing record
				}
				else if ($arrScoreItems[$i-1]->id != -1 && $arrScoreItems[$i-1]->who == "")
				{
					// delete existing record
					$sql = "delete from score where id = " . $arrScoreItems[$i-1]->id;
					//echo $sql;
					$result = mysql_query($sql);
				}
				else
				{
					//echo "Doing nothing";
					// otherwise do nothing, no existing record and nothing was entered
					$result = 1;
				}			
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
		$sql = "Insert into selection (topic, type, article_text, thumbnail_image, graphic_image, breadcrumb, 
			video_embed_code, size, sequence) values(" .
			$topicId . ", " . $selectionType . ", '" . my_escape($article) . "', '" . $thumbnail . "', '" . $image . "', '" .
			my_escape($breadcrumb) . "', '" . my_escape($videoEmbedCode) . "', '" . $size . "', '" .
			$sequence . "')";
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
			//echo "Selection type is scorecard\n";
			for ($i=1; $i<=8; $i++)
			{
				//echo "Scorecard item " . $i . " is " . $arrScoreItems[$i-1]->id . " " . $arrScoreItems[$i-1]->who . " " . $arrScoreItems[$i-1]->score . " " . $arrScoreItems[$i-1]->description;
				if ($arrScoreItems[$i-1]->who != "")
				{
					$sql = "Insert into score (who, score, description, selection) values('" . $arrScoreItems[$i-1]->who . "', '" . $arrScoreItems[$i-1]->score . "', '" .
						my_escape($arrScoreItems[$i-1]->description) . "', '" . $editRecord . "')";
					//echo $sql;
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
	//header("Location: topicselections.php?currentTopic=" . $currentTopic);
}

if ($currentTopic == "")
	$currentTopic = $_GET["currentTopic"];

if (isset($_GET["mode"]))
	$mode = $_GET["mode"];
else
	$mode = 'edit';

if ($editRecord != -1)
	$currentRecord = $editRecord;
else
	$currentRecord = $_GET["record"];

$sql = "Select * from topic where short_name='" . $currentTopic ."'";
//echo $sql;
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$atopic = new topicSpec();
$atopic->id = $row['id'];
$atopic->shortName = $row['short_name'];
$atopic->title = $row['title'];

//echo "CurrentRecord is: " . $currentRecord;

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
	$aSelection->sequence = $row['sequence'];
	$aSelection->article = convertUtf8($row['article_text']);
	$aSelection->thumbnail = $row['thumbnail_image'];
	$aSelection->image = $row['graphic_image'];
	$aSelection->breadcrumb = $row['breadcrumb'];
	$aSelection->videoEmbedCode = $row['video_embed_code'];
	$aSelection->videoURL = $row['video_url'];
	$aSelection->size = $row['size'];
	
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
			$aScoreItem->description = convertUtf8($row['description']);
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
//echo "article strtohex:<br/>" . strToHex($aSelection->article);
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

 var imgHeight;
 var imgWidth;
 var winHeight;
 var winWidth;

 function findHHandWW() {
 imgHeight = this.width; imgWidth = this.width; return true;
 }

 function openWin(imgPath) 
 {
	 var myImage = new Image();
	 myImage.name = imgPath;
	 myImage.onload = findHHandWW;
	 myImage.src = imgPath;
	 if(imgHeight>=500){imgHeight=500;}if(imgWidth>=500){imgWidth=500;}
	 winHeight = imgHeight + 60;winWidth = imgWidth + 30;
	
	 var url1="Image.php?myPath="+imgPath+"&hh="+imgHeight+"&ww="+imgWidth;
	 window.open(url1,"","width="+winWidth+",height="+winHeight+",status=yes,toolbar=no,scrollbars=no,left=100,top=100");
 }
 
</script>
</head>

<body>
<form action="edittopicselection.php" enctype="multipart/form-data" method="post" name="EditTopicSelection">
<input type="hidden" name="magicquotecheck" value="'" />
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
<?php } ?>
</td>
</tr>
<tr>
<td>Sequence</td>
<td><input type="text" name="sequence" value="<?=$aSelection->sequence?>" maxlength="10" size="10" /></td>
</tr>
<?php if ($aSelection->type == Selection::VIDEO || $aSelection->type == Selection::GRAPHIC) { ?>

<tr>
<td valign="top">Thumbnail</td>

<td><?php 
	if (empty($aSelection->thumbnail))
		echo "No thumbnail file chosen";
	else
	{
		$filePath = "../wp-content/images/" . $atopic->shortName . "/" . $aSelection->thumbnail;
		?>
		<a href='javascript:openWin("<?php echo $filePath;?>");'><img src='<?php echo $filePath;?>' width='120' height='90' /><br/>
		<?php echo $aSelection->thumbnail;?></a>
		<?php 
	}
	?>
	<br/>
 <input type="hidden" name="thumbnail" value="<?php echo $aSelection->thumbnail?>" />
 Choose a new file: <input name="mythumbnailfile" type="file" /><br />
</td>
</tr>
<tr>
<td>Breadcrumb</td>
<td>
<input type="text" name="breadcrumb" maxlength="100" size="100" value="<?=$aSelection->breadcrumb?>"/>
</td>
</tr>
<?php } ?>
<?php if ($aSelection->type == Selection::VIDEO) { ?>
<tr>
<td>Video Embed Code</td>
<td><TEXTAREA NAME="videoEmbedCode" class="red-scrollbar" COLS=80 ROWS=12><?=$aSelection->videoEmbedCode ?></TEXTAREA></td>
</tr>
<?php } else if ($aSelection->type == Selection::INSTRUCTIONS) { ?>
<tr>
<td>Instruction Text</td>
<td><TEXTAREA NAME="article" class="red-scrollbar" COLS=80 ROWS=12><?=$aSelection->article ?></TEXTAREA></td>
</tr>
<?php } else if ($aSelection->type == Selection::WRITINGPROMPT) { ?>
<tr>
<td>Writing Prompt</td>
<td><TEXTAREA NAME="article" class="red-scrollbar" COLS=80 ROWS=12><?=$aSelection->article ?></TEXTAREA></td>
</tr>
<tr>
<td>Size</td>
<td><input type="text" name="size" value="<?=$aSelection->size?>" maxlength="10" size="10" /></td>
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
<td>
<?php 
	if (empty($aSelection->image))
		echo "No image file chosen";
	else
	{
		$filePath = "../wp-content/images/" . $atopic->shortName . "/" . $aSelection->image;
		?>
		<a href='javascript:openWin("<?php echo $filePath;?>");'><img src='<?php echo $filePath;?>' width='120' height='90' /><br/>
		<?php echo $aSelection->image;?></a>
		<?php 
	}
	?>
	<br/>
 <input type="hidden" name="image" value="<?php echo $aSelection->image?>" />
 Choose a new file: <input name="myimagefile" type="file" /><br />

<!-- <input type="text" name="image" value="<?=$aSelection->image?>" maxlength="100" size="100" value=""/> -->

</td>
</tr>
<?php } else if ($aSelection->type == Selection::FEATUREDGRAPHIC) { ?>
<tr>
<td>Description</td>
<td><TEXTAREA NAME="article" class="red-scrollbar" COLS=80 ROWS=2><?=$aSelection->article ?></TEXTAREA></td>
</tr>
<tr>
<td>Image</td>
<td>
<?php 
	if (empty($aSelection->image))
		echo "No image file chosen";
	else
	{
		$filePath = "../wp-content/images/" . $atopic->shortName . "/" . $aSelection->image;
		?>
		<a href='javascript:openWin("<?php echo $filePath;?>");'><img src='<?php echo $filePath;?>' width='120' height='90' /><br/>
		<?php echo $aSelection->image;?></a>
		<?php 
	}
	?>
	<br/>
 <input type="hidden" name="image" value="<?php echo $aSelection->image?>" />
 Choose a new file: <input name="myimagefile" type="file" /><br />

<!-- <input type="text" name="image" value="<?=$aSelection->image?>" maxlength="100" size="100" value=""/> -->

</td>
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
<td><input type="submit" name="Submit" value="Submit" /></td>
<td><input type="button" value="Go Back" onClick="location.href='topicselections.php?currentTopic=<?=$currentTopic?>'" /></td>
</tr>
</table>
</form>
</body>
</html>
