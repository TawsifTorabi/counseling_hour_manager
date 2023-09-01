<?php

		//User Merit verification
		$CurrentUserId 		= mysqli_real_escape_string($con, $_COOKIE['userid']);
		$resultUserCheck	= mysqli_query($con, "SELECT * FROM `academic_student_profile` WHERE `user_id`='$CurrentUserId'");
		
		if(!$resultUserCheck){
			echo mysqli_error($con);
		}else{
			if($rows=mysqli_fetch_array($resultUserCheck)){
				$counseling_merit = (int)$rows['counseling_merit'];
			}
				//echo $_COOKIE['userid'];
					
			$minMerit = (int)mysqli_fetch_assoc(mysqli_query($con, "SELECT global_value FROM global_settings WHERE global_key='min_merit'"))['global_value'];
			
			if($counseling_merit < $minMerit){
				echo"<h1 style=\"margin-left: 20px;\">You don't have enough merit point to use the system.</h1></br>
					 <h3 style=\"margin-left: 20px;\">Contact the department for application to allow you into the system.</h3>
					";
				exit;
			}
		}

		
?>