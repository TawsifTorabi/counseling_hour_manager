<?php

function getConversation($user_id,$conn){

    /** getting all the conversations for current (logged in) user */

    //$sql="SELECT conversation_id, MAX(user_1) AS user_1, MAX(user_2) AS user_2 FROM conversations WHERE user_1 = ? OR user_2 = ? GROUP BY conversation_id ORDER BY conversation_id DESC";
    //$sql="SELECT DISTINCT conversation_id, user_1, user_2 FROM conversations WHERE user_1=? OR user_2=? ORDER BY conversation_id DESC";
    $sql="SELECT * fROM conversations WHERE user_1=? OR user_2=? ORDER BY conversation_id DESC";
    $stmt=$conn->prepare($sql);
    $stmt->execute([$user_id,$user_id]);

    if($stmt->rowCount()>0){
        $conversations=$stmt->fetchAll();

        /**creating empty array to store the user conversation */
        $user_data=[];

        #looping through the conversations
        foreach($conversations as $conversations){
            #if conversations user_1 row equal to user_id 
            if($conversations ['user_1']==$user_id){
                $sql2="SELECT `name`,`username`,`p_p`,`last_seen` FROM users WHERE id=?";
                $stmt2=$conn->prepare($sql2);
                $stmt2->execute([$conversations ['user_2']]);
            }else{
                $sql2="SELECT `name`,`username`,`p_p`,`last_seen` FROM users WHERE id=?";
                $stmt2=$conn->prepare($sql2);
                $stmt2->execute([$conversations ['user_1']]);
            }
            $allConversations=$stmt2->fetchAll();
            #pushing the data into the array 
            array_push($user_data,$allConversations[0]);
        
        }
        return $user_data;

    }else{
        $conversations=[];
        return $conversations;

    }

}

?>