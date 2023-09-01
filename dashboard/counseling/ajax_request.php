<?php
session_start();
define('TIMEZONE','Asia/Dhaka');
date_default_timezone_set(TIMEZONE);
//create database connection
include("connect_db.php");

//blank var
$getsessionID = '';

//call session data
if (isset($_COOKIE['sessionid'])) {
	//get session id from browser and update variable
	$getsessionID = $_COOKIE['sessionid'];
}
//set the validity mode for session data
$validity = "valid";
//verify session id


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


if (mysqli_num_rows(mysqli_query($con, "select * from sessions where session_id='$getsessionID' AND validity='$validity'")) > 0) {

		include("model/UserCheck.php");
		//echo $CurrentUserFaculty;
		if ($CurrentUserFaculty == 1) {
			header('Location: faculty/index.php');
			exit();
		}
		

		
		$userid = $_COOKIE['userid'];
				
		include("model/meritCheck.php");
		
		
		$currentUser = $_COOKIE['userid']; 
		$current_user_student_id = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM academic_student_profile WHERE user_id='$currentUser'"))['id'];

		$globalValueSQL = "SELECT `global_value` FROM `global_settings` WHERE `global_key`='current_trimester'";
		if ($globalValueResult = mysqli_query($con, $globalValueSQL)) {
			if ($globalValueSQL = mysqli_fetch_assoc($globalValueResult)) {
				$CurrentTrimesterID = $globalValueSQL['global_value'];
			}
		} else {
			// Handle error here
			echo "Error: " . mysqli_error($con);
		}

		$CurrentTrimesterSQL = "SELECT * FROM `academic_trimester` WHERE `trimester_id`=$CurrentTrimesterID";
		if ($CurrentTrimesterResult = mysqli_query($con, $CurrentTrimesterSQL)) {
			if ($CurrentTrimesterValueSQL = mysqli_fetch_assoc($CurrentTrimesterResult)) {
				$CurrentTrimester = $CurrentTrimesterValueSQL['trimester'] . ' ' . $CurrentTrimesterValueSQL['year'];
			}
		} else {
			// Handle error here
			echo "Error: " . mysqli_error($con);
		}

		//192.168.0.109/DBMS/project/dashboard/counseling/request.php?course_code=CSE%203313&section=A&faculty_id=7

		//Get Current Users Selected Course for current semester
		$GetCourseCode = mysqli_real_escape_string($con, $_GET['course_code']);
		$GetSection = mysqli_real_escape_string($con, $_GET['section']);
		$GetFacultyID =  mysqli_real_escape_string($con, $_GET['faculty_id']);
		$CurrentUserID = $_COOKIE['userid'];
		
		$CurrentUserCoursesSQL = "	SELECT 
										`academic_student_courses`.`course_code` AS `course_code`,
										`academic_student_courses`.`section` AS `section`,
										`academic_course`.`course_name` AS `course_name`,
										`users`.`p_p` AS `p_p`,
										`users`.`name` AS `faculty_name`,
										`users`.`username` AS `faculty_username`,
										`academic_faculty_profile`.`id` AS `faculty_id`,
										`academic_faculty_profile`.`faculty_type` AS `faculty_type`,
										`academic_faculty_profile`.`phone` AS `faculty_phone`,
										`academic_faculty_profile`.`email` AS `faculty_email`,
										`academic_departments`.`name` AS `dept_name`
										
									FROM `academic_student_courses`
		
									INNER JOIN `academic_course` 
									ON `academic_student_courses`.`course_code` = `academic_course`.`course_code`
									
									INNER JOIN `academic_faculty_taken_course` 
									ON `academic_faculty_taken_course`.`course_code` = `academic_student_courses`.`course_code` 
									AND `academic_faculty_taken_course`.`section` = `academic_student_courses`.`section`
									AND `academic_faculty_taken_course`.`trimester_id` = `academic_student_courses`.`trimester_id`
									
									INNER JOIN `academic_faculty_profile` 
									ON `academic_faculty_profile`.`id` = `academic_faculty_taken_course`.`faculty_id` 

									INNER JOIN `users` 
									ON `users`.`id` = `academic_faculty_profile`.`user_id` 

									INNER JOIN `academic_departments` 
									ON `academic_departments`.`id` = `academic_faculty_profile`.`department_id` 

									WHERE `academic_student_courses`.`trimester_id`='$CurrentTrimesterID'
									AND `academic_student_courses`.`student_id`='$CurrentUserID'
									AND `academic_faculty_taken_course`.`course_code`='$GetCourseCode'
									AND `academic_faculty_taken_course`.`section`='$GetSection'
									AND `academic_faculty_taken_course`.`faculty_id`='$GetFacultyID'";

		// Debugging statement
		//echo "Query: $CurrentUserCoursesSQL";
	
		if ($CurrentUserCoursesResult = mysqli_query($con, $CurrentUserCoursesSQL)) {
			if($CurrentUserCoursesSQLValue = mysqli_fetch_assoc($CurrentUserCoursesResult)) {
				//Push the course code and course name into the array
				$courseCode = $CurrentUserCoursesSQLValue['course_code'];
				$courseName = $CurrentUserCoursesSQLValue['course_name'];
				$courseSection = $CurrentUserCoursesSQLValue['section'];
				$courseFacultyName = $CurrentUserCoursesSQLValue['faculty_name'];
				$courseFacultyType = $CurrentUserCoursesSQLValue['faculty_type'];
				$courseFacultyPic = $CurrentUserCoursesSQLValue['p_p'];
				$courseFacultyDeptName = $CurrentUserCoursesSQLValue['dept_name'];
				$courseFacultyUsername = $CurrentUserCoursesSQLValue['faculty_username'];
				$courseFacultyPhone = $CurrentUserCoursesSQLValue['faculty_phone'];
				$courseFacultyEmail = $CurrentUserCoursesSQLValue['faculty_email'];
				$courseFacultyID = $CurrentUserCoursesSQLValue['faculty_id'];
			}
		} else {
			// Handle error here
			echo "Error: " . mysqli_error($con);
		}
		
					
		?>
								
								

		<?php
			$default_off_days_sql = mysqli_fetch_assoc(mysqli_query($con, "SELECT global_value FROM global_settings WHERE global_key='default_close_days'"))['global_value'];
			$default_off_days = explode(", ", $default_off_days_sql);
			$date = strtotime("today"); // start with today's date
			$endDate = strtotime("+14 day"); // end after 7 days
			$dateArray = array();

			while ($date <= $endDate) {
				$dayOfWeek = date("l", $date); // get the day of week for the current date
				if (!in_array($dayOfWeek, $default_off_days)) { // check if the day is not an off day
					$dateArray[] = array(
						'full_date' => date("D, F d, Y", $date),
						'php_format_date' => date('m/d/Y', $date)
					);
				}
				$date = strtotime("+1 day", $date); // move to the next day
			}

		?>												
				
				
				<?php
					//Check if student have any other slots booked or requested with the faculty 
					$facultywithCurrentUser = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `academic_counseling_requests` 
																					WHERE (`status`='approved' OR `status`='pending') 
																					AND `faculty_id`='$courseFacultyID' 
																					AND `student_id`='$current_user_student_id'"));													
				?>
				<?php if($facultywithCurrentUser >= 1){ ?>
					<?php
						$RequestInformation =mysqli_query($con, "SELECT * FROM `academic_counseling_requests` 
																 WHERE (`status`='approved' OR `status`='pending') 
																 AND `faculty_id`='$courseFacultyID' 
																 AND `student_id`='$current_user_student_id'
																 AND `course_code`='$GetCourseCode'
																AND  `section`='$GetSection'
																 ");
						if($row = mysqli_fetch_assoc($RequestInformation)){
					?>
					
					<?php
						if($row['status'] == 'approved'){
							
					?>
					<?php 
						$firstLoop = true;
						$today = '';
						foreach ($dateArray as $date) { 
							$attrib = '';
							$classname = '';
							if(isset($_GET['date']) && $date['php_format_date'] === $_GET['date']) {
								$classname = 'active';
								$attrib = "background: green; color: white;";
							} else if(empty($_GET['date']) && $firstLoop) {
								$classname = 'active';
								$attrib = "background: green; color: white;";
								$today = $date['php_format_date'];
								$firstLoop = false;
							}
						}
					?>
					<?php 

					?>
					
						<h3 id="statusHeader"><i class="fas fa-check-circle" style="color: #35c40e;"></i> Your Request is Approved!</h3>
						<p>
							<span id="statusDetail">Please stay present in the designated room within 10 minutes of starting the time slot!</span></br>
							<hr>
							
							
							<table class="table table-dark" id="users-table">
							  <thead>
								<tr>
									<th scope="col">Slot No.</th>
									<th scope="col"></th>
									<th scope="col">Date</th>
									<th scope="col">Start Time</th>
									<th scope="col">End Time</th>
									<th scope="col">Room</th>
								</tr>
							  </thead>
							  <tbody>
								<?php

									mysqli_set_charset($con, "utf8");
									$sql        = "	SELECT *
													FROM `academic_counseling_requests`
													INNER JOIN `academic_counseling_hours`
													ON `academic_counseling_requests`.`hour_id` = `academic_counseling_hours`.`id`
													WHERE (`academic_counseling_requests`.`status`='approved' OR `academic_counseling_requests`.`status`='pending') 
													AND `academic_counseling_requests`.`faculty_id`='$courseFacultyID' 
													AND `academic_counseling_requests`.`student_id`='$current_user_student_id'
													AND `academic_counseling_requests`.`course_code`='$GetCourseCode'
													AND  `academic_counseling_requests`.`section`='$GetSection'
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

											echo '<script>console.log("this slot-'.$timeslot.'")</script>';
											echo '<script>console.log("today-'.$today.'")</script>';

											// Check if current time is within time slot and date is today
											if ($currentDate == $timeslotDateStr) {
												if ($currentTime >= $startTime && ($currentTime - $startTime) <= $gracePeriod) {
													$dateMessage = "The Time is now!";
												} elseif ($currentTime >= $startTime) {
													$dateMessage = "You are late!";
													$userIsLate = true;
												} else {
													$dateMessage = "Today, Still Some Time Left";
												}
											} elseif (strtotime($timeslotDate) > strtotime($currentDate)) {
												$dateMessage = "Tomorrow";
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
										<script>
										 document.getElementById('statusHeader').innerHTML = "<i class=\"fas fa-check-circle\" style=\"color: red;\"></i> Your Request was Approved and you were Late!";
										 document.getElementById('statusDetail').innerHTML = "Cancel The Request and Try Requesting again! Cancellation will cost you 20 Merits.";
										</script>
									<?php } ?>
											<td><?= $rows['id']; ?></td>
											<td>
												<?= $dateMessage; ?>
												</br>
												<?php if(!$userIsLate  && $currentTime >= $startTime && ($currentTime - $startTime) <= $gracePeriod){ ?>
												<span id="countdown"></span>
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
													  document.getElementById("countdown").innerHTML = "Grace period has ended.";
													  document.getElementById('statusHeader').innerHTML = "<i class=\"fas fa-check-circle\" style=\"color: red;\"></i> Your Request was Approved and you were Late!";
													  document.getElementById('statusDetail').innerHTML = "Cancel The Request and Try Requesting again! Cancellation will cost you 20 Merits.";
													  document.getElementById('headerTableTr').classList.remove("alert-success");
													  document.getElementById('headerTableTr').classList.add("alert-danger");
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
													document.getElementById("countdown").innerHTML = countdownStr;
												  }, 1000);
												</script>
												<?php } ?>
											</td>
											<td>
												<?= date("D, F d, Y", strtotime($rows['request_date'])) ?>
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

										</tr>
									<?php
										}
									}
								?>
							  </tbody>
							</table>
							
							
							<hr>
							<small>
							If you want to cancel the counselling after getting approved,</br>
							Please Write the specific reasons for cancelation. </br>
							<i>Too much Frequent cancellation after approval might reduce your merit and might block you from this system.</i>
							</small>
						</p>
					<?php } ?>
					
					<?php
						if($row['status'] == 'pending'){
					?>
						<h3><i class="fas fa-hourglass-half fa-spin" style="color: #fbb904;"></i>&nbsp; Your Request is Pending!</h3>
						<p>
							Please wait until the faculty approves your request.</br>
							<hr>
							
							
							
							<table class="table table-dark" id="users-table">
							  <thead>
								<tr>
									<th scope="col">Slot No.</th>
									<th scope="col"></th>
									<th scope="col">Date</th>
									<th scope="col">Start Time</th>
									<th scope="col">End Time</th>
									<th scope="col">Room</th>
								</tr>
							  </thead>
							  <tbody>
								<?php

									mysqli_set_charset($con, "utf8");
									$sql        = "	SELECT *
													FROM `academic_counseling_requests`
													INNER JOIN `academic_counseling_hours`
													ON `academic_counseling_requests`.`hour_id` = `academic_counseling_hours`.`id`
													WHERE (`academic_counseling_requests`.`status`='approved' OR `academic_counseling_requests`.`status`='pending') 
													AND `academic_counseling_requests`.`faculty_id`='$courseFacultyID' 
													AND `academic_counseling_requests`.`student_id`='$current_user_student_id'
													AND `academic_counseling_requests`.`course_code`='$GetCourseCode'
													AND  `academic_counseling_requests`.`section`='$GetSection'
													";
									$result		= mysqli_query($con, $sql);
									if(!$result){
										echo mysqli_error($con);
									}
									else{
										while($rows=mysqli_fetch_array($result)){
											$hourID = $rows['id'];
									?>	
									
									<?php 
										$firstLoop = true;
										$today = '';
										foreach ($dateArray as $date) { 
											$attrib = '';
											$classname = '';
											if(isset($_GET['date']) && $date['php_format_date'] === $_GET['date']) {
												$classname = 'active';
												$attrib = "background: green; color: white;";
											} else if(empty($_GET['date']) && $firstLoop) {
												$classname = 'active';
												$attrib = "background: green; color: white;";
												$today = $date['php_format_date'];
												$firstLoop = false;
											}
										} 
									?>

										<tr class="alert alert-warning">
											<td><?= $rows['id']; ?></td>
											<td>
												<?php
												
												// Current time and date
												$currentTime = strtotime('now');
												$currentDate = date('m/d/Y', $currentTime);

												// Time slot and date
												//$timeslot = "6:00 AM - 7:30 AM";
												//$date = "04/21/2023";
												
												$timeslot = $rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime'].' - '.$rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime'];
												$date = $today;
												
												echo '<script>console.log("this slot-'.$timeslot.'")</script>';
												echo '<script>console.log("today-'.$today.'")</script>';

												// Parse time slot
												$timeslotArr = explode(' - ', $timeslot);
												$startTime = strtotime($timeslotArr[0]);
												$endTime = strtotime($timeslotArr[1]);
												
												echo '<script>console.log("start time-'.$timeslotArr[0].'")</script>';
												echo '<script>console.log("end time-'.$timeslotArr[1].'")</script>';

												// Check if current time is within time slot and date is today
												if (($currentTime >= $startTime && $currentTime <= $endTime) && ($date == $currentDate)) {
												  echo "💻 Slot is Running 👉";
												}
												?>	
											</td>
											<td>
												<?= date("D, F d, Y", strtotime($rows['request_date'])) ?>
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

										</tr>
									<?php
										}
									}
								?>
							  </tbody>
							</table>
							
							
							
							<hr>
							<small>
							Faculty member can reschedule or reject any requests. If your request is rescheduled, you will get notified.</br>
							If your request gets rejected, you can request again.</br>
							<i>Too much Frequent cancellation after approval might reduce your merit and might block you from this system.</i>
							</small>
						</p>
					<?php } ?>
					
					</br>

					<a href="dashboard.php" class="btn btn-success"><i class="fa fa-home" aria-hidden="true"></i> Back to Dashboard</a>
					<?php if($row['status'] == 'pending'){?>
					<button onclick="aurnaIframe('edit_request.php?section=<?= $row['section']; ?>&faculty_id=<?= $row['faculty_id']; ?>&hour_id=<?= $row['hour_id']; ?>&course_code=<?= $row['course_code']; ?>&request_date=<?= $row['request_date']; ?>')" class="btn btn-warning"><i class="fa fa-edit" aria-hidden="true"></i> Edit Request Information</button>
					<?php } ?>
					<button onclick="cancelRequest('request_action.php?action=cancel&section=<?= $row['section']; ?>&faculty_id=<?= $row['faculty_id']; ?>&hour_id=<?= $row['hour_id']; ?>&course_code=<?= $row['course_code']; ?>&request_date=<?= $row['request_date']; ?>')" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> Cancel Request</button>
					<script>
					function cancelRequest(url){
						bootbox.confirm({
							message: 'Are you sure Cancelling The Request?',
							buttons: {
								confirm: {
									label: 'Yes',
									className: 'btn-success'
								},
								cancel: {
									label: 'No',
									className: 'btn-danger'
								}
							},
							callback: function (result) {
							 if(result == true){
								window.location.href = url;
							 }
							},
							backdrop: true
						});
					}
						
					</script>
				
					
					<?php }
					}else{ ?>
					<span style="display: inline-block; vertical-align: middle">
						<img style="height: 140px" src="images/OIP.png"/>
					</span>
					<span style="display: inline-block; vertical-align: middle; margin-left: 30px;">
					<h1>No Requests!</h1>
					<p>
						</br>
						You currently have no pending or approved request to this faculty.</br>
						Any Pending or Approved Counselling Hour Request to This Faculty for This Course will appear here.</br>
						Create a Request for time slot. The status and other options will appear here.
						</br>
						</br>
						<i>Happy Communicating!</i>
						</br>
						</br>
						<button onclick="aurnaIframe('../chat/chat.php?user=<?php echo $courseFacultyUsername; ?>&medical=true')" class="btn btn-success"><i class="fa fa-comment" aria-hidden="true"></i> Talk with The Faculty</button>
						<a href="dashboard.php" class="btn btn-success"><i class="fa fa-home" aria-hidden="true"></i> Back to Dashboard</a>
					</p>
					</span>
				<?php } ?>
								
								
								



<?php } else {
	echo "<script>window.open('login.php','_self')</script>";
} ?>