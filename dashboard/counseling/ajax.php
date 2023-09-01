<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

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
	if(mysqli_num_rows(mysqli_query($con, "select * from sessions where session_id='$getsessionID' AND validity='$validity'"))> 0){


	
	
	//get requests for data
	if(isset($_GET['data'])){
		
		
			//search for students
			if($_GET['data'] == 'studentSearch'){
				
				$userID = $_COOKIE['userid'];
				$currentUser = $_COOKIE['userid']; 
				$current_user_student_id = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM academic_student_profile WHERE user_id='$currentUser'"))['id'];

				mysqli_set_charset($con,"utf8");
				
				$queryString    = mysqli_real_escape_string($con, $_GET['q']);
	 
				$sql        = "	SELECT *
								FROM `academic_student_profile`
								INNER JOIN `users` ON `academic_student_profile`.`user_id` = `users`.`id`
								WHERE (`users`.`name` LIKE '%".$queryString."%' OR `users`.`username` LIKE '%".$queryString."%' OR `academic_student_profile`.`academic_id` LIKE '%".$queryString."%')
								AND `users`.`id` != '$userID'
								ORDER BY `user_id` DESC 
								LIMIT 5;";
				
				$result		= mysqli_query($con, $sql);
				
				$files = array();
				$fileIndie = array();
				
				if(!$result){
					echo mysqli_error($con);
				}
				else{
					$TokenCount = 0;				
					while($rows=mysqli_fetch_array($result)){
						$TokenCount++;
						$fileIndie = array(
											'name' => $rows['name'],
											'academic_id' => $rows['academic_id']
										);
										
						$files[] = $fileIndie;
					}
					
					if($TokenCount == 0){
						$data = array(
							'name' => 'null', 
							'academic_id' => 'null'
						);
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($data);	
					}else{						
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($files);
					}
					

				}
			
			}
			
			
			
			//search for institute and institute data and display
			if($_GET['data'] == 'bookmarkSearch'){
				$userID = $_COOKIE['userid'];
				mysqli_set_charset($con,"utf8");
				
				$queryString    = mysqli_real_escape_string($con, $_GET['q']);
	 
				$sql        = "SELECT 
									library_bookmarks.bookmark_id, 
									library_contents.content_id, 
									library_contents.name AS content_name, 
									users.name AS users_name, 
									library_contents.time, 
									library_contents.description, 
									library_contents.filetype
								FROM library_bookmarks
								JOIN library_contents ON library_bookmarks.content_id = library_contents.content_id
								JOIN users ON library_contents.uploaderID = users.id
								WHERE library_bookmarks.user_id = $userID AND
								(`library_contents`.`name` LIKE '%".$queryString."%' OR `library_contents`.`description` LIKE '%".$queryString."%' OR `users`.`name` LIKE '%".$queryString."%')
								ORDER BY `content_id` 
								DESC LIMIT 50";
				
				$result		= mysqli_query($con, $sql);
				
				$files = array();
				$fileIndie = array();
				
				if(!$result){
					echo mysqli_error($con);
				}
				else{
					$TokenCount = 0;				
					while($rows=mysqli_fetch_array($result)){
						$TokenCount++;
						$date1 = $rows['time']; 
					
						$content_id = $rows['content_id']; 
						$dl_count = mysqli_num_rows(mysqli_query($con, "SELECT download_id FROM library_download WHERE content_id=$content_id"));

						$fileIndie = array(
											'content_id' => $rows['content_id'], 
											'content_name' => $rows['content_name'], 
											'description' => $rows['description'], 
											'users_name' => $rows['users_name'], 
											'downloads' => $dl_count, 
											'time' => date("l, jS \of F, Y (h:i:s A)", $date1), 
											'filetype' => $rows['filetype'],
											'bookmark' => $rows['bookmark_id']
										);
										
						$files[] = $fileIndie;
					}
					
					if($TokenCount == 0){
						$data = array(
							'content_id' => 'null', 
							'content_name' => 'null', 
							'description' => 'null', 
							'users_name' => 'null', 
							'downloads' => 'null', 
							'time' => 'null', 
							'filetype' => 'null',
							'bookmark' => 'null'
						);
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($data);	
					}else{						
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($files);
					}
					

				}
			
			}


			//search for institute and institute data and display
			if($_GET['data'] == 'coursecontents'){
				$userID = $_COOKIE['userid'];
				mysqli_set_charset($con,"utf8");
				
				$queryString    = mysqli_real_escape_string($con, $_GET['q']);
	 
				$sql        = "	SELECT `library_contents`.`content_id` AS `content_id`,
									   `library_contents`.`name` AS `content_name`,
									   `users`.`name` AS `users_name`,
									   `library_contents`.`time` AS `time`,
									   `library_contents`.`description` AS `description`,
									   `library_contents`.`filetype` AS `filetype`,
									   `library_bookmarks`.`bookmark_id` AS `bookmark_id`
								FROM `library_contents`
								INNER JOIN `users` ON `library_contents`.`uploaderID` = `users`.`id`
								LEFT JOIN `library_bookmarks` ON `library_bookmarks`.`content_id` = `library_contents`.`content_id` AND `library_bookmarks`.`user_id` = '$userID'
								WHERE (`library_contents`.`name` LIKE '%".$queryString."%' OR `library_contents`.`description` LIKE '%".$queryString."%' OR `users`.`name` LIKE '%".$queryString."%')
								ORDER BY `content_id` DESC 
								LIMIT 50;
								";
								//(`library_contents`.`name` LIKE '%".$queryString."%' OR `library_contents`.`description` LIKE '%".$queryString."%' OR `users`.`name` LIKE '%".$queryString."%')
				
				$result		= mysqli_query($con, $sql);
				
				$files = array();
				$fileIndie = array();
				
				if(!$result){
					echo mysqli_error($con);
				}
				else{
					$TokenCount = 0;				
					while($rows=mysqli_fetch_array($result)){
						$TokenCount++;
						$date1 = $rows['time']; 
					
						$content_id = $rows['content_id']; 
						$dl_count = mysqli_num_rows(mysqli_query($con, "SELECT download_id FROM library_download WHERE content_id=$content_id"));

						$fileIndie = array(
											'content_id' => $rows['content_id'], 
											'content_name' => $rows['content_name'], 
											'description' => $rows['description'], 
											'users_name' => $rows['users_name'], 
											'downloads' => $dl_count, 
											'time' => date("l, jS \of F, Y (h:i:s A)", $date1), 
											'filetype' => $rows['filetype'],
											'bookmark' => $rows['bookmark_id']
										);
										
						$files[] = $fileIndie;
					}
					
					if($TokenCount == 0){
						$data = array(
							'content_id' => 'null', 
							'content_name' => 'null', 
							'description' => 'null', 
							'users_name' => 'null', 
							'downloads' => 'null', 
							'time' => 'null', 
							'filetype' => 'null',
							'bookmark' => 'null'
						);
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($data);	
					}else{						
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($files);
					}
					

				}
			
			}
			
			
			
			//search for institute and institute data and display
			if(isset($_GET['data']) && ($_GET['data'] == 'coursecontentsplaylists' || $_GET['data'] == 'coursecontentsbooklists')){
				$userID = $_COOKIE['userid'];
				mysqli_set_charset($con,"utf8");

				$queryString = mysqli_real_escape_string($con, $_GET['q']);
				$listType = ($_GET['data'] == 'coursecontentsplaylists') ? 'playlist' : 'booklist';

				$sql = "SELECT * FROM `library_list` 
						WHERE `list_type`='$listType' 
						AND (`library_list`.`list_name` LIKE '%" . $queryString . "%' OR `library_list`.`list_description` LIKE '%" . $queryString . "%')
						ORDER BY `list_id`";

				$result = mysqli_query($con, $sql);

				$files = array();
				$fileIndie = array();

				if(!$result){
					echo mysqli_error($con);
				}
				else{
					$TokenCount = 0;                
					while($rows=mysqli_fetch_array($result)){
						$TokenCount++;

						$fileIndie = array(
							'list_id' => $rows['list_id'], 
							'list_name' => $rows['list_name'], 
							'list_description' => $rows['list_description'], 
						);

						$files[] = $fileIndie;
					}

					if($TokenCount == 0){
						$data = array(
							'list_id' => 'null', 
							'list_name' => 'null', 
							'list_description' => 'null', 
						);
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($data);    
					}else{                      
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($files);
					}
				}
			}
			
			
			//search for institute and institute data and display
			if($_GET['data'] == 'playlistsSearch'){
				
				mysqli_set_charset($con, "utf8");
				define('TIMEZONE','Asia/Dhaka');
				date_default_timezone_set(TIMEZONE);
				
				function last_seen($date_time){
					
					   $timestamp = $date_time;
					   $strTime = array("second", "minute", "hour", "day", "month", "year");
					   $length = array("60","60","24","30","12","10");

					   $currentTime = time();
					   if($currentTime >= $timestamp) {
							$diff     = time()- $timestamp;
							for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
							$diff = $diff / $length[$i];
							}

							$diff = round($diff);
							if($diff<10 &&$strTime[$i]=="second"){
								return 'Updated Some moments ago!';
							}else{
								return $diff . " " . $strTime[$i] . "(s) ago ";
							}	
					   }
				}		
				
				$userid = $_COOKIE['userid'];
				$queryString    = mysqli_real_escape_string($con, $_GET['q']);
				$sql = " SELECT 
							`library_list`.`list_id` AS `list_id`,
							`library_list`.`list_name` AS `name`,
							`library_list`.`list_description` AS `description`,
							`users`.`name` AS `users_name`,
							`library_list`.`timestamp` AS `time`,
							`library_list`.`last_update_timestamp` AS `update_time`,
							COUNT(`library_contents`.`content_id`) AS `content_count`
						FROM `library_list` 
						INNER JOIN `users` ON `library_list`.`creator_userid` = `users`.`id`
						LEFT JOIN `library_contents` ON `library_list`.`list_id` = `library_contents`.`list_id`
						WHERE `library_list`.`list_type` = 'playlist'
						 AND (`library_list`.`list_name` LIKE '%$queryString%' OR `library_list`.`list_description` LIKE '%$queryString%')
						GROUP BY `library_list`.`list_id`
						ORDER BY `list_id`";
		
				$result		= mysqli_query($con, $sql);
				
				$files = array();
				$fileIndie = array();
				
				if(!$result){
					echo mysqli_error($con);
				}
				else{
					$TokenCount = 0;				
					while($rows=mysqli_fetch_array($result)){
						$TokenCount++;
						$date1 = $rows['time']; 
					
						$fileIndie = array(
											'list_id' => $rows['list_id'], 
											'name' => $rows['name'], 
											'description' => $rows['description'], 
											'users_name' => $rows['users_name'], 
											'time' => date("l, jS \of F, Y (h:i:s A)", $date1), 
											'update_time' => last_seen($rows['update_time']), 
											'content_count' => $rows['content_count']
										);
										
						$files[] = $fileIndie;
					}
					
					if($TokenCount == 0){
						$data = array(
							'list_id' => 'null', 
							'name' => 'null', 
							'description' => 'null', 
							'users_name' => 'null', 
							'time' => 'null', 
							'update_time' => 'null', 
							'content_count' => 'null'
						);
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($data);	
					}else{						
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($files);
					}
					

				}
			
			}
			
			//search for institute and institute data and display
			if($_GET['data'] == 'booklistsSearch'){
				
				mysqli_set_charset($con, "utf8");
				define('TIMEZONE','Asia/Dhaka');
				date_default_timezone_set(TIMEZONE);
				
				function last_seen($date_time){
					
					   $timestamp = $date_time;
					   $strTime = array("second", "minute", "hour", "day", "month", "year");
					   $length = array("60","60","24","30","12","10");

					   $currentTime = time();
					   if($currentTime >= $timestamp) {
							$diff     = time()- $timestamp;
							for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
							$diff = $diff / $length[$i];
							}

							$diff = round($diff);
							if($diff<10 &&$strTime[$i]=="second"){
								return 'Updated Some moments ago!';
							}else{
								return $diff . " " . $strTime[$i] . "(s) ago ";
							}	
					   }
				}		
				
				$userid = $_COOKIE['userid'];
				$queryString    = mysqli_real_escape_string($con, $_GET['q']);
				$sql = " SELECT 
							`library_list`.`list_id` AS `list_id`,
							`library_list`.`list_name` AS `name`,
							`library_list`.`list_description` AS `description`,
							`users`.`name` AS `users_name`,
							`library_list`.`timestamp` AS `time`,
							`library_list`.`last_update_timestamp` AS `update_time`,
							COUNT(`library_contents`.`content_id`) AS `content_count`
						FROM `library_list` 
						INNER JOIN `users` ON `library_list`.`creator_userid` = `users`.`id`
						LEFT JOIN `library_contents` ON `library_list`.`list_id` = `library_contents`.`list_id`
						WHERE `library_list`.`list_type` = 'booklist'
						 AND (`library_list`.`list_name` LIKE '%$queryString%' OR `library_list`.`list_description` LIKE '%$queryString%')
						GROUP BY `library_list`.`list_id`
						ORDER BY `list_id`";
		
				$result		= mysqli_query($con, $sql);
				
				$files = array();
				$fileIndie = array();
				
				if(!$result){
					echo mysqli_error($con);
				}
				else{
					$TokenCount = 0;				
					while($rows=mysqli_fetch_array($result)){
						$TokenCount++;
						$date1 = $rows['time']; 
					
						$fileIndie = array(
											'list_id' => $rows['list_id'], 
											'name' => $rows['name'], 
											'description' => $rows['description'], 
											'users_name' => $rows['users_name'], 
											'time' => date("l, jS \of F, Y (h:i:s A)", $date1), 
											'update_time' => last_seen($rows['update_time']), 
											'content_count' => $rows['content_count']
										);
										
						$files[] = $fileIndie;
					}
					
					if($TokenCount == 0){
						$data = array(
							'list_id' => 'null', 
							'name' => 'null', 
							'description' => 'null', 
							'users_name' => 'null', 
							'time' => 'null', 
							'update_time' => 'null', 
							'content_count' => 'null'
						);
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($data);	
					}else{						
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($files);
					}
					

				}
			
			}
			
			
			
			
			//search for institute and institute data and display
			if($_GET['data'] == 'myuploadsSearch'){
				
				mysqli_set_charset($con,"utf8");
				$userid = $_COOKIE['userid'];
				$queryString    = mysqli_real_escape_string($con, $_GET['q']);
	 
				$sql        = "SELECT 	`library_contents`.`content_id` AS `content_id`,
										`library_contents`.`name` AS `content_name`,
										`users`.`name` AS `users_name`,
										`library_contents`.`time` AS `time`,
										`library_contents`.`description` AS `description`,
										`library_contents`.`filetype` AS `filetype`
									FROM `library_contents` 
									INNER JOIN `users` 
									ON `library_contents`.`uploaderID` = `users`.`id` 
									WHERE `library_contents`.`uploaderID` = $userid
									  AND (`library_contents`.`name` LIKE '%$queryString%' OR `library_contents`.`description` LIKE '%$queryString%')
									ORDER BY `content_id` DESC
									LIMIT 50";
				
				$result		= mysqli_query($con, $sql);
				
				$files = array();
				$fileIndie = array();
				
				if(!$result){
					echo mysqli_error($con);
				}
				else{
					$TokenCount = 0;				
					while($rows=mysqli_fetch_array($result)){
						$TokenCount++;
						$date1 = $rows['time']; 
					
						$content_id = $rows['content_id']; 
						$dl_count = mysqli_num_rows(mysqli_query($con, "SELECT download_id FROM library_download WHERE content_id=$content_id"));

						$fileIndie = array(
											'content_id' => $rows['content_id'], 
											'content_name' => $rows['content_name'], 
											'description' => $rows['description'], 
											'users_name' => $rows['users_name'], 
											'downloads' => $dl_count, 
											'time' => date("l, jS \of F, Y (h:i:s A)", $date1), 
											'filetype' => $rows['filetype']
										);
										
						$files[] = $fileIndie;
					}
					
					if($TokenCount == 0){
						$data = array(
							'content_id' => 'null', 
							'content_name' => 'null', 
							'description' => 'null', 
							'users_name' => 'null', 
							'downloads' => 'null', 
							'time' => 'null', 
							'filetype' => 'null'
						);
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($data);	
					}else{						
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($files);
					}
					

				}
			
			}
			
			
			
		//Create New Playlist
		if($_GET['data'] == 'NewPlaylist'){
				
			$json = json_decode($_GET['json'], true);
			$name = $json['name'];
			$description = $json['description'];
			$time = time();
			
			mysqli_set_charset($con,"utf8");
				
			$userid = mysqli_real_escape_string($con, $_COOKIE['userid']);
 
			$sql        = 	"INSERT INTO `library_list`(list_name, list_description, list_type, creator_userid, timestamp, last_update_timestamp) 
							VALUES('$name','$description','booklist',$userid,$time,$time)";
			
			$sqlReturn  = 	"SELECT * FROM `library_list` WHERE `creator_userid` = $userid AND `list_name`='$name' AND `timestamp` = $time";
			
			$result		= mysqli_query($con, $sql);
			$resultReturn	= mysqli_query($con, $sqlReturn);
			
			$files = array();
			$fileIndie = array();
			
			if(!$result){
				echo mysqli_error($con);
			}
			else{
				if(!$resultReturn){
					echo mysqli_error($con);
				}
				else{
					$TokenCount = 0;
					if($rows=mysqli_fetch_array($resultReturn)){
							$TokenCount++;					
							$fileIndie = array(
												'list_id' => $rows['list_id'], 
												'name' => $rows['list_name'], 
												'description' => $rows['list_description'], 
												'success' => 'true'
											);
											
							$files[] = $fileIndie;
					}
						
					if($TokenCount == 0){
						$data = array(
							'list_id' => 'null', 
							'name' => 'null', 
							'description' => 'null', 
							'success' => 'false'
						);
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($data);	
					}else{						
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($files);
					}
				}
	
				

			}
		
		}
		
		//Create New Playlist
		if($_GET['data'] == 'NewPlaylistVideo'){
				
			$json = json_decode($_GET['json'], true);
			$name = $json['name'];
			$description = $json['description'];
			$time = time();
			
			mysqli_set_charset($con,"utf8");
				
			$userid = mysqli_real_escape_string($con, $_COOKIE['userid']);
 
			$sql        = 	"INSERT INTO `library_list`(list_name, list_description, list_type, creator_userid, timestamp, last_update_timestamp) 
							VALUES('$name','$description','playlist',$userid,$time,$time)";
			
			$sqlReturn  = 	"SELECT * FROM `library_list` WHERE `creator_userid` = $userid AND `list_name`='$name' AND `timestamp` = $time";
			
			$result		= mysqli_query($con, $sql);
			$resultReturn	= mysqli_query($con, $sqlReturn);
			
			$files = array();
			$fileIndie = array();
			
			if(!$result){
				echo mysqli_error($con);
			}
			else{
				if(!$resultReturn){
					echo mysqli_error($con);
				}
				else{
					$TokenCount = 0;
					if($rows=mysqli_fetch_array($resultReturn)){
							$TokenCount++;					
							$fileIndie = array(
												'list_id' => $rows['list_id'], 
												'name' => $rows['list_name'], 
												'description' => $rows['list_description'], 
												'success' => 'true'
											);
											
							$files[] = $fileIndie;
					}
						
					if($TokenCount == 0){
						$data = array(
							'list_id' => 'null', 
							'name' => 'null', 
							'description' => 'null', 
							'success' => 'false'
						);
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($data);	
					}else{						
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($files);
					}
				}
	
				

			}
		
		}

		//Create New Playlist
		if($_GET['data'] == 'SubmitReview'){
				
			
			$review = mysqli_real_escape_string($con,$_POST['review']);
			$rating = mysqli_real_escape_string($con,$_POST['rating']);
			$content_id = mysqli_real_escape_string($con,$_POST['content_id']);
			$time = time();
			
			mysqli_set_charset($con,"utf8");
				
			$userid = mysqli_real_escape_string($con, $_COOKIE['userid']);
 
			$sql        = 	"INSERT INTO `library_review`(content_id, user_id, review, rating, timestamp) 
							VALUES('$content_id','$userid','$review',$rating,$time)";

			$result		= mysqli_query($con, $sql);

			
			if(!$result){
				echo mysqli_error($con);
			}else{
				header('Location: content.php?id='.$content_id); 
			}
		}
		
		

			
			
			


		// Bookmark Content
		if($_GET['data'] == 'Bookmark'){
			$userID     = $_COOKIE['userid'];
			$contentID  = $_GET['content_id'];
			$time = time();
			mysqli_set_charset($con,"utf8");

			// Check if the content is already bookmarked
			$row = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `library_bookmarks` WHERE `user_id`='$userID' AND `content_id`='$contentID'"));

			if($row >= 1){
				// The content is already bookmarked, so delete the bookmark
				$Bookmarksql = "DELETE FROM `library_bookmarks` WHERE user_id=$userID AND content_id=$contentID";
				$result      = mysqli_query($con, $Bookmarksql);
				if(!$result){
					$data = array('bookmarked' => 'false', 'message' => 'Error Occurred!');
					echo json_encode($data);
				} else {
					$data = array('bookmarked' => 'false', 'message' => 'Bookmark removed!');
					echo json_encode($data);
				}
			} else {
				// The content is not bookmarked yet, so add the bookmark
				$Bookmarksql = "INSERT INTO `library_bookmarks`(`content_id`, `user_id`, `timestamp`) VALUES ('$contentID','$userID', '$time')";
				$result      = mysqli_query($con, $Bookmarksql);
				if(!$result){
					$data = array('bookmarked' => 'false', 'message' => 'Error Occurred!');
					echo json_encode($data);
				} else {
					$data = array('bookmarked' => 'true', 'message' => 'Bookmarked!');
					echo json_encode($data);
				}
			}
		}
			


			
			//permanently delete
			if($_GET['data'] == 'deleteContent'){
				
				mysqli_set_charset($con,"utf8");
				$userID 	= $_COOKIE['userid'];
				$id = mysqli_real_escape_string($con, $_GET['content_id']);
				$query = "DELETE FROM `library_contents` WHERE content_id='$id' AND uploaderID='$userID'";
			
				if ($conn->query("SELECT filename FROM library_contents WHERE content_id='$id'")->num_rows > 0){
					// output data of each row
					if($row = $conn->query("SELECT uploaderID, filename FROM library_contents WHERE content_id='$id' AND uploaderID='$userID'")->fetch_assoc()) {
						
						$userid = $_COOKIE['userid'];
						
						if($userid == $row['uploaderID']){
							$file_pointer = "uploads/".$row['filename'];
							//echo $file_pointer;
							if(mysqli_query($con, $query)){
								unlink($file_pointer);
								$Dataarr = array('deleted' => 'true');
								echo json_encode($Dataarr);
							}else{
								$Dataarr = array('deleted' => 'false');
								echo json_encode($Dataarr);
								die(mysqli_error($con));	
							}
						}
						
					}
				} else {
						$Dataarr = array('deleted' => 'false');
						echo json_encode($Dataarr);
						die(mysqli_error($con));
				}
			}
	

	
	//everything up this line
	}
			
			
		} else { echo "Not Logged In! Session Expired!"; } ?>