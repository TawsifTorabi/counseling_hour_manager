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


			//Current User Variables
			$currentUser = $_COOKIE['userid']; 
			$current_user_student_id = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM academic_student_profile WHERE user_id='$currentUser'"))['id'];





			//Parse Current Trimester Data
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





			//Get Current Users Selected Course for current semester
			$GetHourId = mysqli_real_escape_string($con, $_GET['hour_id']);
			$GetRequestDate = mysqli_real_escape_string($con, $_GET['request_date']);
			//$status = mysqli_real_escape_string($con, "pending");
			$GetCourseCode = mysqli_real_escape_string($con, $_GET['course_code']);
			$GetSection = mysqli_real_escape_string($con, $_GET['section']);
			$GetFacultyID =  mysqli_real_escape_string($con, $_GET['faculty_id']);

			

			
			
			$CurrentUserCoursesSQL = "SELECT 
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
										AND `academic_student_courses`.`student_id`='$currentUser'
										AND `academic_faculty_taken_course`.`course_code`='$GetCourseCode'
										AND `academic_faculty_taken_course`.`section`='$GetSection'
										AND `academic_faculty_taken_course`.`faculty_id`='$GetFacultyID'";

			// Debugging statement
			//echo "Query: $CurrentUserCoursesSQL";
			
			//Check if with this params, the current student is related to the course, section, trimester and faculty
			if(mysqli_num_rows(mysqli_query($con, $CurrentUserCoursesSQL)) > 0){
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
					echo "Error 143";
					exit();
				}
			}else{
				echo "Error 147";
				exit();				
			}
		
		
		
		
		if(isset($_GET['action'])){
			//echo "isset";
			if(!empty($_GET['action']) && $_GET['action'] == 'cancel'){
				
				//Check the status of the request
				$statusCheckSQL = "	SELECT * FROM `academic_counseling_requests` 
											WHERE `academic_counseling_requests`.`trimester_id`='$CurrentTrimesterID'
											AND `academic_counseling_requests`.`student_id`='$current_user_student_id'
											AND `academic_counseling_requests`.`course_code`='$GetCourseCode'
											AND `academic_counseling_requests`.`section`='$GetSection'
											AND `academic_counseling_requests`.`faculty_id`='$GetFacultyID'
											AND `academic_counseling_requests`.`hour_id`='$GetHourId'
											AND `academic_counseling_requests`.`request_date`='$GetRequestDate'
											AND `academic_counseling_requests`.`status`='approved'";
				//$status = mysqli_fetch_assoc(mysqli_query($con, $statusCheckSQL))['status']; 
				$status = isset(mysqli_fetch_assoc(mysqli_query($con, $statusCheckSQL))['status']) ? mysqli_fetch_assoc(mysqli_query($con, $statusCheckSQL))['status'] : ''; 
				echo $status;
				
				//if(
				
				
				//if the request was approved by the faculty, on cancellation, demerit 10.
				if($status == 'approved'){
					
					$minus = (int)mysqli_fetch_assoc(mysqli_query($con, "SELECT `global_value` FROM `global_settings` WHERE `global_key`='default_minus_merit'"))['global_value'];
								
					$minusMerit = "	UPDATE `academic_student_profile` 
									SET `counseling_merit` = `counseling_merit` - $minus
									WHERE `id`='$current_user_student_id'";
									
					mysqli_query($con, $minusMerit);
					
				}
				
				$cancelRequestSQL = 	"	UPDATE `academic_counseling_requests` 
											SET `status`='canceled'
											WHERE `academic_counseling_requests`.`trimester_id`='$CurrentTrimesterID'
											AND `academic_counseling_requests`.`student_id`='$current_user_student_id'
											AND `academic_counseling_requests`.`course_code`='$GetCourseCode'
											AND `academic_counseling_requests`.`section`='$GetSection'
											AND `academic_counseling_requests`.`hour_id`='$GetHourId'
											AND `academic_counseling_requests`.`request_date`='$GetRequestDate'
											AND `academic_counseling_requests`.`faculty_id`='$GetFacultyID'";
				
				$updateRequestSQLResult = mysqli_query($con, $cancelRequestSQL);
				

				if(!$updateRequestSQLResult){
						//echo "is not empty";
						echo mysqli_error($con);
						die();
				}else{
						echo "<script>
									window.location.href = 'request.php?course_code=$GetCourseCode&section=$GetSection&faculty_id=$GetFacultyID';	
							  </script>";
						exit();
				}
					
			}														
		}
		
		
		
		
		?>								
		

