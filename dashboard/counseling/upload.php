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
	
	
	
	$statusMsg = 'File Size Limit is 100 MB for PDF, 7GB for VIDEOS';

	if(isset($_FILES['pdf_file'])) {
		
		// Define the maximum file size (in bytes)
		$max_filesize = 300000000; // 100MB
		
		// Define the valid file extensions
		$valid_extensions = array('pdf');
		
		// Get the file extension
		$extension = pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION);
		
		// Check if the file size is within the allowed limit
		if($_FILES['pdf_file']['size'] <= $max_filesize) {
			
			// Check if the file extension is valid
			if(in_array($extension, $valid_extensions)) {
				
				// Generate a unique name for the file
				$filename = uniqid() . '.' . $extension;
				
				// Upload the file to a specific folder
				$upload_dir = 'uploads/';
				move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_dir . $filename);
				$uploaderID = $_COOKIE['userid'];
				$name = mysqli_real_escape_string($con, $_POST['name']);
				$description = mysqli_real_escape_string($con, $_POST['description']);
				$time = time();
				$listid = $_POST['list_id'];
				$insert = mysqli_query($con, "INSERT into library_contents (name, description, uploaderID, filename, filetype, time, list_id) 
										VALUES ('$name','$description','$uploaderID','$filename','pdf', $time, $listid)");
				if($listid !== '0'){
					mysqli_query($con, "UPDATE library_list SET last_update_timestamp = $time WHERE list_id=$listid");
				}
				
				// Display a success message
				$statusMsg = 'The file has been uploaded.';
				
			} else {
				// Display an error message
				$statusMsg = 'Invalid file extension. Only PDF files are allowed.';
			}
			
		} else {
			// Display an error message
			$statusMsg = 'The file size exceeds the maximum allowed limit of 100 MB.';
		}
	}


	if(isset($_FILES['video_file'])) {
		
		// Define the maximum file size (in bytes)
		$max_filesize = 7000000000; // 7GB
		
		// Define the valid file extensions
		$valid_extensions = array('mp4');
		
		// Get the file extension
		$extension = pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION);
		
		// Check if the file size is within the allowed limit
		if($_FILES['video_file']['size'] <= $max_filesize) {
			
			// Check if the file extension is valid
			if(in_array($extension, $valid_extensions)) {
				
				// Generate a unique name for the file
				$filename = uniqid() . '.' . $extension;
				
				// Upload the file to a specific folder
				$upload_dir = 'uploads/';
				move_uploaded_file($_FILES['video_file']['tmp_name'], $upload_dir . $filename);
				$uploaderID = $_COOKIE['userid'];
				$name = mysqli_real_escape_string($con, $_POST['name']);
				$description = mysqli_real_escape_string($con, $_POST['description']);
				$time = time();
				$listid = $_POST['list_id'];
				$insert = mysqli_query($con, "INSERT into library_contents (name, description, uploaderID, filename, filetype, time, list_id) 
										VALUES ('$name','$description','$uploaderID','$filename','video', $time, $listid)");
				// Display a success message
				$statusMsg = 'The file has been uploaded.';
			
				
			} else {
				// Display an error message
				$statusMsg = 'Invalid file extension. Only MP4 files are allowed.';
			}
			
		} else {
			// Display an error message
			$statusMsg = 'The file size exceeds the maximum allowed limit of 100 MB.';
		}
	}
	
	$statusMsg = "<h3 style=\"padding: 16px 48px; background: #ffc800; color: white; font-size: 16px; border-radius: 6px;\"><i class=\"fa-solid fa-circle-info\"></i> ".$statusMsg."</h3>";
	
	
	
	
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
		  <li><a href="myuploads.php"><i class="fa-solid fa-pencil"></i> My Uploads</a></li>
		  <li class="active"><a href="upload.php"><i class="fa-solid fa-plus"></i> Add New Upload</a></li>
		  <li><a href="bookmarks.php"><i class="fa-solid fa-star"></i> Bookmarks</a></li>
		  <li><a href="bloodbank.php"><i class="fa-solid fa-laptop"></i> Course Contents</a></li>
		  <li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
	    </ul><br>
    </div>
    <br>

    <div class="col-sm-9 main">
      <div class="row">
        <div class="col-sm-12">
				<p>
				
					<?php if(!isset($_GET['type'])){ ?>
						<div class="row">
							<div class="col-sm-5" style="scale: 1.5; margin-left: 15%; margin-top: 30vh;">
								<h1 style="font-size: 50px;"><i class="fa-solid fa-file-pdf"></i> Upload Files!</h1>
								<h3>Upload Ebook, Notes or Papers or Videos and Lectures!</h3>
								</br>
								<a style="margin-left: 10px;" class="button-boot" href='upload.php?type=pdf'><i class="fa-solid fa-upload"></i> Upload PDF</a>
								<a style="margin-left: 10px;" class="button-boot" href='upload.php?type=video'><i class="fa-solid fa-upload"></i> Upload Video</a>
								</br>	
						</div>	
						
					<?php } ?>
				
				
					<?php if(isset($_GET['type']) && $_GET['type'] == 'pdf'){ ?>
					<div class="row">
						<div class="col-sm-5">
							<h1 style="font-size: 50px;"><i class="fa-solid fa-file-pdf"></i> Upload New PDF!</h1>
							<h3>Select Ebook, Notes or Papers!</h3>
							<?php echo $statusMsg; ?>
							</br>
							<a style="margin-left: 10px;" class="button-boot button-boot-active" href='upload.php?type=pdf'><i class="fa-solid fa-upload"></i> Upload PDF</a>
							<a style="margin-left: 10px;" class="button-boot" href='upload.php?type=video'><i class="fa-solid fa-upload"></i> Upload Video</a>
							</br>
							</br>
							<span style="font-size: 30px;">Select PDF File to Upload:</span></br></br>
							<form action="upload.php?type=pdf&?uploader=true" method="post" id="main_form" enctype="multipart/form-data">
								<div class="form-group">
									<input type="file" class="form-control" accept=".pdf" name="pdf_file" style="padding: 20px; height: 100px; text-align: center;" oninput="framePrev.src=window.URL.createObjectURL(this.files[0]);"/></br>
									<input placeholder="Name..." type="text" class="form-control" name="name"/></br>
									<input placeholder="Description..." type="text" class="form-control button" name="description"/>
								</div>
									<span style="font-size: 20px;">Add to Booklist</span>
									<div class="material-switch pull-right">
										<input id="addtolist" name="addtolist" type="checkbox"/>
										<label for="addtolist" onclick="setplaylistselector();" class="label-success"></label>
										<script>
										function setplaylistselector(){
											let elem = document.getElementById('playlistselector');
											let eleminput = document.getElementById('exampleDataList');
											let set_list_id = document.getElementById('set_list_id');
											if(elem.style.display === 'none'){
												elem.style.display = 'inherit';
												eleminput.value = '';
												set_list_id.value = 0;
											}else{
												elem.style.display = 'none';
												eleminput.value = '';
												set_list_id.value = 0;
											};
										}
										</script>
									</div>

								<input type="hidden" name="list_id" id="set_list_id" value="0"/>
							</form>
							
							<div class="form-group">
								<div id="playlistselector" style="display: none;">
									<input class="form-control" list="datalistOptions" type="option" id="exampleDataList" placeholder="Type to search Booklist..."/>
									<datalist id="datalistOptions">
										<?php 
										// query the database
										$userid = $_COOKIE['userid'];
										$sql = "SELECT * FROM `library_list` WHERE `list_type`='booklist' AND `creator_userid`=$userid";
										$result = mysqli_query($conn, $sql);

										if(!$result){
											echo mysqli_error($con);
										}else{
											// loop through the results and add each row to the array
											while ($rows = mysqli_fetch_assoc($result)) { ?>
												<option data-list_id="<?php echo $rows['list_id']; ?>" value="<?php echo $rows['list_name']; ?>">
											<?php }}  ?>
									</datalist>

									<script>
										// Get the input element
										const inputElement = document.getElementById('exampleDataList');
										const addButtonElement = document.createElement('button');
										// Add an event listener to update the hidden input value whenever the user selects an option from the datalist
										inputElement.addEventListener('input', function() {
											// Get the selected option element
											const optionElement = document.querySelector(`#datalistOptions option[value="${this.value}"]`);

											// Get the value of the data-list_id attribute from the selected option element
											const listId = optionElement ? optionElement.getAttribute('data-list_id') : '0';

											// Set the value of the hidden input element to the data-list_id value
											document.getElementById('set_list_id').value = listId;
											console.log(listId);
											  
											  // If the listId is '0' and the input is not empty, show the add button
											  if (listId === '0' && this.value.trim() !== '') {
												// Limit the input value to 200 characters and replace the rest with three dots before replacing with $$$
												const inputText = this.value.trim().substring(0, 200).replace(/^\s+|\s+$/g, '').replace(/^(.{20}).+/, '$1...').concat('...');
												const buttonText = `+ Add "${inputText}" To Booklist`;

												addButtonElement.textContent = buttonText;
												addButtonElement.classList.add('btn');
												addButtonElement.classList.add('btn-primary');
												addButtonElement.style.marginTop = '15px';
												addButtonElement.setAttribute("onclick","promptDescription('"+inputElement.value+"')");
												addButtonElement.setAttribute("id","PlaylistAddButton");

												// Add the add button to the DOM after the input element
												inputElement.parentNode.insertBefore(addButtonElement, inputElement.nextSibling);
											  } else {
												// If the listId is not '0' or the input is empty, hide the add button
												addButtonElement.remove();
											  }
										});
										
										function promptDescription(name){
											  // Handle adding the input text to the booklist here
											  bootbox.prompt('<h3>New Booklist "'+name+'"</h3></br>Write Short Description',
												function(result) {
													createList(name,result);
												});	
										}
										
										function createList(name, description) {
										  const xhr = new XMLHttpRequest();
										  const url = 'ajax.php?data=NewPlaylist&json=';
										  console.log("sending... - "+name);
										  console.log("sending... - "+description);
										 
										  const data = JSON.stringify({name: name, description: description});

										  xhr.onreadystatechange = function() {
											if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
											  console.log(this.responseText);
											  const response = JSON.parse(this.responseText);
											  if (response[0].success === 'true') {
												// Add new option element to the datalist
												const datalist = document.querySelector('#datalistOptions');
												const newOption = document.createElement('option');
												newOption.value = response[0].name;
												newOption.setAttribute('data-list_id', response[0].list_id);
												datalist.appendChild(newOption);
												document.getElementById('PlaylistAddButton').remove();
												document.getElementById('set_list_id').value = response[0].list_id;
											  }
											}
										  };

										  xhr.open('GET', url+data, true);
										  xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
										  xhr.send(data);
										}
									</script>
								</div>
							</div>
							<script>
								function disableButton(btn) {
								  btn.disabled = true;
								  btn.innerHTML = "<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i> Processing...";
								}
							</script>
							<div class="form-group">
								<button type="submit" style="font-size: 20px; width: 60%" name="submit" onclick="document.getElementById('main_form').submit();disableButton(this);" class="button-boot">
									<i class="fa-solid fa-upload"></i> Upload
								</button>
							</div>	
						</div>
						<div class="col-sm-7">
							<div class="well">
								<h5>Uploaded File Preview</h5>
								<iframe src="sample/samplepdf.html" style="width: 100%; height: 84vh;" id="framePrev"></iframe>
							</div>
						</div>
					</div>	
					
				<?php } ?>




				<?php if(isset($_GET['type']) && $_GET['type'] == 'video'){ ?>
					<div class="row">
						<div class="col-sm-5">
							<h1 style="font-size: 50px;"><i class="fa-solid fa-file-video"></i> Upload New Video!</h1>
							<h3>Select Lecture, Videos or Tutorials!</h3>
							<?php echo $statusMsg; ?>
							</br>
							<a style="margin-left: 10px;" class="button-boot" href='upload.php?type=pdf'><i class="fa-solid fa-upload"></i> Upload PDF</a>
							<a style="margin-left: 10px;" class="button-boot button-boot-active" href='upload.php?type=video'><i class="fa-solid fa-upload"></i> Upload Video</a>
							</br>
							</br>
							<span style="font-size: 30px;">Select MP4 File to Upload:</span></br></br>
							<form action="upload.php?type=video&?uploader=true" method="post" id="main_form" enctype="multipart/form-data">
								<div class="form-group">
									<input type="file" class="form-control" accept=".mp4" name="video_file" style="padding: 20px; height: 100px; text-align: center;" oninput="framePrev.src=window.URL.createObjectURL(this.files[0]); framePrev.controls=true;"/></br>
									<input placeholder="Name..." type="text" class="form-control" name="name"/></br>
									<input placeholder="Description..." type="text" class="form-control button" name="description"/>
								</div>
									<span style="font-size: 20px;">Add to Playlist</span>
									<div class="material-switch pull-right">
										<input id="addtolist" name="addtolist" type="checkbox"/>
										<label for="addtolist" onclick="setplaylistselector();" class="label-success"></label>
										<script>
										function setplaylistselector(){
											let elem = document.getElementById('playlistselector');
											let eleminput = document.getElementById('exampleDataList');
											let set_list_id = document.getElementById('set_list_id');
											if(elem.style.display === 'none'){
												elem.style.display = 'inherit';
												eleminput.value = '';
												set_list_id.value = 0;
											}else{
												elem.style.display = 'none';
												eleminput.value = '';
												set_list_id.value = 0;
											};
										}
										</script>
									</div>

								<input type="hidden" name="list_id" id="set_list_id" value="0"/>
							</form>
							
							<div class="form-group">
								<div id="playlistselector" style="display: none;">
									<input class="form-control" list="datalistOptions" type="option" id="exampleDataList" placeholder="Type to search Playlist..."/>
									<datalist id="datalistOptions">
										<?php 
										// query the database
										$userid = $_COOKIE['userid'];
										$sql = "SELECT * FROM `library_list` WHERE `list_type`='playlist' AND `creator_userid`=$userid";
										$result = mysqli_query($conn, $sql);

										if(!$result){
											echo mysqli_error($con);
										}else{
											// loop through the results and add each row to the array
											while ($rows = mysqli_fetch_assoc($result)) { ?>
												<option data-list_id="<?php echo $rows['list_id']; ?>" value="<?php echo $rows['list_name']; ?>">
											<?php }} ?>
									</datalist>

									<script>
										// Get the input element
										const inputElement = document.getElementById('exampleDataList');
										const addButtonElement = document.createElement('button');
										// Add an event listener to update the hidden input value whenever the user selects an option from the datalist
										inputElement.addEventListener('input', function() {
											// Get the selected option element
											const optionElement = document.querySelector(`#datalistOptions option[value="${this.value}"]`);

											// Get the value of the data-list_id attribute from the selected option element
											const listId = optionElement ? optionElement.getAttribute('data-list_id') : '0';

											// Set the value of the hidden input element to the data-list_id value
											document.getElementById('set_list_id').value = listId;
											console.log(listId);
											  
											  // If the listId is '0' and the input is not empty, show the add button
											  if (listId === '0' && this.value.trim() !== '') {
												// Limit the input value to 200 characters and replace the rest with three dots before replacing with $$$
												const inputText = this.value.trim().substring(0, 200).replace(/^\s+|\s+$/g, '').replace(/^(.{20}).+/, '$1...').concat('...');
												const buttonText = `+ Add "${inputText}" To Booklist`;

												addButtonElement.textContent = buttonText;
												addButtonElement.classList.add('btn');
												addButtonElement.classList.add('btn-primary');
												addButtonElement.style.marginTop = '15px';
												addButtonElement.setAttribute("onclick","promptDescription('"+inputElement.value+"')");
												addButtonElement.setAttribute("id","PlaylistAddButton");

												// Add the add button to the DOM after the input element
												inputElement.parentNode.insertBefore(addButtonElement, inputElement.nextSibling);
											  } else {
												// If the listId is not '0' or the input is empty, hide the add button
												addButtonElement.remove();
											  }
										});
										
										function promptDescription(name){
											  // Handle adding the input text to the booklist here
											  bootbox.prompt('<h3>New Playlist "'+name+'"</h3></br>Write Short Description',
												function(result) {
													if(result === ''){
														promptDescription(name);
													}else{
														createList(name,result);
													}
												});	
										}

										function createList(name, description) {
										  const xhr = new XMLHttpRequest();
										  const url = 'ajax.php?data=NewPlaylistVideo&json=';
										  console.log("sending... - "+name);
										  console.log("sending... - "+description);
										 
										  const data = JSON.stringify({name: name, description: description});

										  xhr.onreadystatechange = function() {
											if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
											  console.log(this.responseText);
											  const response = JSON.parse(this.responseText);
											  if (response[0].success === 'true') {
												// Add new option element to the datalist
												const datalist = document.querySelector('#datalistOptions');
												const newOption = document.createElement('option');
												newOption.value = response[0].name;
												newOption.setAttribute('data-list_id', response[0].list_id);
												datalist.appendChild(newOption);
												document.getElementById('PlaylistAddButton').remove();
												document.getElementById('set_list_id').value = response[0].list_id;
											  }
											}
										  };

										  xhr.open('GET', url+data, true);
										  xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
										  xhr.send(data);
										}
									</script>
								</div>
							</div>
							<div class="form-group">
								<script>
									function disableButton(btn) {
									  btn.disabled = true;
									  btn.innerHTML = "<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i> Processing...";
									}
								</script>
								<button type="submit" style="font-size: 20px; width: 60%" name="submit" onclick="document.getElementById('main_form').submit();disableButton(this);" class="button-boot">
									<i class="fa-solid fa-upload"></i> Upload
								</button>
							</div>	
						</div>
						<div class="col-sm-7">
							<div class="well">
								<h5>Uploaded File Preview</h5>
								<video src="" style="width: 100%; height: 84vh;" id="framePrev"></video>
							</div>
						</div>
					</div>	
					
				<?php } ?>
					
				</p> 
          
		</div>
       </div>


</div>
</div>
</body>
</html>

</body>
</html>


<?php 	}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>