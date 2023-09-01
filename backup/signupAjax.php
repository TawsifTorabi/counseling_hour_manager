<?php
		//create database connection
		include("connect_db.php");

		if(isset($_GET['data'])){
			//Check username
			if($_GET['data'] == 'GetUsername'){
				
				mysqli_set_charset($con,"utf8");
				$input    	= mysqli_real_escape_string($con, $_GET['username']);
			
				// it return number of rows in the table.
				$row = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `users` WHERE `username`='$input'"));
				if($row == 1){
					//Return Json to Client
					$data = array('available' => 'false');
				}
				if($row == 0){
					//Return Json to Client
					$data = array('available' => 'true');
				}
				echo json_encode($data);
			}
		}
		
?>