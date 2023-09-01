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
	
	if(mysqli_num_rows(mysqli_query($con, "select * from sessions where session_id='$getsessionID' AND validity='$validity'"))> 0){
	?>
		
		
	  <link rel="stylesheet" type="text/css" href="css/aurna-lightbox.css"/>
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	   <div class="col-sm-12">
          <div class="well">
            <p>
				<h3 style="margin-left:30px;">AVAILABLE DOCTOR</h3>
				<center>
					<?php
					if ($resultQ = $conn->query("SELECT * FROM staffs_profile WHERE type='doctor' ORDER BY id LIMIT 1")->num_rows > 0) {
						// output data of each row
						if($rowDoctorDetail = $conn->query("SELECT * FROM staffs_profile WHERE type='doctor'  ORDER BY id DESC LIMIT 1")->fetch_assoc()) {
					?>
					<div class="headnamecontainer">
						<div style="display: inline-block;vertical-align: bottom; "><img class="profilephoto" style="width: 40vh;" src="images/myphoto.jpg"></div>	
						<div style="display: inline-block;text-align: left;margin-left: 32px;">
							<br>
							<h4 class="namehead"><?php echo $rowDoctorDetail['first_name'] ?> <?php echo $rowDoctorDetail['last_name'] ?></h4>
							<br>
							<p class="parentdetail">
							<?php echo $rowDoctorDetail['bio'] ?>
							</p>
							</br>
						</div>
					</div>
					<?php }}else{ ?>
						<div class="headnamecontainer" style="font-family: Trebuchet MS;">
							</br>
							</br>
							<h1>No Doctor Available!</h1>
							<h3>Contact with UIU Medical Center Now!</h3>
						</div>
					<?php } ?>
				</center>		
			</p> 
          </div>
        </div>
		
		
<?php 	}	else { echo "Not Logged In"; } ?>