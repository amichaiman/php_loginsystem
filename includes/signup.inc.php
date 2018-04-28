<?php


if (isset($_POST['submit'])){

	include_once 'dbh.inc.php';

	$first = mysqli_real_escape_string($conn,$_POST['submit']);
	$last = mysqli_real_escape_string($conn,$_POST['last']);
	$email = mysqli_real_escape_string($conn,$_POST['email']);
	$uid = mysqli_real_escape_string($conn,$_POST['uid']);
	$pwd = mysqli_real_escape_string($conn,$_POST['pwd']);


	//Error handlers
	//check for empty fields
	if (empty($first) || empty($last)|| empty($email)|| empty($uid)|| empty($pwd)){
		go_back_to_main("empty");
	} elseif (!preg_match("/^[a-zA-Z]*$/",$first) || !preg_match("/^[a-zA-Z]*$/",$last)){
		go_back_to_main("invalid");
	} elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
		go_back_to_main("email");
	}

	//check uid is unique
	$sql = "SELECT * FROM users WHERE user_id='$uid'";
	$result = mysqli_query($conn,$sql);
	$resultCheck = mysqli_num_rows($result);

	if ($resultCheck > 0){
		go_back_to_main("usertaken");
	}

	//hashing the password
	$hashed_password = password_hash($pwd, PASSWORD_DEFAULT);

	//insert the user to the database
	$sql = "INSERT INTO users (user_first, user_last, user_email,user_uid, user_psw) 
			VALUES ($first, $last, $email, $uid, $hashed_password);";
	mysqli_query($conn, $sql);
	go_back_to_main("success");
} else {
	go_back_to_main("");
}

function go_back_to_main($error_message){
	header("Location: ../signup.php" . "?signup=" . $error_message);
	exit();
}