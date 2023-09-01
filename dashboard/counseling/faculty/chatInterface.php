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
		
	?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Messenger</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="../library/css/aurna-lightbox.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
			width: 82%;
			margin-left: 17%;
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
<script src="../library/js/aurna-lightbox.js"></script>
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
    <div class="col-sm-2 sidenav hidden-xs">
      <h2>UIU UCAM</h2>
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
		  <li><a href="../logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
	    </ul><br>
    </div>
    <br>

    <div class="col-sm-10 main">

      <h2><i class="fas fa-comment"></i> Messenger</h2>
      <div class="row">
        <div class="col-sm-4">
          <div class="well">
            <p>
				<iframe id="AA" src="home.php?hide=true" style="border: none; height: 80vh; width: 100%;"></iframe>
			</p> 
          </div>
        </div>
		<div class="col-sm-8">
          <div class="well">
            <p>
				<iframe id="BB" src="" style="border: none; height: 80vh; width: 100%;"></iframe>
			</p> 
          </div>
        </div>
      </div> 
    </div>
<script>
  // Wait for the iframe to load
  var iframe = document.getElementById("AA");
  iframe.onload = function() {
    // Get all links in the iframe
    var links = iframe.contentWindow.document.getElementsByTagName("a");
    var bb = document.getElementById("BB");

    // Get URL parameter
    var urlParams = new URLSearchParams(window.location.search);
    var user = urlParams.get('user');
    if (user !== null) {
      // Set iframe source with URL parameter
      bb.src = "chat.php?user=" + user + "&medical=true";
      // Update URL parameter
      history.replaceState(null, '', window.location.pathname);
    }
    
    // Attach onclick event to each link
    for (var i = 0; i < links.length; i++) {
      var link = links[i];
      link.onclick = function(event) {
        // Prevent the default link behavior
        event.preventDefault();
        // Get the link href
        var href = this.getAttribute("href");
        // Set the href as the src of the other iframe
        bb.src = href+ "&medical=true";
        // Update URL parameter
        history.replaceState(null, '', window.location.pathname + "?user=" + href.substring(href.lastIndexOf("=") + 1));
      }
    }

    // Observe the iframe for changes
    var observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.type === "childList") {
          // Get all links in the iframe
          var links = iframe.contentWindow.document.getElementsByTagName("a");
          
          // Attach onclick event to each link
          for (var i = 0; i < links.length; i++) {
            var link = links[i];
            link.onclick = function(event) {
              // Prevent the default link behavior
              event.preventDefault();
              // Get the link href
              var href = this.getAttribute("href");
              // Set the href as the src of the other iframe
              bb.src = href + "&medical=true";
              // Update URL parameter
              history.replaceState(null, '', window.location.pathname + "?user=" + href.substring(href.lastIndexOf("=") + 1));
            }
          }
        }
      });    
    });

    observer.observe(iframe.contentWindow.document, { childList: true, subtree: true });
  };
</script>

</body>
</html>


<script>
		String.prototype.isMatch = function(s){
		   return this.match(s)!==null
		}		
</script>
</body>
</html>


<?php 	}	else { echo "<script>window.open('../login.php','_self')</script>"; } ?>