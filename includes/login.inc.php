<?php
session_start();
if (isset($_POST['submit'])){

	include 'dbh.inc.php';

	$uid = mysqli_real_escape_string($conn,$_POST['uid']);
	$pwd = mysqli_real_escape_string($conn,$_POST['pwd']);

	//Error handlers

	if (empty($uid) || empty($pwd)){
		go_back_to_main("error");
	}
	$sql = "SELECT * FROM users WHERE user_first='$uid' OR user_email='$uid'";
	$result = mysqli_query($conn,$sql);
	$resultCheck = mysqli_num_rows($result);

	if ($resultCheck < 1){
		go_back_to_main("fdas");
	}

	if ($row = mysqli_fetch_assoc($result)){
		//De-hashing the password
		$hashedPwdCheck = password_verify($pwd,$row['user_pwd']);

		if ($hashedPwdCheck == false){
			go_back_to_main("error");
		} elseif ($hashedPwdCheck == true){
			//Log in the user
			$_SESSION['u_id'] = $row['user_id'];
			$_SESSION['u_first'] = $row['user_first'];
			$_SESSION['u_last'] = $row['user_last'];
			$_SESSION['u_email'] = $row['user_email'];
			$_SESSION['u_uid'] = $row['user_uid'];
			go_back_to_main("success");
		}
		go_back_to_main("error");
	}

} else {
	go_back_to_main("error");
}


function go_back_to_main($message){
	header("Location: ../index.php" . "?login=" . $message);
	exit();
}