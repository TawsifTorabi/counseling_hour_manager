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
		exit();
	}		

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UIU Question and Content Library</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/aurna-lightbox.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>

	#TokenCounter{
		font-family: monospace;
	}
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
		text-decoration: none;
	}
	.button-boot:hover {
		color: #fff;
		background-color: #0d6efd;
		border-color: #0d6efd;
		text-decoration: none;
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


		fieldset, label { margin: 0; padding: 0; }
		body{ margin: 20px; }
		h1 { font-size: 1.5em; margin: 10px; }

		/****** Style Star Rating Widget *****/

		.rating { 
		  border: none;
		  float: left;
		}

		.rating > input { display: none; } 
		.rating > label:before { 
		  margin: 5px;
		  font-size: 1.25em;
		  font-family: FontAwesome;
		  display: inline-block;
		  content: "\f005";
		}

		.rating > .half:before { 
		  content: "\f089";
		  position: absolute;
		}

		.rating > label { 
		  color: #ddd; 
		 float: right; 
		}

		/***** CSS Magic to Highlight Stars on Hover *****/

		.rating > input:checked ~ label, /* show gold star when clicked */
		.rating:not(:checked) > label:hover, /* hover current star */
		.rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

		.rating > input:checked + label:hover, /* hover current star when changing rating */
		.rating > input:checked ~ label:hover,
		.rating > label:hover ~ input:checked ~ label, /* lighten current selection */
		.rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 
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
      <h2><i class="fa-solid fa-notes-medical"></i> UIU Content Library</h2>
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
		  <li><a href="../"><i class="fa-solid fa-house"></i> Back to Homepage</a></li>
		  <li class="active"><a href="dashboard.php"><i class="fa-solid fa-book"></i> Back to Library</a></li>
		  <li><a href="myuploads.php"><i class="fa-solid fa-pencil"></i> My Uploads</a></li>
		  <li><a href="upload.php"><i class="fa-solid fa-plus"></i> Add New Upload</a></li>
		  <li><a href="bookmarks.php"><i class="fa-solid fa-star"></i> Bookmarks</a></li>
		  <li><a href="bloodbank.php"><i class="fa-solid fa-laptop"></i> Course Contents</a></li>
		  <li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
	    </ul><br>
    </div>
    <br>
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
<?php 

if(isset($_GET['id']) && !empty($_GET['id'])){

	$id 		= (int)$_GET['id'];
	mysqli_set_charset($con, "utf8");
	$sql        = "	SELECT 
							`library_contents`.`content_id` AS `content_id`,
							`library_contents`.`name` AS `content_name`,
							`users`.`name` AS `users_name`,
							`users`.`id` AS `users_id`,
							`library_contents`.`time` AS `time`,
							`library_contents`.`description` AS `description`,
							`library_contents`.`filetype` AS `filetype`
						FROM `library_contents` 
						INNER JOIN `users` 
						ON `library_contents`.`uploaderID` = `users`.`id`
						WHERE content_id='".$id."'
						ORDER BY `content_id` 
						DESC LIMIT 50";
	$result		= mysqli_query($con, $sql);
	if(!$result){
		echo mysqli_error($con);
	}
	else{
		while($rows=mysqli_fetch_array($result)){
?>

    <div class="col-sm-9 main">

      <div class="row">

        <div class="col-sm-12">
			<div style="margin-left: 20px;">
			<span style="font-size: 20px;"><?php echo $rows['content_name']; ?></span></br>
				<small style="font-size: 15px; color: grey;"> Uploaded By : 
					<b><a href="profile.php?id=<?php echo $rows['users_id']?>"><?php echo $rows['users_name']?></a></b>&nbsp;
				</small>
			</div>
			</br>
            <div class="col-sm-5">
				<div class="well">
					<p>				
						<i class="fa-solid fa-download"></i> 
						<?php 
							$content_id = $rows['content_id']; 
							$dl_count = mysqli_num_rows(mysqli_query($con, "SELECT download_id FROM library_download WHERE content_id=$content_id"));
							if($dl_count <= 0){
								$dl_text = "No Download or Views";
							}else{
								$dl_text = $dl_count." Times";
							} 
							echo $dl_text;
							?></br>
						<i class="fa-solid fa-clock"></i> <?php echo date("l, jS \of F, Y (h:i:s A)", $rows['time']);?></br>
						</br>
						<b>Description:</b></br>
						<small><?php echo $rows['description']?></small>
						</br>
						</br>
						<?php if($rows['filetype'] == "pdf"){ ?>
						<span>
							<button class="button-amazon" onclick="showFrame()"><i class="fa-solid fa-square-arrow-up-right"></i> Reading Mode</button>&nbsp;
							<button class="button-amazon"  onclick="window.open('file.php?content_id=<?php echo $rows['content_id']; ?>','_blank')"><i class="fa-solid fa-arrow-up-right-from-square"></i> Open in New Tab</button>&nbsp;
							<button class="button-amazon"  onclick="window.open('file.php?content_id=<?php echo $rows['content_id']; ?>','_blank')"><i class="fa-solid fa-download"></i> Download</button>&nbsp;
							<?php 
								$BookmarkQuery = mysqli_query($con, "SELECT * FROM `library_bookmarks` WHERE content_id='".$rows['content_id']."' AND user_id='".$_COOKIE['userid']."'"); 
								if(mysqli_num_rows($BookmarkQuery) > 0){
							?>
								<button class="button-amazon" style="padding: 8px 19px;" id="bookmark<?php echo $rows['content_id']; ?>" onclick="toggleBookmark(<?php echo $rows['content_id']; ?>)" title="Saved"><i class="fa-solid fa-bookmark"></i></button>
							<?php }else{ ?>
								<button class="button-amazon" style="padding: 8px 19px;" id="bookmark<?php echo $rows['content_id']; ?>" onclick="toggleBookmark(<?php echo $rows['content_id']; ?>)" title="Save to Bookmarks"><i class="fa-regular fa-bookmark"></i></button>
							<?php } ?>
						</span>
						<?php } ?>
						<?php if($rows['filetype'] == "video"){ ?>
						<span>
							<button class="button-amazon" onclick="window.open('file.php?content_id=<?php echo $rows['content_id']; ?>','_blank')"><i class="fa-solid fa-arrow-up-right-from-square"></i> Open in New Tab</button>
							<button class="button-amazon" onclick="window.open('file.php?content_id=<?php echo $rows['content_id']; ?>','_blank')"><i class="fa-solid fa-download"></i> Download</button>
							<?php 
								$BookmarkQuery = mysqli_query($con, "SELECT * FROM `library_bookmarks` WHERE content_id='".$rows['content_id']."' AND user_id='".$_COOKIE['userid']."'"); 
								if(mysqli_num_rows($BookmarkQuery) > 0){
							?>
								<button class="button-amazon" style="padding: 8px 19px;" id="bookmark<?php echo $rows['content_id']; ?>" onclick="toggleBookmark(<?php echo $rows['content_id']; ?>)" title="Saved"><i class="fa-solid fa-bookmark"></i></button>
							<?php }else{ ?>
								<button class="button-amazon" style="padding: 8px 19px;" id="bookmark<?php echo $rows['content_id']; ?>" onclick="toggleBookmark(<?php echo $rows['content_id']; ?>)" title="Save to Bookmarks"><i class="fa-regular fa-bookmark"></i></button>
							<?php } ?>							
						</span>
						<?php } ?>
						
					</p>
				</div>

				<div class="well">
			<?php
				// Check if the form has been submitted
				$userid = $_COOKIE['userid'];
				
				if(isset($_GET['editreview']) && $_GET['editreview'] == 'true') {
					// Get the form data
					$review_id = mysqli_real_escape_string($con,$_POST['review_id']);
					$review = mysqli_real_escape_string($con,$_POST['review']);
					$rating = mysqli_real_escape_string($con,$_POST['rating']);

					// Update the review in the database
					$update_query = "UPDATE `library_review` SET review='$review', rating='$rating' WHERE review_id='$review_id' AND user_id='$userid'";
					$result7 = mysqli_query($con, $update_query);

					if($result7) {
						$message2 = "Review updated successfully!";
						echo "<script>window.open('content.php?id=".$content_id."','_self')</script>";
					} else {
						$message2 = "Error updating review: " . mysqli_error($con);
					}
				}


				if(isset($_GET['deletereview']) && $_GET['deletereview'] == 'true') {
					// Get the form data
					$content_id = mysqli_real_escape_string($con,$_GET['id']);
					// Update the review in the database
					$update_query = "DELETE FROM `library_review` WHERE content_id='$content_id' AND user_id='$userid'";
					$result7 = mysqli_query($con, $update_query);

					if($result7) {
						$message2 = "Review Deleted successfully!";
						echo "<script>window.open('content.php?id=".$content_id."','_self')</script>";
					} else {
						$message2 = "Error Deleting review: " . mysqli_error($con);
					}
				}
				
				$reviewSelfQuery = mysqli_query($con, "SELECT * FROM `library_review` WHERE content_id='$content_id' AND user_id='$userid'");				
				$countReview = mysqli_num_rows($reviewSelfQuery);
				if($countReview > 0){
					if($row = mysqli_fetch_assoc($reviewSelfQuery)){
						if(isset($_GET['revieweditable']) && $_GET['revieweditable']=='true'){

								
								// Get the review_id from the URL parameter
								$content_id = $_GET['id'];
								$userid = $_COOKIE['userid'];

								// Pre-populate the form fields with the review information
								$review_id = $row['review_id'];
								$review = $row['review'];
								$rating = $row['rating'];
							?>
							
							<div id="dopostContainerMain">
								<form name="editform" id="updateform" method="post" action="content.php?id=<?php echo $content_id; ?>&editreview=true">
									<input type="hidden" name="review_id" value="<?php echo $review_id; ?>">
									<input type="hidden" name="content_id" value="<?php echo $content_id; ?>">
									<textarea name="review" class="form-control" style="width:100%; overflow-y: scroll; max-height: 150px; height: 100px; max-width: 100%; min-width: 100%" placeholder="Write a Comment..."><?php echo $review; ?></textarea></br>
									<fieldset class="rating">
										<input type="radio" id="star5" name="rating" value="5" <?php if ($rating == 5) echo "checked"; ?> />
										<label class="full" for="star5" title="Awesome - 5 stars"></label>

										<input type="radio" id="star4" name="rating" value="4" <?php if ($rating == 4) echo "checked"; ?> />
										<label class="full" for="star4" title="Pretty good - 4 stars"></label>

										<input type="radio" id="star3" name="rating" value="3" <?php if ($rating == 3) echo "checked"; ?> />
										<label class="full" for="star3" title="Meh - 3 stars"></label>

										<input type="radio" id="star2" name="rating" value="2" <?php if ($rating == 2) echo "checked"; ?> />
										<label class="full" for="star2" title="Kinda bad - 2 stars"></label>

										<input type="radio" id="star1" name="rating" value="1" <?php if ($rating == 1) echo "checked"; ?> />
										<label class="full" for="star1" title="Sucks big time - 1 star"></label>

									</fieldset>
									<button class="button-amazon" style="margin-top: 5px; float: right;" onclick="updateform.submit();disableButton(this);"><i class="fa-solid fa-pencil" type="editreview"></i> Update Review</button>
								</form>
							</div>
							</br>
							</br>
							</br>
							
							
							
							
						<?php }else{ ?>
						
						
					<div id="dopostContainerMain">
						<h4>Your Review</h4>
						<textarea name="review" class="form-control" style="width:100%; overflow-y: scroll; max-height: 150px; height: 100px; max-width: 100%; min-width: 100%" disabled placeholder="Write a Comment..."/><?php echo $row['review']; ?></textarea></br>
						<?php for($i=(int)$row['rating']; $i>=1; $i--){ ?>
							<i class="fa-solid fa-star" style="color: #ffb100;"></i>
						<?php } ?>
						<span style="float: right;">
							<i class="fa-solid fa-clock"></i> <?php echo date("jS \of F, Y", $row['timestamp']);?>&nbsp;&nbsp;
							<button onclick="window.open('content.php?id=<?php echo $content_id;?>&revieweditable=true'); disableButton(this);"><i class="fa-solid fa-edit"></i></button>
							<button onclick="confirmDelete()"><i class="fa-solid fa-trash"></i></button>
							<script>
							function confirmDelete(){
								bootbox.confirm('Are you sure?', function(result){
									if(result == true){
										window.open('content.php?id=<?php echo $content_id;?>&deletereview=true');
									}
								});
							}
							
							</script>
						</span></br>
						</br>
						</br>
					</div>
			<?php
			
						}
					}
				}else{
					//No Current User Review Found, Open the Form 
			?>	
				<div id="dopostContainerMain">
					<form name="reviewform" method="post" action="ajax.php?data=SubmitReview">
					<input type="hidden" name="content_id" value="<?php echo $content_id; ?>"/>
					<textarea name="review" class="form-control" style="width:100%; overflow-y: scroll; max-height: 150px; height: 100px; max-width: 100%; min-width: 100%" placeholder="Write a Comment..."/></textarea></br>
					<fieldset class="rating">
						<input type="radio" id="star5" name="rating" value="5" />
						<label class = "full" for="star5" title="Awesome - 5 stars"></label>
						
						<input type="radio" id="star4" name="rating" value="4" />
						<label class = "full" for="star4" title="Pretty good - 4 stars"></label>
						
						<input type="radio" id="star3" name="rating" value="3" />
						<label class = "full" for="star3" title="Meh - 3 stars"></label>
						
						<input type="radio" id="star2" name="rating" value="2" />
						<label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
						
						<input type="radio" id="star1" name="rating" value="1" />
						<label class = "full" for="star1" title="Sucks big time - 1 star"></label>
						
					</fieldset>
					<button class="button-amazon" style="margin-top: 5px; float: right;"><i class="fa-solid fa-pencil" type="submit"></i> Post Your Review</button>
					</form>
				</div>
				</br>
				</br>
				</br>
			<?php 
				}
			?>
			
			
			<?php 
			//show all reviews
				$reviewQuery = mysqli_query($con, "SELECT * FROM `library_review` INNER JOIN `users` ON `library_review`.`user_id` = `users`.`id` WHERE content_id='$content_id' ORDER BY review_id DESC");				
			?>
					<h3>
						<?php echo mysqli_num_rows($reviewQuery); ?> Reviews:</h3>
					<div id="commentContainer" style="
						overflow-y: scroll;
						height: 48vh;
						border-top: 3px solid green;
						padding: 11px;
					">
					<?php
						$countReview = mysqli_num_rows($reviewQuery);
						if($countReview > 0){
							while($rows2 = mysqli_fetch_assoc($reviewQuery)){
					?>
								<p class="well">
									<b><?php echo $rows2['name']; ?></b></br>
									<small><?php echo $rows2['review']; ?></small>
									</br>
									</br>
									<?php for($i=(int)$rows2['rating']; $i>=1; $i--){ ?>
										<i class="fa-solid fa-star" style="color: #ffb100;"></i>
									<?php } ?>
									<span style="float: right;"><i class="fa-solid fa-clock"></i> <?php echo date("jS \of F, Y", $rows2['timestamp']);?> </span>
								</p>
					<?php }}else{ ?>
								<p class="well">
									<center><h3>No Reviews Yet!</h3></center>
								</p>
					<?php } ?>
					</div>
				</div>

			</div>            
			<style>
				.button-amazon{
					display: inline-block;
					outline: 0;
					cursor: pointer;
					border-radius: 8px;
					box-shadow: 0 2px 5px 0 rgb(213 217 217 / 50%);
					background: #FFD814;
					border: 1px solid #FCD200;
					font-size: 15px;
					padding: 8px;
					text-align: center;
					font-weight: 500;
					color: #0F1111;
					margin-bottom:5px;
				}	
				.button-amazon:hover{
						background: #F7CA00;
						border-color: #F2C200;
						box-shadow: 0 2px 5px 0 rgb(213 217 217 / 50%);
				}
									
				#closeBtn {
					color: #3a2dff;
					display: none;
					position: fixed;
					top: 81px;
					bottom: 0px;
					right: 30px;
					width: 20px;
					padding: 0px 35px 56px 16px;
					border: none;
					z-index: 9999999;
					height: 20px;
					font-weight: bold;
					font-size: 39px;
					border-radius: 6px;
					box-shadow: 1px 1px 20px 0px black;
				}
			</style>
				<?php if($rows['filetype'] == "video"){ ?>
				<div class="col-sm-7">
					<div class="well">
						<p>
							<video controls autoplay onloadstart="this.volume=0.15" src="file.php?content_id=<?php echo $rows['content_id']; ?>" style="width:100%;height: 86vh;background: black;"></video>
						</p>
					</div>
				</div>
				<?php } ?>
				<?php if($rows['filetype'] == "pdf"){ ?>
				<div class="col-sm-7">
					<div class="well">
						<p>
							<iframe id="frame1" src="file.php?content_id=<?php echo $rows['content_id']; ?>" style="width: 100%; height: 87vh;"></iframe>
						</p>
					</div>
				</div>

				<button id="closeBtn" onclick="closeFrame()">&times;</button>
				<script>
					document.addEventListener('keydown', function(event) {
					  if (event.key === 'Escape') {
						closeFrame(); // call your function here
					  }
					});
					var css1 = "position: fixed; top: 0px; bottom: 0px; right: 0px; width: 100%;	border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;	height: 100%;";
					var css2 = "width: 100%; height: 87vh";
					
					var Frame1 = document.getElementById("frame1");
					var closeBtn = document.getElementById("closeBtn");
					function showFrame(){
						Frame1.style = css1;
						closeBtn.style.display = "inherit";
					}
					function closeFrame(){
						Frame1.style = css2;  
						closeBtn.style.display = "none";  
					}
				</script>
				<?php } ?>
        </div>    	
      </div> 
    </div>
  </div>
</div>


</body>
</html>

<script>
	String.prototype.isMatch = function(s){
	   return this.match(s)!==null
	}
	
	function disableButton(btn) {
	  btn.disabled = true;
	  btn.innerHTML = "<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i> Processing...";
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
		
	function toggleBookmark(contentID){
		const url = 'ajax.php?data=Bookmark&content_id='+contentID;
		const elem = document.getElementById('bookmark'+contentID);
		let xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET", url, true);
		xmlhttp.onreadystatechange=function() {
			if (this.readyState == 4 && this.status == 200){
				console.log(this.responseText);
				let RetJson = JSON.parse(this.responseText);
				if(RetJson.bookmarked == 'true'){
					console.log('Content bookmarked!');
					elem.innerHTML = '<i class="fa-solid fa-bookmark"></i>';
					w3alert('Content bookmarked!');
				} else if(RetJson.bookmarked == 'false'){
					console.log('Content un-bookmarked!');
					elem.innerHTML = '<i class="fa-regular fa-bookmark"></i>';
					w3alert('Removed Bookmark!');
				} else {
					console.log('Bookmarking failed!');
					w3alert('Bookmarking failed!');
				}
			}
		}
		xmlhttp.send();
	}
</script>

<?php
	}
}
?>

<?php }else{
	echo "<script>window.open('dashboard.php','_self')</script>";
} ?>
</body>
</html>


<?php 	}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>