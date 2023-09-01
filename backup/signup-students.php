<?php
	session_start();
	
	//create database connection
	include("commons/connect_db.php");
	
	//create blank variable
	$getsessionID = "";
	$message = "";
	
	
	//call session data
	if(isset($_SESSION['librarypanel'])){	
		//get session id from browser and update variable
		$getsessionID = $_SESSION['librarypanel'];
	}
	
	//set the validity mode for session data
	$validity = mysqli_real_escape_string($con,"valid");
	
	//verify session id
	if(mysqli_num_rows(mysqli_query($con, "select * from sessions where session_id='$getsessionID' AND validity='$validity' ORDER BY `id` DESC LIMIT 1"))> 0){
	
		echo "<script>window.open('logout.php','_self')</script>";
	
	} else {
			
				//get signup form data
				
				if(isset($_POST['signup'])){
					//get user name and password
					$name = mysqli_real_escape_string($con, $_POST["full_name"]);
					$user_name = mysqli_real_escape_string($con, $_POST["user_name"]);
					$user_pass = mysqli_real_escape_string($con, $_POST["pass"]);
					$user_blood_group = mysqli_real_escape_string($con, $_POST["blood_group"]);
					$user_dob = mysqli_real_escape_string($con, $_POST["dob"]);
					$user_gender = mysqli_real_escape_string($con, $_POST["gender"]);
					
					//match the username from database
					if(mysqli_num_rows(mysqli_query($con, "select * from users where username='$user_name'"))<= 0){

						//get current time
						$issuetime = time();

						//Save Information to Database
						mysqli_query($con, "
						 Insert Into `users` (`name`, `username`, `password`, `adminprivilege`, `CreationTimestamp`, `blood_group`, `dob`, `gender`) Values
						  (
							'$name',
							'$user_name',
							'$user_pass',
							'no',
							'$issuetime',
							'$user_blood_group',
							'$user_dob',
							'$user_gender'
						  )
						  ");	  
						echo "<script>window.open('login.php','_self')</script>";
					}else{
						$message = "Same Username Found";
					}
				}
		
			?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" type="text/css" href="assets/css/signup.css"/>
		<title>Signup</title>
	</head>
	
	<body>
		<div style="height: 100px;"></div>
		<div style="margin-left: 30%;margin-right: 30%;">
		
			<div style="margin-left:-36px; text-align: center;">
				<h3 style="color: crimson; margin: unset;">We're here to</h3> 
				<h1 style="color: crimson; margin: unset;">annoy you.</h1>
				<br>
				<input type="text" autocomplete="off" placeholder="Name..." name="name" class="inputArea" id="name"><br>
				<input type="text" autocomplete="off" placeholder="Email..." name="email" class="inputArea" id="email"><br>
				<input type="password" autocomplete="off" placeholder="Password..." class="inputArea" id="pass"><br>
				<input type="password" autocomplete="off" placeholder="Retype Password..." name="pass" class="inputArea" id="confpass"><br>
				<select style="width: 120px;" class="inputBox" name="blood_group">
					<option selected value="none">Blood Group 🔽</option>
					<option value="A+">A+</option>
					<option value="A-">A-</option>
					<option value="B+">B+</option>
					<option value="B-">B-</option>
					<option value="AB+">AB+</option>
					<option value="AB-">AB-</option>
					<option value="O+">O+</option>
					<option value="O-">O-</option>
				</select>
				<select style="width: 120px;" class="inputBox" name="gender">
					<option selected value="none">Gender 🔽</option>
					<option value="male">Male</option>
					<option value="female">Female</option>
					<option value="others">Others</option>
				</select>
				</br>
				<button type="submit" name="submit" id="buttonSbmt" class="buttonSbmt naughtyBtnGoRight" onmouseover="flyAway()" disabled="disabled">Submit</button>
				<br>
				<div id="notificDiv" style="display: none; background: crimson; text-align: center;" class="alert">
				  <span class="closebtn" onclick="this.parentElement.style.display='none';">×</span> 
				  <span id="notification"></span>
				</div>
			</div>
		</div>
		<div style="height: 100px;"></div>

		<script>	
		var button = document.getElementById('buttonSbmt');
		var nameinput = document.getElementById('name');
		var email = document.getElementById('email');
		var pass = document.getElementById('pass');
		var confpass = document.getElementById('confpass');
		
		button.setAttribute("onmouseover","flyAway()");
		button.setAttribute("disabled","");
		
		var InputArr = [nameinput, email, pass, confpass];
		
		for(var i=0; i < InputArr.length; i++){
			InputArr[i].addEventListener("keyup", function(){
				Validate();
			});
			InputArr[i].addEventListener("change", function(){
				Validate();
			});
		}
		
		function resetButtonStyle(){
			button.style.removeProperty("margin-right");
			button.style.removeProperty("margin-left");
			button.removeAttribute("disabled","");
		}

		function flyAway(){
			if(button.style.marginRight == false){   
				button.style.removeProperty("margin-left");
				button.style.marginRight = "-"+((Math.random()*200)+70)+"px";
			}else{
				button.style.removeProperty("margin-right");
				button.style.marginLeft = "-"+((Math.random()*200)+80)+"px";
			}
		}

		function formEnabled(msg){
			notificDiv.style.display = "inline-block";
			notificDiv.style.background = "limegreen";
			notification.innerHTML = msg;
			button.removeAttribute("onmouseover");
			resetButtonStyle();
		}
		
		function formDisabled(msg){
			notificDiv.style.display = "inline-block";
			notification.innerHTML = msg;
			notificDiv.style.background = "crimson";
			button.setAttribute("onmouseover","flyAway()");
			button.setAttribute("disabled","");
		}
		
		document.onload= function(){
			console.log('Loaded!');
			Validate();
		};
		
		
		function Validate(){
			let validated = 0;
			if(nameinput.value.length > 0){
				//First Check if full name is entered
				if(nameinput.value.length < 4){
					formDisabled("Input Full Name");
					nameinput.classList.add("inputAreaAlert");
				}else{
					nameinput.classList.remove("inputAreaAlert");
					notification.parentElement.style.display= 'none';
					validated++;
				}
			}
			
			if(email.value.length > 0){
				//Check Email is correctly entered
				if(email.value.split('@').length == 2){
					email.classList.remove("inputAreaAlert");
					notification.parentElement.style.display= 'none';
					validated++;
				}else{
					formDisabled("Input Correct Email address");
					email.classList.add("inputAreaAlert");
				}
			}
			
			if(pass.value.length > 0){
				if(pass.value.length > 8){
					//If Password Value is longer than 8
					pass.classList.remove("inputAreaAlert");
					notification.parentElement.style.display= 'none';
					if(confpass.value.length > 0){
						//If Retype Password value is more than 0, the user started typing.
						if(pass.value == confpass.value){
							//Check If password and retype password Matches
							pass.classList.remove("inputAreaAlert");
							confpass.classList.remove("inputAreaAlert");
							validated++;
						}else{
							//If password does not matches
							formDisabled("Password Mismatch");
							confpass.classList.add("inputAreaAlert");
							
						}
					}
				}
				
				if(pass.value.length <= 8){
					formDisabled("Password Should be more than 8");
					pass.classList.add("inputAreaAlert");
				}
			}
			console.log(validated);
			
			if(validated == 3){
				//All Three Inputs Validated
				formEnabled("Green Light! Green Light!");
			}
								String.prototype.isMatch = function(s){
					   return this.match(s)!==null
					}
		}
		</script>
	

</body></html>
	<?php } ?>