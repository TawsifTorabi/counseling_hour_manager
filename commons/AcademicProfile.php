<?php 


	//Creates Academic Student Profile if doesnt exists. 
	//Takes informations from Student Username.
	
	function createAcademicProfile($username, $userId, $mysqli) {
		$programCode_init = substr($username, 0, 3);
		$batchCode = substr($username, 3, 3);
		$serialNumber = substr($username, 6);
		$year = "20" . substr($batchCode, 0, 2);
		$merit = (int)mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT `global_value` FROM `global_settings` WHERE `global_key`='default_merit'"))['global_value'];

		// check if student profile already exists
		$check_query = "SELECT id FROM academic_student_profile WHERE user_id=?";
		$check_stmt = $mysqli->prepare($check_query);
		$check_stmt->bind_param("i", $userId);
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