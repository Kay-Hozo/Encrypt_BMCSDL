<?php
 include ("myClass/clsCreateTable.php");
 $p = new table();
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>
<form id="login-form" name="form1" method="post">
  <table width="400" border="0" class="table-login center" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td height="29" colspan="2" align="center"><h2>LOGIN</h2></td>
      </tr>
      <tr>
        <td width="176" height="34" align="right" class="lable-login">Username </td>
        <td width="224"><input name="txtUsernameLG" type="text" required="required" id="txtUsername" class="input-form"></td>
      </tr>
      <tr>
        <td height="35" align="right" class="lable-login">Password  </td>
        <td><input name="txtPassLG" type="password" required="required" id="txtPass" class="input-form"></td>
      </tr>
      <tr>
        <td height="38" align="right" class="lable-login">Key </td>
        <td><input type="text" name="txtKey" id="txtPLKey" class="input-form"></td>
      </tr>
      <tr>
        <td height="42" align="right" class="lable-login">IV Key </td>
        <td><input type="text" name="txtIVKey" id="txtPRKey" class="input-form"></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="btn_login" class="btn-submit btn_login" value="Login"></td>
      </tr>
    </tbody>
  </table>
  <?php
  	switch($_REQUEST["btn_login"])
	{
		case 'Login':
		{
			$p->login();	
			break;	
		}	
	}
  ?>
</form>
</body>
</html>