<?php
require_once(ABSPATH . 'wp-content/php/dbdefs.php');
require_once(ABSPATH . 'wp-content/php/topic.class.php');
require_once(ABSPATH . 'wp-content/php/topicselection.class.php');
require_once(ABSPATH . 'wp-content/php/searchresult.class.php');
require_once(ABSPATH . 'wp-content/php/util.php');

$searchString = get_search_query();
//$searchString = "baseball's";
//echo "Hex of searchString: " . strToHex($searchString) . "<br/>";
$searchString = htmlspecialchars_decode($searchString, ENT_QUOTES);
//echo "Hex of decodedString: " . strToHex($searchString) . "<br/>";
//echo "Search string is: " . $searchString . "<br/>";
//echo "Escaped search string is: " . $escapedString . "<br/>";

$searchResults = array();

$con = mysql_connect(DBHOST, DBUSER, DBPASSWORD);
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db(DBSCHEMA, $con);
$sql = "select tp.short_name, tp.title, sl.article_text from topic tp inner join selection sl on tp.id = sl.topic where sl.article_text like '%" . 
	mysql_real_escape_string($searchString) . "%' group by tp.id order by tp.sequence";

//echo $sql;

$result = mysql_query($sql);
while($row = mysql_fetch_array($result))
{
	//echo "Title: " . $row['title'] . "<br/>";		
	//echo "Text: " . $row['article_text'] . "<br/>";
	$sr = new SearchResult();
	$sr->shortName = $row['short_name'];
	$sr->title = $row['title'];
	$sr->text = $sr->getTextSnippet($row['article_text'], $searchString);
	array_push($searchResults, $sr);
}

mysql_close($con);


?>