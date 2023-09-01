<?php
	session_start();
	
	//create database connection
	include("connect_db.php");
	
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
				<title>Sign Up - <?php echo $GlobalAppName; ?></title>
				<style type="text/css" >
				@font-face {
					font-family: nlight;
					src: url(css/NexaLight.otf);
				}
				@font-face {
					font-family: nbold;
					src: url(css/NexaBold.otf);
				}

				body, html {
					height: 100%;
					margin: 0;
					font-family: nlight;
					/*background: linear-gradient(#f70cbc, #73009d);*/
					color: white;
				}

				html{
					margin: 0px;
					background-image: linear-gradient(#f70cbc, #73009d);
					height: 100%;
					background-position: center;
					background-attachment: fixed;
					background-repeat: no-repeat;
					background-size: cover;
				}
				.clearfix{
					height: 40px;
				}
				.bloglinks {
				  color: white;
				  text-decoration: none;
				  font-style: italic;
				  background-color: #ce0adf2e;
				  padding: 5px;
				  line-height: 14px;
				  font-size: 12px;
				  border-radius: 2px;
				  text-shadow: 1px 1px 2px #00000078;
				  width: 100%;
				  display: inline-block;
				  margin-bottom: 2px;
				  box-shadow: 0px 0px 10px #00000054;
				  transition: 0.3s;
				  animation: ease-in;
				}
				.bloglinks:hover {
				  background-color: #d60fe87d;
				  padding: 5px;
				  line-height: 14px;
				  font-size: 13px;
				  border-radius: 2px;
				  text-shadow: 1px 1px 2px #00000078;
				  display: inline-block;
				  margin-bottom: 2px;
				  transition: 0.3s;
				  animation: ease-in;
				}

				.bgimage{
					margin: 0px;
					background-image: url("../images/cover2.jpg");
					height: 100%; 
					background-position: center;
					background-attachment: fixed;
					background-repeat: no-repeat;
					background-size: cover;
				}

				.bgtext {
					float: left;
				}



				.logoicontop{
				  border-radius: 50%;
				  border: 3px solid white;
				  height: 62px;
				  padding: 2px;
				  position: fixed;
				  z-index: 9999;
				  overflow: visible;
				  background: linear-gradient(to left top, #34039d, #d75a9c);
				  margin-top: 11px;
				  margin-left: 20px;
				}

				.headnamecontainer {
				  display: table-cell;
				  font-family: nexa light;
				  font-synthesis: style;
				  background-color: rgba(0,0,0, 0.4);
				  color: white;
				  font-weight: bold;
				  width: 42%;
				  padding: 21px;
				  margin-top: 8%;
				  vertical-align: text-bottom;
				  height: 342px;
				  padding-left: unset;
				}

				.headnamecontainer_1 {
				  display: table-cell;
				  font-family: nexa light;
				  font-synthesis: style;
				  background-color: rgba(184, 6, 234, 0.65);
				  color: white;
				  font-weight: bold;
				  width: 42%;
				  padding: 21px;
				  padding-left: 21px;
				  margin-top: 8%;
				  vertical-align: text-bottom;
				  height: 342px;
				  padding-left: unset;
				}

				.skillcontainer {
				  display: table-cell;
				  font-family: nexa light;
				  font-synthesis: style;
				  background-color: rgba(0,0,0, 0.4);
				  color: white;
				  font-weight: bold;
				  width: 42%;
				  padding: 21px;
				  margin-top: 8%;
				  vertical-align: text-bottom;
				  height: 342px;
				  padding-left: unset;
				}


				.namehead {
					letter-spacing: 3px;
					font-family: nlight;
					font-size: 60px;
				}
				.thishead {
					letter-spacing: 3px;
					font-family: nlight;
					font-size: 20px;
				}
				.parentdetail{
					letter-spacing: 1px;
					font-family: nlight;
					font-size: 14px;		
					word-wrap: break-word;
				}


				.profilephoto{
					border-radius: 50%;
					border: 8px solid #fff;
					/* width: 25%; */
					height: 237px;
					box-shadow: 0 7px 8px -4px #662d8c,0 10px 30px 4px rgba(207,0,255,.5),0 4px 5px -1px #2b0950,0 0 1px 0 rgba(207,0,255,.5),inset 0 0 0 1px rgba(255,255,255,.24);
					transition: 0.7s;
				}
				.profilephoto:hover {
					border: 8px solid #d98eff;
					transition: 0.7s;
					box-shadow: 0 10px 10px -4px #662d8c,0 15px 30px 4px rgba(207,0,255,.5),0 7px 7px -1px #2b0950,0 1px 1px 0 rgba(207,0,255,.5),inset 0 0 8px 0 #ff005b;
				}

				@media screen and (max-width: 600px) {
					.profilephoto{
						margin-bottom: 21px;
					}
				}






				@keyframes glitch {
				  0% {
					text-shadow: -2px 3px 0 red, 2px -3px 0 blue;
					transform: translate(var(--glitch-translate));
				  }
				  2% {
					text-shadow: 2px -3px 0 red, -2px 3px 0 blue;
				  }
				  4%, 100% {  text-shadow: none; transform: none; }
				}

				/* Top navigation */

				.topnav {
				  overflow: hidden;
				  background-color: #333;
				  z-index: 5;
				  width: 100%;
				}

				.topnav a {
				  float: left;
				  display: block;
				  color: #f2f2f2;
				  text-align: center;
				  padding: 14px 16px;
				  text-decoration: none;
				  font-size: 17px;
				  font-family: nbold;
				  text-shadow: 1px 2px 0px rgba(0, 255, 231, 0.57);
				  background-color: #510044;
				}

				.active {
				  background: linear-gradient(#f70cbc, #73009d);
				  color: white;
				}

				.topnav .icon {
				  display: none;
				}

				.dropdown {
				  float: left;
				  overflow: hidden;
				}

				.dropdown .dropbtn {
				  font-size: 17px;    
				  border: none;
				  outline: none;
				  color: white;
				  padding: 14px 16px;
				  background-color: inherit;
				  font-family: nbold;
				  margin: 0;

				}

				.dropdown-content {
				  display: none;
				  position: absolute;
				  background-color: #f9f9f9;
				  min-width: 160px;
				  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				  z-index: 1;
				}

				.dropdown-content a {
				  float: none;
				  color: white;
				  padding: 12px 16px;
				  text-decoration: none;
				  display: block;
				  text-align: left;
				  background-color: #581cb0;
				}

				.topnav a:hover, .dropdown:hover .dropbtn {
				  background-color: #555;
				  color: white;
				  animation-duration: 1s;
				  animation-iteration-count: 5;
				  transform-origin: bottom;
				  animation-name: glitch;
				  background-image: linear-gradient(#f700ff,#7e2dc1,#0d8c93);
				}

				.dropdown-content a:hover {
				  background-color: #ddd;
				  color: white;
				  background-image: linear-gradient(#071033, #ca0084);
				}

				.dropdown:hover .dropdown-content {
				  display: block;
				}

				@media screen and (max-width: 600px) {
				  .topnav a:not(:first-child), .dropdown .dropbtn {
					display: none;
				  }
				  .topnav a.icon {
					display: block;
				  }
				}

				@media screen and (max-width: 600px) {
				  .topnav.responsive {position: relative;}
				  .topnav.responsive .icon {
					position: absolute;
					right: 0;
					top: 0;
				  }
				  .topnav.responsive a {
					float: none;
					display: block;
					text-align: left;
				  }
				  .topnav.responsive .dropdown {float: none;}
				  .topnav.responsive .dropdown-content {position: relative;}
				  .topnav.responsive .dropdown .dropbtn {
					display: block;
					width: 100%;
					text-align: left;
				  }
				}
				.inputBox{
				  padding: 5px;
				  width: 250px;
				  font-size: 12px;
				  color: gray;
				  border-radius: 7px;
				  border: 1px solid gray;
				  background: white;
				  margin-bottom: 7px;
				}


				/* CSS */
				.button-15 {
				  background-image: linear-gradient(#42A1EC, #0070C9);
				  border: 1px solid #0077CC;
				  border-radius: 8px;
				  box-sizing: border-box;
				  color: #FFFFFF;
				  cursor: pointer;
				  direction: ltr;
				  display: block;
				  font-family: "SF Pro Text","SF Pro Icons","AOS Icons","Helvetica Neue",Helvetica,Arial,sans-serif;
				  font-size: 19px;
				  font-weight: 400;
				  letter-spacing: 0.08em;
				  line-height: 1.47059;
				  min-width: 30px;
				  overflow: visible;
				  padding: 4px 15px;
				  text-align: center;
				  vertical-align: baseline;
				  user-select: none;
				  -webkit-user-select: none;
				  touch-action: manipulation;
				  white-space: nowrap;
				  width: 250px;
				}

				.button-15:disabled {
				  cursor: default;
				  opacity: .3;
				}

				.button-15:hover {
				  background-image: linear-gradient(#51A9EE, #147BCD);
				  border-color: #1482D0;
				  text-decoration: none;
				}

				.button-15:active {
				  background-image: linear-gradient(#3D94D9, #0067B9);
				  border-color: #006DBC;
				  outline: none;
				}

				.button-15:focus {
				  box-shadow: rgba(131, 192, 253, 0.5) 0 0 0 3px;
				  outline: none;
				}
				
				.container {
					background-color: #0000007a;
					/* text-align: center; */
					width: 359px;
					padding: 30px;
					border-radius: 11px;
					box-shadow: 0px 0px 21px 18px #ffffff21;
				}
				a{
					color: white;
				}
				</style>
			</head>
			<body>
				<div style="height: 8%;"></div>
				<center>
				<div class="container">
				<a style="text-decoration: none;" href="index.php"><h1><?php echo $GlobalAppName;?></h1></a>
				<h2>Sign Up</h2>
				<span id="message"><?php echo $message; ?></span>
				<form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<input class="inputBox" type="text" placeholder="Name" name="full_name">
					</br>
					<input onkeyup="checkUsername(this.value)" class="inputBox" type="text" placeholder="UIU ID" name="user_name">
					</br>
					<label for="dob">Date of Birth</label>
					<input class="inputBox" type="date" placeholder="Date of Birth" id="dob" name="dob">
					</br>
					<input onkeyup="verifyPassword()" id="pass1" class="inputBox" type="password" placeholder="Password">
					</br>
					<input onkeyup="matchPassword()" id="pass2" class="inputBox" type="password" placeholder="Confirm Password" name="pass">
					</br>
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
					<input class="button-15" id="submitBtn" type="submit" value="Sign Up" name="signup">
					<script>
					String.prototype.isMatch = function(s){
					   return this.match(s)!==null
					}

					function verifyPassword() {  
						var pw = document.getElementById("pass1").value;  
						var pw2 = document.getElementById("pass2").value;  
						//check empty password field  
						if(pw == "") {  
							document.getElementById("message").innerHTML = "**Fill the password please!";  
							document.getElementById("submitBtn").setAttribute('disabled','');  
							return false;  
						}

						//minimum password length validation  
						if(pw.length < 8) {  
							document.getElementById("message").innerHTML = "**Password length must be atleast 8 characters";
							document.getElementById("submitBtn").setAttribute('disabled','');
							return false;  
						}  

						//maximum length of password validation  
						if(pw.length > 15) {  
							document.getElementById("message").innerHTML = "**Password length must not exceed 15 characters";
							document.getElementById("submitBtn").setAttribute('disabled','');
							return false;  
						} else {  
							document.getElementById("message").innerHTML = "";
							document.getElementById("submitBtn").removeAttribute('disabled');
							matchPassword();
						}  
					}

					function matchPassword(){  
						var pw = document.getElementById("pass1").value;  
						var pw2 = document.getElementById("pass2").value;  
							if(pw != pw2){
								document.getElementById("message").innerHTML = "**Passwords did not match";
								document.getElementById("submitBtn").setAttribute('disabled','');								
								return false;
							}else{
								document.getElementById("message").innerHTML = "";
								document.getElementById("submitBtn").removeAttribute('disabled');								
							}  
					}
					
					function checkUsername(username){
						xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() {
							if(this.readyState == 4 && this.status == 200) {
								console.log(this.responseText);
								var RetJson1 = JSON.parse(this.responseText);
								if(RetJson1.available.isMatch('true')){
									document.getElementById("message").innerHTML = '';
									document.getElementById("submitBtn").removeAttribute('disabled');
								}
								if(RetJson1.available.isMatch('false')){
									document.getElementById("submitBtn").setAttribute('disabled','');		
									document.getElementById("message").innerHTML = '<i class="fa-solid fa-thumbs-down"></i> THIS UIU ID USER ALREADY EXISTS!';
								}
							}
						}
						xmlhttp.open("GET","signupAjax.php?data=GetUsername&username="+username, true);
						xmlhttp.send();		
					}
					</script>
				</form>
				</div>
				</center>
				</br>
				</br>
			</body>
			</html>
	<?php } ?>