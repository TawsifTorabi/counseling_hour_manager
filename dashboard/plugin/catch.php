<?php
	session_start();
	define('TIMEZONE','Asia/Dhaka');
	date_default_timezone_set(TIMEZONE);
	
	//create database connection
	include("connect_db.php");
	
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
	
	// Retrieve the parameters from the GET request
	$name = $_GET["name"];
	$dob = $_GET["dob"];
	$phone = trim('+'.$_GET["phone"], ' ');

	$trimester = $_GET["trimester"];
	$studentID = $_GET["studentID"];
	$courses = $_GET["courses"];
	
	if(isset($courses) && isset($studentID) && isset($trimester) && isset($name) && isset($dob) && isset($phone)){
		
			
		// Demo data array for shuffling missing values
		$demoData = array(
			"blood_group" => array("A+", "B+", "O+", "AB+", "A-", "B-", "O-", "AB-"),
			"gender" => array("male", "female", "other")
		);

		// Shuffle missing data from demo data array
		$bloodGroup = isset($_GET["blood_group"]) ? $_GET["blood_group"] : $demoData["blood_group"][array_rand($demoData["blood_group"])];
		$gender = '';

		// Split the names by comma
		$names = explode(" ", $name);
		// Initialize counters for male and female names
		$maleCount = 0;
		$femaleCount = 0;

		// Loop through each name
		foreach ($names as $n) {
		  // Call the API to get the gender of the name
		  $json = file_get_contents("https://api.genderize.io/?name=".$n);
		  // Decode the JSON response
		  $response = json_decode($json, true);
		  // Check if the gender is male or female and increment the corresponding counter
		  if ($response['gender'] == 'male') {
				echo $response['gender'].' ';
				$maleCount++;
		  } elseif($response['gender'] == 'female') {
				echo $response['gender'].' ';
				$femaleCount++;
		  }else{
				echo $response['gender'].' ';
				$maleCount++;
		  }
		}

		// Determine the gender based on the counters
		if ($femaleCount >= 1) {
		  $gender = 'female';
		} else {
		  $gender = 'male';
		}
		
		
		$p_p = isset($_GET["p_p"]) ? $_GET["p_p"] : "default.jpg";

		// Check if user already exists
		$stmt = $con->prepare("SELECT id FROM users WHERE username = ? AND dob = ?");
		$stmt->bind_param("ss", $studentID, $dob);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			// User already exists, get the ID
			$row = $result->fetch_assoc();
			$user_id = $row["id"];
		} else {
			// Insert new user into the `users` table
			$stmt = $con->prepare("INSERT INTO users (name, username, usertype, password, adminprivilege, CreationTimestamp, blood_group, dob, gender, phone, last_seen, p_p) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$hashedPassword = "1111";
			$userType = "student";
			$adminPrivilege = "no";
			$timestamp = time();
			$stmt->bind_param("ssssssssssss", $name, $studentID, $userType, $hashedPassword, $adminPrivilege, $timestamp, $bloodGroup, $dob, $gender, $phone, $timestamp, $p_p);
			$stmt->execute();
			$user_id = $stmt->insert_id; // Get the inserted user ID
			createAcademicProfile($studentID, $user_id, $con);
		}

		//`academic_student_profile`(`id`, `user_id`, `program_id`, `academic_id`, `year`, `batch`, `serial`, `counseling_merit`)
		//`users`(`id`, `name`, `username`, `usertype`, `password`, `adminprivilege`, `CreationTimestamp`, `blood_group`, `dob`, `gender`, `phone`, `last_seen`, `p_p`)
		//THESE TWO LINES ARE DATABASE TABLE AND STRUCTURE


		// Get the current trimester ID
		$globalValueSQL = "SELECT `global_value` FROM `global_settings` WHERE `global_key`='current_trimester'";
		if ($globalValueResult = mysqli_query($con, $globalValueSQL)) {
		  if ($globalValueSQL = mysqli_fetch_assoc($globalValueResult)) {
			$CurrentTrimesterID = $globalValueSQL['global_value'];
		  }
		} else {
		  // Handle error here
		  echo "Error: " . mysqli_error($con);
		}


		// check if student already has this course in this trimester
		$courses_array = explode(',', $courses);
		foreach ($courses_array as $course_str) {
			$course_info = explode('(', $course_str);
			$course_code = trim($course_info[0]);
			$course_section = trim($course_info[1], ' )');
			
			$check_course_sql = "SELECT COUNT(*) AS count FROM `academic_student_courses` WHERE `trimester_id`=$CurrentTrimesterID AND `course_code`='$course_code' AND `section`='$course_section' AND `student_id`=$user_id";
			$check_course_result = mysqli_query($con, $check_course_sql);
			$check_course_row = mysqli_fetch_assoc($check_course_result);
			$count = (int)$check_course_row['count'];
			
			if ($count == 0) {
				// insert course
				$insert_course_sql = "INSERT INTO `academic_student_courses`(`trimester_id`, `course_code`, `section`, `student_id`, `passed`, `grade`) VALUES ($CurrentTrimesterID, '$course_code', '$course_section', $user_id, '', '')";
				mysqli_query($con, $insert_course_sql);
			}
		}

		
	}
	
	


	//Creates Academic Student Profile if doesnt exists. 
	//Takes informations from Student Username.
	
	function createAcademicProfile($username, $userId, $mysqli) {
		$programCode_init = substr($username, 0, 3);
		$batchCode = substr($username, 3, 3);
		$serialNumber = substr($username, 6);
		$year = "20" . substr($batchCode, 0, 2);
		$merit = (int)mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT `global_value` FROM `global_settings` WHERE `global_key`='default_merit'"))['global_value'];

		// check if student profile already exists
		$check_query = "SELECT id FROM academic_student_profile WHERE user_id=? AND academic_id=?";
		$check_stmt = $mysqli->prepare($check_query);
		$check_stmt->bind_param("is", $userId,$username);
		$check_stmt->execute();
		$check_result = $check_stmt->get_result();
		if ($check_result->num_rows > 0) {
			// profile already exists, skip insertion
			return;
		}

		$programCode = '';
		if($programCode_init === '011'){
			$programCode = 'BSCSE';
		}
		if($programCode_init === '111'){
			$programCode = 'BBA';
		}		
		if($programCode_init === '021'){
			$programCode = 'BSEEE';
		}
		
		// U1customFunctions.GetDept = function(idPrefix){
			// if(idPrefix == '011'){
				// return 'CSE';
			// }else if(idPrefix == '021'){
				// return 'EEE';
			// }else if(idPrefix == '111'){
				// return 'BBA';
			// }else if(idPrefix == '121'){
				// return 'ECONOMICS';
			// }else if(idPrefix == '031'){
				// return 'CIVIL';
			// } else {
				// return 'Wrong Dept. Code';
			// }
		// }
		
		// get program ID from program code
		$program_query = "SELECT program_id FROM academic_programs WHERE program_code=?";
		$program_stmt = $mysqli->prepare($program_query);
		$program_stmt->bind_param("s", $programCode);
		$program_stmt->execute();
		$program_result = $program_stmt->get_result();
		if ($program_result->num_rows == 0) {
			// program not found, return error
			return "Program not found";
		}
		$program_row = $program_result->fetch_assoc();
		$programId = $program_row['program_id'];

		// insert student profile
		$insert_query = "INSERT INTO academic_student_profile (user_id, program_id, academic_id, year, batch, serial, counseling_merit) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$insert_stmt = $mysqli->prepare($insert_query);
		if ($insert_stmt === false) {
			throw new Exception($mysqli->error);
		}
		$insert_stmt->bind_param("iissssi", $userId, $programId, $username, $year, $batchCode, $serialNumber, $merit);
		if (!$insert_stmt->execute()) {
			// insertion failed, return error
			throw new Exception($insert_stmt->error);
			return "Insertion failed";
		}
	}

?>