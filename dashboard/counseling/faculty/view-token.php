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
				$id    		= mysqli_real_escape_string($con, $_COOKIE['userid']);
				$tokenid    		= mysqli_real_escape_string($con, $_GET['tokenid']);
				$sql        = "SELECT * FROM `medical_tokens` INNER JOIN `medical_prescription` ON `medical_tokens`.`token_id` = `medical_prescription`.`token_id` WHERE `medical_tokens`.`token_id` = $tokenid AND `medical_tokens`.`validity`='invalid' ORDER BY `medical_tokens`.`token_id` DESC";
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
					<h2 class="appointmentTitle"><i class="fa-solid fa-ticket"></i> Token Number <i>#<?php echo $rows['token_id']; $UserTokenID= $rows['token_id'];?></i></h2>
					</br>
					<b><i class="fa-solid fa-clock"></i> Date: </b> <?php echo date("l, jS \of F, Y (h:i:s A)", $date1);?></br>										
						<?php if($rows['transaction_id'] != 'null'){?>
								<b><i class="fa-solid fa-money-bill-transfer"></i> Transaction ID: </b> <a href='javascript:void(0)'><?php echo $rows['transaction_id'];?></a>
							<?php }else{ ?>
								<b style="line-height: 2; color: red;"><i class="fa-solid fa-money-bill-transfer"></i> This tokens' payment isn't complete.</b></br>
							<?php } ?>
					</br>
					<h3><i class="fa-solid fa-arrow-down-short-wide"></i> Details </h3>
					<div id="problemholder1"><p><?php echo $rows['problem']?></p></div>
					<hr>
					<h3><i class="fa-solid fa-briefcase-medical"></i> Prescription </h3>
					<div id="prescriptionHolder1"><p><p><?php echo $rows['prescription']?></p></div>
					<script>
						//Chat GPT Wrote that
						const problemholder = document.getElementById('problemholder1');
						const images = problemholder.getElementsByTagName('img');

						for (let i = 0; i < images.length; i++) {
						  let src = images[i].getAttribute('src');
						  
						  if (!src.includes('http://') && !src.includes('https://')) {
							src = '../'+src;
							images[i].setAttribute('src', src);
						  }
						}

						//Chat GPT Wrote that
						const prescriptionHolder = document.getElementById('prescriptionHolder1');
						const images2 = prescriptionHolder.getElementsByTagName('img');

						for (let i = 0; i < images2.length; i++) {
						  let src = images2[i].getAttribute('src');
						  
						  if (!src.includes('http://') && !src.includes('https://')) {
							  if(!src.includes('../')){
								src = '../'+src;  
							  }
							images2[i].setAttribute('src', src);
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
		
		
	<?php 	}}	else { echo "Not Logged In"; } ?>