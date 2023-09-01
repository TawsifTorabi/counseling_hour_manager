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
	if($CurrentUserFaculty == 1){
		
		
		
	//Get Time of the day
	function cusgetTimeOfDay($timeStr) {
	  $time = DateTime::createFromFormat('H:i A', $timeStr);
	  $hour = $time->format('H');

	  if ($hour >= 5 && $hour < 12) {
		return '<i class="fas fa-sun" style="color: #fb8c50;"></i> Morning';
	  } elseif ($hour >= 12 && $hour < 15) {
		return '<i class="fas fa-sun" style="color: #ffc800;"></i> Noon';
	  } elseif ($hour >= 15 && $hour < 18) {
		return '<i class="fas fa-sun" style="color: #d97915;"></i> Afternoon';
	  } else {
		return '<i class="fas fa-sun" style="color: blue;"></i> Evening';
	  }
	}

	$globalValueSQL = "SELECT `global_value` FROM `global_settings` WHERE `global_key`='current_trimester'";
	if ($globalValueResult = mysqli_query($con, $globalValueSQL)) {
		if ($globalValueSQL = mysqli_fetch_assoc($globalValueResult)) {
			$CurrentTrimesterID = $globalValueSQL['global_value'];
		}
	} else {
		// Handle error here
		echo "Error: " . mysqli_error($con);
	}

	$CurrentTrimesterSQL = "SELECT * FROM `academic_trimester` WHERE `trimester_id`=$CurrentTrimesterID";
	if ($CurrentTrimesterResult = mysqli_query($con, $CurrentTrimesterSQL)) {
		if ($CurrentTrimesterValueSQL = mysqli_fetch_assoc($CurrentTrimesterResult)) {
			$CurrentTrimester = $CurrentTrimesterValueSQL['trimester'] . ' ' . $CurrentTrimesterValueSQL['year'];
		}
	} else {
		// Handle error here
		echo "Error: " . mysqli_error($con);
	}
	
	
	$currentUserID = $_COOKIE['userid'];
	$courseFacultyID = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM academic_faculty_profile WHERE user_id='$currentUserID'"))['id'];
	
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Faculty Dashboard - UIU Counselling Hour Booking System</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="../css/aurna-lightbox.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
  <script src="../js/tinymce_6.2.0/tinymce/js/tinymce/tinymce.min.js" ></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script>
	tinymce.init({
	  selector: 'textarea#prescription',
	  plugins: 'image code',
	  promotion: false,
	  toolbar: 'undo redo | link image | code',
	  /* enable title field in the Image dialog*/
	  image_title: true,
	  /* enable automatic uploads of images represented by blob or data URIs*/
	  automatic_uploads: true,
	  /*
		URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
		images_upload_url: 'postAcceptor.php',
		here we add custom filepicker only to Image dialog
	  */
	  images_upload_url: 'ImageUploader.php',
	  file_picker_types: 'image',
	  /* and here's our custom image picker*/
	  file_picker_callback: function (cb, value, meta) {
		var input = document.createElement('input');
		input.setAttribute('type', 'file');
		input.setAttribute('accept', 'image/*');

		/*
		  Note: In modern browsers input[type="file"] is functional without
		  even adding it to the DOM, but that might not be the case in some older
		  or quirky browsers like IE, so you might want to add it to the DOM
		  just in case, and visually hide it. And do not forget do remove it
		  once you do not need it anymore.
		*/

		input.onchange = function () {
		  var file = this.files[0];

		  var reader = new FileReader();
		  reader.onload = function () {
			/*
			  Note: Now we need to register the blob in TinyMCEs image blob
			  registry. In the next release this part hopefully won't be
			  necessary, as we are looking to handle it internally.
			*/
			var id = 'blobid' + (new Date()).getTime();
			var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
			var base64 = reader.result.split(',')[1];
			var blobInfo = blobCache.create(id, file, base64);
			blobCache.add(blobInfo);

			/* call the callback and populate the Title field with the file name */
			cb(blobInfo.blobUri(), { title: file.name });
		  };
		  reader.readAsDataURL(file);
		};

		input.click();
	  },
	  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
	});

	</script>
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
			width: 100%;
			margin-left: 25%;
		}
    }


	.alert-grey {
		color: #525252;
		background-color: #d3d3d3;
		border-color: #c3c3c3;
	}


	.alert-green {
		color: #156417;
		background-color: #8acb6f;
		border-color: #2c9700c7;
		font-weight: bold;
	}
		
	.alert-cyan {
		color: #269169;
		background-color: #94ffbf;
		border-color: #a5c3bd;
		animation: fadeBackground 2s ease-in-out infinite;
	}

	@keyframes fadeBackground {
		0% {
			background-color: #94ffbf;
		}
		50% {
			background-color: #a5c3bd;
		}
		100% {
			background-color: #94ffbf;
		}
	}

	.high-contrast {
		animation: textColorAnimationHighContrast 2s ease-in-out infinite;
	}

	.low-contrast {
		animation: textColorAnimationLowContrast 2s ease-in-out infinite;
	}

	@keyframes textColorAnimationHighContrast {
		0% {
			color: #269169;
		}
		50% {
			color: #ffffff;
		}
		100% {
			color: #269169;
		}
	}

	@keyframes textColorAnimationLowContrast {
		0% {
			color: #269169;
		}
		50% {
			color: #000000;
		}
		100% {
			color: #269169;
		}
	}
	/* CSS */
	.button-10 {
	  align-items: center;
	  padding: 6px 14px;
	  font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
	  border-radius: 6px;
	  border: none;

	  color: #fff;
	  background: linear-gradient(180deg, #4B91F7 0%, #367AF6 100%);
	   background-origin: border-box;
	  box-shadow: 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2);
	  user-select: none;
	  -webkit-user-select: none;
	  touch-action: manipulation;
	  transition: 0.9s;
	}


	.button-10:disabled {
		color: #ddd;
		background: linear-gradient(180deg, #2E3237 0%, #686868 100%);
		transition: 0.9s;
	}



	/* CSS */
	.button-11 {
		align-items: center;
		padding: 6px 14px;
		font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
		border-radius: 6px;
		border: none;
		color: #fff;
		background: linear-gradient(180deg, #D53030 0%, #8E2B2B 100%);
		background-origin: border-box;
		box-shadow: 0px 0.5px 1.5px rgba(221, 74, 150, 0.25), inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2);
		user-select: none;
		-webkit-user-select: none;
		touch-action: manipulation;
		transition: 0.9s;
	}

	.button-11:disabled {
		color: #ddd;
		background: linear-gradient(180deg, #2E3237 0%, #686868 100%);
		transition: 0.9s;
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
	.queueElem {
		display: inline-block;
		outline: 0;
		border: 0;
		font-weight: 600;
		color: #fff;
		height: 38px;
		vertical-align: middle;
		padding: 10px;
		border-radius: 7px;
		background-image: linear-gradient(180deg,#7c8aff,#3c4fe0);
		box-shadow: 0 4px 11px 0 rgb(37 44 97 / 15%), 0 1px 3px 0 rgb(93 100 148 / 20%);
		transition: all .2s ease-out;
		cursor: pointer;
	}   
	.queueElem:hover{
		box-shadow: 0 8px 22px 0 rgb(37 44 97 / 15%), 0 4px 6px 0 rgb(93 100 148 / 20%);
	}
	.pointedQ {
		background: linear-gradient(179deg, #d90101, #a70000);
		/*animation: blink-live 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;*/
	}
	#QueueCont2{
		margin: 4px, 4px;
        padding: 4px;        
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
	}
	/* width */
	::-webkit-scrollbar {
	  width: 4px;
	}

	/* Track */
	::-webkit-scrollbar-track {
	  background: #f1f1f1;
	}

	/* Handle */
	::-webkit-scrollbar-thumb {
	  background: #888;
	}

	/* Handle on hover */
	::-webkit-scrollbar-thumb:hover {
	  background: #555;
	}
  </style>
</head>
<body>
<script src="../js/aurna-lightbox.js"></script>


<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-12 main">

      <h2><i class="fa-solid fa-user-graduate"></i> Faculty Dashboard</h2>
      <div class="row">
        <div class="col-sm-12">
          <div>
            <p>
			<div id="pageTitle" class="col-sm-6">
				<h2><i class="fa-solid fa-calendar-check"></i> Reschedule Schedule</h2>
				<!-- h5>Please respect the queue priority when possible.</h5 -->
			</div>		
			</p> 
          </div>
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
		


		function getTableContents() {
		  console.log('Checking... For All Pending Requests');
		  const url = 'ajax.php?data=LatestRequests'; // replace with your PHP page URL
		  const tableBody = document.getElementById('requestTableBody');
		  
		  // make AJAX request to PHP page to get updated table contents
		  const xhr = new XMLHttpRequest();
		  xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			  const newTableContent = this.responseText;
			  
			  // check if the new table content is different from the current table content
			  if (newTableContent !== tableBody.innerHTML) {
				// update the table with the new content
				tableBody.innerHTML = newTableContent;
			  }
			}
		  };
		  xhr.open('GET', url);
		  xhr.send();
		}


		function getAcceptedRequestsTableContents() {
		  console.log('Checking... For Accepted Requests');
		  const url = 'ajax.php?data=AcceptedRequests'; // replace with your PHP page URL
		  const tableBody = document.getElementById('AcceptedRequestsTableBody');
		  const tableHidden = document.getElementById('storeValApproved');
		  
		  // make AJAX request to PHP page to get updated table contents
		  const xhr = new XMLHttpRequest();
		  xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			  const newTableContent = this.responseText;
			  
			  // check if the new table content is different from the current table content
			  if (newTableContent !== tableHidden.value) {
				// update the table with the new content
				tableBody.innerHTML = newTableContent;
				tableHidden.value = newTableContent;
			  }
			}
		  };
		  xhr.open('GET', url);
		  xhr.send();
		}

		function getStat() {
		  console.log('Checking... For Stats');
		  const url = 'ajax.php?data=RequestCount'; // replace with your PHP page URL
		  let pendingCount = document.getElementById('pendingCount');						
		  let approvedCount = document.getElementById('approvedCount');						
		  let passedCount = document.getElementById('passedCount');						
		 
		
			//$data = array('pending_count' => $row['pending_count'], 'approved_count' => $row['approved_count'], 'before_today_count' => $row['before_today_count']);
		  
		  // make AJAX request to PHP page to get updated table contents
		  let xhr = new XMLHttpRequest();
		  xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				console.log(this.responseText);
				let RetJson;
				if(RetJson = JSON.parse(this.responseText)){
					pendingCount.innerHTML = RetJson.pending_count;
					approvedCount.innerHTML = RetJson.approved_count;
					passedCount.innerHTML = RetJson.before_today_count;
				}else{
					//
				}
			}
		  };
		  xhr.open('GET', url);
		  xhr.send();
		}
		getStat();

		// call the getTableContents function every 3 seconds to check for updates
		setInterval(getAcceptedRequestsTableContents, 3000);
		setInterval(getTableContents, 3000);
		setInterval(getStat, 3000);

		var waitHTML = "<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i> Please Wait...";


		function AcceptRequest(button, request_id, course_code, section){
			button.setAttribute("disabled","true");
			button.nextElementSibling.setAttribute("disabled","true");
			button.innerHTML = waitHTML;
	
			var	xmlhttp=new XMLHttpRequest();
		
			xmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
					console.log("____________________");
					console.log(this.responseText);
					console.log("____________________");
					let RetJson;
					if(RetJson = JSON.parse(this.responseText)){
						if(RetJson.accepted == 'true'){
							button.parentNode.parentElement.remove();
						}else{
							button.removeAttribute("disabled");
							button.nextElementSibling.removeAttribute("disabled");
							button.innerHTML = "Error! Retry";
						}
					}else{
						button.removeAttribute("disabled");
						button.nextElementSibling.removeAttribute("disabled");
						button.innerHTML = "Error! Retry";
					}
				}		
			};

			xmlhttp.open("GET","ajax.php?data=AcceptRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section, true);
			xmlhttp.send();
		}



		function RejectRequest(button, request_id, course_code, section){
			button.setAttribute("disabled","true");
			button.previousElementSibling.setAttribute("disabled","true");
			button.innerHTML = waitHTML;
	
			var	xmlhttp=new XMLHttpRequest();
		
			xmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
					console.log(this.responseText);
					let RetJson;
					if(RetJson = JSON.parse(this.responseText)){
						if(RetJson.rejected == 'true'){
							button.parentNode.parentElement.remove();
						}else{
							button.removeAttribute("disabled");
							button.previousElementSibling.removeAttribute("disabled");
							button.innerHTML = "Error! Retry";
						}
					}else{
						button.removeAttribute("disabled");
						button.previousElementSibling.removeAttribute("disabled");
						button.innerHTML = "Error! Retry";
					}
				}		
			};

			xmlhttp.open("GET","ajax.php?data=RejectRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section, true);
			xmlhttp.send();
		}



		function RemoveRequest(button, request_id, course_code, section){
			button.setAttribute("disabled","true");
			//button.nextElementSibling.setAttribute("disabled","true");
			button.innerHTML = waitHTML;
	
			var	xmlhttp=new XMLHttpRequest();
		
			xmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
					console.log(this.responseText);
					let RetJson;
					if(RetJson = JSON.parse(this.responseText)){
						if(RetJson.canceled == 'true'){
							button.parentNode.parentElement.remove();
						}else{
							button.removeAttribute("disabled");
							//button.nextElementSibling.removeAttribute("disabled");
							button.innerHTML = "Error! Retry";
						}
					}else{
						button.removeAttribute("disabled");
						//button.nextElementSibling.removeAttribute("disabled");
						button.innerHTML = "Error! Retry";
					}
				}		
			};

			xmlhttp.open("GET","ajax.php?data=RemoveRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section, true);
			xmlhttp.send();
		}



		function PresentRequest(button, request_id, course_code, section){
			button.setAttribute("disabled","true");
			//button.nextElementSibling.setAttribute("disabled","true");
			button.innerHTML = waitHTML;
	
			var	xmlhttp=new XMLHttpRequest();
		
			xmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
					console.log(this.responseText);
					let RetJson;
					if(RetJson = JSON.parse(this.responseText)){
						if(RetJson.present == 'true'){
							button.parentNode.parentElement.remove();
						}else{
							button.removeAttribute("disabled");
							//button.nextElementSibling.removeAttribute("disabled");
							button.innerHTML = "Error! Retry";
						}
					}else{
						button.removeAttribute("disabled");
						//button.nextElementSibling.removeAttribute("disabled");
						button.innerHTML = "Error! Retry";
					}
				}		
			};

			xmlhttp.open("GET","ajax.php?data=PresentRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section, true);
			xmlhttp.send();
		}


		function AbsentRequest(button, request_id, course_code, section){
			button.setAttribute("disabled","true");
			//button.nextElementSibling.setAttribute("disabled","true");
			button.innerHTML = waitHTML;
	
			var	xmlhttp=new XMLHttpRequest();
		
			xmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
					console.log(this.responseText);
					let RetJson;
					if(RetJson = JSON.parse(this.responseText)){
						if(RetJson.absent == 'true'){
							button.parentNode.parentElement.remove();
						}else{
							button.removeAttribute("disabled");
							//button.nextElementSibling.removeAttribute("disabled");
							button.innerHTML = "Error! Retry";
						}
					}else{
						button.removeAttribute("disabled");
						//button.nextElementSibling.removeAttribute("disabled");
						button.innerHTML = "Error! Retry";
					}
				}		
			};

			xmlhttp.open("GET","ajax.php?data=AbsentRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section, true);
			xmlhttp.send();
		}


		function RescheduleRequest(button, request_id, course_code, section){
			button.setAttribute("disabled","true");
			//button.nextElementSibling.setAttribute("disabled","true");
			button.innerHTML = waitHTML;
	
			var	xmlhttp=new XMLHttpRequest();
		
			xmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
					console.log(this.responseText);
					let RetJson;
					if(RetJson = JSON.parse(this.responseText)){
						if(RetJson.absent == 'true'){
							button.parentNode.parentElement.remove();
						}else{
							button.removeAttribute("disabled");
							//button.nextElementSibling.removeAttribute("disabled");
							button.innerHTML = "Error! Retry";
						}
					}else{
						button.removeAttribute("disabled");
						//button.nextElementSibling.removeAttribute("disabled");
						button.innerHTML = "Error! Retry";
					}
				}		
			};

			xmlhttp.open("GET","ajax.php?data=RescheduleRequest&request_id="+request_id+"&course_code="+course_code+"&section="+section, true);
			xmlhttp.send();
		}
		
		
		//RemoveAllPassed(this)// Remove All Passed Requests
		//AcceptAllRequest(this)// Accept All Requests
		//RejectAllRequest(this)// Reject All Requests
		
		function RemoveAllPassed(button){
			
			bootbox.confirm({
				message: 'Remove All Passed Date Requests?',
				buttons: {
				confirm: {
				label: 'Yes',
				className: 'btn-success'
				},
				cancel: {
				label: 'No',
				className: 'btn-danger'
				}
				},
				callback: function (result) {
					if(result == true){
						confirmRemoveAllPassed(button);
					}
				}
			});
		}

	function confirmRemoveAllPassed(button){
		let prevHTML = button.innerHTML;
		button.setAttribute("disabled","true");
		button.innerHTML = waitHTML;

		var	xmlhttp=new XMLHttpRequest();

		xmlhttp.onreadystatechange=function() {
			if(this.readyState == 4 && this.status == 200) {
				console.log(this.responseText);
				let RetJson;
				if(RetJson = JSON.parse(this.responseText)){
					if(RetJson.removed == 'true'){
						button.innerHTML = prevHTML;
						button.removeAttribute("disabled");
					}else{
						button.removeAttribute("disabled");
						button.innerHTML = "Error! "+ prevHTML;
					}
				}else{
					button.removeAttribute("disabled");
					button.innerHTML = "Error! "+ prevHTML;
				}
			}		
		};

		xmlhttp.open("GET","ajax.php?data=RemoveAllPassed", true);
		xmlhttp.send();
		
	}
	
	
	
	
	function RejectAllRequest(button){
			
			bootbox.confirm({
				message: 'Reject All Requests?',
				buttons: {
				confirm: {
				label: 'Yes',
				className: 'btn-success'
				},
				cancel: {
				label: 'No',
				className: 'btn-danger'
				}
				},
				callback: function (result) {
					if(result == true){
						confirmRejectAllRequest(button);
					}
				}
			});
		}

	function confirmRejectAllRequest(button){
		let prevHTML = button.innerHTML;
		button.setAttribute("disabled","true");
		button.innerHTML = waitHTML;

		var	xmlhttp=new XMLHttpRequest();

		xmlhttp.onreadystatechange=function() {
			if(this.readyState == 4 && this.status == 200) {
				console.log(this.responseText);
				let RetJson;
				if(RetJson = JSON.parse(this.responseText)){
					if(RetJson.rejected == 'true'){
						button.innerHTML = prevHTML;
						button.removeAttribute("disabled");
					}else{
						button.removeAttribute("disabled");
						button.innerHTML = "Error! "+ prevHTML;
					}
				}else{
					button.removeAttribute("disabled");
					button.innerHTML = "Error! "+ prevHTML;
				}
			}		
		};

		xmlhttp.open("GET","ajax.php?data=RejectAllRequest", true);
		xmlhttp.send();
		
	}



	function AcceptAllRequest(button){
			
			bootbox.confirm({
				message: 'Acccept All Requests?',
				buttons: {
				confirm: {
				label: 'Yes',
				className: 'btn-success'
				},
				cancel: {
				label: 'No',
				className: 'btn-danger'
				}
				},
				callback: function (result) {
					if(result == true){
						confirmAcceptAllRequest(button);
					}
				}
			});
		}

	function confirmAcceptAllRequest(button){
		let prevHTML = button.innerHTML;
		button.setAttribute("disabled","true");
		button.innerHTML = waitHTML;

		var	xmlhttp=new XMLHttpRequest();

		xmlhttp.onreadystatechange=function() {
			if(this.readyState == 4 && this.status == 200) {
				console.log(this.responseText);
				let RetJson;
				if(RetJson = JSON.parse(this.responseText)){
					if(RetJson.accepted == 'true'){
						button.innerHTML = prevHTML;
						button.removeAttribute("disabled");
					}else{
						button.removeAttribute("disabled");
						button.innerHTML = "Error! "+ prevHTML;
					}
				}else{
					button.removeAttribute("disabled");
					button.innerHTML = "Error! "+ prevHTML;
				}
			}		
		};

		xmlhttp.open("GET","ajax.php?data=AcceptAllRequest", true);
		xmlhttp.send();
		
	}
			
		var CurrentTokenNumber;
		var loadedToken;
		
		function LoadLatestTokenNumber(){
			let	latestTokenNumberxmlhttp = new XMLHttpRequest();
			latestTokenNumberxmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
					let RetJson = JSON.parse(this.responseText);
						CurrentTokenNumber =  RetJson.current;
						LoadToken(CurrentTokenNumber);
				}
			}
			latestTokenNumberxmlhttp.open("GET","ajax.php?data=LatestToken", true);
			latestTokenNumberxmlhttp.send();
		}
		LoadLatestTokenNumber();	

		
		
		
		function LoadToken(id){
		
			tinyMCE.activeEditor.setContent('');
			let tokenNumberTitle = document.getElementById('tokenNumberTitle');
			let tokenNumber		 = document.getElementById('tokenNumber');
			let tokenDetailsArea = document.getElementById('tokenDetailsArea');
			let TokenDate 		 = document.getElementById('TokenDate');
			let transactionId 	 = document.getElementById('transactionId');
			let UserNameTitle 	 = document.getElementById('UserNameTitle');
			let UserGender		 = document.getElementById('UserGender');

			var	xmlhttp=new XMLHttpRequest();
			xmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
					let RetJson1 = JSON.parse(this.responseText);
					if(RetJson1.return.isMatch(1)){
						//Token Found
						loadedToken = RetJson1.token;
						document.getElementById('tokenFalse').style.display = 'none';
						document.getElementById('tokenTrue').style.display = 'inherit';
						tokenNumberTitle.innerHTML	=  RetJson1.token;
						tokenNumber.innerHTML 		=  RetJson1.token;
						tokenDetailsArea.innerHTML 	=  "";
						tokenDetailsArea.innerHTML 	=  RetJson1.details;
						TokenDate.innerHTML 		=  RetJson1.date;
						UserNameTitle.innerHTML 	=  RetJson1.name;
						transactionId.innerHTML 	=  RetJson1.transaction;
						UserGender.innerHTML 		=  RetJson1.gender;
						
						//Set and Correct Image URLs 
						const divElement = tokenDetailsArea;
						const imageTags = tokenDetailsArea.querySelectorAll('img');

						// Loop through the image tags and add prefix to src attribute
						for (let i = 0; i < imageTags.length; i++) {
						  let divElement = tokenDetailsArea;
						  const imageTag = imageTags[i];
						  const currentSrc = imageTag.getAttribute('src');
						  const newSrc = '../' + currentSrc;
						  imageTag.setAttribute('src', newSrc);
						}
						
					}else{
						//Token Not Found
						document.getElementById('tokenFalse').style.display = 'inherit';
						document.getElementById('tokenTrue').style.display = 'none';
						tokenNumberTitle.innerHTML	=  RetJson1.token;
						tokenNumber.innerHTML 		=  RetJson1.token;
						tokenDetailsArea.innerHTML 	=  "";
						tokenDetailsArea.innerHTML 	=  RetJson1.details;
						TokenDate.innerHTML 		=  RetJson1.date;
						UserNameTitle.innerHTML 	=  RetJson1.name;
						transactionId.innerHTML 	=  RetJson1.transaction;
						UserGender.innerHTML 		=  RetJson1.gender;
					}
				}		
			};
			

			xmlhttp.open("GET","ajax.php?data=searchToken&token_id="+id, true);
			xmlhttp.send();
		}	


		function TokenPresent(){
			let id = loadedToken;
			let prescription = 'prescription';
			let AprBtn = 'AprBtn';
			let DecBtn = 'DecBtn';
			//let PrescriptionText = document.getElementById(prescription).value;
			let PrescriptionText =  encodeURIComponent(tinyMCE.activeEditor.getContent());
			
			console.log('ID: ' + id);
			console.log('Prescription: ' + PrescriptionText);
			
			document.getElementById(AprBtn).setAttribute("disabled","true");
			document.getElementById(DecBtn).setAttribute("disabled","true");
			
			var	xmlhttp=new XMLHttpRequest();
		
			xmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
						setTimeout(function(){
							tinyMCE.activeEditor.setContent('');
							LoadLatestTokenNumber();
							LoadToken(CurrentTokenNumber);
							document.getElementById(AprBtn).removeAttribute("disabled");
							document.getElementById(DecBtn).removeAttribute("disabled");
							//location.reload();
						}, 500);
				}		
			};

			xmlhttp.open("GET","ajax.php?data=ApproveToken&token_id="+id+"&prescription="+PrescriptionText, true);
			xmlhttp.send();
		}
		


		function TokenAbsent(){
			let id = loadedToken;
			let prescription = 'prescription';
			let AprBtn = 'AprBtn';
			let DecBtn = 'DecBtn';
			
			document.getElementById(AprBtn).setAttribute("disabled","true");
			document.getElementById(DecBtn).setAttribute("disabled","true");
			
			var	xmlhttp=new XMLHttpRequest();
		
			xmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
						setTimeout(function(){
							tinyMCE.activeEditor.setContent('');
							LoadLatestTokenNumber();
							LoadToken(CurrentTokenNumber);
							document.getElementById(AprBtn).removeAttribute("disabled");
							document.getElementById(DecBtn).removeAttribute("disabled");
						}, 500);
				}		
			};
			

			xmlhttp.open("GET","ajax.php?data=DeclineToken&token_id="+id, true);
			xmlhttp.send();
		}
		

		function TokenCounter(){
			let ElemCont = 'TokenCounter';
			let ElemCont2 = 'LatestTokenCont';
			let MsgCont = 'TokenCounterMsg';
			let QueueCont = 'QueueCont';
			let	xmlhttp=new XMLHttpRequest();
			let	xmlhttp2=new XMLHttpRequest();
			xmlhttp.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
					let RetJson = JSON.parse(this.responseText);
					if(RetJson.current.isMatch(RetJson.latest)){
						document.getElementById(ElemCont).innerHTML =  RetJson.current;
						document.getElementById(ElemCont2).innerHTML =  RetJson.latest;
						document.getElementById(MsgCont).innerHTML = '<i class="fa-solid fa-check"></i> <b>This is the Earliest Token!</b>';
					}else{
						document.getElementById(ElemCont).innerHTML =  RetJson.current;
						document.getElementById(ElemCont2).innerHTML =  RetJson.latest;
						document.getElementById(MsgCont).innerHTML = 'Current token the doctor might consulting.';
					}
				}
			}
			xmlhttp2.onreadystatechange=function() {
				if(this.readyState == 4 && this.status == 200) {
					document.getElementById(QueueCont).innerHTML = '';
					let RetJson2 = JSON.parse(this.responseText);
					for(i=0; i<RetJson2.length; i++){
						let classes; 
						if(i==0){
							classes = "queueElem pointedQ";
						}else{
							classes = "queueElem"; 
						}
						document.getElementById(QueueCont).innerHTML +=  '<b onclick="LoadToken('+RetJson2[i]+')" class="'+classes+'">'+RetJson2[i]+'</b> ';
					}
				}
			}
			xmlhttp.open("GET","ajax.php?data=LatestToken", true);
			xmlhttp2.open("GET","ajax.php?data=LatestTokenList", true);
			xmlhttp.send();	
			xmlhttp2.send();	
		}
		
		// setInterval(function(){
		  // TokenCounter();
		// }, 1000);
		
</script>
<script>
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();   
	});
</script>
</body>
</html>


<?php 	}}	else { echo "<script>window.open('../login.php','_self')</script>"; } ?>