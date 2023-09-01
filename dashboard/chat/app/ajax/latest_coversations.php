<?php
session_start();
    if (isset($_COOKIE['sessionid'])){

        #database connection file
        include'../../app/db.conn.php';
        include '../../app/helpers/user.php';
        include '../../app/helpers/conversations.php';
        include '../../app/helpers/timeAge.php';
        include'../../app/helpers/last_chat.php';

        #get user data 
        $user=getUser($_SESSION['username'],$conn);
        #get user Conversations
        $conversations=getConversation($user['id'],$conn);

?>
		<?php if(!empty($conversations)){ ?>
			<?php 
			foreach($conversations as $conversation){?>
			<li class="list-group-item">
				<a href="chat.php?user=<?=$conversation['username']?>"class="d-flex justify-content-between align-items-center p-2">
					<div class="d-flex align-items-center">
						<img src="uploads/<?=$conversation['p_p']?>" alt="" srcset="" class="w-10 rounded-circle">
						<h3 class="fs-xs m-2">
							<?=$conversation['name']?> <br>
						<small>
							<?php lastChat($_SESSION['userid'],$conversation['username'],$conn); ?>
						</small>
						</h3>
					</div>
					<?php if(last_seen($conversation['last_seen'])=='Active'){?>
					<div title="online">
						<div class="online"></div>
					</div>
					<?php }?>
				</a>
			</li>
			<?php  } ?>
		<?php }else{?>
			<div class="alert alert-info text-center">
				<i class="fa fa-comments d-block fs-big"></i>
			   No messages yet, start the conversations
			</div>
		<?php }?>
			</a>
		</li>
<?php
}else{
    header("location:index.php");
    exit;
}
?>