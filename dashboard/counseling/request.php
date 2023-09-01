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





if (mysqli_num_rows(mysqli_query($con, "select * from sessions where session_id='$getsessionID' AND validity='$validity'")) > 0) {

	include("model/UserCheck.php");
	echo $CurrentUserFaculty;
	if ($CurrentUserFaculty == 1) {
		header('Location: faculty/index.php');
		exit();
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



	?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<title>Request - Counseling Hour Manager</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/aurna-lightbox.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
			integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
			crossorigin="anonymous" referrerpolicy="no-referrer" />
		<style>
			#TokenCounter {
				font-family: monospace;
			}

			/* Set height of the grid so .sidenav can be 100% (adjust as needed) */
			.row.content {
				height: 550px
			}

			/* Set gray background color and 100% height */
			.sidenav {
				background-color: #f1f1f1;
				position: -webkit-sticky;
				position: fixed;
				top: 0;
				height: 100vh;
				z-index: 9;
			}

			/* On small screens, set height to 'auto' for the grid */
			@media screen and (max-width: 767px) {
				.row.content {
					height: auto;
				}
			}

			@media screen and (min-width: 768px) {
				.main {
					width: 75%;
					margin-left: 25%;
				}
			}

			.button-10 {
				align-items: center;
				padding: 6px 14px;
				font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
				border-radius: 6px;
				border: none;
				color: #fff;
				background: linear-gradient(180deg, #4B91F7 0%, #367AF6 100%);
				background-origin: border-box;
				box-shadow: 0px 0.5px 1.5px rgb(54 122 246 / 25%), inset 0px 0.8px 0px -0.25px rgb(255 255 255 / 20%);
				user-select: none;
				-webkit-user-select: none;
				touch-action: manipulation;
				transition: 0.9s;
				font-size: 20px;
				margin: 7px 0px 7px 0px;
			}

			.button-10:disabled {
				color: #979797;
				background: linear-gradient(180deg, #333 0%, #777 100%);
				opacity: 0.3;
			}

			.blinking_live {
				height: 15px;
				width: 15px;
				border-radius: 15px;
				background: #db0a0a;
				color: white;
				padding: 2px 13px 2px 13px;
				font-size: 15px;
				animation: blink-live 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
				vertical-align: text-bottom;
				font-weight: bold;
			}

			@keyframes blink-live {

				0% {
					opacity: 1.0;
				}

				50% {
					opacity: 0.0;
				}

				100% {
					opacity: 1.0;
				}
			}

			.button-boot {
				cursor: pointer;
				outline: 0;
				display: inline-block;
				font-weight: 400;
				line-height: 1.5;
				text-align: center;
				background-color: transparent;
				border: 1px solid transparent;
				padding: 6px 12px;
				font-size: 1.3rem;
				border-radius: .25rem;
				transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
				color: #0d6efd;
				border-color: #0d6efd;
				text-decoration: none;
			}

			.button-boot:hover {
				color: #fff;
				background-color: #0d6efd;
				border-color: #0d6efd;
				text-decoration: none;
			}

			.button-boot-active {
				color: #fff;
				background-color: #0d6efd;
				border-color: #0d6efd;
				text-decoration: none;
			}

			.button-pay {
				border: 0;
				outline: 0;
				cursor: pointer;
				color: white;
				background-color: rgb(84, 105, 212);
				box-shadow: rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(0 0 0 / 12%) 0px 1px 1px 0px, rgb(84 105 212) 0px 0px 0px 1px, rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(60 66 87 / 8%) 0px 2px 5px 0px;
				border-radius: 4px;
				font-size: 14px;
				font-weight: 500;
				padding: 4px 8px;
				display: inline-block;
				min-height: 28px;
				transition: background-color .24s, box-shadow .24s;
			}

			.button-pay:hover {
				box-shadow: rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(0 0 0 / 12%) 0px 1px 1px 0px, rgb(84 105 212) 0px 0px 0px 1px, rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(60 66 87 / 8%) 0px 3px 9px 0px, rgb(60 66 87 / 8%) 0px 2px 5px 0px;
			}

			.msg-bar-red {
				padding: 3px 8px 3px 8px;
				background: #cb1f36;
				border-radius: 5px;
				color: white;
				border-bottom: 2px solid #8f0909;
				box-shadow: 0px 3px 4px #00000057;
			}

			.msg-bar-green {
				padding: 3px 8px 3px 8px;
				background: #01b559;
				border-radius: 5px;
				color: white;
				border-bottom: 2px solid #008d45;
				box-shadow: 0px 3px 4px #00000057;
			}

			.appointmentTitle {
				font-family: Trebuchet MS;
				font-weight: bold;
				font-size: 22px;
				color: white;
				background: linear-gradient(359deg, #9b0b0b, #d50b0b);
				padding: 4px 24px 4px 24px;
				border-radius: 7px;
				border-bottom: 3px solid #6e0000;
				box-shadow: 0px 5px 8px #00000075;
				margin-bottom: 18px;
				line-height: 60px;
			}
		</style>
	</head>

	<body>
		<script src="js/aurna-lightbox.js"></script>
		<nav class="navbar navbar-inverse visible-xs">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Logo</a>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
						<li class="active"><a href="#">Dashboard</a></li>
						<li><a href="#">Age</a></li>
						<li><a href="#">Gender</a></li>
						<li><a href="#">Geo</a></li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="container-fluid">
			<div class="row content">
				<div class="col-sm-3 sidenav hidden-xs">
					<h2><i class="fa-solid fa-notes-medical"></i> Counseling Hour Manager</h2>
					<a href="javascript:void(0);">
						<?php
						$userid = $_COOKIE['userid'];
						if ($conn->query("SELECT name FROM users WHERE id='$userid'")->num_rows > 0) {
							// output data of each row
							if ($row = $conn->query("SELECT name FROM users WHERE id='$userid'")->fetch_assoc()) {
								echo "<span>Welcome! <strong>" . $row['name'] . "</strong></span>";
							}
						} else {
							echo "<b>Something Went Wrong!</b>";
						}
						?>
					</a>&nbsp;

					</br>
					</br>
					<ul class="nav nav-pills nav-stacked">
						<li><a href="../"><i class="fa-solid fa-house"></i> Back to Homepage</a></li>
						<li class="active"><a href="dashboard.php"><i class="fa-solid fa-laptop"></i> Dashboard</a></li>
						<li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
					</ul><br>
				</div>
				<br>

				<div class="col-sm-9 main">

					<div class="row">

						<div class="col-sm-12">
							<p>
							<div class="row">
								<h1 style="margin-left: 20px;"><i class="fa-solid fa-laptop"></i> Request Counseling Hour</h1></br>
								
								<?php include("model/meritCheck.php"); ?>
								
								<?php
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
								
								

									<div class="col-sm-12">
											<p>
												<span>	
													<span style="display: inline-block; margin-right: 20px;">
														<b>Current Trimester:</b></br>
														<?= $CurrentTrimester; ?>
													</span>
													
													<span style="display: inline-block; margin-right: 20px;">
														<b>Your Merit:</b></br>
														<?php $merit_point = mysqli_fetch_assoc(mysqli_query($con, "SELECT `counseling_merit` FROM `academic_student_profile` WHERE `id`='$current_user_student_id'"))['counseling_merit']; ?>
														<?php if($merit_point <= 30){ 
															echo"<b style='color: red;'>".$merit_point."</b>";?> <a href="#" data-toggle="tooltip" data-placement="right" title="Further Merit Point Decrement will result a block from this system."><i class="fas fa-question-circle"></i></a> <?php }else{ echo $merit_point;} ?>
													</span>
													<script>
														$(document).ready(function(){
															$('[data-toggle="tooltip"]').tooltip();   
														});
													</script>
													<span style="display: inline-block; margin-right: 20px;">
														<b>Current Date:</b></br>
														<?php  $currentTime = strtotime('now');
															   $currentDate = date('m/d/Y', $currentTime); 
															   echo $currentDate; ?>
													</span>
												</span>
												</br>
												<style>
													.circular_image {
													  width: 100px;
													  height: 100px;
													  border-radius: 50%;
													  overflow: hidden;
													  background-color: #3ebc3e;
													  border: 3px solid #3ebc3e;
													  box-shadow: -1px 6px 5px 2px #00000033;
													  /* commented for demo
													  float: left;
													  margin-left: 125px;
													  margin-top: 20px;
													  */
													  
													  /*for demo*/
													  display:inline-block;
													  vertical-align:middle;
													}
													.circular_image img{
													  width:100%;
													}
													.portfolio-elements {
														padding: 27px;
														background: #f5f5f573;
														box-shadow: 0px 6px 17px #00000038;
														border-bottom: 3px solid green;
														margin: 8.1px;
													}
													
													.image-portfolio {
														height: 150px;
														display: inherit;
														overflow: hidden;
														margin-bottom: 20px;
													}
													
													.used{
														display: inline-block;
														margin-left: 5px;
														vertical-align: middle;
													}
													.facultyName{
														font-weight: bold;
														font-size: 18px;
														font-family: Trebuchet MS;
													}	
													.facultyType{
														font-size: 15px;
													}
													.facultySchl{
														font-size: 11px;
													}
												</style>
												<span>	

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
														<div style="height: 441px;" class="col-sm-4 portfolio-elements">
															<h1><?php echo $courseName.' ('.$courseSection.')'; ?></h1>
															<h3>Course Code:<?= $courseCode; ?> | Section: <?= $courseSection;?></h3>
															</br>
															<p class="detail-project">Course Conducted by,</p>
															<span class="circular_image" style="height: 100px;width: 100px;"><img src="../../uploads/<?php echo $courseFacultyPic; ?>" class="img-responsive" style="width:100%" alt="Image"></span>
															<span class="used">
																<span class="facultyName"><?php echo $courseFacultyName; ?></span></br>
																<span class="facultyType"><?php echo $courseFacultyType; ?></span></br>
																<span class="facultySchl"><?php echo $courseFacultyDeptName; ?></span></br>
																<span class="facultySchl"><?php echo $courseFacultyPhone; ?></span></br>
																<span class="facultySchl"><?php echo $courseFacultyEmail; ?></span></br>
															</span>
															</br>
															<span style="float: right;">
																<button onclick="aurnaIframe('../chat/chat.php?user=<?php echo $courseFacultyUsername; ?>&medical=true')" class="btn btn-success"><i class="fa fa-comment" aria-hidden="true"></i> Send Message</button>
															</span>
														</div>
														
														
														<div style="height: 441px;" class="col-sm-7 portfolio-elements" id="requestUpdateArea">
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
														</div>
												</span></br>
											</p>
									</div>								
								
								

									<div class="col-sm-12">																								
										<h4>Slot Dates</h4>
										<small>Weekends and Close Days are not Included, 30 Min Slots are allowed for each.</small>
										</br>
										</br>
										<ul class="nav nav-pills">
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
											
											?>
												<li><button style="border-bottom: 2px solid green; <?= $attrib; ?>" data-date="<?= $date['php_format_date']; ?>" class="btn btn-light <?= $classname; ?>" onclick="location.href='<?php echo $_SERVER['PHP_SELF'] . '?' . http_build_query(array_merge($_GET, ['date' => $date['php_format_date']])); ?>'"><?= $date['full_date'] ?></button></li>
											<?php } ?>
										</ul>	
										</br>

										
										<?php 
											if(isset($_GET['date']) && !empty($_GET['date'])){
												$date = mysqli_real_escape_string($con, $_GET['date']);

												// Check if date is in correct format
												if (!preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $date, $matches)) {
												  echo "Date format is incorrect. Please use format MM/DD/YYYY.";
												  exit;
												}

												// Check if day of week is not Thursday, Friday or Monday
												$dayOfWeek = date('l', strtotime($date));
												if (in_array($dayOfWeek, $default_off_days)) {
												  echo "Sorry, counseling sessions are not available on " . $dayOfWeek . ".";
												  exit;
												}

												// Check if date is not in the past
												if (strtotime($date) < strtotime(date('Y-m-d'))) {
												  echo "Sorry, you cannot book a session for a past date.";
												  exit;
												}
												
												$today = mysqli_real_escape_string($con, $_GET['date']);
												
											}
										?>
										</br>
										Select Custom Date:
										<input value="<?= date('Y-m-d', strtotime($today)); ?>" style="width: 200px"; class="btn alert-success"  type="date" id="myDate">
										<input value="<?= date('m/d/Y', strtotime($today)); ?>" type="hidden" id="myDateHidden">
										<script>console.log("Input Value 1:<?= date('Y-m-d', strtotime($today)); ?>");</script>
										<script>console.log("Input Value 2:<?= date('m/d/Y', strtotime($today)); ?>");</script>
										<button class="btn btn-success" id="goButton">GO</button>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span style="border-bottom: 5px solid green; padding: 6px;">Showing Slots for: <b style="color: green;"><?= date("D, F d, Y", strtotime($today)) ?></b></span>
										<script>
											// define the close days as an array of weekday names
											var close_days_names = <?= json_encode($default_off_days); ?>;

											// convert the close days to an array of weekday indices
											var close_days = close_days_names.map(function(day) {
												return ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"].indexOf(day);
											});

											// get a reference to the input field and the "GO" button
											var myDate = document.getElementById("myDate");
											var myDateHidden = document.getElementById("myDateHidden");
											var goButton = document.getElementById("goButton");
											var backupDate = "<?= date('Y-m-d', strtotime($today)); ?>";
											var backupDateHidden = "<?= date('m/d/Y', strtotime($today)); ?>";

											// add an event listener for when the user changes the date
											myDate.addEventListener("change", function() {
												// get the selected date
												var selectedDate = new Date(this.value);

												// get the day of the week (0 for Sunday, 1 for Monday, etc.)
												var dayOfWeek = selectedDate.getDay();
												const today = new Date();

												if (selectedDate < today) {
													this.value = backupDate;			
													myDateHidden.value = backupDateHidden;			
													bootbox.alert({
														message: "<h3>⚠️ Date is too from the Past!</h3>",
														backdrop: true
													});
												}else{
												
													// check if the selected date is a close day
													if (close_days.includes(dayOfWeek)) {
													  // find the next available close day
													  var nextCloseDay = getNextCloseDay(selectedDate);

													  // update the input field with the next close day
													  console.log("GOING myDate Value: "+formatDateForInput(nextCloseDay));
													  console.log("GOING myDateHidden Value: "+formatDateForPhp(nextCloseDay));
													  myDateHidden.value = formatDateForPhp(nextCloseDay);
													  this.value = formatDateForInput(nextCloseDay);

													  // show a message to inform the user
													  //alert("The selected date is a close day and has been changed to " + ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"][nextCloseDay.getDay()] + ".");
													  bootbox.alert({
														message: "<h5>⚠️ The selected date is a close day and has been changed to " + ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"][nextCloseDay.getDay()] + ".</h5>",
														backdrop: true
														});
													  console.log("Javascipt Corrected");
													  console.log("myDate Value: "+myDate.value);
													  console.log("myDateHidden Value: "+myDateHidden.value);			    
													  
													}else{
														// enable the "GO" button				
													   myDateHidden.value = formatDateForPhp(selectedDate);
													   this.value = formatDateForInput(selectedDate);													
													  console.log("All Is Correct");
													  console.log("myDate Value: "+myDate.value);
													  console.log("myDateHidden Value: "+myDateHidden.value);			  
													}
												}

											});

											// add an event listener for when the "GO" button is clicked
											goButton.addEventListener("click", function() {
												// get the current URL
												var currentUrl = new URL(window.location.href);

												// get the current "date" query parameter, if it exists
												var currentDateParam = currentUrl.searchParams.get("date");

												// get the selected date
												var selectedDate = myDateHidden.value;
												console.log("goButton clicked: "+selectedDate);

												// set the "date" query parameter to the formatted date
												currentUrl.searchParams.set("date", selectedDate);
												console.log(currentUrl.toString());
												// navigate to the updated URL
												window.location.href = currentUrl.toString();
											});


											// function to find the next available close day
											function getNextCloseDay(date) {
											  // iterate over the next 7 days
											  for (var i = 1; i <= 7; i++) {
												// get the date of the next day
												var nextDay = new Date(date.getTime() + i * 24 * 60 * 60 * 1000);

												// get the day of the week
												var dayOfWeek = nextDay.getDay();

												// check if the next day is not a close day
												if (!close_days.includes(dayOfWeek)) {
												  return nextDay;
												}
											  }

											  // if no next close day is found within the next 7 days, return null
											  return null;
											}

											// function to format a date as yyyy-mm-dd (the format used by the input field)
											function formatDateForInput(date) {
											  var year = date.getFullYear();
											  var month = date.getMonth() + 1;
											  if (month < 10) month = "0" + month;
											  var day = date.getDate();
											  if (day < 10) day = "0" + day;
											  console.log("formatDateHTML: "+year+ "-" + month  + "-" + day);
											  return year+ "-" + month  + "-" + day;
											}
											function formatDateForPhp(date) {
											  var year = date.getFullYear();
											  var month = date.getMonth() + 1;
											  if (month < 10) month = "0" + month;
											  var day = date.getDate();
											  if (day < 10) day = "0" + day;
											  console.log("formatDatePHP: "+month + "/" + day + "/" + year);
											  return month + "/" + day + "/" + year;
											}

										</script>				
										<script>
											function confirmRequest(hour_id, date, faculty_id, course_code, section){
												bootbox.confirm('Are you sure booking this slot?', function(result){
													if(result == true){
														aurnaIframe('send_request.php?hour_id='+hour_id+'&request_date='+date+'&faculty_id='+faculty_id+'&course_code='+course_code+'&section='+section);
													}
												});
											}
										</script>								
										
										
										<table class="table table-dark" id="users-table">
										  <thead>
											<tr>
												<th scope="col">Slot No.</th>
												<th scope="col"></th>
												<th scope="col">Start Time</th>
												<th scope="col">End Time</th>
												<th scope="col">#</th>
												<th scope="col"></th>
											</tr>
										  </thead>
										  <tbody id="slotUpdateArea">
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
											?>
										  </tbody>
										</table>	
									</div>	      	

								
								
							</div>
							
							</p>

						</div>
					</div>
				</div>


	</body>

	</html>

	<script>
		String.prototype.isMatch = function (s) {
			return this.match(s) !== null
		}
		
		var requestUpdateArea = document.getElementById('requestUpdateArea');
		var requestUpdateAreaElem = document.getElementById('requestUpdateArea').innerHTML;

		
		var slotUpdateArea = document.getElementById('slotUpdateArea');
		var slotUpdateAreaElem = document.getElementById('slotUpdateArea').innerHTML;

		function requestUpdate() {
		  console.log('Checking... For Request Update');
		  const url = 'ajax_request.php'+window.location.search; // replace with your PHP page URL

		  // make AJAX request to PHP page to get updated table contents
		  const xhr = new XMLHttpRequest();
		  xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			  const newTableContent = this.responseText;
			  
			  // check if the new table content is different from the current table content
			  if (newTableContent !== requestUpdateAreaElem) {
				// update the table with the new content
				requestUpdateArea.innerHTML = newTableContent;
				requestUpdateAreaElem = newTableContent;
				console.log("Triggered! Request is Updated!");
			  }
			}
		  };
		  xhr.open('GET', url);
		  xhr.send();
		}

		function slotUpdate() {
		  console.log('Checking... For Timeslot Update');
		  const url = 'ajax_timeslot.php'+window.location.search; // replace with your PHP page URL

		  // make AJAX request to PHP page to get updated table contents
		  const xhr = new XMLHttpRequest();
		  xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			  const newTableContent = this.responseText;
			  
			  // check if the new table content is different from the current table content
			  if (newTableContent !== slotUpdateAreaElem) {
				// update the table with the new content
				slotUpdateArea.innerHTML = newTableContent;
				slotUpdateAreaElem = newTableContent;
				console.log("Triggered! Slots are Updated!");
			  }
			}
		  };
		  xhr.open('GET', url);
		  xhr.send();
		}
		
		setInterval(requestUpdate, 3000);
		setInterval(slotUpdate, 3000);

	</script>
	</body>

	</html>


<?php } else {
	echo "<script>window.open('login.php','_self')</script>";
} ?>