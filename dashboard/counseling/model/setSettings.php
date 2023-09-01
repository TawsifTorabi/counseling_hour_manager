<?php
		//User Settings, Set Content If Not Exists
		$CurrentUserId 		= mysqli_real_escape_string($con, $_COOKIE['userid']);
		
		$resultUserCheck	= mysqli_query($con, "SELECT * FROM `user_settings` WHERE `user_id`='$CurrentUserId'");
		
		if(!$resultUserCheck){
			echo mysqli_error($con);
		}else{
			if(mysqli_num_rows($resultUserCheck) == 0){
				$insertSettings = " INSERT INTO `user_settings`
									(
									`user_id`,
									`hide_user_bloodbank`
									)
									VALUES ($CurrentUserId, 0)
									";
									
				mysqli_query($con, $insertSettings);
				
			}else if(mysqli_num_rows($resultUserCheck) == 1){
				if($rows=mysqli_fetch_array($resultUserCheck)){
					$hide_user_bloodbank = $rows['hide_user_bloodbank'];
					if($hide_user_bloodbank == '' || empty($hide_user_bloodbank) || $hide_user_bloodbank == null){
						
						$insertSettings2 = 	" 
											UPDATE `user_settings`
											SET 
											`hide_user_bloodbank` = 0 
											WHERE `user_id`=$CurrentUserId
											";
												
						mysqli_query($con, $insertSettings2);
					}
				}
			}
		}
?>