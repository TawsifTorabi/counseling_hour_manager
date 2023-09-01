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
		
	include("../model/UserCheck.php");
	if($CurrentUserFaculty == 1){
		
		
	//Blood Donation Record
	$userID 	= $_COOKIE['userid'];
	$time = time();
	mysqli_set_charset($con,"utf8");
	?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Counselling Hour Time Slots</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="../css/aurna-lightbox.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 550px}
    
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
      .row.content {height: auto;} 
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

	@keyframes blink-live{

		0% { opacity: 1.0; }
		50% { opacity: 0.0; }
		100% { opacity: 1.0; }
	}
	.button-boot{
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
		transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
		color: #0d6efd;
		border-color: #0d6efd;
	}
	.button-boot:hover {
		color: #fff;
		background-color: #0d6efd;
		border-color: #0d6efd;
	}
	
	.button-pay{
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
		transition: background-color .24s,box-shadow .24s;
	}
	.button-pay:hover {
		box-shadow: rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(0 0 0 / 12%) 0px 1px 1px 0px, rgb(84 105 212) 0px 0px 0px 1px, rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(60 66 87 / 8%) 0px 3px 9px 0px, rgb(60 66 87 / 8%) 0px 2px 5px 0px;
	}

	.msg-bar-red{
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
	.material-switch > input[type="checkbox"] {
		display: none;   
	}

	.material-switch > label {
		cursor: pointer;
		height: 0px;
		position: relative; 
		width: 40px;  
	}

	.material-switch > label::before {
		background: rgb(0, 0, 0);
		box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
		border-radius: 8px;
		content: '';
		height: 16px;
		margin-top: -8px;
		position:absolute;
		opacity: 0.3;
		transition: all 0.4s ease-in-out;
		width: 40px;
	}
	.material-switch > label::after {
		background: rgb(255, 255, 255);
		border-radius: 16px;
		box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
		content: '';
		height: 24px;
		left: -4px;
		margin-top: -8px;
		position: absolute;
		top: -4px;
		transition: all 0.3s ease-in-out;
		width: 24px;
	}
	.material-switch > input[type="checkbox"]:checked + label::before {
		background: inherit;
		opacity: 0.5;
	}
	.material-switch > input[type="checkbox"]:checked + label::after {
		background: inherit;
		left: 20px;
	}
  </style>
</head>
<body>
<script src="../js/aurna-lightbox.js"></script>
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
      <h2><i class="fa-solid fa-notes-medical"></i> UIU Counselling Hour </br>Booking System</h2>
		<a href="javascript:void(0);">
			<?php
				$userid = $_COOKIE['userid'];
				if ($conn->query("SELECT name FROM users WHERE id='$userid'")->num_rows > 0) {
					// output data of each row
					if($row = $conn->query("SELECT name FROM users WHERE id='$userid'")->fetch_assoc()) {
						echo "<span>Welcome! <strong>".$row['name']."</strong></span>";
					}
				} else {
					echo "<b>Something Went Wrong!</b>";
				}
			?>
		</a>&nbsp;
		
		</br>
		</br>
        <ul class="nav nav-pills nav-stacked">
		  <li><a href="../../"><i class="fa-solid fa-house"></i> Back to Homepage</a></li>
		  <li><a href="dashboard.php"><i class="fa-solid fa-dashboard"></i> Faculty Dashboard</a></li>
		  <li class="active"><a href="timeslots.php"><i class="fa-solid fa-clock"></i> Time Slots</a></li>
		  <li><a href="history.php"><i class="fa-solid fa-book"></i> History</a></li>
		  <li><a href="chat.php"><i class="fas fa-comment"></i> Student Chats</a></li>
		  <li><a href="../logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
	    </ul><br>
    </div>
    <br>
	<style>
		.highlitedRow{
			background: linear-gradient(45deg, #b5ffc9, #05fbb78a);
			transition: 0.6s;
		}
		.highlitedRowNull{
			background: transparent;
			transition: 0.6s;
		}
	</style>
    <div class="col-sm-9 main">

      <h2><i class="fa-solid fa-hospital-user"></i> Counselling Hour Time Slots</h2>
	  </br>
      <div class="row">
        <div class="col-sm-12">
			<center>
			<table class="table table-hover table-dark" id="users-table">
			  <thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Start Time</th>
					<th scope="col">End Time</th>
					<th scope="col">#</th>
					<th scope="col"></th>
				</tr>
			  </thead>
			  <tbody>
				<?php
				
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
					$current_user_faculty_id = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM academic_faculty_profile WHERE user_id='$currentUser'"))['id'];
					
				
					mysqli_set_charset($con, "utf8");
					$sql        = "	SELECT 
										*
									FROM `academic_counseling_hours` 
									ORDER BY `id` 
									ASC";
					$result		= mysqli_query($con, $sql);
					if(!$result){
						echo mysqli_error($con);
					}
					else{
						while($rows=mysqli_fetch_array($result)){
								$SelectedMarker = 0;
								$BookmarkQuery = mysqli_query($con, "SELECT * FROM `academic_counselling_faculty_selected_hours` WHERE hour_id='".$rows['id']."' AND faculty_id='".$current_user_faculty_id."'"); 
								if(mysqli_num_rows($BookmarkQuery) > 0){
									$SelectedMarker = 1;
									$TRclassNames = 'highlitedRow';
								}else{ 
									$SelectedMarker = 0;
									$TRclassNames = 'highlitedRowNull';
								} 
					?>
						<tr class="<?= $TRclassNames; ?>">
							<td></td>
							<td>
								<?= $rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime'] ?></br>
								<?=  cusgetTimeOfDay($rows['start_hour'].':'.$rows['start_minute'].' '.$rows['start_daytime']); ?>
							</td>
							<td>
								<?= $rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime'] ?></br>
								<?=  cusgetTimeOfDay($rows['end_hour'].':'.$rows['end_minute'].' '.$rows['end_daytime']); ?>
							</td>
							<td>
							<?php if($SelectedMarker == 0){	?>
								<div class="material-switch pull-right">
									<input id="timeslot<?= $rows['id']?>" onchange="toggleTimeslot(this,'<?= $rows['id']?>')" name="someSwitchOption<?= $rows['id']?>" type="checkbox"/>
									<label for="timeslot<?= $rows['id']?>" class="label-success"></label>
								</div>
							<?php }else{ ?>
								<div class="material-switch pull-right">
									<input id="timeslot<?= $rows['id']?>" onchange="toggleTimeslot(this,'<?= $rows['id']?>')" name="someSwitchOption<?= $rows['id']?>" checked type="checkbox"/>
									<label for="timeslot<?= $rows['id']?>" class="label-success"></label>
								</div>

							<?php } ?>
							</td>
							<td></td>
						</tr>
					<?php
						}
					}
				?>
			  </tbody>
			</table>
			</center>	
        </div>	      	
      </div> 
    </div>


</body>
</html>


<script>
	String.prototype.isMatch = function(s){
	   return this.match(s)!==null
	}

	function w3alert(text){
		const w3alert = document.getElementById('w3alert');
		const w3alertContent = document.getElementById('w3alertContent');
		w3alert.style.display = 'unset';
		w3alertContent.innerHTML = text;
		const timeout = setTimeout(function(){
			w3alertContent.parentElement.style.display='none';
		}, 2000);
	}
	
	function toggleTimeslot(checkbox, id) {
	  const url = `ajax.php?data=sethourid&hour_id=${id}`;
	  const xhr = new XMLHttpRequest();
	  xhr.open('GET', url);
	  xhr.onload = () => {
		const { checked } = JSON.parse(xhr.responseText);
		checkbox.closest('tr').classList.toggle('highlitedRow', checked === 'true');
		checkbox.closest('tr').classList.toggle('highlitedRowNull', checked === 'false');
		console.log(`Hour Slot ${checked === 'true' ? 'checked' : 'Un-checked'}!`);
		w3alert(`Hour Slot ${checked === 'true' ? 'Checked' : 'Un-checked'}!`);
	  };
	  xhr.send();
	}
	
</script>

<style>
.alert {
	padding: 20px;
	background-color: #f44336;
	color: white;
	position: fixed;
	width: 20%;
	bottom: 0;
	left: 20px;
	z-index: 999999999999;
	font-size: 14px;
	vertical-align: middle;
}

.closebtn {
  margin-left: 15px;
  color: white;
  font-weight: bold;
  float: right;
  font-size: 22px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: black;
}
</style>
<div id="w3alert" style="display: none;" class="alert">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
  <span id="w3alertContent"><strong>Danger!</strong> Indicates a dangerous or potentially negative action.</span>
</div>

</body>
</html>


	<?php 	}}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>