<?php
session_start();

if (isset($_POST['submit'])) {
	include_once 'dbh.inc.php';
	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
	// Error Handlers
if (empty($uid) || empty($pwd)) {
		header("Location: ../index.php?signin=empty");
 		exit();
	}else{
			$sql = "SELECT * FROM users WHERE user_uid='$uid' or user_email='$uid'";
			$result = mysqli_query($conn, $sql);
			$resultCheck = mysqli_num_rows($result);
			if ($resultCheck < 1) {
				header("Location: ../index.php?signin=Usernameandpasswordnotcorrect");
 				exit();
			}else{
				if ($row = mysqli_fetch_assoc($result)) {
					//De-Hashing The Password
					$hashedpwdcheck = password_verify($pwd, $row['user_pwd']);
					if ($hashedpwdcheck == false) {
						header("Location: ../index.php?signin=passwordnotcorrect");
 						exit();

					}elseif ($hashedpwdcheck == true) {
						//Login The User
						$SESSION['u_id'] = $row['user_id'];
						$SESSION['u_first'] = $row['user_first'];
						$SESSION['u_last'] = $row['user_last'];
						$SESSION['u_email'] = $row['user_email'];
						$SESSION['u_uid'] = $row['user_uid'];
						header("Location: ../index.php?signin=success");
 						exit();

					}
				}
			}
	}
}else{
	header("Location: ../index.php?signin=error");
 		exit();
}