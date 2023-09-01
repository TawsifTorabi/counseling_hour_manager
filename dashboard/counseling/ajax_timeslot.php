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
			$firstLoop = true;
			$today = '';
			foreach ($dateArray as $date) { 
				$attrib = '';
				$classname = '';
				if(isset($_GET['date']) && $date['php_format_date'] === $_GET['date']) {
					$classname = 'active';
					$attrib = "background: green; color: white;";
					$today = $_GET['date'];
				} else if(empty($_GET['date']) && $firstLoop) {
					$classname = 'active';
					$attrib = "background: green; color: white;";
					$today = $date['php_format_date'];
					$firstLoop = false;
				}
			}
		?>
		
		
											<?php
												mysqli_set_charset($con, "utf8");
												$sql        = "	SELECT *
																FROM `academic_counselling_faculty_selected_hours`
																INNER JOIN `academic_counseling_hours`
																ON `academic_counseling_hours`.`id` = `academic_counselling_faculty_selected_hours`.`hour_id`
																WHERE `academic_counselling_faculty_selected_hours`.`faculty_id`= '$courseFacultyID'
																ORDER BY `academic_counseling_hours`.`id` ASC";
												$result		= mysqli_query($con, $sql);
												if(!$result){
													echo mysqli_error($con);
												}
												else{
													while($rows=mysqli_fetch_array($result)){
														$hourID = $rows['id'];
														//Check if there are any requests for this slot.
														$thishourRequests = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `academic_counseling_requests` 
																												WHERE `request_date`='$today' 
																												AND `faculty_id`='$courseFacultyID' 
																												AND (`status`='pending' OR `status`='approved') 
																												AND `hour_id`='$hourID'"));
														
																												
														//Check if the request for this slot is approved by the faculty
														$thishourApproved = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `academic_counseling_requests` 
																												WHERE `request_date`='$today' 
																												AND `faculty_id`='$courseFacultyID' 
																												AND `status`='approved' 
																												AND `hour_id`='$hourID'"));
														
														//Check if This slot is requested by the current user
														$thishourforCurrentUser = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `academic_counseling_requests` 
																														WHERE `request_date`='$today' 
																														AND (`status`='approved' OR `status`='pending') 
																														AND `faculty_id`='$courseFacultyID' 
																														AND `hour_id`='$hourID' 
																														AND `student_id`='$current_user_student_id'"));
														
														
																														
														//Check if student have any other slots booked or requested with the faculty 
														$facultywithCurrentUser = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `academic_counseling_requests` 
																														WHERE (`status`='approved' OR `status`='pending') 
																														AND `faculty_id`='$courseFacultyID' 
																														AND `student_id`='$current_user_student_id'"));
														
														

														//Check if student have same slots booked or requested with other faculty 
														$OtherfacultywithCurrentUser = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `academic_counseling_requests` 
																														WHERE (`status`='approved' OR `status`='pending') 
																														AND `faculty_id`!='$courseFacultyID' 
																														AND `student_id`='$current_user_student_id'
																														AND `hour_id`='$hourID'
																														AND `request_date`='$today'"));
														
														//Default Declarations
														//If the slot is available
														$Availability = false;
														//If the request for the slot is approved by the faculty														
														$Approved = false;
														//If the Slot is accupied by the current user
														$currentUsersSlot = false;
														//If the student taken other counseling hour with the same faculty.
														$currentUserswithFaculty = false;
														//If the student taken other counseling hour with other faculty in the same time slot.
														$currentUserswithOtherFaculty = false;
														
														
														//This Hour Booked By Current User
														if($thishourforCurrentUser >= 1){
															$currentUsersSlot = true;
														}
														
														//Current User Have Other Slots With This Faculty
														if($facultywithCurrentUser >= 1){
															$currentUserswithFaculty = true;
														}

														//Current User Have The Same Slots in Same Day With Other Faculty
														if($OtherfacultywithCurrentUser >= 1){
															$currentUserswithOtherFaculty = true;
														}
														
														//Check if there are any public requests for this slot.
														if($thishourRequests <= 0){
															//Pubic Request for This Slot is Found, make it un-available
															$Availability = true;
														}else{
															
															$Availability = false;
															if($thishourApproved >= 1){
																//This Slot is Booked, make it un-available
																$Availability = false;
																//Make it Red marked
																$Approved = true;
															}else{
																//This slot is not available and not approved
																$Availability = false;
																$Approved = false;
															}
														}
												?>	
												
												
												<?php if($Availability == true){?>
													<tr class="alert alert-success">
													<?php
														//Green
													?>
												<?php } ?>
												
												<?php if($Availability == false && $Approved == true){ ?>
													<tr class="alert alert-danger">
													<?php
														//Red
													?>
												<?php } ?>
												
												<?php if($Availability == false && $Approved == false){ ?>
													<tr class="alert alert-warning">
													<?php
														//Yellow
													?>
												<?php } ?>
												

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
															<?= $rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime'] ?></br>
															<?=  cusgetTimeOfDay($rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime']); ?>
														</td>
														<td>
															<?= $rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime'] ?></br>
															<?=  cusgetTimeOfDay($rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime']); ?>
														</td>
														<td>
														<?php if($Availability && !$currentUserswithFaculty && !$currentUserswithOtherFaculty){
																
															// Check if current time is within time slot and date is today
															if ($date == $currentDate) {
															  if ($currentTime >= ($startTime+900) && $currentTime <= $endTime) {
																echo "<button disabled class='btn btn-info'>Slot Running, Can't book</button>";
															  } elseif ($currentTime > $endTime) { 
																//If The Current time has passed the $endTime and the date is current date
																echo "<button disabled class='btn btn-danger'>Time is Out</button>";
															  } else {
																?>
																<a href="javascript:confirmRequest('<?=$hourID?>','<?=$today;?>','<?=$courseFacultyID;?>','<?=$GetCourseCode;?>','<?=$GetSection;?>')" class="btn btn-success">Request This Slot</a>																	
																<?php
															  }
															} else {
															  ?>
															  <a href="javascript:confirmRequest('<?=$hourID?>','<?=$today;?>','<?=$courseFacultyID;?>','<?=$GetCourseCode;?>','<?=$GetSection;?>')" class="btn btn-success">Request This Slot</a>																	
															  <?php
															} ?>
														
														<?php }else if($Approved){ ?>
															<button class="btn btn-danger" disabled>Slot Taken!</button>
														<?php }else if(!$Availability && !$Approved){ ?>
															<button class="btn btn-warning" disabled>Request Pending</button>
														<?php }else if($Availability && $currentUserswithOtherFaculty){ ?>
															<button class="btn btn-warning" disabled>🌚 Slot Collides!!!</button>
														<?php } ?>
														</td>
														<td>
														<?php 	if(!$Availability){ 

																	$taken_user_details = mysqli_query($con, "SELECT * FROM `academic_counseling_requests`
																												INNER JOIN `academic_student_profile`
																												ON `academic_student_profile`.`id` = `academic_counseling_requests`.`student_id`
																												INNER JOIN `users`
																												ON `academic_student_profile`.`user_id` = `users`.`id`
																												WHERE (`status`='approved' OR `status`='pending') 
																												AND `academic_counseling_requests`.`faculty_id`='$courseFacultyID' 
																												AND `academic_counseling_requests`.`request_date`='$today' 
																												AND `academic_counseling_requests`.`hour_id`='$hourID' 																												
																											");
																													
																	if($row = mysqli_fetch_assoc($taken_user_details)){
																	?>	
																		<small>Slot Booked by: </small> <?= $row['name'] ?> - <?= $row['username'] ?>
																		
																	<?php } 
																} 												
														?>
														<?php 	if($Availability && $currentUserswithOtherFaculty){

																	$taken_user_details = mysqli_query($con, "SELECT * FROM `academic_counseling_requests`
																												INNER JOIN `academic_faculty_profile`
																												ON `academic_faculty_profile`.`id` = `academic_counseling_requests`.`faculty_id`
																												INNER JOIN `users`
																												ON `academic_faculty_profile`.`user_id` = `users`.`id`
																												WHERE (`status`='approved' OR `status`='pending')  
																												AND `academic_counseling_requests`.`request_date`='$today' 
																												AND `academic_counseling_requests`.`student_id`='$current_user_student_id' 																												
																												AND `academic_counseling_requests`.`hour_id`='$hourID' 																												
																											");
																													
																	if($row = mysqli_fetch_assoc($taken_user_details)){
																	?>	
																		<span style="width: 60px; height: 60px;" class="circular_image"><img src="../../uploads/<?php echo $row['p_p']; ?>" class="img-responsive" style="width:100%; display: inline-block;" alt="Image"></span>
																		<span style="display: inline-block; vertical-align: middle;">
																			<small>You Requested This Time Slot to faculty... </small></br> 
																			<b><?= $row['name'] ?></b> - <?= $row['username'] ?>
																		</span>
																		
																	<?php } 
																} 												
														?>
														</td>
													</tr>
												<?php
													}
											}
										}
									
											?>