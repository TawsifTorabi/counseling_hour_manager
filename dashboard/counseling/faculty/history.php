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
	if($CurrentUserAdmin == 1){
		
		
	//Blood Donation Record
	$userID 	= $_COOKIE['userid'];
	$time = time();
	mysqli_set_charset($con,"utf8");
	?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Medical Center Portal Dashboard</title>
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
		  <li><a href="dashboard.php"><i class="fa-solid fa-house-medical"></i> Admin Dashboard</a></li>
		  <li class="active"><a href="history.php"><i class="fa-solid fa-book-medical"></i> Prescription History</a></li>
		  <li><a href="bloodbank.php"><i class="fa-solid fa-truck-droplet"></i> UIU Blood Bank Network</a></li>
		  <li><a href="../logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
	    </ul><br>
    </div>
    <br>

    <div class="col-sm-9 main">

      <h2><i class="fa-solid fa-hospital-user"></i> Your Appointment History</h2>
      <div class="row">
        <div class="col-sm-12">
          
            <p>

			<center>
			<div class="form-group input-group">
			  <input type="text" id="textInput" onkeyup="SearchTable()" class="form-control" placeholder="Search..">
			  <span class="input-group-btn">
				<button class="btn btn-default" onclick="SearchTable()" type="button">
				  <span class="glyphicon glyphicon-search"></span>
				</button>
			  </span>        
			</div>
			<table class="table table-hover">
			  <thead>
				<tr>
					<th scope="col"><i class="fa-solid fa-ticket"></i> Token No.</th>
					<th scope="col"><i class="fa-solid fa-info-circle"></i> Info</th>
					<th scope="col"><i class="fa-solid fa-user"></i> Patient Detail</th>
					<th scope="col">More Options</th>
				</tr>
			  </thead>
			  <tbody id="historycontainer">			
				<?php
					mysqli_set_charset($con,"utf8");
					$id    		= mysqli_real_escape_string($con, $_COOKIE['userid']);
					$sql        = "	SELECT * FROM `medical_tokens` 
									INNER JOIN `medical_prescription` 
									ON `medical_tokens`.`token_id` = `medical_prescription`.`token_id`
									INNER JOIN `users`
									ON `users`.`id` = `medical_tokens`.`user_id`
									WHERE `medical_tokens`.`validity`='invalid' 
									ORDER BY `medical_tokens`.`token_id` DESC";
					$result		= mysqli_query($con, $sql);
					$TokenCount = 0;
					if(!$result){
						echo mysqli_error($con);
					}
					else{
						while($rows=mysqli_fetch_array($result)){
							$TokenCount++;
							
					?>		
					<?php $date1 = $rows['createDateTime']; ?>
						<tr>
							<th><i class="fa-solid fa-ticket"></i> <i>#<?php echo $rows['token_id'];?></i></th>
							<td>
								<span style="font-size: 17px; border-bottom: 1px dotted black"><b><i class="fa-solid fa-user"></i> Name: </b> <?php echo $rows['name'];?> (<?php echo $rows['username'];?>)</span></br>
								<?php echo date("l, jS \of F, Y (h:i:s A)", $date1);?></br>
								<b><i class="fa-solid fa-phone"></i> Phone: </b> <?php echo $rows['phone'];?></br>
							</td>
							<td>
								<b><i class="fa-solid fa-droplet"></i> Blood Group: </b> <?php echo $rows['blood_group'];?></br>
								<b><i class="fa-solid fa-baby"></i> DOB: </b> <?php echo $rows['dob'];?></br>
								<b><i class="fa-solid fa-venus-mars"></i> Gender: </b> <?php echo $rows['gender'];?></br>
								<b><i class="fa-solid fa-money-bill-transfer"></i></b>
								<?php if($rows['transaction_id'] != 'null'){?>
										Paid
									<?php }else{ ?>
										<b style="color: red;">Not Paid! </b>
								<?php } ?>
								
							</td>
							<td><button class="button-boot" onclick="aurnaIframe('view-token.php?tokenid=<?php echo $rows['token_id'];?>');"><i class="fa-solid fa-list"></i> See More</button></td>
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
										<h3><i class="fa-solid fa-ticket"></i> You have no History!</h3>
										<button class="button-10" style="text-decoration: none;" onclick="aurnaIframe('create-new-token.php');">Get a Token</a></button>
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
	
	function SearchTable(){
		// get a reference to the table element
		const table = document.getElementById('historycontainer');
		let SearchQuery = document.getElementById('textInput').value;
		let BloodGroup = encodeURIComponent(document.getElementById('bloodGroupInput').value);
		let Gender = document.getElementById('genderInput').value;

		// send an AJAX request to the PHP script
		const xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (this.readyState === 4 && this.status === 200) {
				// parse the JSON response
				console.log(this.responseText);
				const users = JSON.parse(this.responseText);
				console.log(users);
				table.querySelectorAll("td").forEach(function (data) {
				  data.parentNode.remove();
				});
				// loop through the users and add rows to the table
				/*
				Ajax Returns - 
					'tokenid' => $rows['token_id'], 
					'name' => $rows['name'], 
					'username' => $rows['username'], 
					'time' => date("l, jS \of F, Y (h:i:s A)", $date1), 
					'phone' => $rows['phone'], 
					'blood_group' => $rows['blood_group'], 
					'dob' => $rows['dob'], 
					'transaction_id' => $rows['transaction_id'] 
				*/
				for (let i = 0; i < users.length; i++) {
					let payment; 
					if(users[i].transaction_id === 'null'){ 
						payment = "<b style=\"color: red;\"> Not Paid!</b>";
					}else{
						payment = "Paid";
					}
										
					const row = table.insertRow();
					const cell1 = row.insertCell(0);
					const cell2 = row.insertCell(1);
					const cell3 = row.insertCell(2);
					const cell4 = row.insertCell(3);
					cell1.innerHTML = "<i class=\"fa-solid fa-ticket\"></i> <i>#"+users[i].tokenid+"</i>";
					cell2.innerHTML = "<span style=\"font-size: 17px; border-bottom: 1px dotted black\"><b><i class=\"fa-solid fa-user\"></i> Name: </b> "+users[i].name+" ("+users[i].username+")</span></br>"
										+users[i].time+"</br>"
										+"<b><i class=\"fa-solid fa-phone\"></i> Phone: </b> "+users[i].phone+"</br>";
					cell3.innerHTML = "<b><i class=\"fa-solid fa-droplet\"></i> Blood Group: </b> "+users[i].blood_group+"</br>"+
									  "<b><i class=\"fa-solid fa-baby\"></i> DOB: </b> "+users[i].dob+"</br>"+
									  "<b><i class=\"fa-solid fa-gender\"></i> Gender: </b> "+users[i].gender+"</br>"+
									  "<b><i class=\"fa-solid fa-money-bill-transfer\"></i></b>"+ payment
					cell4.innerHTML = "<button class=\"button-boot\" onclick=\"aurnaIframe('view-token.php?tokenid="+users[i].tokenid+"');\"><i class=\"fa-solid fa-list\"></i> See More</button>";
				}
			}
		};
		xhr.open('GET', 'ajax.php?data=PrescriptionSearch&q='+SearchQuery+'&bg='+BloodGroup+'&gender='+Gender, true);
		xhr.send();
	}
</script>
</body>
</html>



<?php 	}}	else { echo "<script>window.open('../login.php','_self')</script>"; } ?>