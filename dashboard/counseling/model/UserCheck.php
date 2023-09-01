<?php
		
		include("setSettings.php");
		
		//User Verification, Show Content if User is Eligible
		$CurrentUserId 		= mysqli_real_escape_string($con, $_COOKIE['userid']);
		$resultUserCheck	= mysqli_query($con, "SELECT * FROM `users` WHERE `id`='$CurrentUserId'");
		$userTypeAdmin = '';
		$userTypeDoctor = '';
		
		if(!$resultUserCheck){
			echo mysqli_error($con);
		}else{
			if($rows=mysqli_fetch_array($resultUserCheck)){
				$userTypeAdmin = $rows['adminprivilege'];
				$userTypeFaculty = $rows['usertype'];
				
			}
		}
		$memresult1 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `users` WHERE `id`='$CurrentUserId'"));
		
		
		if($userTypeAdmin == 'no' && $userTypeFaculty == 'teacher'){
			if($memresult1 == 1){
				$CurrentUserFaculty = 1;
			}else{
				$CurrentUserFaculty = 0;
			}
		}else{
			$CurrentUserFaculty = 0;
		}
		
?>