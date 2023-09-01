<?php
	session_start();
	
	//create database connection
	include("../connect_db.php");
	
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
	?>
		
		
	  <link rel="stylesheet" type="text/css" href="../css/aurna-lightbox.css"/>
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	   <div class="col-sm-12">
          <div class="well">
            <p>

			<?php
				mysqli_set_charset($con,"utf8");
				$currentUserID = $_COOKIE['userid'];
				$courseFacultyID = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM academic_faculty_profile WHERE user_id='$currentUserID'"))['id'];

				$id    		= mysqli_real_escape_string($con, $_COOKIE['userid']);
				$requestid  = mysqli_real_escape_string($con, $_GET['request_id']);
				$sql        = "	SELECT 
									`acr`.`topic` AS `topic`,
									`acr`.`problem` AS `problem`,
									`acr`.`alongwith_student_id` AS `alongwith_student_id`,
									UNIX_TIMESTAMP(`acr`.`created_at`) AS `createDateTime`,
									`u1`.`name` AS `student_name`,
									`u1`.`username` AS `student_id`,
									COALESCE(`u2`.`name`, 0) AS `alongwith_student_name`
									
								FROM `academic_counseling_requests` AS `acr`
								INNER JOIN `academic_student_profile` AS `asp`
									ON `acr`.`student_id` = `asp`.`id`
								INNER JOIN `users` AS `u1`
									ON `asp`.`user_id` = `u1`.`id`
								LEFT JOIN `users` AS `u2`
									ON `acr`.`alongwith_student_id` = `u2`.`username`
								WHERE `acr`.`faculty_id` = '$courseFacultyID' 
									AND `acr`.`id` = $requestid;";
				$result		= mysqli_query($con, $sql);
				$TokenCount = 0;
				if(!$result){
					echo mysqli_error($con);
				}
				else{
					while($rows=mysqli_fetch_array($result)){
						$TokenCount++;
						
				?>		

						
				<div class="well">
					<?php $date1 = $rows['createDateTime']; ?>
					<b><span>Topic:</span></b>
					<h4 class="appointmentTitle"><?php if(empty($rows['topic'])){ echo "Untitled Request";}else{ echo $rows['topic']; }; ?></h4>
					<b><i class="fa-solid fa-clock"></i> Date: </b> <?php echo date("l, jS \of F, Y (h:i:s A)", $date1);?></br>										
					<b><i class="fa-solid fa-user-graduate"></i> Student Name: </b> <?php echo $rows['student_name'];?> - (<?php echo $rows['student_id'];?>)</br>
				<?php if($rows['alongwith_student_name'] != '0'){?>
					<b><i class="fa-solid fa-user"></i> Alongwith Student Name: </b> <?php echo $rows['alongwith_student_name'];?> - (<?php echo $rows['alongwith_student_id'];?>)
				<?php } ?>
					</br>
					<h3><i class="fa-solid fa-arrow-down-short-wide"></i> Details </h3>
					<p><?php  if($rows['problem'] != ''){echo $rows['problem'];}else{echo "The Student haven't wrote any details about the topic or the problem.";}?></p>
					<script>
					//Chat GPT Wrote that
					const prescriptionHolder = document.getElementById('prescriptionholder');
					const images = prescriptionHolder.getElementsByTagName('img');

					for (let i = 0; i < images.length; i++) {
					  let src = images[i].getAttribute('src');
					  
					  if (!src.includes('http://') && !src.includes('https://')) {
						src = src.replace('../', '');
						images[i].setAttribute('src', src);
					  }
					}
					</script>
				</div>		

				<?php
						
					}
					if($TokenCount == 0){
						?>
						<div class="well">
							<center>
								</br>
								</br>
								</br>
								</br>
									<h3><i class="fa-solid fa-ticket"></i> You have no Tokens!</h3>
								</br>
								</br>
								</br>
								</br>
							</center>
						</div>
						<?php
					}
				}

			?>



	
			</p> 
          </div>
        </div>
		
		
<?php 	}	else { echo "Not Logged In"; } ?>