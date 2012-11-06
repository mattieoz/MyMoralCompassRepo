<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Testing PHP Mail</title>
<?php
if(isset($_POST["sendmail"]))
{
	// The message
	$message = "Line 1\nLine 2\nLine 3";
	
	// In case any of our lines are larger than 70 characters, we should use wordwrap()
	$message = wordwrap($message, 70);
	$headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	// Send
	$result = mail('mosber@scilearn.com', 'Mail Test Subject', $message, $headers);
	
	echo "Mail result is: " . $result;
	exit();
}
?>
</head>

<body>
<form method="post">
Go Send it!<br/>
<input type="submit" />
<input type="hidden" name="sendmail" value="1" />
</form>
</body>
</html>
