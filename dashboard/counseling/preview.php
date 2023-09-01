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
	html{
		font-family: Trebuchet MS;
	}
	</style>
    </head>

    <body>
		<?php if(isset($_GET['content_id'])){
				
				$id 		= (int)$_GET['content_id'];
				mysqli_set_charset($con, "utf8");
				$sql        = "	SELECT 
										`library_contents`.`content_id` AS `content_id`,
										`library_contents`.`name` AS `content_name`,
										`users`.`name` AS `users_name`,
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
						<span style="font-size: 20px;"><?php echo $rows['content_name']; ?></span>
						</br>
						<small style="font-size: 15px; color: grey;"> Uploaded By : 
						<b><?php echo $rows['users_name']?></b>&nbsp;
						<button class="button-amazon" style="float: right;" onclick="window.open('file.php?content_id=<?php echo $rows['content_id']; ?>','_blank')">Open in New Tab</button>
						</small>
						</br>
						</br>
						<style>
						.button-amazon{
							display: inline-block;
							outline: 0;
							cursor: pointer;
							border-radius: 8px;
							box-shadow: 0 2px 5px 0 rgb(213 217 217 / 50%);
							background: #FFD814;
							border: 1px solid #FCD200;
							font-size: 18px;
							height: 27px;
							padding: 0 12px;
							text-align: center;
							font-weight: 500;
							color: #0F1111;
						}	
						.button-amazon:hover{
								background: #F7CA00;
								border-color: #F2C200;
								box-shadow: 0 2px 5px 0 rgb(213 217 217 / 50%);
						}
							
						</style>
						<?php if($rows['filetype'] == "video"){ ?>
							<video controls autoplay onloadstart="this.volume=0.15" src="file.php?content_id=<?php echo $rows['content_id']; ?>" style="width:100%;height: 86vh;background: black;"></video>
						<?php } ?>
						<?php if($rows['filetype'] == "pdf"){ ?>
							<object data="file.php?content_id=<?php echo $rows['content_id']; ?>" style="width: 100%; height: 87vh;"></object>
						<?php } ?>

				<?php
					}
				}
				?>

    </body>
</html>

<?php	}
	
		}	else { echo "<script>parent.window.open('login.php','_self')</script>"; } ?>