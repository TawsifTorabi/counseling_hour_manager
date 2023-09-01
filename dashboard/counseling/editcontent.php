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
	if(mysqli_num_rows(mysqli_query($con,"select * from sessions where session_id='$getsessionID' AND validity='$validity'"))> 0){
?>
<!DOCTYPE html>
<html>
    <head>
		<style>
		body{font-family: Trebuchet MS; margin-left: 30%;}
		.uploaderBtn{max-width: 324px; background: #2aa582;padding: 17px;font-size: 14px;border-radius: 11px;box-shadow: 2px 2px 33px 3px #0000006b;}
		.submitBtn {
		  background: #2aa582;
		  padding: 17px;
		  font-size: 16px;
		  border-radius: 7px;
		  color: white;
		  text-transform: capitalize;
		  box-shadow: 2px 2px 33px 3px #0000006b;
		  font-weight: bold;
		  font-family: Trebuchet MS;
		  border: none;
		}
		.inputText{
		  border-radius: 8px;
		  border: none;
		  height: 37px;
		  margin-top: 9px;
		  margin-bottom: 9px;
		  width: 339px;
		  padding-left: 18px;
		  border: 2px solid green;
		}
		</style>
    </head>

    <body>
	<?php

	if(isset($_GET['content_id'])){
			
				mysqli_set_charset($con,"utf8");
				$userid = $_COOKIE['userid'];
				$id    = mysqli_real_escape_string($con, $_GET['content_id']);
				$sql        = "SELECT * FROM `library_contents` WHERE `uploaderID`=$userid AND`content_id`=$id";
				$result		= mysqli_query($con, $sql);
				if(!$result){
					echo mysqli_error($con);
				}
				else{
					while($rows=mysqli_fetch_array($result)){
	?>
		<div class="maincont">
			<h3>Edit Content - <?php echo mysqli_real_escape_string($con, $rows['name']) ?></h3>
			<form action="editcontent.php?id=<?php echo mysqli_real_escape_string($con, $rows['content_id']) ?>&save" method="post" enctype="multipart/form-data">
				Name:</br>
				<input placeholder="Name..." type="text" value="<?php echo mysqli_real_escape_string($con, $rows['name']) ?>" class="inputText" name="name"></br>
				Description:</br>
				<input placeholder="Description..." type="text" value="<?php echo mysqli_real_escape_string($con, $rows['description']) ?>" class="inputText" name="description">
				</br>
				<input type="submit" name="submit" class="submitBtn" value="Save">
			</form>
		</div>
			
	<?php }}}?>
	
	
	
	<?php 
	
	//edit or update data 
	if(isset($_GET['save'])){

			mysqli_set_charset($con,"utf8");
			$id = 				mysqli_real_escape_string($con, $_GET['id']);
			$name = 			mysqli_real_escape_string($con, $_POST['name']);
			$description = 		mysqli_real_escape_string($con, $_POST['description']);
			

			$query =	"UPDATE `library_contents` 
						SET `name` = '$name',  
							`description` = '$description' 
							WHERE `content_id`='$id'";
				  
				  if(mysqli_query($con, $query)){
						echo"<script>parent.SearchTable();parent.hideIframe();</script>";
					}else{
						return 1;
						die(mysqli_error($con));	
					}

		
	}
	
	?>



    </body>
</html>

<?php 	}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>