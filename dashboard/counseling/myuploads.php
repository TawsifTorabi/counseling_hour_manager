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
	.button-boot-active {
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
		  <li><a href="dashboard.php"><i class="fa-solid fa-book"></i> Library Dashboard</a></li>
		  <li class="active"><a href="myuploads.php"><i class="fa-solid fa-pencil"></i> My Uploads</a></li>
		  <li><a href="upload.php"><i class="fa-solid fa-plus"></i> Add New Upload</a></li>
		  <li><a href="bookmarks.php"><i class="fa-solid fa-star"></i> Bookmarks</a></li>
		  <li><a href="bloodbank.php"><i class="fa-solid fa-laptop"></i> Course Contents</a></li>
		  <li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
	    </ul><br>
    </div>
    <br>

    <div class="col-sm-9 main">

      <div class="row">

        <div class="col-sm-9">
          <div class="well">
            <p>
				<h1><i class="fa-solid fa-file"></i> My Uploads</h1></br>
				<a style="margin-left: 10px;" class="button-boot button-boot-active" href='myuploads.php'><i class="fa-solid fa-list"></i> Your Uploads</a>
				<a style="margin-left: 10px;" class="button-boot" href='mybooklists.php'><i class="fa-solid fa-book"></i> Your Booklists</a>
				<a style="margin-left: 10px;" class="button-boot" href='myplaylists.php'><i class="fa-solid fa-play"></i> Your Playlists</a>
				</br>
				</br>
				<div class="form-group input-group">
				  <input type="text" id="queryInput" onkeypress="return fireentersearch(event)" class="form-control" placeholder="Search..">
				  <span class="input-group-btn">
					<button class="btn btn-default" onclick="searchAjax()" type="button">
					  <span class="glyphicon glyphicon-search"></span>
					</button>

					<!-- select onchange="SearchTable()" id="bloodGroupInput" class="btn btn-default">
								<option value="">All Groups</option>
						<?php 
						// query the database
						$sql = "SELECT DISTINCT `blood_group` FROM users";
						$result = mysqli_query($conn, $sql);

						if(!$result){
							echo mysqli_error($con);
						}else{
							// loop through the results and add each row to the array
							while ($rows = mysqli_fetch_assoc($result)) { ?>
								<option value="<?php echo $rows['blood_group']; ?>"><?php echo $rows['blood_group']; ?></option>
							<?php }}  ?>
					</select>
					<select onchange="SearchTable()" id="genderInput" class="btn btn-default">
						<option value="">All Gender</option>
						<option value="male">Male</option>
						<option value="female">Female</option>
						<option value="others">Others</option>
					</select -->
				  </span>        
				</div>
			
				<style>
				th{
					position: sticky;
					top: 0;
					background: #f5f5f5;
					border-bottom: 2px solid green;
				}
				.button-2{
                    display: inline-block;
                    outline: none;
                    cursor: pointer;
                    padding: 0 16px;
                    background-color: #0070d2;
                    border-radius: 0.25rem;
                    border: 1px solid #0070d2;
                    color: #fff;
                    font-size: 13px;
                    line-height: 30px;
                    font-weight: 400;
                    text-align: center;
					text-decoration: none;
				}
                .button-2:hover {
					background-color: #005fb2;  
					border-color: #005fb2;
					color: white;text-decoration: none;
				} 
                
				</style>	

				
				
				<table id="dataTable" class="table table-hover table-dark" style="position: sticky; top: 0; width: 100%;">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Name</th>
						</tr>
					</thead>
					<tbody id="hahaha">
					  <?php
						$userid = $_COOKIE['userid'];
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
										WHERE `library_contents`.`uploaderID` = $userid
										ORDER BY `content_id` 
										DESC LIMIT 50";
						$result		= mysqli_query($con, $sql);
						if(!$result){
							echo mysqli_error($con);
						}
						else{
							while($rows=mysqli_fetch_array($result)){
						?>
							<tr id="datarow<?php echo $rows['content_id'];?>">
								<td style="font-weight:bold; text-align:center;color:#dc2323;">
									<?php if($rows['filetype'] == "pdf"){ ?>
										<i style="font-size: 48px;" class="fa-solid fa-file-pdf"></i>
									<?php
									}
									if($rows['filetype'] == "video"){ ?>
										<i style="font-size: 48px; color: skyblue;" class="fa-solid fa-file-video"></i>
									<?php } ?>
								</td>
								<td style="width: 47vh;">
									<a href="content.php?id=<?= $rows['content_id']; ?>"><span style="font-size:14px; font-weight: bold;"><?php echo $rows['content_name']?></span></a></br>
									<p><small><?php echo $rows['description']?></small></p>
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
								</td>
								<td>
								<?php if($rows['filetype'] == "pdf"){ ?>
									<a class="button-2" href="file.php?content_id=<?php echo $rows['content_id']; ?>" download><i class="fa-solid fa-download"></i> Download</a>
									<a class="button-2" href="#" onclick="aurnaIframe('preview.php?content_id=<?php echo $rows['content_id']; ?>')"><i class="fa-solid fa-file-pdf"></i> View</a>
								<?php
								}
								if($rows['filetype'] == "video"){ ?>
									<a class="button-2" href="file.php?content_id=<?php echo $rows['content_id']; ?>" download><i class="fa-solid fa-download"></i> Download</a>
									<a class="button-2" href="#" onclick="aurnaIframe('preview.php?content_id=<?php echo $rows['content_id']; ?>')"><i class="fa-solid fa-file-video"></i> Watch</a>
								<?php } ?>
								</td>
								<td>
									<div class="dropdown">
										<button class="btn btn-primary dropdown-toggle" type="button" id="about-us" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Action
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" aria-labelledby="about-us">
											<li><a href="javascript:aurnaIframe('editcontent.php?content_id=<?php echo (int)$rows['content_id'];?>')"><i class="fa-solid fa-pencil"></i> Edit</a></li>
											<li><a href="javascript:DeleteContent(<?php echo $rows['content_id'];?>, this)"><i class="fa-solid fa-trash"></i> Delete</a></li>
										</ul>
									</div>
								</td>
							</tr>
						<?php
							}
						}
						?>
					</tbody>
				</table>
			</p> 
          </div>
        </div>
        <div class="col-sm-3">
          <div class="well">
            <p>
				<center>
					<a style="margin-bottom: 4px;"href="rewards.php">
						<h4><i class="fa-solid fa-medal"></i> </br>Your Library Point</h4>
					</a>
				</center>
				<?php
					// output data of library point
					
					$userid - $_COOKIE['userid'];
					// Execute the query and fetch the results
					$sql = "
						SELECT lc.uploaderID, lc.content_id, lc.name, COUNT(*) AS download_count
						FROM library_contents lc
						JOIN library_download ld ON lc.content_id = ld.content_id
						WHERE lc.uploaderID = $userid
						GROUP BY lc.uploaderID, lc.content_id, lc.name
						ORDER BY download_count DESC;
					";

					$sql2 = "
						SELECT lc.uploaderID, lc.content_id, lc.name
						FROM library_contents lc
						WHERE lc.uploaderID = $userid;
					";

					$result = mysqli_query($con, $sql);
					$result2 = mysqli_num_rows(mysqli_query($con, $sql2));

					// Calculate the total download count for the uploader
					$total_downloads = 0;
					$total_uploads = $result2;
					
					while ($row = mysqli_fetch_assoc($result)) {
						$total_downloads += $row['download_count'];
					}
					
					$points = ($total_downloads*2)+($total_uploads*5);
				?>
						<center>
							<span style="font-size: 35px;">
								<span id="TokenCounter"></span>
							</span></br>
						</center></br>
						<script>

						var num = parseInt(<?php echo $points;?>);
						var count=0;
						var time;
						if(num < 99){
							time = 50;
						}else if(num < 1000){
							time = 15;
						}else if(num < 9999){
							time = 10;
						}else if(num < 99999){
							time = 1;
						}
						var counter=setInterval(timer, time); //1000 will  run it every 1 second
						function timer()
						{
						  count=count+1;
						  if (count >= num)
						  {
							 clearInterval(counter);
							 //counter ended, do something here
							  document.getElementById("TokenCounter").innerHTML=num ;
							  
							 return;
						  }

						  //Do code for showing the number of seconds here
							 document.getElementById("TokenCounter").innerHTML=count ; // watch for spelling

						}

						</script>
				<span id="TokenCounterMsg" class="greenMsg">Your Contribution to the Community and Counting... Score: <b><?php echo $points;?></b></span></br>
			</p> 
          </div>
        </div> 

        <div class="col-sm-3">
            <p>
				<center>
					<a style="margin-left: 10px;" class="button-boot" href='upload.php?type=pdf'><i class="fa-solid fa-upload"></i> Upload PDF</a>
					<a style="margin-left: 10px;" class="button-boot" href='upload.php?type=video'><i class="fa-solid fa-upload"></i> Upload Video</a>
				</center>
			</p> 
			</br>
		</div>

        <div class="col-sm-3">
          <div class="well">
            <p>
				
				<h4><i class="fa-solid fa-play"></i> Latest Playlists</h4>
					<ul class="nav nav-pills nav-stacked">
				  <?php
					mysqli_set_charset($con, "utf8");
					$sql        = "	SELECT * FROM `library_list` WHERE `list_type`='playlist' ORDER BY `list_id` DESC LIMIT 5 ";
					$result		= mysqli_query($con, $sql);
					if(!$result){
						echo mysqli_error($con);
					}
					else{
						while($rows=mysqli_fetch_array($result)){
					?>
						<li>
							<div class="panel panel-primary">
								<a href="lists.php?id=<?php echo $rows['list_id']; ?>">
									<div class="panel-heading">
										<i class="fa-solid fa-arrow-right-from-bracket"></i> 
										<span style="font-size: 14px; font-weight: bold;"><?php echo $rows['list_name']; ?></span>
										<p><small><?php echo $rows['list_description']; ?></small></p>
									</div>
								</a>
							</div>
						</li>
					<?php 
							} 
						}					  
					?>
				</ul>
				<a style="margin-bottom: 4px;"href="playlists.php"><i class="fa-solid fa-list"></i> Browse Playlists</a></br>
			</p> 
          </div>
        </div>	

		<div class="col-sm-3">
          <div class="well">
            <p>
				<h4><i class="fa-solid fa-book"></i> Latest Booklists</h4>
				<ul class="nav nav-pills nav-stacked">
				  <?php
					mysqli_set_charset($con, "utf8");
					$sql        = "	SELECT * FROM `library_list` WHERE `list_type`='booklist' ORDER BY `list_id` DESC LIMIT 5";
					$result		= mysqli_query($con, $sql);
					if(!$result){
						echo mysqli_error($con);
					}
					else{
						while($rows=mysqli_fetch_array($result)){
					?>
						<li>
							<div class="panel panel-primary">
								<a href="lists.php?id=<?php echo $rows['list_id']; ?>">
									<div class="panel-heading">
										<i class="fa-solid fa-arrow-right-from-bracket"></i> 
										<span style="font-size: 14px; font-weight: bold;"><?php echo $rows['list_name']; ?></span>
										<p><small><?php echo $rows['list_description']; ?></small></p>
									</div>
								</a>
							</div>
						</li>
					<?php
							}
						}
					?>
					</ul>
				<a style="margin-bottom: 4px;"href="booklists.php"><i class="fa-solid fa-list"></i> Browse Booklists</a></br>
			</p> 
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
	
	function fireentersearch(event){
		if (event.keyCode == 13) {
			SearchTable();
		}
	}
	
	function SearchTable(){
		// get a reference to the table element
		const table = document.getElementById('dataTable');
		let SearchQuery = document.getElementById('queryInput').value;

		// send an AJAX request to the PHP script
		const xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (this.readyState === 4 && this.status === 200) {
				
				// parse the JSON response
				console.log(this.responseText);
				
				const content = JSON.parse(this.responseText);
				console.log(content);
				
				table.querySelectorAll("td").forEach(function (data) {
				  data.parentNode.remove();
				});
				
				// loop through the users and add rows to the table
				/*
				Ajax Returns - 
					'content_id' => $rows['content_id'], 
					'content_name' => $rows['content_name'], 
					'description' => $rows['description'], 
					'users_name' => $rows['users_name'], 
					'downloads' => $dl_count, 
					'time' => date("l, jS \of F, Y (h:i:s A)", $date1), 
					'filetype' => $rows['filetype']
				*/

				for (let i = 0; i < content.length; i++) {
					
					let icon;
					let dl_button;			
					
					if(content[i].filetype === 'pdf'){ 
						icon = "<i style=\"font-size: 48px;\" class=\"fa-solid fa-file-pdf\"></i>";
						dl_button = "<i class=\"fa-solid fa-file-pdf\"></i> View</a>";
					}else if(content[i].filetype === 'video'){ 
						icon = "<i style=\"font-size: 48px; color: skyblue;\" class=\"fa-solid fa-file-video\"></i>";
						dl_button = "<i class=\"fa-solid fa-file-video\"></i> Watch</a>";
					}
					let downloadTimes; 
					if(parseInt(content[i].downloads) <= 0){ 
						downloadTimes = "No Downloads or Views";
					}else{
						downloadTimes = content[i].downloads + " Times";
					}
				
					const row = table.insertRow();
					row.id = "datarow"+content[i].content_id;
					const cell1 = row.insertCell(0);
					const cell2 = row.insertCell(1);
					const cell3 = row.insertCell(2);
					const cell4 = row.insertCell(3);
					
					cell1.style = "font-weight:bold; text-align:center;color:#dc2323;";
					cell1.innerHTML = icon;
					
					cell2.style = "width: 47vh;";
					cell2.innerHTML = 	"<span style=\"font-size:14px; font-weight: bold;\">"+content[i].content_name+"</span></br>"+
										"<p><small>"+content[i].description+"</small></p>"+
										"<i class=\"fa-solid fa-download\"></i> "+downloadTimes+"</br>"+
										"<i class=\"fa-solid fa-clock\"></i> "+content[i].time+"</br>";
										
					cell3.innerHTML = 	"<a class=\"button-2\" href=\"file.php?content_id="+content[i].content_id+"\" download><i class=\"fa-solid fa-download\"></i> Download</a>&nbsp;"+
										"<a class=\"button-2\" href=\"#\" onclick=\"aurnaIframe('preview.php?content_id="+content[i].content_id+"')\">"+dl_button+"</a>";

					cell4.innerHTML = 	"<div class=\"dropdown\">"+
											"<button class=\"btn btn-primary dropdown-toggle\" type=\"button\" id=\"about-us\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Action<span class=\"caret\"></span></button>"+
											"<ul class=\"dropdown-menu\" aria-labelledby=\"about-us\">"+
												"<li><a href=\"javascript:aurnaIframe('editcontent.php?content_id="+content[i].content_id+"')\"><i class=\"fa-solid fa-pencil\"></i> Edit</a></li>"+
												"<li><a href=\"javascript:DeleteContent("+content[i].content_id+", this)\"><i class=\"fa-solid fa-trash\"></i> Delete</a></li>"+
											"</ul>"+
										"</div>";
					
				}
			}
		};
		
		xhr.open('GET', 'ajax.php?data=myuploadsSearch&q='+SearchQuery, true);
		xhr.send();
	}
	
	
	function DeleteContent(id, elem){
		bootbox.confirm({
			title: 'Confirm Delete?',
			message: 'Do you want to delete the content now? This cannot be undone.',
			buttons: {
			cancel: {
			label: '<i class="fa fa-times"></i> Cancel'
			},
			confirm: {
			label: '<i class="fa fa-check"></i> Delete'
			}
			},
			callback: function (result) {
				if(result == true){
					deleteRecord(id);
				}
			}
		});
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
					bootbox.alert({
						message: 'Content Deleted Successfully!',
						backdrop: true
                    });
					row.remove();
				}else if(returnData.deleted.isMatch('false')){				
					bootbox.alert({
						message: 'Error Occured! Content not Deleted!',
						backdrop: true
                    });
				}
			}
		};
		xhr.open('GET', 'ajax.php?data=deleteContent&content_id='+id, true);
		xhr.send();
	}
	
	
</script>
</body>
</html>


<?php 	}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>