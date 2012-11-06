<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

class Selection
{
	public $id;
	public $type;
	public $sequence;
	public $article;
	public $thumbnail;
	public $image;
	public $breadcrumb;
	public $videoEmbedCode;
	public $videoURL;
	
	public function topicLabel() {
		if ($this->type == 1)
			return "";
		else if ($this->type == 2)
			return "Article";
		else if ($this->type == 3)
			return "Video";
		else if ($this->type == 4)
			return "Cartoon";
		else if ($this->type == 5)
			return "Links";
		else if ($this->type == 6)
			return "Scorecard";
		else
			return "";
	}
}

class Score
{
	public $who;
	public $score;
	public $description;
	
	public function scoreToGrade() {
		if ($this->score >= 97)
			return "A+";
		else if ($this->score >= 93)
			return "A";
		else if ($this->score >= 90)
			return "A-";
		else if ($this->score >= 87)
			return "B+";
		else if ($this->score >= 83)
			return "B";
		else if ($this->score >= 80)
			return "B-";
		else if ($this->score >= 77)
			return "C+";
		else if ($this->score >= 73)
			return "C";
		else if ($this->score >= 70)
			return "C-";
		else if ($this->score >= 67)
			return "D+";
		else if ($this->score >= 63)
			return "D";
		else if ($this->score >= 60)
			return "D-";
		else if ($this->score == -1)
			return "--";
		else
			return "F";
		}
}

error_reporting(-1);

$topic=$_GET["topic"];
$page=$_GET["page"];

$con = mysql_connect('localhost', 'mattieoz', 'nomahh');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("mcscorecard", $con);

$sql = "Select * from topic where topic.short_name = '".$topic ."'";
//echo $sql;
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$title = $row['title'];
//echo "Title is " . $title;
$sql="SELECT * FROM selection inner join topic on selection.topic=topic.id WHERE topic.short_name = '".$topic."' order by sequence";
//echo $sql;

$result = mysql_query($sql);
$numberOfPages = 0;
while($row = mysql_fetch_array($result))
  {
  	$aSelection = new Selection();
	// row[0] yields id of selection
	$aSelection->id = $row[0];
	$aSelection->type = $row['type'];
	$aSelection->sequence = $row['sequence'];
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
  
$thisSelection = $topicSelections[$page-1];
$numberOfScores = 0;
if ($thisSelection->type == 6)
{
	$sql = "SELECT * from score where score.selection = " . $thisSelection->id;
	//echo $sql;

	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result))
	  {
		$aScore = new Score();
		$aScore->who = $row['who'];
		$aScore->score = $row['score'];
		$aScore->description = $row['description'];
		$scoreCardItems[$numberOfScores] = $aScore;
		$numberOfScores = $numberOfScores + 1;
	  }
  
}
mysql_close($con);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title><?=$title?></title>
  <meta name="generator" content="Amaya, see http://www.w3.org/Amaya/" />
  <style type="text/css">
<!--
.style1 {
	font-size: 14px
}
-->
  </style>
</head>

<body>

<h1 align="center"><?=$title?></h1>
<p align="left">&nbsp;</p>
<table width="1391" border="0" cellpadding="10">
  <tr>
    <td width="1091" height="725" valign="top">
    <table width="700" height="525" border="0">
    <tr height="30">
    <td align="left"><a href="../EventsMenu.html">Events Menu</a>
    </td></tr>
    <tr height="100" align="center">
    <td>
    <? if ($page == 1) { ?>
    <img src="../images/arrowblank.jpg" border="0" />&nbsp;&nbsp;Page <?=$page?> of <?=$numberOfPages?>&nbsp;&nbsp; <a href="?topic=<?=$topic ?>&page=<?=$page+1?>"><img src="../images/rightarrow.jpg" border="0" /></a>
    <? } elseif ($page == $numberOfPages) {?>
    <a href="?topic=<?=$topic ?>&page=<?=$page-1?>"><img src="../images/leftarrow.jpg" border="0" /></a>&nbsp;&nbsp;Page <?=$page?> of <?=$numberOfPages?>&nbsp;&nbsp; <img src="../images/arrowblank.jpg" border="0" />
    <? } else { ?>
    <a href="?topic=<?=$topic ?>&page=<?=$page-1?>"><img src="../images/leftarrow.jpg" border="0" /></a>&nbsp;&nbsp;Page <?=$page?> of <?=$numberOfPages?>&nbsp;&nbsp; <a href="?topic=<?=$topic ?>&page=<?=$page+1?>">
    <img src="../images/rightarrow.jpg" border="0" /></a>
    <? } ?>
    </td>
    </tr>
    <tr align="center">
    <td>
    <p>
	<? 
		if ($page >= 1)
			print($topicSelections[$page-1]->breadcrumb); 
	?>
    </p>
    <?
		if (($topicSelections[$page-1]->type == 1) or ($topicSelections[$page-1]->type == 2) or ($topicSelections[$page-1]->type == 5))
			echo "<p align='left'>" . $topicSelections[$page-1]->article . "</p>";
		else if ($topicSelections[$page-1]->type == 3)
		{
			if (isset($topicSelections[$page-1]->videoURL))
				echo "<p><a href='" .  $topicSelections[$page-1]->videoURL . "' target=\"_new\">Click here to open in a new window</a></p>";
			else
				echo "<p>" . $topicSelections[$page-1]->videoEmbedCode . "</p>";
		}
		else if ($topicSelections[$page-1]->type == 4)
		{
			echo "<p><img src='../images/" . $topicSelections[$page-1]->image . "' border='0'>";
		}
		else if ($topicSelections[$page-1]->type == 6)
		{
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
		}
	?>
    </td>
    </tr>
    </table>
    </td>
    <td width="400" valign="top"><table width="532" height="749" border="0">
    <?
	foreach ($topicSelections as $topicSelection) {
	?>
      <tr>
      	<? if ($topicSelection->sequence == $page) { ?>
        <td width="30" height="90"><img src="../images/pagemarker.jpg" border="0" /></td>
        <? } else { ?>
        <td width="30" height="90">&nbsp;</td>
        <? } ?>
        <td width="128" height="90"><a href="?topic=<?=$topic ?>&page=<?=$topicSelection->sequence?>">
        <img src="../images/<?=$topicSelection->thumbnail?>" alt="" width="120" height="104" align="middle" /></a></td>
        <td width="262"><p><?=$topicSelection->topicLabel()?></p>
          <p><?=$topicSelection->breadcrumb?></p></td>
      </tr>
    <? } ?>
    </table></td>
  </tr>
</table>
<p align="left">&nbsp; </p>
<p></p>

</body>
</html>
