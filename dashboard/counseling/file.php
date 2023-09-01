<?php
	session_start();
	
	//create database connection
	include("connect_db.php");
	
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
	if(mysqli_num_rows(mysqli_query($con,"select * from sessions where session_id='$getsessionID' AND validity='$validity'"))> 0){

		if(isset($_GET['content_id'])){
					
			mysqli_set_charset($con, "utf8");

			$id 		= (int)$_GET['content_id'];
			$sql        = "SELECT * FROM `library_contents` where content_id='".$id."'";
			$result		= mysqli_query($con, $sql);
			if(!$result){
				echo mysqli_error($con);
			}
			else{
				if($rows=mysqli_fetch_array($result)){
					
					$content_id = $rows['content_id'];
					$downloader = $_COOKIE['userid'];
					$download_time = time();
					
					if($downloader != $rows['uploaderID']){
						mysqli_query($con,"INSERT INTO `library_download`(`download_id`, `content_id`, `downloader`, `download_time`) VALUES(DEFAULT, $content_id, $downloader, $download_time)");
					}
					
					if($rows['filetype'] == 'pdf'){
						$location = "uploads/".$rows['filename'];
						$filename = $rows['filename'];
						$file = $location;
						
						header('Content-type: application/pdf');
						header('Content-Disposition: inline; filename="' . $filename . '"');
						header('Content-Transfer-Encoding: binary');
						header('Content-Length: ' . filesize($file));
						header('Accept-Ranges: bytes');
						@readfile($file);					
					
						exit();
					}
					if($rows['filetype'] == 'video'){
						$location = "uploads/".$rows['filename'];
						$filename = $rows['filename'];
						$file = $location;
						
						header('Location:'.$file);

					}
				}
			}
		}
	
	}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>