<?php 


require_once(ABSPATH . 'wp-content/php/dbdefs.php');
require_once(ABSPATH . 'wp-content/php/topic.class.php');
require_once(ABSPATH . 'wp-content/php/topicselection.class.php');
require_once(ABSPATH . 'wp-content/php/topictimeperiod.class.php');
require_once(ABSPATH . 'wp-content/php/featuredtopic.php');
require_once(ABSPATH . 'wp-content/php/util.php');

$con = mysql_connect(DBHOST, DBUSER, DBPASSWORD);
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db(DBSCHEMA, $con);

$lstTimePeriods = array();
$lstFeaturedTopics = array();
$numberOfTimePeriods = 0;

$sql = "SELECT time_period.id, time_period.name, topic.id, topic.short_name, topic.featured, topic.title, topic.description
FROM topic INNER JOIN time_period ON topic.time_period = time_period.id
ORDER BY time_period.id, topic.sequence";
//echo $sql;
$result = mysql_query($sql);
while($row = mysql_fetch_array($result))
{
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
	$topic->featured = $row['featured'];
	$topic->title = $row['title'];
	$topic->description = $row['description'];
	array_push($timePeriod->topics, $topic);
	if ($topic->featured == 1)
		array_push($lstFeaturedTopics, $topic);
}

if (isset($_GET["featuredtopic"]))
	$featuredTitle = $_GET['featuredtopic'];
else
	$featuredTitle = findTodaysFeaturedTopic($lstFeaturedTopics);
$featuredTopic = fetchTopic($featuredTitle);
$featuredSelections = fetchSelections($featuredTitle);

foreach ($featuredSelections as $aSelection)
{
	if ($aSelection->type == Selection::FEATUREDGRAPHIC)
	{
		$featuredImage = $aSelection->image;
		$featuredCaption = $aSelection->article;
		break;
	}
}

/*
$featured_query = new WP_Query(array('posts_per_page' => 1, 'meta_key' => '_compass_featured_post', 'meta_value' => 'on'));
$featuredTitle = '';
$featuredTopic = null;
$featuredImage = '';
$featuredCaption = '';

if ($featured_query->have_posts())
{
	$featured_query->the_post();
	$featuredTitle = the_title('', '', false);
	$featuredImage = get_the_post_thumbnail();
	//echo "Featured post title is: " . $featuredTitle;
	$featuredTopic = fetchTopic($featuredTitle);
	$featuredSelections = fetchSelections($featuredTitle);
	
	foreach ($featuredSelections as $aSelection)
	{
		if ($aSelection->type == Selection::FEATUREDGRAPHIC)
		{
			$featuredImage = $aSelection->image;
			$featuredCaption = $aSelection->article; 
			break;
		}
	}
	//echo "FeaturedIntro is: " . $featuredIntro;
	//exit();
}
*/
mysql_close($con);

	function fetchTopic($topicString)
	{
		$sql = "Select * from topic where topic.short_name = '".$topicString ."'";
		//echo $sql;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$topic = new TopicSpec();
		$topic->id = $row[2];
		$topic->shortName = $row['short_name'];
		$topic->title = $row['title'];
		$topic->description = $row['description'];
		return $topic;
	}

	function fetchSelections($topicString)
	{
		$sql="SELECT * FROM selection sl inner join topic tp on sl.topic=tp.id WHERE tp.short_name = '".$topicString."' order by sl.sequence";
		//echo $sql;
		$result = mysql_query($sql);
		$topicSelections =  array();
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
			array_push($topicSelections, $aSelection);
		}
		return $topicSelections;
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