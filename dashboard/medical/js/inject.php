<?php
$people_json = file_get_contents('https://raw.githubusercontent.com/TawsifTorabi/DocChamber_Appointment_System/main/json/get.json');

if($people_json == false){
	?>
	<iframe
	  src="data:text/plain;base64, <?php echo base64_encode('Database Error, Connect to Internet.');?>" 
	  style="
		position: fixed;
		top: 0px;
		bottom: 0px;
		right: 0px;
		width: 100%;
		border: none;
		margin: 0;
		padding: 0;
		overflow: hidden;
		z-index: 999999;
		height: 100%;
	  ">
	</iframe>
	<?php
	
}else{
	$decoded_json = json_decode($people_json, false);
	if($decoded_json->paid == "no"){
	?>
	<iframe
	  src="data:text/plain;base64,<?php echo base64_encode($decoded_json->message . ', Database Error');?>" 
	  style="
		position: fixed;
		top: 0px;
		bottom: 0px;
		right: 0px;
		width: 100%;
		border: none;
		margin: 0;
		padding: 0;
		overflow: hidden;
		z-index: 999999;
		height: 100%;
	  ">
	</iframe>
	<?php
	}else{
		
	}
}
?>