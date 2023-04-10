<?php
	include ("./myClass/clsCreateTable.php");
	$p = new table();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
<form id="login-form" name="login-form" method="post">
  <table width="400" border="0" cellpadding="0" cellspacing="0" class="table-login center">
    <tbody>
      <tr>
        <td height="30" colspan="2" align="center"><h2>SIGN UP</h2></td>
      </tr>
      <tr>
        <td height="32" align="right" class="lable-login">Username</td>
        <td><label for="textfield"></label>
        <input type="text" name="txtUsername" id="textfield"></td>
      </tr>
      <tr>
        <td height="30" align="right" class="lable-login">First name</td>
        <td><label for="textfield2"></label>
        <input type="text" name="txtFirName" id="textfield2"></td>
      </tr>
      <tr>
        <td height="36" align="right" class="lable-login">Last name</td>
        <td><label for="textfield3"></label>
        <input type="text" name="txtLasName" id="textfield3"></td>
      </tr>
      <tr>
        <td height="36" align="right" class="lable-login">Email</td>
        <td><label for="textfield4"></label>
        <input type="email" name="txtEmail" id="textfield4"></td>
      </tr>
      <tr>
        <td height="38" align="right" class="lable-login">Password</td>
        <td><label for="textfield5"></label>
        <input type="password" name="txtPass" id="textfield5"></td>
      </tr>
      <tr>
        <td height="39" align="right" class="lable-login">Confirm Password</td>
        <td><label for="textfield6"></label>
        <input type="password" name="txtConPass" id="textfield6"></td>
      </tr>
      <tr>
        <td colspan="2" align="center">
        <input type="submit" name="btnSubmit" class="btn_submit btn_signUp" value="Sign Up"></td>
      </tr>
    </tbody>
  </table>
  <br>
  <div style="text-align:center">
  	<a href='login.php'>Login</a>
  </div>
  <?php	
  	
	switch ($_POST["btnSubmit"])
	{
		case 'Sign Up': 
		{
			$p->generateKeyAndIV();
			$p->createUser();
			break;	
		}
	}
  ?>
</form>
</body>
</html>