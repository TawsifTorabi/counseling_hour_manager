<?php

session_start();

#check if the user is logged in 
if(isset($_SESSION['username'])){
	$time = time();
    #database connection file
    include'../db.conn.php';

    #get the logged in user's username for SESSION
    $id=$_SESSION['userid'];

    $sql="UPDATE users SET last_seen = $time WHERE id = ?";
    $stmt=$conn->prepare($sql);
    $stmt->execute([$id]);


}else{
    header("Location:../../index.php");
}


?>