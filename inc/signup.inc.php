<?php 
	session_start();

if (isset($_POST['submit'])) {
	// include dbh
	include_once 'dbh.inc.php';

	//accept Data from user Input
	$first = mysqli_real_escape_string($conn, $_POST['first']);
	$last = mysqli_real_escape_string($conn, $_POST['last']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

	// create Error Handlers
	// check for Empty Fields

	if (empty($first) || empty($last) || empty($email) || empty($uid) || empty($pwd) ) {
		header("Location: ../index.php?signup=empty");
 		exit();
	}else {
		// check if input character are valid
		if (!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last)) {
			header("Location: ../index.php?signup=invalidcharacter");
 			exit();
		}else{
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				header("Location: ../index.php?signup=invalidemail");
 				exit();
			}else {
				//check if users exist
				$sql ="SELECT * FROM users WHERE user_uid='$uid'";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);
				if ($resultCheck > 0) {
					header("Location: ../index.php?signup=userTaken");
 					exit();
				}else{
					$hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);
					// Insert Data Into DB
					$sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd) VALUES('$first', '$last', '$email', '$uid', '$hashedpwd');";
					$result = mysqli_query($conn, $sql);
					header("Location: ../index.php?signup=success");
 					exit();
				}

			}
		}
	}

}else {
 	header("Location: ../index.php?signup=error");
 	exit();
}