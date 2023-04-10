<?php
	class table 
	{
		private $fix_iv;
		private $fix_key;

		public function __construct() {
			$this->fix_key = '84605963fb74d1e6';
		  	$this->fix_iv = '2443e4506578dc6c';
		}
		
		function connectDB($dbname='iuh_test_encrypt', $username='root', $pass='')
		{
			$con = mysql_connect("localhost", $username, $pass);
			if(!$con)
			{
				die('Could not connect: ' . mysql_error());	
			}
			
			mysql_select_db($dbname, $con);		
			return $con;
		}

		function createUserTable()
		{
			
			$key_iv = $this->generateKeyAndIV();
			$con = $this->connectDB();
			$key =  $_SESSION['key'];
			$iv =  $_SESSION['iv'];
			
			$query = "
			CREATE TABLE `iuh_test_encrypt`.`users`(
			`ID` INT NOT NULL AUTO_INCREMENT ,
			`username` VARCHAR( 200 ) NOT NULL ,
			`userEncrypt` VARCHAR( 200 ) NOT NULL ,
			`password` VARCHAR( 200 ) NOT NULL ,
			`passEncrypt` VARCHAR( 200 ) NOT NULL ,
			`FirstName` VARCHAR( 200 ) NOT NULL ,
			`LastName` VARCHAR( 200 ) NOT NULL ,
			`Email` VARCHAR( 200 ) NOT NULL ,
			`CheckExist` VARCHAR( 200 ) NOT NULL ,
			PRIMARY KEY ( `ID` )
			) ENGINE = INNODB";
			
			if (mysql_query($query, $con)) {
				echo "<br>Tạo bảng user thành công";
			} else {
				echo "<br>Lỗi khi tạo bảng user: " . mysql_error($con);
			}
		}
		
		function createRecordTable()
		{
			$con = $this->connectDB();
			$key =  $_SESSION['key'];
			$iv =  $_SESSION['iv'];
			
			$query = "
			CREATE TABLE `iuh_test_encrypt`.`Records` (
				`ID_post` INT NOT NULL AUTO_INCREMENT ,
				`Content` VARCHAR( 300 ) NOT NULL ,
				`Author` VARCHAR( 200 ) NOT NULL ,
				PRIMARY KEY ( `ID_post` )
				) ENGINE = InnoDB ";
			
			if (mysql_query($query, $con)) {
				echo "<br>Tạo bảng record thành công";
			} else {
				echo "<br>Lỗi khi tạo bảng record: " . mysql_error($con);
			}
		}
		
		function closeDB($con)
		{
			mysql_close($con);		
		}
		
		function login()
		{
			session_start();
			$username = $_REQUEST["txtUsernameLG"];
			$password = $_REQUEST["txtPassLG"];
			$_SESSION['keyUser'] = $_REQUEST["txtKey"];
			$_SESSION['ivKeyUser'] = $_REQUEST["txtIVKey"];
			
			$_SESSION['username'] = $this->convertInfo($username, $_SESSION['keyUser'], $_SESSION['ivKeyUser']);
			$_SESSION['password'] = $this->convertInfo($password, $_SESSION['keyUser'], $_SESSION['ivKeyUser']);
			$con = $this->connectDB();
			
			$query = 
			"
				SELECT username FROM users 
				WHERE userEncrypt = '{$_SESSION['username']}' AND passEncrypt = '{$_SESSION['password']}'
			";
			
			$result = mysql_query($query, $con);
			$data = mysql_fetch_array($result);
			
			if($data == false)
			{
				echo "<script>alert('Sai username hoặc mật khẩu!')</script>";	
			}
			else
			{
				echo 'toi otio';
				$_SESSION['accountName'] = $this->decryptAES($data['username'], $_SESSION['keyUser'], $_SESSION['ivKeyUser']);
				header('location: welcome.php');
			}			
		}
		
		function welcome()
		{
			$loichao = 
			"
			<div class='background-box' style='text-align:center' >
				<h2 style='padding: 10px;'>Welcome to website {$_SESSION['accountName']}</h2>
				<img src='image/hello.png' width=400 alt=''/>
				<form method='post' action=''>
				  <button type='submit' class='btn-login-form' name='logout'>Log out</button>
				</form>
			</div>
			";
				
			return $loichao;
		}
		
		function logout()
		{
			session_destroy();
			header('location: login.php');
		}
		
		function convertInfo($info, $key, $iv)
		{
			if($key == '' || $iv == '')
			{
				$key = $this->fix_key;
				$iv = $this->fix_iv;
			}
			
			$info =	md5($this->encryptAES($info, $key, $iv));
			return $info;
		}
		
		function confirmLogin($username, $pass)
		{	
			$con = $this->connectDB();
			
			$query = 
			"
				SELECT username FROM users 
				WHERE userEncrypt = '{$username}' AND passEncrypt = '{$pass}'
			";	
			
			$result = mysql_query($query, $con);
			$data = mysql_fetch_array($result);
			
			if($data == false)
			{
				header('location: login.php');
			}		
		}
		
		function createUser()
		{		
			$key =  $_SESSION['key'];
			$iv =  $_SESSION['iv'];
			
			$con = $this->connectDB();
			$username = $this->encryptAES($_REQUEST["txtUsername"], $key, $iv);
			$userEnCrypt = md5($username);
			$pass = $this->encryptAES($_REQUEST["txtPass"], $key, $iv);
			$passEncrypt = strval(md5($pass));
			$firstName = $this->encryptAES($_REQUEST["txtFirName"], $key, $iv);
			$lastName = $this->encryptAES($_REQUEST["txtLasName"], $key, $iv);
			$email = $this->encryptAES($_REQUEST["txtEmail"], $key, $iv);
			$checkExist = md5($_REQUEST["txtEmail"].$this->fix_key);
			
			$isExist = $this->checkEmailExist($checkExist);
			
			if($isExist == true)
			{
				echo "<script>alert('Email đã được đăng ký!');</script>";
				return false;
			}			
			else
			{
				$query = "insert into users
					(username, userEncrypt, password, passEncrypt, FirstName, LastName, Email, CheckExist) 
					values 
					(N'{$username}', '{$userEnCrypt}', '{$pass}', '{$passEncrypt}', N'{$firstName}', N'{$lastName}', '{$email}', '{$checkExist}')
						";
						
				$result = mysql_query($query, $con);
					
				if($result)
				{
					echo "<script>alert('Đăng ký thành công!');</script>";
					
				}
				else
				{
					echo "<script>alert('Đăng ký không thành công!');</script>";
				}	
				
			}
		}
		
		function checkEmailExist($check)
		{
			$con = $this->connectDB();
			$query = "SELECT COUNT(*) FROM users where CheckExist = '{$check}'";
			$result = mysql_query($query, $con);
			$row = mysql_fetch_array($result);
			
    		if ($row[0] >= 1) 
			{
				return true;
			} 
			else
			{
				return false;	
			}
		}
		
		function generateKeyAndIV() {
		   $key = substr(md5(uniqid(mt_rand(), true)), 0, 16);
  		   $iv = substr(md5(uniqid(mt_rand(), true)), 0, 16);
		   
		   session_start();		
		   $_SESSION['key'] = $key;
		   $_SESSION['iv'] = $iv;
		   
		   echo "<br><br>
				<div style='text-align: center;'>
					<p>Key: {$_SESSION['key']}</p>
					<p>IV Key: {$_SESSION['iv']}</p>
					<br>
					<a href='login.php'>Login</a>
			</div>";
		   return array('key' => $key,'iv' => $iv);
		}
		
		function encryptAES($plaintext, $key, $iv) {
		  $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
		  return base64_encode($ciphertext);
		}
		
		function decryptAES($ciphertext, $key, $iv) {
		  $ciphertext = base64_decode($ciphertext);
		  $plaintext = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext, MCRYPT_MODE_CBC, $iv);
		  return rtrim($plaintext, "\0");
		}
	}
?>