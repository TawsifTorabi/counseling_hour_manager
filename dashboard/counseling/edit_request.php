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
}else{
	exit();
}
//set the validity mode for session data
$validity = "valid";
//verify session id





if (mysqli_num_rows(mysqli_query($con, "select * from sessions where session_id='$getsessionID' AND validity='$validity'")) < 0) { exit(); }

			include("model/UserCheck.php");
			//echo $CurrentUserFaculty;
			if ($CurrentUserFaculty == 1) {
				header('Location: faculty/index.php');
				exit();
			}
			include("model/meritCheck.php");


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
			$GetHourId = mysqli_real_escape_string($con, $_GET['hour_id']);
			$GetRequestDate = mysqli_real_escape_string($con, $_GET['request_date']);
			$status = mysqli_real_escape_string($con, "pending");
			$GetCourseCode = mysqli_real_escape_string($con, $_GET['course_code']);
			$GetSection = mysqli_real_escape_string($con, $_GET['section']);
			$GetFacultyID =  mysqli_real_escape_string($con, $_GET['faculty_id']);
			$CurrentUserID = $_COOKIE['userid'];
			
			
			if(isset($_GET['update'])){
				if(!empty($_GET['update']) && $_GET['update']=='true'){
					$problem = mysqli_real_escape_string($con, $_POST['problem']);
					$alongwithStudentID = mysqli_real_escape_string($con, $_POST['alongwithStudentID']);
					$updateRequestSQL = "	UPDATE `academic_counseling_requests` 
												SET `problem`='$problem',
													`alongwith_student_id`='$alongwithStudentID'
												WHERE `academic_counseling_requests`.`trimester_id`='$CurrentTrimesterID'
												AND `academic_counseling_requests`.`student_id`='$current_user_student_id'
												AND `academic_counseling_requests`.`course_code`='$GetCourseCode'
												AND `academic_counseling_requests`.`section`='$GetSection'
												AND `academic_counseling_requests`.`faculty_id`='$GetFacultyID'";


					if ($updateRequestSQLResult = mysqli_query($con, $updateRequestSQL)) {
						if(!$updateRequestSQLResult){
							die();
						}else{
							echo "<script>
										parent.bootbox.alert('Update is Done.', function() {
											console.log('This was logged in the callback!');
											parent.hideIframe();
											parent.window.location.reload();
										});
										  //parent.bootbox.alert({message: '', backdrop: true});	
								  </script>";
						    exit();
						}
					}	
				}														
			}
			
			
			
			//Get the relation of user student with the faculty teacher 
			//parameterizing current trimester, student taken courses, faculty taken courses
			
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
	
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<title>UIU Question and Content Library</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/aurna-lightbox.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
		<script src="../medical/js/tinymce_6.2.0/tinymce/js/tinymce/tinymce.min.js" ></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
			integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
			crossorigin="anonymous" referrerpolicy="no-referrer" />
		<script>
		tinymce.init({
		  selector: 'textarea#problem',
		  plugins: 'image code',
		  promotion: false,
		  toolbar: 'undo redo | link image | code',
		  /* enable title field in the Image dialog*/
		  image_title: true,
		  /* enable automatic uploads of images represented by blob or data URIs*/
		  automatic_uploads: true,
		  /*
			URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
			images_upload_url: 'postAcceptor.php',
			here we add custom filepicker only to Image dialog
		  */
		  images_upload_url: 'ImageUploader.php',
		  file_picker_types: 'image',
		  /* and here's our custom image picker*/
		  file_picker_callback: function (cb, value, meta) {
			var input = document.createElement('input');
			input.setAttribute('type', 'file');
			input.setAttribute('accept', 'image/*');

			/*
			  Note: In modern browsers input[type="file"] is functional without
			  even adding it to the DOM, but that might not be the case in some older
			  or quirky browsers like IE, so you might want to add it to the DOM
			  just in case, and visually hide it. And do not forget do remove it
			  once you do not need it anymore.
			*/

			input.onchange = function () {
			  var file = this.files[0];

			  var reader = new FileReader();
			  reader.onload = function () {
				/*
				  Note: Now we need to register the blob in TinyMCEs image blob
				  registry. In the next release this part hopefully won't be
				  necessary, as we are looking to handle it internally.
				*/
				var id = 'blobid' + (new Date()).getTime();
				var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
				var base64 = reader.result.split(',')[1];
				var blobInfo = blobCache.create(id, file, base64);
				blobCache.add(blobInfo);

				/* call the callback and populate the Title field with the file name */
				cb(blobInfo.blobUri(), { title: file.name });
			  };
			  reader.readAsDataURL(file);
			};

			input.click();
		  },
		  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
		});

		</script>
	</head>

	<body>
		<script src="js/aurna-lightbox.js"></script>
		<script src="js/requestdetails.js"></script>
		

				<br>

				<div class="col-sm-12 main">

					<div class="row">

						<div class="col-sm-12">
							<p>
							<div class="row">
									<div class="col-sm-12">																								
										
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
										
										<div class="col-sm-7">
											<h3><i class="fa-solid fa-laptop"></i> Edit Request for Counseling Hour</h3>
											Date:
											<input disabled value="<?= date('Y-m-d', strtotime($GetRequestDate)); ?>" style="width: 200px"; class="btn alert-success"  type="date" id="myDate">
											<input value="<?= date('m/d/Y', strtotime($GetRequestDate)); ?>" name="request_date" type="hidden" id="myDateHidden">
											<table class="table table-dark" id="users-table">
											  <thead>
												<tr>
													<th scope="col">Slot No.</th>
													<th scope="col">Note</th>
													<th scope="col">Start Time</th>
													<th scope="col">End Time</th>
													<th scope="col">Info</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
													mysqli_set_charset($con, "utf8");
													$sql        = "	SELECT *
																	FROM `academic_counselling_faculty_selected_hours`
																	INNER JOIN `academic_counseling_hours`
																	ON `academic_counseling_hours`.`id` = `academic_counselling_faculty_selected_hours`.`hour_id`
																	WHERE `academic_counselling_faculty_selected_hours`.`faculty_id`= '$courseFacultyID'
																	AND `academic_counselling_faculty_selected_hours`.`hour_id` = '$GetHourId'
																	ORDER BY `academic_counseling_hours`.`id` ASC";
													$result		= mysqli_query($con, $sql);
													if(!$result){
														echo mysqli_error($con);
													}
													else{
														while($rows=mysqli_fetch_array($result)){
															$GetHourId = $rows['id'];
															//Check if there are any requests for this slot.
															$thishourRequests = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `academic_counseling_requests` 
																													WHERE `request_date`='$GetRequestDate' 
																													AND `faculty_id`='$courseFacultyID' 
																													AND (`status`='pending' OR `status`='approved') 
																													AND `hour_id`='$GetHourId'"));
															
																													
															//Check if the request for this slot is approved by the faculty
															$thishourApproved = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `academic_counseling_requests` 
																													WHERE `request_date`='$GetRequestDate' 
																													AND `faculty_id`='$courseFacultyID' 
																													AND `status`='approved' 
																													AND `hour_id`='$GetHourId'"));
															
															//Check if This slot is requested by the current user
															$thishourforCurrentUser = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `academic_counseling_requests` 
																															WHERE `request_date`='$GetRequestDate' 
																															AND (`status`='approved' OR `status`='pending') 
																															AND `faculty_id`='$courseFacultyID' 
																															AND `hour_id`='$GetHourId' 
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
																															AND `hour_id`='$GetHourId'
																															AND `request_date`='$GetRequestDate'"));
															
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
															
															
															if($Availability == true){
																//Register for this user first
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
																$date = $GetRequestDate;
																
																echo '<script>console.log("this slot-'.$timeslot.'")</script>';
																echo '<script>console.log("today-'.$GetRequestDate.'")</script>';

																// Parse time slot
																$timeslotArr = explode(' - ', $timeslot);
																$startTime = strtotime($timeslotArr[0]);
																$endTime = strtotime($timeslotArr[1]);
																
																echo '<script>console.log("start time-'.$timeslotArr[0].'")</script>';
																echo '<script>console.log("end time-'.$timeslotArr[1].'")</script>';

																// Check if current time is within time slot and date is today
																if (($currentTime >= $startTime && $currentTime <= $endTime) && ($date == $currentDate)) {
																  echo "💻 Slot is Running 👉";
																  //set available false to block user from booking
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
															<?php 	if(!$Availability){ 

																		$taken_user_details = mysqli_query($con, "SELECT * FROM `academic_counseling_requests`
																													INNER JOIN `academic_student_profile`
																													ON `academic_student_profile`.`id` = `academic_counseling_requests`.`student_id`
																													INNER JOIN `users`
																													ON `academic_student_profile`.`user_id` = `users`.`id`
																													WHERE (`status`='approved' OR `status`='pending') 
																													AND `academic_counseling_requests`.`faculty_id`='$courseFacultyID' 
																													AND `academic_counseling_requests`.`request_date`='$GetRequestDate' 
																													AND `academic_counseling_requests`.`hour_id`='$GetHourId' 																												
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
																													AND `academic_counseling_requests`.`request_date`='$GetRequestDate' 
																													AND `academic_counseling_requests`.`hour_id`='$GetHourId' 																												
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
										</br>
									</div>
										<div class="col-sm-5">
												<p>
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
															<h3>Course Information:</h3>
															<span><?php echo $courseName.' ('.$courseSection.')'; ?></span></br>
															<span><b>Course Code:</b><?= $courseCode; ?> | <b>Section:</b> <?= $courseSection;?></span>
															</br>
															</br>
															<span class="circular_image" style="height: 100px;width: 100px;"><img src="../../uploads/<?php echo $courseFacultyPic; ?>" class="img-responsive" style="width:100%" alt="Image"></span>
															<span class="used">
																<span class="facultyName"><?php echo $courseFacultyName; ?></span></br>
																<span class="facultyType"><?php echo $courseFacultyType; ?></span></br>
																<span class="facultySchl"><?php echo $courseFacultyDeptName; ?></span></br>
																<span class="facultySchl"><?php echo $courseFacultyPhone; ?></span></br>
																<span class="facultySchl"><?php echo $courseFacultyEmail; ?></span></br>
															</span>

												</p>
										</div>
										<div class="col-sm-12">
										<?php if(!$Availability && $currentUserswithFaculty && !$currentUserswithOtherFaculty){
												$submissionCount = 0;
												$submissionCountSQL = mysqli_query($con,"
													SELECT * FROM `academic_counseling_requests` 
													WHERE `student_id`='$current_user_student_id' 
													AND `faculty_id`='$courseFacultyID'
													AND `hour_id` = '$GetHourId'
													AND `trimester_id` = '$CurrentTrimesterID'
													AND `request_date` = '$GetRequestDate' 
													AND `course_code` = '$GetCourseCode'
													AND `section` = '$GetSection'
													AND `status`= 'pending' 
												");
												if(!empty($submissionCountSQL)){
													$submissionCount = mysqli_num_rows($submissionCountSQL);
													//echo mysqli_error($con);
												}	
										?>										
												<style>
												#myInput {
												  box-sizing: border-box;
												  background-image: url('assets/searchicon.png');
												  background-position: 14px 12px;
												  background-repeat: no-repeat;
												  font-size: 16px;
												  padding: 14px 20px 12px 45px;
												  border: none;
												  border-bottom: 1px solid #ddd;
												}

												#myInput:focus {outline: 3px solid #ddd;}

												.dropdown {
												  position: relative;
												  display: inline-block;
												}

												.dropdown-content {
												  display: none;
												  position: absolute;
												  background-color: #f6f6f6;
												  min-width: 230px;
												  overflow: auto;
												  border: 1px solid #ddd;
												  z-index: 99;
												}

												.dropdown-content a {
												  color: black;
												  padding: 12px 16px;
												  text-decoration: none;
												  display: block;
												}

												.dropdown a:hover {background-color: #ddd;}

												.show {display: block;}
												.clickbtn{
													display: inline-block;
													outline: none;
													cursor: pointer;
													padding: 0 16px;
													background-color: #fff;
													border-radius: 0.25rem;
													border: 1px solid #dddbda;
													color: #0070d2;
													font-size: 13px;
													line-height: 30px;
													font-weight: 400;
													text-align: center;
												}
												.clickbtn:hover {
													background-color: #f4f6f9; 
												} 
													
												</style>
												<div class="col-sm-4">
													<hr>
													<h3>Add Alongwith Student</h3>
													<p>
														Add student by student ID or Name.</br>
													</p>
													<span style="display: inline-block; vertical-align: middle;">
														<div class="dropdown">
															<button onclick="myFunction()" class="btn btn-success"><i class="fas fa-user-graduate"></i> Add Alongwith Student</button>&nbsp;
															<a href="#" data-toggle="tooltip" data-placement="bottom" title="Take your classmate or Friend with you. Add Student ID."><i class="fas fa-question-circle"></i></a>
															<div id="myDropdown" class="dropdown-content">
																<input type="text" onkeyup="studentSearch();" autocomplete="off" placeholder="Search Student ID..." id="myInput">
																<span id="studentList">
																</span>
															</div>
														</div>
														<script>
															$(document).ready(function(){
																$('[data-toggle="tooltip"]').tooltip();   
															});

															/* When the user clicks on the button, toggle between hiding and showing the dropdown content */
															function myFunction() {
																document.getElementById("myDropdown").classList.toggle("show");
															}
															function studentSearch(){
																// get a reference to the table element
																const table = document.getElementById('studentList');
																let SearchQuery = document.getElementById('myInput').value;

																// send an AJAX request to the PHP script
																const xhr = new XMLHttpRequest();
																xhr.onreadystatechange = function() {
																	if (this.readyState === 4 && this.status === 200) {
																		
																		console.log(this.responseText);
																		
																		// parse the JSON response
																		const content = JSON.parse(this.responseText);
																		console.log(content);
																		
																		table.querySelectorAll("a").forEach(function (data) {
																		  data.remove();
																		});
																		
																		// loop through the users and add rows to the table
																		/*
																		Ajax Returns - 
																			'name' => $rows['name'], 
																			'academic_id' => $rows['academic_id']
																		*/

																		for (let i = 0; i < content.length; i++) {
																			// Create a new anchor element
																			var newAnchor = document.createElement("a");

																			// Set the href attribute of the anchor
																			newAnchor.setAttribute("href", "#"+content[i].academic_id);
																			newAnchor.setAttribute("onclick", "setSelectedStudent('"+content[i].academic_id+"', '"+content[i].name+"')");

																			// Set the text of the anchor
																			newAnchor.innerHTML = content[i].name+"</br><small>"+content[i].academic_id+"</small>";

																			// Insert the anchor into the span element
																			table.appendChild(newAnchor);																	
																		}
																	}
																};
																
																xhr.open('GET', 'ajax.php?data=studentSearch&q='+SearchQuery, true);
																xhr.send();
															}
															
															
															function setSelectedStudent(id, name) {
																document.getElementsByName("alongwithStudentID")[0].value = id;
																document.getElementById("alongwithstudentID").innerHTML = id;
																document.getElementById("alongwithstudentName").innerHTML = name;
																document.getElementById("selectedALongwith").style.display = "unset";
																myFunction();
															}
															
															// Hide the dropdown-content when clicking outside of it
															document.addEventListener("click", function(event) {
															  var dropdownContent = document.getElementById("myDropdown");
															  if (!event.target.closest(".dropdown") && !event.target.closest(".dropdown-content")) {
																dropdownContent.classList.remove("show");
															  }
															});
															
														</script>
													</span>
													&nbsp;
												<?php if($submissionCount >= 1){ 
														if($row = mysqli_fetch_assoc($submissionCountSQL)){
		
															$ThisRequestDate = $row['request_date'];
															$ThisHourId = $row['hour_id'];
															$ThisCourseFacultyID = $row['faculty_id'];
															$ThisCourseSection = $row['section'];
															$ThisCourseCode = $row['course_code'];
															$ThisAlongwithStudentID = $row['alongwith_student_id'];
															$ThisProblem = $row['problem'];
															$ThisTopic = $row['topic'];
															
															$selectedALongwith = false;
															$ALongwith_Name = '';
															$ALongwith_studentID = '';
															
															if($ThisAlongwithStudentID != '' || !empty($ThisAlongwithStudentID)){
																$selectedALongwith = true;
																
																if($row2 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `users` WHERE `username` = $ThisAlongwithStudentID AND `usertype`='student'"))){
																	$ALongwith_Name = $row2['name'];
																	$ALongwith_studentID = $row2['username'];
																}
															}
															
																
														}
												?>
													<span id="selectedALongwith" <?php if(!$selectedALongwith){ ?> style="display: none;" <?php } ?>>
														<span style="display: inline-block; vertical-align: middle;">
															<i class="fas fa-list-ol"></i> <span id="alongwithstudentID"><?= $ALongwith_studentID ?></span><br>
															<i class="fas fa-user-graduate"></i> <span id="alongwithstudentName"><?= $ALongwith_Name ?></span>
														</span>
														&nbsp;
														<span style="display: inline-block; vertical-align: middle;">
															<button type="button" class="clickbtn" onclick="deleteALongwith()" id="deleteAlongsideBtn"><i class="fas fa-trash"></i></button>
														</span>
													</span>
													</br>
													<hr>
													<h3>Write Topic</h3>
													<p>
														Summarized title for your problem.</br>
														ex. 'Need Help with Recursion' or 'Understanding Incapsulation'
													</p>
													<input type="text" class="form-control" value="<?= htmlentities($ThisTopic); ?>" onkeyup="document.getElementById('topicData').value = this.value" placeholder="Write Topic..."/>
													<hr>
												</div>
												<script>

												  function deleteALongwith() {
													  var clickbtn = document.querySelector("#deleteAlongsideBtn");
													  var alongwithStudentID = document.querySelector("[name='alongwithStudentID']");
													  var alongwithstudentIDSpan = document.querySelector("#alongwithstudentID");
													  var alongwithstudentNameSpan = document.querySelector("#alongwithstudentName");
													  var selectedALongwith = document.querySelector("#selectedALongwith");
														alongwithStudentID.value = "";
														alongwithstudentIDSpan.innerHTML = "";
														alongwithstudentNameSpan.innerHTML = "";
														selectedALongwith.style.display = "none";
												  }
												</script>
												<div class="col-sm-8">
													<form name="tokenCreate" method="post" id="updateForm" action="send_request.php?update=true&hour_id=<?=$ThisHourId;?>&request_date=<?=$ThisRequestDate;?>&faculty_id=<?=$ThisCourseFacultyID;?>&course_code=<?=$ThisCourseCode;?>&section=<?=$ThisCourseSection;?>">
														<h4>What is Your Problem?</h4>
														<textarea  style="height: 57vh;" placeholder="Write Down Your Problems, Attach Images If Required..." id="problem"><?= $ThisProblem; ?></textarea>
														<input type="hidden" name="hourID" value="<?= $ThisHourId; ?>"/>
														<input type="hidden" name="courseFacultyID" value="<?= $ThisCourseFacultyID; ?>"/>
														<input type="hidden" name="courseSection" value="<?= $ThisCourseSection; ?>"/>
														<input type="hidden" name="courseCode" value="<?= $ThisCourseCode; ?>"/>
														<input type="hidden" name="alongwithStudentID" value="<?= $ThisAlongwithStudentID; ?>"/>
														<input type="hidden" name="problem" id="textData" value="<?= htmlentities($ThisProblem); ?>"/>
														<input type="hidden" name="topic" id="topicData" value="<?= htmlentities($ThisTopic); ?>"/>
														</br>
														<button type="button" onclick="PostNow()" class="btn btn-success" style="position: fixed;right: 24px;top: 1%;z-index: 9998;font-size: 19px;">Update Request</button>
													</form>
												</div>	
												<?php } ?>
										<?php }else{ ?>
											<h3>Slot is not Booked!</h3>
										<?php } ?>							
										</div>
										
										
										
										<?php
											if($Availability && !$currentUserswithFaculty && !$currentUserswithOtherFaculty){
												//INSERT Query to book the slot primarily for user.
											}
										?>
										
										
									</div>	      	

								
								
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
	</script>
	</body>

	</html>

