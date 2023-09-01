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
		
	include("model/UserCheck.php");
	if($CurrentUserAdmin == 1){
		header('Location: admin/index.php');
	}
	
	//Blood Donation Record
	$userID 	= $_COOKIE['userid'];
	$time = time();
	mysqli_set_charset($con,"utf8");
	
	// Check If User Donated 
	$row = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `medical_blood_donation_record` WHERE `user_id`='$userID' ORDER BY `last_donated` DESC LIMIT 1"));
	if($row >= 1){;
		//Get Last Donation Record
		if($row12 = $conn->query("SELECT * FROM `medical_blood_donation_record` WHERE `user_id`='$userID'")->fetch_assoc()){
			$last_donated_time = $row12['last_donated'];
		}
		
		$last_donation_date = gmdate("Y-m-d", $last_donated_time);
		$current_date = gmdate("Y-m-d", $time);
		$datediff = $time - $last_donated_time;
		$dateCount = round($datediff / (60 * 60 * 24));
		
		if($dateCount > 119){								
			$BloodMsessageData = "<span class='msg-bar-green'><i class='fa-solid fa-circle-check'></i> You are Eligible to Donate!</span>";
			$BloodBoolean = "true";
			$LastDonate = $last_donated_time;
		}else{
			$BloodMsessageData = "<span class='msg-bar-red'><i class='fa-solid fa-circle-exclamation'></i> 4 Months Not Passed!</span>";
			$BloodBoolean = "false";
			$LastDonate = $last_donated_time;
		}
		
	}else{
			$BloodMsessageData = "<span class='msg-bar-green'><i class='fa-solid fa-circle-check'></i> You are Eligible to Donate!</span>";
			$BloodBoolean = "true";
			$LastDonate = 'null';
	}
		

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Medical Center Portal Dashboard</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/aurna-lightbox.css"/>
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
      <h2><i class="fa-solid fa-notes-medical"></i> UIU Medical Portal</h2>
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
		  <li><a href="dashboard.php"><i class="fa-solid fa-house-medical"></i> Medical Dashboard</a></li>
		  <li><a href="history.php"><i class="fa-solid fa-book-medical"></i> Your History</a></li>
		  <li class="active"><a href="donationrecord.php"><i class="fa-solid fa-hand-holding-droplet"></i> Blood Donation Record</a></li>
		  <li><a href="bloodbank.php"><i class="fa-solid fa-truck-droplet"></i> UIU Blood Bank Network</a></li>
		  <li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
	    </ul><br>
    </div>
    <br>
	<div class="col-sm-9 main">
		<div class="col-sm-4">
		  <div class="well">
			<p>
				<h3><i class="fa-solid fa-hand-holding-droplet"></i> Blood Donation</h3>
				
				<?php
					// output data of blood group
					if($row = $conn->query("SELECT * FROM users WHERE id='$userid'")->fetch_assoc()) {
						?>
						<b>Blood Group: <?php echo $row['blood_group']; ?></b></br>
						<?php
					}
				?>
				Keep a Record of you donation.</br>
				<button onclick="DonatedBlood()" id="yesDonate" class="button-10">
					<i class="fa-solid fa-tarp-droplet"></i> Record A Donation
				</button>&nbsp;</br>
				<span id="bloodMsg"></span>
				</br>
				<small>Recording Donation will hide your name from the UIU Blood Bank for 4 months.</small>
				</br>
			</p> 
		  </div>
		</div>
		<div class="col-sm-4">
		  <div class="well">
			<p>
						
				<h3><i><span>Message: </br></span></i></h3>
				<?php echo $BloodMsessageData; ?>
				</br></br>
				<a style="margin-bottom: 4px;"href="bloodbank.php"><i class="fa-solid fa-truck-droplet"></i>
				 Browse UIU Blood Bank Network
				</a>
			</p> 
		  </div>
		</div>
		<div class="col-sm-4">
		  <div class="well">
			<p>
						
				<h3><span>Settings</span></h3>
					Hide Name from Blood Network
					<div class="material-switch pull-right">
						<input id="hide_name_blood_network" name="someSwitchOption001" type="checkbox"/>
						<label for="hide_name_blood_network" onclick="setHidename()" class="label-success"></label>
					</div>
				</br>
			</p> 
		  </div>
		</div>
		
		
		<?php if($BloodBoolean == "false"){ ?>
		<div class="col-sm-8">
		  <div class="well">
			<p>
				<i class="fa-solid fa-clock"></i><b> Countdown to Next Donation</b>: 
				<b id="timer" style=""></b>
			</p> 
		  </div>
		</div>
		<?php } ?>	
		
		
	</div>

    <div class="col-sm-9 main">
      <h2><i class="fa-solid fa-hand-holding-droplet"></i> Your Donation Records</h2>
      <div class="row">
        <div class="col-sm-12">
          
            <p>

			<center>
			<table class="table table-hover">
			  <thead id="tablehead">
				<tr>
					<th scope="col"><i class="fa-solid fa-ticket"></i> Donation ID.</th>
					<th scope="col"><i class="fa-solid fa-clock"></i> Donation Record Date</th>
					<th scope="col"><i class="fa-solid fa-archive"></i> Note</th>
					<th scope="col"><i class="fa-solid fa-eject"></i></th>
					<th scope="col"><i class="fa-solid fa-list"></i></th>
				</tr>
			  </thead>
			  <tbody>			
				<?php
					mysqli_set_charset($con,"utf8");
					$id    		= mysqli_real_escape_string($con, $_COOKIE['userid']);
					$sql        = "SELECT * FROM `medical_blood_donation_record` INNER JOIN `users` ON `medical_blood_donation_record`.`user_id` = `users`.`id` WHERE `medical_blood_donation_record`.`user_id` = '$id' ORDER BY `medical_blood_donation_record`.`last_donated` DESC";
					$result		= mysqli_query($con, $sql);
					$TokenCount = 0;
					if(!$result){
						echo mysqli_error($con);
					}
					else{
						while($rows=mysqli_fetch_array($result)){
							$TokenCount++;
							
					?>		
					<?php $date1 = $rows['last_donated']; ?>
						<tr id="datarow<?php echo $rows['donate_id'];?>">
							<th scope="row"><i>#<?php echo $rows['donate_id'];?></i></th>
							<td><?php echo date("l, jS \of F, Y", $date1);?></td>
							<td>
								<?php if($rows['note'] != ''){?>
										<?php echo $rows['note'];?>
									<?php }else{ ?>
										<button class="button-pay" onclick="aurnaIframe('editrecordnote.php?donation_id=<?php echo $rows['donate_id'];?>');"><i class="fa-solid fa-pencil"></i> Add Note</button>		
								<?php } ?>								
							</td>
							<td>
								<div class="dropdown">
								<button class="btn btn-primary dropdown-toggle" type="button" id="about-us" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Action
								<span class="caret"></span>
								</button>
								<ul class="dropdown-menu" aria-labelledby="about-us">
									<li><a href="javascript:aurnaIframe('editrecordnote.php?donation_id=<?php echo (int)$rows['donate_id'];?>')"><i class="fa-solid fa-pencil"></i> Edit</a></li>
									<li><a href="javascript:deleteRecord(<?php echo $rows['donate_id'];?>, this)"><i class="fa-solid fa-trash"></i> Delete</a></li>
								</ul>
								</div>
							</td>
							<td>
								
							</td>
						</tr>

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
										<h3><i class="fa-solid fa-ticket"></i> You have no Record!</h3>
										<script>
											document.getElementById('tablehead').style.display = 'none';
										</script>
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
				 </tbody>
			</table>
			</center>	
	
			</p> 
        </div>	      	
      </div> 
    </div>


</body>
</html>


<script>
	String.prototype.isMatch = function(s){
	   return this.match(s)!==null
	}


	var inputHidename = document.getElementById('hide_name_blood_network');
	LoadSetting();
	function LoadSetting(){
		let	xmlhttp=new XMLHttpRequest();
		
		xmlhttp.onreadystatechange=function() {
			
			if(this.readyState == 4 && this.status == 200){
				console.log(this.responseText);				
				let RetJson = JSON.parse(this.responseText);
				if(RetJson[0].hide_user_bloodbank === '1'){
					console.log('checked');
					inputHidename.setAttribute("checked","");
				}else if(RetJson[0].hide_user_bloodbank ==='0'){
					console.log('unchecked');
					inputHidename.removeAttribute("checked");
				}
			}else{
				//Do Nothing
			}
			
		}
		
		xmlhttp.open("GET","ajax.php?data=GetDataSettings", true);
		xmlhttp.send();		
	}
	
	function setHidename(){
		let	xmlhttp=new XMLHttpRequest();		
		xmlhttp.onreadystatechange=function() {
			if(this.readyState == 4 && this.status == 200){
				let RetJson = JSON.parse(this.responseText);
				if(RetJson[0].updated === 'true'){
					if(RetJson[0].state === '1'){
						console.log('checked');
						inputHidename.setAttribute("checked","");
					}else if(RetJson[0].state ==='0'){
						console.log('unchecked');
						inputHidename.removeAttribute("checked");
					}
				}
			}else{
				if(inputHidename.checked){
					inputHidename.setAttribute("checked","");
				}else{
					inputHidename.removeAttribute("checked");
				}
			}
		}
		xmlhttp.open("GET","ajax.php?data=SetHideFromBloodBankSettings", true);
		xmlhttp.send();		
	}
	

function deleteRecord(id){
	
	// send an AJAX request to the PHP script
	const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {
			// parse the JSON response
			console.log(this.responseText);
			const returnData = JSON.parse(this.responseText);
			console.log(returnData);
			if(returnData.deleted.isMatch('true')){
				var row = document.getElementById('datarow'+id);
				alert('Data Deleted Successfully!');
				row.remove();
			}else if(returnData.deleted.isMatch('false')){				
				alert('Error Occured!');
			}
		}
	};
	xhr.open('GET', 'ajax.php?data=DeleteBloodRecord&donation_id='+id, true);
	xhr.send();
}

	let bloodBtnBool = '<?php echo $BloodBoolean; ?>';
	if(bloodBtnBool == 'false'){
		document.getElementById('yesDonate').setAttribute("disabled","true");
		document.getElementById('yesDonate').innerHTML='<i class="fa-solid fa-tarp-droplet"></i> Already Recorded!';
	}else{
		//Do Nothing
	}
	
	var lastDonateTime = <?php echo $LastDonate; ?>;
	if(bloodBtnBool == 'false' && lastDonateTime != 'null'){
		// Get the current time in seconds
		var now = Math.floor(Date.now() / 1000);

		// The given Unix timestamp, representing the last donation time
		var lastDonationTime = lastDonateTime; // Replace with your own timestamp

		// Calculate the number of seconds elapsed since the last donation
		var secondsSinceLastDonation = now - lastDonationTime;

		// Calculate the number of seconds in four months
		var secondsInFourMonths = 60 * 60 * 24 * 30 * 4;

		// If four months have passed since the last donation
		if (secondsSinceLastDonation >= secondsInFourMonths) {
		  // Display the "You are eligible" message
		  document.getElementById("timer").textContent = "You are eligible";
		} else {
		  // Calculate the number of seconds remaining until four months have passed
		  var secondsRemaining = secondsInFourMonths - secondsSinceLastDonation;

		  // Update the timer every second
		  var intervalId = setInterval(function() {
			// Decrement the number of seconds remaining
			secondsRemaining--;

			// Calculate the remaining days, hours, minutes, and seconds
			var days = Math.floor(secondsRemaining / (60 * 60 * 24));
			var hours = Math.floor((secondsRemaining % (60 * 60 * 24)) / (60 * 60));
			var minutes = Math.floor((secondsRemaining % (60 * 60)) / 60);
			var seconds = secondsRemaining % 60;

			// Format the remaining time as a string
			var remainingTime = days + " days, " + hours + " hours " + minutes + " miniutes " + seconds + " seconds";

			// Display the remaining time in the "timer" div
			document.getElementById("timer").textContent = remainingTime;

			// If the timer has reached zero, display the "You are eligible" message
			if (secondsRemaining <= 0) {
			  document.getElementById("timer").textContent = "You are eligible";
			  clearInterval(intervalId);
			}
		  }, 1000);
		}
	}
	
	
	function DonatedBlood(){
		let BtnCont = 'yesDonate';
		let BloodMsg = 'bloodMsg';
		document.getElementById(BtnCont).setAttribute("disabled","true");
		document.getElementById(BtnCont).innerHTML='Registering...';
		let	xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function() {
			if(this.readyState == 4 && this.status == 200) {
				document.getElementById(BtnCont).disabled='true';
				console.log(this.responseText);
				let RetJson = JSON.parse(this.responseText);
				if(RetJson.posted.isMatch('true')){
					document.getElementById(BtnCont).innerHTML='<i class="fa-solid fa-check"></i> Recorded';
					setTimeout(function(){location.reload()}, 1000);
				}
				if(RetJson.posted.isMatch('false')){
					document.getElementById(BtnCont).innerHTML='<i class="fa-solid fa-check"></i> Canceled';
				}
				document.getElementById(BloodMsg).innerHTML= RetJson.message;
			}else{
				
			}
		}
		xmlhttp.open("GET","ajax.php?data=DonateBloodRecord", true);
		xmlhttp.send();		
	}
</script>
</body>
</html>


<?php 	}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>