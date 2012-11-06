<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once 'include/dbdefs.php';
require_once 'include/topic.class.php';
require_once 'include/topictimeperiod.class.php';


// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$con = mysql_connect(DBHOST, DBUSER, DBPASSWORD);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db(DBSCHEMA, $con);

$lstTimePeriods = array();
$numberOfTimePeriods = 0;
  
$sql = "SELECT time_period.id, time_period.name, topic.id, topic.short_name, topic.title, topic.description
FROM topic INNER JOIN time_period ON topic.time_period = time_period.id
ORDER BY time_period.id, topic.id";
//echo $sql;
$result = mysql_query($sql);
while($row = mysql_fetch_array($result))
{
  	//echo "time period: " . $row['name'] . "  topic: " . $row['title'] . "<br/>";
  	$timePeriod = timePeriodPresent($lstTimePeriods, $row[0]);
  	if ($timePeriod == null)
  	{
  		$timePeriod = new TopicTimePeriod();
  		$timePeriod->id = $row[0];
  		$timePeriod->timePeriodName = $row[1];
  		$lstTimePeriods[$numberOfTimePeriods] = $timePeriod;
  		$numberOfTimePeriods++;
  	}
  	$topic = new TopicSpec();
  	$topic->id = $row[2];
  	$topic->shortName = $row['short_name'];
  	$topic->title = $row['title'];
  	$topic->description = $row['description'];
  	array_push($timePeriod->topics, $topic);
}

mysql_close($con);
foreach ($lstTimePeriods as $timePeriod)
{
	echo "Time period: " . $timePeriod->timePeriodName . "<br/>";
	//echo "Number of topics: " . sizeof($timePeriod->topics) . "<br/>";
	foreach ($timePeriod->topics as $topic)
		echo "Topic: " . $topic->title . "<br/>";
}
function timePeriodPresent($lstTimePeriods, $timePeriodId)
{
	foreach ($lstTimePeriods as $timePeriod)
	{
		if ($timePeriod->id == $timePeriodId)
			return $timePeriod;
	}
	return null;
}
?>
</head>
<body/>
</html>