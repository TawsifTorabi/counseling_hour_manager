<?php
session_start();
#check if username & password are submitted
if(isset($_POST['username'])&&
    isset($_POST['password'])){
	include("../../connect_db.php");
	//get user name and password
	$user_name = mysqli_real_escape_string($con, $_POST["username"]);
	$user_pass = mysqli_real_escape_string($con, $_POST["password"]);
	
	//match the username and password from database
	if(mysqli_num_rows(mysqli_query($con, "select * from users where username='$user_name' AND password='$user_pass'"))> 0){

		//get user ID and Privilage
		$new_query="select * from users where username='$user_name' AND password='$user_pass'";				
		if($rows=mysqli_fetch_array(mysqli_query($con, $new_query), MYSQLI_ASSOC)){  
			$userid = $rows['id'];
			$privilege = $rows['adminprivilege'];
		}
		
		//create unique session id
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$sessionID = time();
		//$sessionID = hash('sha256', $user_name + $_SERVER['REMOTE_ADDR'] + time());
		$issuetime = time();
		$expirytime = "0";
		$validity = "valid";
		$browser = $_SERVER['HTTP_USER_AGENT'];
		$user_ip = getenv('REMOTE_ADDR');
		$geo = "";
		$country = "Bangladesh";
		$city = "Dhaka";
		$location = "";		  
		//save session id, IP Address, Login Information to Database
		 mysqli_query($con, "
		 Insert Into `sessions` (`session_id`, `user_id`, `issued`, `expiry_time`, `ipaddress`,`browser` ,`location`, `validity`) Values
		  (
			'$sessionID',
			'$userid',
			'$issuetime',
			'$expirytime',
			'$ipaddress',
			'$browser',
			'$location',
			'$validity'
		  )
		  ");	  

			$_SESSION['librarypanel'] = $sessionID;
			$_SESSION['username'] = $user_name;
			$_SESSION['userid']= $userid;
			$_SESSION['privilege']= $privilege;
			
			setcookie("sessionid", $sessionID, time() + 31536000, '/');
			setcookie("username", $user_name, time() + 31536000, '/');
			setcookie("userid", $userid, time() + 31536000, '/');
			setcookie("privilege", $privilege, time() + 31536000, '/');
			
			echo "<script>window.open('../../home.php','_self')</script>";
			//echo mysql_error();
			
	} else {
		$message = "User Name or Password is Incorrect";
		header("location:../../index.php?error=$message");
		//echo "<script>alert('User Name or Password is Incorrect')</script>";
		//echo mysql_error();
	}

}else{
    header("location:../../index.php");
    exit;
}

?>