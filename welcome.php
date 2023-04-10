<?php 
include ("myClass/clsCreateTable.php");
$p = new table();

session_start();
if(isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['keyUser']) && isset($_SESSION['ivKeyUser']))
{
	$p->confirmLogin($_SESSION['username'], $_SESSION['password']);
}
else
{
	header('location: login.php');	
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Post</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
	
<body>
<?php
	echo $p->welcome();
	if(isset($_REQUEST["logout"]))
	{
		$p->logout();	
	}
?>

</body>
</html>