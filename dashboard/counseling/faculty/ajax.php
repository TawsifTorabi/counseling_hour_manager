<?php
	session_start();
	
	//create database connection
	include("../connect_db.php");
	
	//blank var
	$getsessionID = '';
	
	//call session data
	if(isset($_COOKIE['sessionid'])){
		//get session id from browser and update variable
		$getsessionID = $_COOKIE['sessionid'];
	}
	//set the validity mode for session data
	$validity = "valid";	
	//verify session id
	if(mysqli_num_rows(mysqli_query($con, "select * from sessions where session_id='$getsessionID' AND validity='$validity'"))> 0){
		
	
	$currentUserID = $_COOKIE['userid'];
	$courseFacultyID = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM academic_faculty_profile WHERE user_id='$currentUserID'"))['id'];
	

	if($_GET['data'] == 'RequestCount'){

		$sql = "SELECT 
					COUNT(CASE WHEN `academic_counseling_requests`.`status` = 'pending' THEN 1 END) AS pending_count,
					COUNT(CASE WHEN `academic_counseling_requests`.`status` = 'approved' THEN 1 END) AS approved_count,
					COUNT(CASE WHEN STR_TO_DATE(`academic_counseling_requests`.`request_date`, '%m/%d/%Y') < CURDATE() THEN 1 END) AS before_today_count
				FROM `academic_counseling_requests`
				INNER JOIN `academic_counseling_hours`
				ON `academic_counseling_hours`.`id` = `academic_counseling_requests`.`hour_id`
				INNER JOIN `academic_course`
				ON `academic_course`.`course_code` = `academic_counseling_requests`.`course_code`
				INNER JOIN `academic_student_profile`
				ON `academic_student_profile`.`id` = `academic_counseling_requests`.`student_id`
				INNER JOIN `users`
				ON `users`.`id` = `academic_student_profile`.`user_id`
				WHERE `academic_counseling_requests`.`faculty_id` = '$courseFacultyID'";

		$result = mysqli_query($con, $sql);
		header('Content-Type: application/json; charset=utf-8');

		if($result){
			if($row = mysqli_fetch_assoc($result)){
				$data = array('pending_count' => $row['pending_count'], 'approved_count' => $row['approved_count'], 'before_today_count' => $row['before_today_count']);
			}
			echo json_encode($data);
		} else {
			echo mysqli_error($con);
		}
	}

	if($_GET['data'] == 'AcceptRequest'){
				//ajax.php?data=AcceptRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section
				$request_id    = mysqli_real_escape_string($con, $_GET['request_id']);
				$course_code   = mysqli_real_escape_string($con, $_GET['course_code']);
				$section    = mysqli_real_escape_string($con, $_GET['section']);
				$room    = mysqli_fetch_assoc(mysqli_query($con, "SELECT `room` FROM `academic_faculty_profile` WHERE `user_id`='$currentUserID'"))['room'];
				$UpdateSQL  = " UPDATE `academic_counseling_requests` 
								SET `status`='approved', `room`='$room'
								WHERE `course_code`='$course_code'
								AND `id`='$request_id'
								AND `section`='$section'
								";
				$result		= mysqli_query($con, $UpdateSQL);
				header('Content-Type: application/json; charset=utf-8');
				
				if(!$result){
					$data = array('accepted' => 'false');
					echo mysqli_error($con);
					echo json_encode($data);
				}else{
					$data = array('accepted' => 'true');
					echo mysqli_error($con);
					echo json_encode($data);	
				}	
	}


	if($_GET['data'] == 'PresentRequest'){
				//ajax.php?data=PresentRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section
				$request_id    = mysqli_real_escape_string($con, $_GET['request_id']);
				$course_code   = mysqli_real_escape_string($con, $_GET['course_code']);
				$section    = mysqli_real_escape_string($con, $_GET['section']);
				
				$UpdateSQL  = " UPDATE `academic_counseling_requests`
								INNER JOIN `academic_student_profile`
								ON `academic_student_profile`.`id` = `academic_counseling_requests`.`student_id`
								SET `academic_counseling_requests`.`status`='present',
								`academic_student_profile`.`counseling_merit` = (`academic_student_profile`.`counseling_merit` + 25)
								WHERE `academic_counseling_requests`.`course_code`='$course_code'
								AND `academic_counseling_requests`.`id`='$request_id'
								AND `academic_counseling_requests`.`section`='$section'
								";
				$result		= mysqli_query($con, $UpdateSQL);
				header('Content-Type: application/json; charset=utf-8');
				
				if(!$result){
					$data = array('present' => 'false');
					echo mysqli_error($con);
					echo json_encode($data);
				}else{
					$data = array('present' => 'true');
					echo mysqli_error($con);
					echo json_encode($data);	
				}	
	}


	if($_GET['data'] == 'AbsentRequest'){
				//ajax.php?data=AbsentRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section
				$request_id    = mysqli_real_escape_string($con, $_GET['request_id']);
				$course_code   = mysqli_real_escape_string($con, $_GET['course_code']);
				$section    = mysqli_real_escape_string($con, $_GET['section']);
				
				$UpdateSQL  = " UPDATE `academic_counseling_requests`
								INNER JOIN `academic_student_profile`
								ON `academic_student_profile`.`id` = `academic_counseling_requests`.`student_id`
								SET `academic_counseling_requests`.`status`='absent',
								`academic_student_profile`.`counseling_merit` = (`academic_student_profile`.`counseling_merit` - 10)
								WHERE `academic_counseling_requests`.`course_code`='$course_code'
								AND `academic_counseling_requests`.`id`='$request_id'
								AND `academic_counseling_requests`.`section`='$section'
								";
				$result		= mysqli_query($con, $UpdateSQL);
				header('Content-Type: application/json; charset=utf-8');
				
				if(!$result){
					$data = array('absent' => 'false');
					echo mysqli_error($con);
					echo json_encode($data);
				}else{
					$data = array('absent' => 'true');
					echo mysqli_error($con);
					echo json_encode($data);	
				}	
	}


	if($_GET['data'] == 'RemoveRequest'){
				//ajax.php?data=RemoveRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section
				$request_id    = mysqli_real_escape_string($con, $_GET['request_id']);
				$course_code   = mysqli_real_escape_string($con, $_GET['course_code']);
				$section    = mysqli_real_escape_string($con, $_GET['section']);
				$UpdateSQL  = " UPDATE `academic_counseling_requests` 
								SET `status`='canceled' 
								WHERE `course_code`='$course_code'
								AND `id`='$request_id'
								AND `section`='$section'
								";
				$result		= mysqli_query($con, $UpdateSQL);
				header('Content-Type: application/json; charset=utf-8');
				
				if(!$result){
					$data = array('canceled' => 'false');
					echo mysqli_error($con);
					echo json_encode($data);
				}else{
					$data = array('canceled' => 'true');
					echo mysqli_error($con);
					echo json_encode($data);	
				}	
	}


	if($_GET['data'] == 'RejectRequest'){
				//ajax.php?data=RejectRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section
				$request_id    = mysqli_real_escape_string($con, $_GET['request_id']);
				$course_code   = mysqli_real_escape_string($con, $_GET['course_code']);
				$section    = mysqli_real_escape_string($con, $_GET['section']);
				$UpdateSQL  = " UPDATE `academic_counseling_requests` 
								SET `status`='rejected' 
								WHERE `course_code`='$course_code'
								AND `id`='$request_id'
								AND `section`='$section'
								";
				$result		= mysqli_query($con, $UpdateSQL);
				header('Content-Type: application/json; charset=utf-8');
				
				if(!$result){
					$data = array('rejected' => 'false');
					echo mysqli_error($con);
					echo json_encode($data);
				}else{
					$data = array('rejected' => 'true');
					echo mysqli_error($con);
					echo json_encode($data);	
				}	
	}


	if($_GET['data'] == 'RescheduleRequest'){
				//ajax.php?data=RejectRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section
				$request_id    = mysqli_real_escape_string($con, $_GET['request_id']);
				$course_code   = mysqli_real_escape_string($con, $_GET['course_code']);
				$section    = mysqli_real_escape_string($con, $_GET['section']);
				$UpdateSQL  = " UPDATE `academic_counseling_requests` 
								SET `status`='rejected' 
								WHERE `course_code`='$course_code'
								AND `id`='$request_id'
								AND `section`='$section'
								";
				$result		= mysqli_query($con, $UpdateSQL);
				header('Content-Type: application/json; charset=utf-8');
				
				if(!$result){
					$data = array('rejected' => 'false');
					echo mysqli_error($con);
					echo json_encode($data);
				}else{
					$data = array('rejected' => 'true');
					echo mysqli_error($con);
					echo json_encode($data);	
				}	
	}



	if($_GET['data'] == 'RemoveAllPassed'){
				//ajax.php?data=RemoveAllPassed&course_code="+course_code+"&section="+section
				$UpdateSQL  = " UPDATE `academic_counseling_requests` 
								INNER JOIN `academic_counseling_hours`
								ON `academic_counseling_hours`.`id` = `academic_counseling_requests`.`hour_id`
								SET `academic_counseling_requests`.`status`='canceled' 
								WHERE `academic_counseling_requests`.`faculty_id`='$courseFacultyID'
								AND `academic_counseling_requests`.`status`='pending'
								AND (
									STR_TO_DATE(`academic_counseling_requests`.`request_date`, '%m/%d/%Y') < CURDATE() OR
									(STR_TO_DATE(`academic_counseling_requests`.`request_date`, '%m/%d/%Y') = CURDATE() AND
									TIME_FORMAT(CONCAT_WS(' ', `academic_counseling_hours`.`start_hour`, `academic_counseling_hours`.`start_minute`, `academic_counseling_hours`.`start_daytime`), '%H:%i:%s') < CURTIME())
								);
								";
				$result		= mysqli_query($con, $UpdateSQL);
				header('Content-Type: application/json; charset=utf-8');
				
				if(!$result){
					$data = array('removed' => 'false');
					echo mysqli_error($con);
					echo json_encode($data);
				}else{
					$data = array('removed' => 'true');
					echo mysqli_error($con);
					echo json_encode($data);	
				}	
	}


	if($_GET['data'] == 'AcceptAllRequest'){
				//ajax.php?data=RemoveAllPassed&course_code="+course_code+"&section="+section
				$UpdateSQL  = "UPDATE `academic_counseling_requests` 
								INNER JOIN `academic_counseling_hours`
								ON `academic_counseling_hours`.`id` = `academic_counseling_requests`.`hour_id`
								SET `academic_counseling_requests`.`status`='approved' 
								WHERE `academic_counseling_requests`.`faculty_id`='$courseFacultyID'
								AND `academic_counseling_requests`.`status`='pending'
								AND (
									STR_TO_DATE(`academic_counseling_requests`.`request_date`, '%m/%d/%Y') > CURDATE() OR
									(STR_TO_DATE(`academic_counseling_requests`.`request_date`, '%m/%d/%Y') = CURDATE() AND
									TIME_FORMAT(CONCAT_WS(' ', `academic_counseling_hours`.`start_hour`, `academic_counseling_hours`.`start_minute`, `academic_counseling_hours`.`start_daytime`), '%H:%i:%s') >= CURTIME())
								);
								";
				$result		= mysqli_query($con, $UpdateSQL);
				header('Content-Type: application/json; charset=utf-8');
				
				if(!$result){
					$data = array('accepted' => 'false');
					echo mysqli_error($con);
					echo json_encode($data);
				}else{
					$data = array('accepted' => 'true');
					echo mysqli_error($con);
					echo json_encode($data);	
				}	
	}

	if($_GET['data'] == 'RejectAllRequest'){
				//ajax.php?data=RejectAllRequest&course_code="+course_code+"&section="+section
				$UpdateSQL  = "UPDATE `academic_counseling_requests` 
								INNER JOIN `academic_counseling_hours`
								ON `academic_counseling_hours`.`id` = `academic_counseling_requests`.`hour_id`
								SET `academic_counseling_requests`.`status`='rejected' 
								WHERE `academic_counseling_requests`.`faculty_id`='$courseFacultyID'
								AND `academic_counseling_requests`.`status`='pending'
								AND (
									STR_TO_DATE(`academic_counseling_requests`.`request_date`, '%m/%d/%Y') > CURDATE() OR
									(STR_TO_DATE(`academic_counseling_requests`.`request_date`, '%m/%d/%Y') = CURDATE() AND
									TIME_FORMAT(CONCAT_WS(' ', `academic_counseling_hours`.`start_hour`, `academic_counseling_hours`.`start_minute`, `academic_counseling_hours`.`start_daytime`), '%H:%i:%s') >= CURTIME())
								);
								";
				$result		= mysqli_query($con, $UpdateSQL);
				header('Content-Type: application/json; charset=utf-8');
				
				if(!$result){
					$data = array('rejected' => 'false');
					echo mysqli_error($con);
					echo json_encode($data);
				}else{
					$data = array('rejected' => 'true');
					echo mysqli_error($con);
					echo json_encode($data);	
				}	
	}


		
		
			//Get Time of the day
			function cusgetTimeOfDay($timeStr) {
			  $time = DateTime::createFromFormat('H:i A', $timeStr);
			  $hour = $time->format('H');

			  if ($hour >= 5 && $hour < 12) {
				return '<i class="fas fa-sun" style="color: #fb8c50;"></i> Morning';
			  } elseif ($hour >= 12 && $hour < 15) {
				return '<i class="fas fa-sun" style="color: #ffc800;"></i> Noon';
			  } elseif ($hour >= 15 && $hour < 18) {
				return '<i class="fas fa-sun" style="color: #d97915;"></i> Afternoon';
			  } else {
				return '<i class="fas fa-sun" style="color: blue;"></i> Evening';
			  }
			}
		
		

	//Returns Prescription History Search
	if($_GET['data'] == 'LatestRequests'){
		
	?>		
					<?php
						$currentUserID = $_COOKIE['userid'];
						$courseFacultyID = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM academic_faculty_profile WHERE user_id='$currentUserID'"))['id'];
						mysqli_set_charset($con, "utf8");
						$sql        = "	SELECT 
										`academic_course`.`course_name`, 
										`users`.`name`,
										`academic_student_profile`.`academic_id`,
										`academic_counseling_hours`.`id` As `id`,
										`academic_counseling_hours`.`start_hour`, 
										`academic_counseling_hours`.`start_minute`,
										`academic_counseling_hours`.`start_daytime`, 
										`academic_counseling_hours`.`end_hour`, 
										`academic_counseling_hours`.`end_minute`, 
										`academic_counseling_hours`.`end_daytime`,
										`academic_counseling_requests`.`id` AS `request_id`, 
										`academic_counseling_requests`.`student_id`, 
										`academic_counseling_requests`.`alongwith_student_id`, 
										`academic_counseling_requests`.`faculty_id`, 
										`academic_counseling_requests`.`hour_id`, 
										`academic_counseling_requests`.`trimester_id`, 
										`academic_counseling_requests`.`request_date`, 
										`academic_counseling_requests`.`course_code`, 
										`academic_counseling_requests`.`section`, 
										`academic_counseling_requests`.`status`, 
										`academic_counseling_requests`.`room`, 
										`academic_counseling_requests`.`topic`, 
										`academic_counseling_requests`.`problem`, 
										`academic_counseling_requests`.`attachment`, 
										`academic_counseling_requests`.`created_at`,
										`academic_counseling_requests`.`updated_at`
										FROM `academic_counseling_requests`
										INNER JOIN `academic_counseling_hours`
										ON `academic_counseling_hours`.`id` = `academic_counseling_requests`.`hour_id`
										INNER JOIN `academic_course`
										ON `academic_course`.`course_code` = `academic_counseling_requests`.`course_code`
										INNER JOIN `academic_student_profile`
										ON `academic_student_profile`.`id` = `academic_counseling_requests`.`student_id`
										INNER JOIN `users`
										ON `users`.`id` = `academic_student_profile`.`user_id`
										
										WHERE `academic_counseling_requests`.`status`='pending'
										AND `academic_counseling_requests`.`faculty_id`='$courseFacultyID'
										";
						$result		= mysqli_query($con, $sql);
						if(!$result){
							echo mysqli_error($con);
						}
						else{
							while($rows=mysqli_fetch_array($result)){
								$hourID = $rows['id'];
								
								$userIsLate = false;
								$currentTime = strtotime('now');
								$currentDate = date('m/d/Y', $currentTime);
								$today = $currentDate;

								// DEMO Input Time slot and date
								//$timeslot = "11:00 AM - 11:30 AM";
								//$timeslotDate = "04/23/2023";
								
								$timeslot = $rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime'].' - '.$rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime'];
								$timeslotDate = $rows['request_date'];
								$gracePeriod = 10 * 60;
								
								// Parse timeslot
								$timeslotArr = explode(' - ', $timeslot);
								$startTime = strtotime($timeslotArr[0]);
								$endTime = strtotime($timeslotArr[1]);

								// Parse timeslot date
								$timeslotDateStr = date('m/d/Y', strtotime($timeslotDate));

								//echo '<script>console.log("this slot-'.$timeslot.'")</script>';
								//echo '<script>console.log("today-'.$today.'")</script>';

								if ($currentDate == $timeslotDateStr) {
									//Today
									if ($currentTime >= $startTime && ($currentTime - $startTime) <= $gracePeriod) {
										//Time is now
										$dateMessage = "The Time is now!";
									} elseif ($currentTime >= $startTime) {
										//Time is after
										$dateMessage = "You are late!";
										$userIsLate = true;
									} else {
										//Time is before
										$dateMessage = "Today, Still Some Time Left";
									}
								} elseif (strtotime($timeslotDate) == strtotime('+1 day', strtotime($currentDate))) {
									$dateMessage = "Tomorrow";
								} elseif (strtotime($timeslotDate) > strtotime('+1 day', strtotime($currentDate))) {
									$dateMessage = "Plenty of days left";
								} elseif (strtotime($timeslotDate) < strtotime($currentDate)) {
									$dateMessage = "Date is Past";
									$userIsLate = true;
								}
								
								echo '<script>console.log("start time-'.$timeslotArr[0].'")</script>';
								echo '<script>console.log("end time-'.$timeslotArr[1].'")</script>';

						?>

						<?php if(!$userIsLate){ ?>
							<tr id="headerTableTr" class="alert alert-success">
						<?php }else{ ?>
							<tr id="headerTableTr" class="alert alert-danger">
						<?php } ?>
								<td><?= $rows['request_id']; ?></td>
								<td><?= $rows['id']; ?></td>
								<td>
									<b><?= $rows['topic']; ?></b></br>
									<small><?= $rows['name']; ?> - <?= $rows['academic_id']; ?>
								</td>
								<td>
									<a href="javascript:void(0);" data-toggle="tooltip" data-placement="right" title="<?= $rows['course_name']; ?>">
										<?= $rows['course_code']; ?> (<?= $rows['section']; ?>)
									</a></br>
									<?= $rows['course_name']; ?>
								</td>
								<td>
									<?= date("D, F d, Y", strtotime($rows['request_date'])) ?></br>
									<?= $dateMessage; ?>
								</td>
								<td>
									<?= $rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime'] ?></br>
									<?=  cusgetTimeOfDay($rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime']); ?>
								</td>
								<td>
									<?= $rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime'] ?></br>
									<?=  cusgetTimeOfDay($rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime']); ?>
								</td>
								<td>
									<?php
									if($rows['room'] != ''){
										echo $rows['room'];
									}else{
										echo "<i class=\"fas fa-times-circle\" style=\"color: #b81919;\"></i> Not Assigned";
									}
									?>
								</td>
								<td>
									<button class="btn btn-info" onclick="ViewInfo(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')" title="View Information"><i class="fa-solid fa-circle-info"></i></button>
									&nbsp;
									&nbsp;
									&nbsp;
									<?php if(!$userIsLate){ ?>
										<button class="btn btn-success" onclick="AcceptRequest(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')" title="Accept Request"><i class="fa-solid fa-square-check"></i></button>
									</a>
										<button class="btn btn-danger" onclick="RejectRequest(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')" title="Reject Request"><i class="fa-solid fa-trash-can"></i></button>
									<?php }else{ ?>
										<button class="btn btn-danger" onclick="RemoveRequest(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')" title="Remove Delayed Request"><i class="fa-regular fa-calendar-xmark"></i></button>
									<?php } ?>

								</td>
							</tr>
						<?php
							}
						}
					?>
					
		

	<?php
		//Ends Here
	}
		
		
		
		
		
		//Returns Prescription History Search
	if($_GET['data'] == 'AcceptedRequests'){
		
	?>
		
				<?php

						mysqli_set_charset($con, "utf8");
						$sql        = "SELECT 
									  `academic_course`.`course_name`, 
									  `users`.`name`,
									  `academic_student_profile`.`academic_id`,
									  `academic_counseling_hours`.`id` AS `id`,
									  `academic_counseling_hours`.`start_hour`, 
									  `academic_counseling_hours`.`start_minute`,
									  `academic_counseling_hours`.`start_daytime`, 
									  `academic_counseling_hours`.`end_hour`, 
									  `academic_counseling_hours`.`end_minute`, 
									  `academic_counseling_hours`.`end_daytime`,
									  `academic_counseling_requests`.`id` AS `request_id`, 
									  `academic_counseling_requests`.`student_id`, 
									  `academic_counseling_requests`.`alongwith_student_id`, 
									  `academic_counseling_requests`.`faculty_id`, 
									  `academic_counseling_requests`.`hour_id`, 
									  `academic_counseling_requests`.`trimester_id`, 
									  `academic_counseling_requests`.`request_date`, 
									  `academic_counseling_requests`.`course_code`, 
									  `academic_counseling_requests`.`section`, 
									  `academic_counseling_requests`.`status`, 
									  `academic_counseling_requests`.`room`, 
									  `academic_counseling_requests`.`topic`, 
									  `academic_counseling_requests`.`problem`, 
									  `academic_counseling_requests`.`attachment`, 
									  `academic_counseling_requests`.`created_at`,
									  `academic_counseling_requests`.`updated_at`
									FROM `academic_counseling_requests`
									INNER JOIN `academic_counseling_hours`
									  ON `academic_counseling_hours`.`id` = `academic_counseling_requests`.`hour_id`
									INNER JOIN `academic_course`
									  ON `academic_course`.`course_code` = `academic_counseling_requests`.`course_code`
									INNER JOIN `academic_student_profile`
									  ON `academic_student_profile`.`id` = `academic_counseling_requests`.`student_id`
									INNER JOIN `users`
									  ON `users`.`id` = `academic_student_profile`.`user_id`
									WHERE `academic_counseling_requests`.`status`='approved'
									AND `academic_counseling_requests`.`faculty_id`='$courseFacultyID'
									ORDER BY 
									  STR_TO_DATE(`academic_counseling_requests`.`request_date`, '%m/%d/%Y') ASC, 
									  TIME_FORMAT(CONCAT_WS(' ', `academic_counseling_hours`.`start_hour`, `academic_counseling_hours`.`start_minute`, `academic_counseling_hours`.`start_daytime`), '%h:%i %p') ASC;
									";
						$result		= mysqli_query($con, $sql);
						if(!$result){
							echo mysqli_error($con);
						}
						else{
							while($rows=mysqli_fetch_array($result)){
								$hourID = $rows['id'];
								
								$userIsLate = false;
								$currentTime = strtotime('now');
								$currentDate = date('m/d/Y', $currentTime);
								$today = $currentDate;

								// DEMO Input Time slot and date
								//$timeslot = "11:00 AM - 11:30 AM";
								//$timeslotDate = "04/23/2023";
								
								$timeslot = $rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime'].' - '.$rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime'];
								$timeslotDate = $rows['request_date'];
								$gracePeriod = 10 * 60;
								
								// Parse timeslot
								$timeslotArr = explode(' - ', $timeslot);
								$startTime = strtotime($timeslotArr[0]);
								$endTime = strtotime($timeslotArr[1]);

								// Parse timeslot date
								$timeslotDateStr = date('m/d/Y', strtotime($timeslotDate));

								//echo '<script>console.log("this slot-'.$timeslot.'")</script>';
								//echo '<script>console.log("today-'.$today.'")</script>';

								if ($currentDate == $timeslotDateStr) {
									//Today
									if ($currentTime >= $startTime && ($currentTime - $startTime) <= $gracePeriod) {
										//Time is now
										$dateMessage = "The Time is now!";
									} elseif ($currentTime >= $startTime) {
										//Time is after
										$dateMessage = "Late!";
										$userIsLate = true;
									} else {
										//Time is before
										$dateMessage = "Today, Still Some Time Left";
									}
								} elseif (strtotime($timeslotDate) == strtotime('+1 day', strtotime($currentDate))) {
									$dateMessage = "Tomorrow";
								} elseif (strtotime($timeslotDate) > strtotime('+1 day', strtotime($currentDate))) {
									$dateMessage = "Plenty of days left";
								} elseif (strtotime($timeslotDate) < strtotime($currentDate)) {
									$dateMessage = "Date is Past";
									$userIsLate = true;
								}
								
								echo '<script>console.log("start time-'.$timeslotArr[0].'")</script>';
								echo '<script>console.log("end time-'.$timeslotArr[1].'")</script>';

						?>



						<?php if(!$userIsLate && ($currentTime >= $startTime && ($currentTime - $startTime) <= $gracePeriod) && $currentDate == $timeslotDateStr){ ?>
							<tr class="alert alert-green" id="tr<?= $rows['request_id']; ?>">
						<?php }elseif(!$userIsLate){ ?>
							<tr class="alert alert-success" id="tr<?= $rows['request_id']; ?>">
						<?php }elseif($userIsLate  && ($currentTime >= $startTime && ($currentTime <= $endTime)) && $currentDate == $timeslotDateStr){ ?>
							<tr class="alert alert-cyan" id="tr<?= $rows['request_id']; ?>">
						<?php }elseif($userIsLate){ ?>
							<tr class="alert alert-grey" id="tr<?= $rows['request_id']; ?>">
						<?php } ?>
						
								<td><?= $rows['request_id']; ?></td>
								<td><?= $rows['id']; ?></td>
								<td>
									<b><?= $rows['topic']; ?></b></br>
									<small><?= $rows['name']; ?> - <?= $rows['academic_id']; ?>
								</td>
								<td>
									<a href="javascript:void(0);" data-toggle="tooltip" data-placement="right" title="<?= $rows['course_name']; ?>">
										<?= $rows['course_code']; ?> (<?= $rows['section']; ?>)
									</a></br>
									<?= $rows['course_name']; ?>
								</td>
								<td>
									<?= date("D, F d, Y", strtotime($rows['request_date'])) ?></br>
									<?= $dateMessage; ?>
									</br>
									<?php if(!$userIsLate  && $currentTime >= $startTime && ($currentTime - $startTime) <= $gracePeriod){ ?>
									<span id="countdown<?= $rows['request_id']; ?>"></span>
									<script>
									  // JavaScript code
									  // Convert the PHP timestamp to JavaScript timestamp (milliseconds)
									  var startTime = <?= $startTime * 1000 ?>;
									  
									  // Set the target time for the end of the grace period (10 minutes after appointment slot start time)
									  var gracePeriodEnd = new Date(startTime + 10 * 60 * 1000);

									  // Update the countdown timer every second
									  var timer = setInterval(function() {
										// Get the current time
										var now = new Date();

										// Calculate the time remaining until the end of the grace period
										var timeRemaining = gracePeriodEnd - now;

										// If the grace period has ended
										if (timeRemaining <= 0) {
										  clearInterval(timer);
										  document.getElementById("countdown<?= $rows['request_id']; ?>").innerHTML = "Grace period has ended.";
										  document.getElementById('tr<?= $rows['request_id']; ?>').classList.remove("alert-success");
										  document.getElementById('tr<?= $rows['request_id']; ?>').classList.add("alert-cyan");
											var presentButton = document.getElementById("present<?= $rows['request_id']; ?>");
											var absentButton = document.createElement("button");
											absentButton.className = "btn btn-grey";
											absentButton.id = "reject<?= $rows['request_id']; ?>";
											absentButton.innerHTML = "<i class=\"fa-solid fa-user-xmark\"></i>";
											absentButton.onclick = function() {
												AbsentRequest(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>');
											}
											presentButton.parentNode.replaceChild(absentButton, presentButton);
											
										  return;
										}

										// Calculate the minutes and seconds remaining
										var minutesRemaining = Math.floor(timeRemaining / 60000);
										var secondsRemaining = Math.floor((timeRemaining % 60000) / 1000);

										// Format the countdown timer string
										var countdownStr = "Grace period ends in ";
										if (minutesRemaining > 0) {
										  countdownStr += minutesRemaining + " min " + (minutesRemaining > 1 ? "s" : "") + " and ";
										}
										countdownStr += secondsRemaining + " sec." + (secondsRemaining > 1 ? "s" : "");

										// Update the countdown timer display
										document.getElementById("countdown<?= $rows['request_id']; ?>").innerHTML = countdownStr;
									  }, 1000);
									</script>
									<?php } ?>
								</td>
								<td>
									<?= $rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime'] ?></br>
									<?=  cusgetTimeOfDay($rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime']); ?>
								</td>
								<td>
									<?= $rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime'] ?></br>
									<?=  cusgetTimeOfDay($rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime']); ?>
								</td>
								<td>
									<?php
									if($rows['room'] != ''){
										echo $rows['room'];
									}else{
										echo "<i class=\"fas fa-times-circle\" style=\"color: #b81919;\"></i> Not Assigned";
									}
									?>
								</td>
								<td>
									<button class="btn btn-info" onclick="ViewInfo(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')"><i class="fa-solid fa-circle-info"></i></button>
									&nbsp;
									&nbsp;
									&nbsp;
									<?php if(!$userIsLate  && $currentTime >= $startTime && ($currentTime - $startTime) <= $gracePeriod){ ?>
										<button class="btn btn-success" id="present<?= $rows['request_id']; ?>" onclick="PresentRequest(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')" title="Student Present"><i class="fa-solid fa-user-check"></i></button>
										<button class="btn btn-warning" onclick="RescheduleRequest(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')" title="Request Reschedule"><i class="fas fa-calendar-alt"></i></button>
									<?php }elseif($userIsLate){ ?>
										<button class="btn btn-success" id="present<?= $rows['request_id']; ?>" onclick="PresentRequest(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')" title="Student Present"><i class="fa-solid fa-user-check"></i></button>
									    <button class="btn btn-danger" id="reject<?= $rows['request_id']; ?>" onclick="AbsentRequest(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')" title="Student Absent"><i class="fa-solid fa-user-xmark"></i></button>
										<button class="btn btn-warning" id="reschedule<?= $rows['request_id']; ?>" onclick="RescheduleRequest(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')" title="Request Reschedule"><i class="fas fa-calendar-alt"></i></button>
									<?php }else{ ?>
										<button class="btn btn-warning" onclick="RescheduleRequest(this, '<?= $rows['request_id']; ?>', '<?= $rows['course_code']; ?>', '<?= $rows['section']; ?>')" title="Request Reschedule"><i class="fas fa-calendar-alt"></i></button>
									<?php } ?>

								</td>
							</tr>
						<?php
							}
						}
					?>
		

	<?php
		//Ends Here
	}
		
	


	
	// Check Hour ID 
	if($_GET['data'] == 'sethourid'){
		$userID     = $_COOKIE['userid'];
		$facultyID = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM academic_faculty_profile WHERE user_id = '{$_COOKIE['userid']}'"))['id'];
		$hourID  = $_GET['hour_id'];
		$time = time();
		mysqli_set_charset($con,"utf8");

		// Check if the content is already checked
		$row = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `academic_counselling_faculty_selected_hours` WHERE `faculty_id`='$facultyID' AND `hour_id`='$hourID'"));

		if($row >= 1){
			// The content is already checked, so delete the bookmark
			$Bookmarksql = "DELETE FROM `academic_counselling_faculty_selected_hours` WHERE faculty_id=$facultyID AND hour_id=$hourID";
			$result      = mysqli_query($con, $Bookmarksql);
			if(!$result){
				$data = array('checked' => 'false', 'message' => 'Error Occurred on removal!');
				echo json_encode($data);
			} else {
				$data = array('checked' => 'false', 'message' => 'Bookmark removed!');
				echo json_encode($data);
			}
		} else {
			// The content is not checked yet, so add the hour
			$Bookmarksql = "INSERT INTO `academic_counselling_faculty_selected_hours`(`faculty_id`, `hour_id`) VALUES ('$facultyID','$hourID')";
			$result      = mysqli_query($con, $Bookmarksql);
			if(!$result){
				$data = array('checked' => 'false', 'message' => 'Error Occurred on Register!');
				echo json_encode($data);
			} else {
				$data = array('checked' => 'true', 'message' => 'checked!');
				echo json_encode($data);
			}
		}
	}
		


	
	//get requests for data
	if(isset($_GET['data'])){
		
			if($_GET['data'] == 'searchToken'){
		
				mysqli_set_charset($con,"utf8");
				$tokenID    = mysqli_real_escape_string($con, $_GET['token_id']);				
				$sql        = "SELECT * FROM `medical_tokens` WHERE validity='valid' AND token_id='$tokenID' LIMIT 1" ;
				$result		= mysqli_query($con, $sql);
				$TokenCount = 0;
				if(!$result){
					echo mysqli_error($con);
				}
				else{
					while($rows=mysqli_fetch_array($result)){
						$TokenCount++;
						
						if($row=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `medical_tokens` INNER JOIN `users` ON `medical_tokens`.`user_id`=`users`.`id` WHERE validity='valid' AND token_id='$tokenID' LIMIT 1"), MYSQLI_ASSOC)){
							
							$date = $row['date'];
							$name = $row['name'];
							$gender = $row['gender'];

							$data = array(
								'token' => $row['token_id'],
								'userid' => $row['user_id'],
								'transaction' => $row['transaction_id'],
								'date' => date("F j, Y. D", strtotime($date)), 
								'name' => $name, 
								'gender' => $gender, 
								'details' => $row['problem'], 
								'message'=>'null',
								'return'=>'1'
							);
							header('Content-Type: application/json; charset=utf-8');
							echo json_encode($data);	

						}
					}
					if($TokenCount == 0){
							$data = array(
								'token' => 'null',
								'transaction' => 'null',
								'userid' => 'null',
								'date' => 'null', 
								'name' => 'null', 
								'gender' => 'null', 
								'details' => 'null', 
								'message'=>'null',
								'return'=>'0'
							);
							header('Content-Type: application/json; charset=utf-8');
							echo json_encode($data);	
					}
				}
				
				
			}




			if($_GET['data'] == 'ApproveToken'){
						//ajax.php?data=ApproveToken&token_id=15&prescription=Heheheheehhe
						//ajax.php?data=ApproveToken&token_id="+id+"&prescription="+PrescriptionText
		
						$tokenID    = mysqli_real_escape_string($con, $_GET['token_id']);
						$prescription    = mysqli_real_escape_string($con, $_GET['prescription']);
						$UpdateSQL       	= "UPDATE medical_tokens SET validity='invalid', attendance='yes' WHERE token_id='$tokenID'";
						$result				= mysqli_query($con, $UpdateSQL);
						header('Content-Type: application/json; charset=utf-8');
						if(!$result){
							$data = array('posted' => 'falseasdasdasd');
							echo mysqli_error($con);
							echo json_encode($data);
						}else{
							$data = array('posted' => 'true');
							$UpdateSQL1       	= "INSERT INTO medical_prescription VALUES(DEFAULT, '$tokenID', '$prescription')";
							$result				= mysqli_query($con, $UpdateSQL1);
							if(!$result){
								$data = array('posted' => 'false');
								echo json_encode($data);
								echo mysqli_error($con);
							}else{
								$data = array('posted' => 'true');
								echo json_encode($data);
							}	
							
						}	
			}


			if($_GET['data'] == 'DeclineToken'){
						//ajax.php?data=ApproveToken&token_id=15&prescription=Heheheheehhe
						//ajax.php?data=ApproveToken&token_id="+id+"&prescription="+PrescriptionText
		
						$tokenID    = mysqli_real_escape_string($con, $_GET['token_id']);
						$prescription    = mysqli_real_escape_string($con, $_GET['prescription']);
						$UpdateSQL       	= "UPDATE medical_tokens SET validity='invalid', attendance='no' WHERE token_id='$tokenID'";
						$result				= mysqli_query($con, $UpdateSQL);
						header('Content-Type: application/json; charset=utf-8');
						if(!$result){
							$data = array('posted' => 'false');
							echo mysqli_error($con);
							echo json_encode($data);
						}else{
							$data = array('posted' => 'true');
							echo json_encode($data);
						}	
			}


			//Returns Current Visiting Token Number of Doctor
			if($_GET['data'] == 'LatestToken'){
				
				$time = time();
				mysqli_set_charset($con,"utf8");
				header('Content-Type: application/json; charset=utf-8');
	
				// Check If valid token found 
				$row = mysqli_num_rows(mysqli_query($con, "SELECT * FROM medical_tokens WHERE validity='valid' ORDER BY token_id ASC LIMIT 1"));
				if($row >= 1){
				
					//Get Last Token Record
					if($row12 = $conn->query("SELECT * FROM medical_tokens WHERE validity='valid' ORDER BY token_id ASC LIMIT 1")->fetch_assoc()){
						$CurrentToken = $row12['token_id'];
					}

					//Get Last Donation Record
					if($row12 = $conn->query("SELECT * FROM medical_tokens WHERE validity='valid' ORDER BY token_id DESC LIMIT 1")->fetch_assoc()){
						$LatestToken = $row12['token_id'];
					}

					$data = array(
						'current' => $CurrentToken, 
						'latest' => $LatestToken, 
						'message'=>'null'
					);
					echo json_encode($data);

				}
				
				if($row == 0){
					$data = array(
						'current' => null, 
						'latest' => null,
						'message'=>'No Tokens in Queue!'
					);
					echo json_encode($data);	
				}
				
				
				//Ends Here
			}

			//Returns Current Visiting Token Number of Doctor
			if($_GET['data'] == 'LatestTokenList'){
				
				mysqli_set_charset($con,"utf8");
				header('Content-Type: application/json; charset=utf-8');
	
	
				// Check If valid token found 
				$row = mysqli_num_rows(mysqli_query($con, "SELECT token_id FROM medical_tokens WHERE validity='valid' ORDER BY token_id ASC LIMIT 20"));
				
				$data = array();
				//If rows found
				if($row >= 1){
					
					//Get Last Limited Token Record
					$result = mysqli_query($con, "SELECT token_id FROM medical_tokens WHERE validity='valid' ORDER BY token_id ASC LIMIT 20");
						
					if(!$result){
						echo mysqli_error($con);
					}else{
						while($rows=mysqli_fetch_array($result)){
							$data[] = $rows['token_id'];
						}
					echo json_encode($data);
				}
				}
				//if rows not found
				if($row == 0){
					$data1 = array(
						'index' => null, 
						'tokenid' => null
					);
					echo json_encode($data1);	
				}
				
				
				//Ends Here
			}


			//Returns Prescription History Search
			if($_GET['data'] == 'PrescriptionSearch'){
				
				mysqli_set_charset($con,"utf8");
				//header('Content-Type: application/json; charset=utf-8');
				
					$searchquery 	= mysqli_real_escape_string($con, $_GET['q']);
					$id    			= mysqli_real_escape_string($con, $_COOKIE['userid']);
					$sql        	= "	SELECT * FROM `medical_tokens` 
										INNER JOIN `medical_prescription` 
										ON `medical_tokens`.`token_id` = `medical_prescription`.`token_id`
										INNER JOIN `users`
										ON `users`.`id` = `medical_tokens`.`user_id`
										WHERE 
												`users`.`name` LIKE '$searchquery%'
											OR	`users`.`username` LIKE '$searchquery%'
											OR	`users`.`phone` LIKE '%$searchquery%'
											OR	`users`.`usertype` LIKE '$searchquery%'
											OR	`medical_prescription`.`prescription` LIKE '%$searchquery%'
											OR	`medical_tokens`.`problem` LIKE '%$searchquery%'
											OR	`medical_tokens`.`token_id` LIKE '$searchquery%'
										AND`medical_tokens`.`validity`='invalid' 
										ORDER BY `medical_tokens`.`token_id` DESC";
					$result		= mysqli_query($con, $sql);
					$TokenCount = 0;
					// create an empty array to store the results
					$users = array();
					$usersIndie = array();
					if(!$result){
						echo mysqli_error($con);
					}
					else{
						while($rows=mysqli_fetch_array($result)){
							$TokenCount++;
							$date1 = $rows['createDateTime']; 
							
							$usersIndie = array(
												'tokenid' => $rows['token_id'], 
												'name' => $rows['name'], 
												'username' => $rows['username'], 
												'time' => date("l, jS \of F, Y (h:i:s A)", $date1), 
												'phone' => $rows['phone'], 
												'blood_group' => $rows['blood_group'], 
												'gender' => $rows['gender'], 
												'dob' => $rows['dob'], 
												'transaction_id' => $rows['transaction_id'] 
											);
											
							$users[] = $usersIndie;
						}
						
						// output the results in JSON format
						header('Content-Type: text/json');
						echo json_encode($users);
					}	
				//Ends Here
			}
			
	}
		
}else{ echo "<script>window.open('../login.php','_self')</script>"; } ?>