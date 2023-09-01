<?php

session_start();

#check if the user is logged in 
if(isset($_SESSION['userid'])){

    if(isset($_POST['message'])&& isset($_POST['to_id'])){


    #database connection file
    include'../db.conn.php';
	
	#get data form XHR request and store them in var 
	$message =$_POST['message'];
	$time = time();
	$to_id=$_POST['to_id'];
	
	#get the logged in user's username form the session 
	$from_id=$_SESSION['userid'];
	$sql="INSERT INTO 
			chats(`from_id`,`to_id`,`message`,`created_at`)
			VALUES (?,?,?,?) ";
	$stmt=$conn->prepare($sql);
	$res=$stmt->execute([$from_id,$to_id,$message,$time]);
	
	#if the message inserted 
	if($res){
		/**check if this is the first conversation between them  */
		$sql ="SELECT * FROM conversations WHERE (user_1=? AND user_2=?) OR (user_2=? AND user_1=?)";
		$stmt2=$conn->prepare($sql);
		$stmt2->execute([$from_id,$to_id,$from_id,$to_id]);

		define('TIMEZONE','Asia/Dhaka');
		date_default_timezone_set(TIMEZONE);
		$time=date('h:i:s a');

		if($stmt2->rowCount()==0){
			$sql3="INSERT INTO conversations(user_1,user_2)VALUES (?,?)";
			$stmt3=$conn->prepare($sql3);
			$stmt3->execute([$from_id,$to_id]);
		}
		?>
		<p class="rtext align-self-end  border rounded p-2 mb-1">
			 <?=$message?> 
			 <small class="d-block"><?=$time?></small>
		 </p>
<?php
    }
}

}else{
    header("Location:../../index.php");
    exit;
}
?>