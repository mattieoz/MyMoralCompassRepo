<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
$topic=$_GET["topic"];

$con = mysql_connect('localhost', 'root', '');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wordpress", $con);

$sql="SELECT * FROM selection inner join topic on selection.topic=topic.id WHERE topic.key = '".$topic."' order by sequence desc";
echo $sql;

$result = mysql_query($sql);

echo "<table border='1'>
<tr>
<th>Type</th>
<th>Sequence</th>
<th>Thumbnail</th>
<th>Title</th>
</tr>";

while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['type'] . "</td>";
  echo "<td>" . $row['sequence'] . "</td>";
  echo "<td>" . $row['thumbnail_image'] . "</td>";
  echo "<td>" . $row['title'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysql_close($con);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
