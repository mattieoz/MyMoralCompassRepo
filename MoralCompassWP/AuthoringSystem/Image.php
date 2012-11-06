<html><head><title>Image Display</title></head>

<script type="text/javascript">
function closeWin(){ window.close(); }
</script><body>

<?php 
$imagePath = $_GET["myPath"];
$hh = $_GET["hh"];
$ww = $_GET["ww"];
?>

<img src="<?php echo $imagePath?>" height="<?php echo $hh?>" width="<?php echo $ww?>" />
<center>Width: <?php echo $ww?>  Height: <?php echo $hh?><br/>
<input type="button" value="Close" onclick="closeWin()" /> </center>
</body>
</html>