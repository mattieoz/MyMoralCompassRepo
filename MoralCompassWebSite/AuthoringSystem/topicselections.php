<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Topic Selections</title>
<?php

$currentTopic = "";

if (isset($_POST["currentTopic"]))
	$currentTopic = $_POST["currentTopic"];

if ($currentTopic == "")
	$currentTopic = $_GET["currentTopic"];
	
require_once '../include/dbdefs.php';
require_once '../include/topic.class.php';
require_once '../include/topicselection.class.php';

$con = mysql_connect(DBHOST, DBUSER, DBPASSWORD);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db(DBSCHEMA, $con);

$sql = "Select * from topic order by title";
//echo $sql;
$result = mysql_query($sql);
$numberOfTopics = 0;
	while($row = mysql_fetch_array($result))
	  {
	  	$atopic = new topicSpec();
		$atopic->id = $row['id'];
		$atopic->shortName = $row['short_name'];
		$atopic->title = $row['title'];
		$topics[$numberOfTopics] = $atopic;
		$numberOfTopics = $numberOfTopics + 1;
	  }

if ($currentTopic != '')
{
	$sql="SELECT * FROM selection inner join topic on selection.topic=topic.id WHERE topic.short_name = '".$currentTopic."' order by selection.id";
	echo $sql;
	
	$result = mysql_query($sql);
	$numberOfPages = 0;
	while($row = mysql_fetch_array($result))
	  {
		$aSelection = new Selection();
		// row[0] yields id of selection
		$aSelection->id = $row[0];
		$aSelection->type = $row['type'];
		$aSelection->article = nl2br($row['article_text']);
		$aSelection->thumbnail = $row['thumbnail_image'];
		$aSelection->image = $row['graphic_image'];
		$aSelection->breadcrumb = nl2br($row['breadcrumb']);
		$aSelection->videoEmbedCode = $row['video_embed_code'];
		$aSelection->videoURL = $row['video_url'];
		
		$topicSelections[$numberOfPages] = $aSelection;
		$numberOfPages = $numberOfPages + 1;
		/*
	  echo "<tr>";
	  echo "<td>" . $row['type'] . "</td>";
	  echo "<td>" . $row['sequence'] . "</td>";
	  echo "<td>" . $row['thumbnail_image'] . "</td>";
	  echo "<td>" . $row['title'] . "</td>";
	  echo "</tr>";
	  */
	  }
}  

mysql_close($con);
?>

</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <label>Topic
  <select name="currentTopic" id="currentTopic" onchange="this.form.submit()">
  <?php
  	if ($currentTopic == "")
		echo "<option selected value=''>Select a topic</option>";
	else
		echo "<option value=''>Select a topic</option>";
  	foreach ($topics as $topic) {
		if ($currentTopic == $topic->shortName)
			echo "<option selected value='" . $topic->shortName . "'>" . $topic->title . "</option>";
		else
			echo "<option value='" . $topic->shortName . "'>" . $topic->title . "</option>";
	}
  ?>
  </select>
  </label>
</form>
<?php
	if ($currentTopic != '')
	{
		echo "<table border=1>";
		echo "<tr><td>&nbsp;</td><td>Type</td><td>Thumbnail</td><td>Image</td><td>Breadcrumb</td><td>Video Embed</td></tr>";
		foreach ($topicSelections as $selection)
		{
			echo "<tr>";
			echo "<td><a href='edittopicselection.php?currentTopic=" . $currentTopic . "&record=" . $selection->id . "&mode=edit'>Edit</a></td>";
			echo "<td>" . $selection->topicLabel() . "</td>";
			echo "<td>" . $selection->thumbnail . "</td>";
			echo "<td>" . $selection->image . "</td>";
			echo "<td>" . $selection->breadcrumb . "</td>";
			echo "<td>" . $selection->tokenizeLink($selection->videoEmbedCode) . "</td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "<a href='edittopicselection.php?currentTopic=" . $currentTopic . "&mode=add'>Add new selection</a>";
	}
?>
</body>
</html>
