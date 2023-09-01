<?php
session_start();

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
	
	
	?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<title>Counseling Hour Manager</title>
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
					<h2><i class="fa-solid fa-notes-medical"></i> Counselling Hour Manager</h2>
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
								<h1 style="margin-left: 20px;"><i class="fa-solid fa-laptop"></i> Your Courses</h1></br>
								
								<?php include("model/meritCheck.php"); ?>
								
								<?php
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
								$CurrentUserID = $_COOKIE['userid'];
								$CurrentUsersCourses_Array = array();

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

															WHERE `academic_student_courses`.`trimester_id`=$CurrentTrimesterID
															AND `academic_student_courses`.`student_id`=$CurrentUserID";

								// Debugging statement
								//echo "Query: $CurrentUserCoursesSQL";
							
								if ($CurrentUserCoursesResult = mysqli_query($con, $CurrentUserCoursesSQL)) {
									while ($CurrentUserCoursesSQLValue = mysqli_fetch_assoc($CurrentUserCoursesResult)) {
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
										array_push($CurrentUsersCourses_Array, array(	$courseCode, 						//0
																						$courseName, 						//1
																						$courseSection, 					//2
																						$courseFacultyName, 				//3 
																						$courseFacultyType, 				//4
																						$courseFacultyPic, 					//5
																						$courseFacultyDeptName,				//6
																						$courseFacultyUsername, 			//7
																						$courseFacultyPhone, 				//8
																						$courseFacultyEmail, 				//9
																						$courseFacultyID 					//10
																					));
									}
								} else {
									// Handle error here
									echo "Error: " . mysqli_error($con);
								}
								?>
								<div class="col-sm-12">
										<p>
											<span>
												<!-- <b>Current Trimester:</b>
												<?= $CurrentTrimester; ?> -->
											<!-- </span></br> -->
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
												<?php foreach ($CurrentUsersCourses_Array as $courseDetail): ?>
												<div class="col-sm-5 portfolio-elements">
													<h4><?php echo $courseDetail[1].' ('.$courseDetail[2].')'; ?></h4>
													<h5>Course Code:<?= $courseDetail[0]; ?> | Section: <?= $courseDetail[2];?></h5>
													<p class="detail-project">Course Conducted by,</p>
													<span class="circular_image"><img src="../../uploads/<?php echo $courseDetail[5]; ?>" class="img-responsive" style="width:100%" alt="Image"></span>
													<span class="used">
														<span class="facultyName"><?php echo $courseDetail[3]; ?></span></br>
														<span class="facultyType"><?php echo $courseDetail[4]; ?></span></br>
														<span class="facultySchl"><?php echo $courseDetail[6]; ?></span></br>
														<span class="facultySchl"><?php echo $courseDetail[8]; ?></span></br>
														<span class="facultySchl"><?php echo $courseDetail[9]; ?></span></br>
													</span>
													</br>
													</br>
													<span style="float: right;">
														<button onclick="aurnaIframe('../chat/chat.php?user=<?php echo $courseDetail[7]; ?>&medical=true')" class="btn btn-success"><i class="fa fa-comment" aria-hidden="true"></i> Send Message</button>
														<a href="request.php?course_code=<?= $courseDetail[0]; ?>&section=<?= $courseDetail[2];?>&faculty_id=<?= $courseDetail[10];?>" class="btn btn-success"><i class="fa fa-external-link-square" aria-hidden="true"></i> Request Counselling Hour</a>
													</span>
												</div>
												<?php endforeach; ?>
											</span></br>
										</p>
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


<?php } else {
	echo "<script>window.open('login.php','_self')</script>";
} ?>