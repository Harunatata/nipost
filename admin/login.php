<?php
	//Start session
	session_start();
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Connect to mysql server
	// $link = mysql_connect('localhost','root',"");
	// if(!$link) {
	// 	die('Failed to connect to server: ' . mysql_error());
	// }
	
	// //Select database
	// $db = mysql_select_db('parcel', $link);
	// if(!$db) {
	// 	die("Unable to select database");
	// }
	// Connect to MySQL server and select database
$link = mysqli_connect('localhost', 'root', '', 'nipost');

if (!$link) {
    die('Failed to connect to server: ' . mysqli_connect_error());
}

	
	//Function to sanitize values received from the form. Prevents SQL injection
	// function clean($str) {
	// 	$str = @trim($str);
	// 	if(get_magic_quotes_gpc()) {
	// 		$str = stripslashes($str);
	// 	}
	// 	return mysql_real_escape_string($str);
	// }

	function clean($conn, $str) {
    return mysqli_real_escape_string($conn, trim($str));
}

	
	//Sanitize the POST values
	// $login = clean($_POST['username']);
	// $password = clean($_POST['password']);
	$login = clean($link, $_POST['username']);
$password = clean($link, $_POST['password']);

	
	//Input Validations
	if($login == '') {
		$errmsg_arr[] = 'Username missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: index.php");
		exit();
	}
	
	// //Create query
	// $qry="SELECT * FROM user WHERE username='$login' AND password='$password'";
	// // $result=mysql_query($qry);
	// $result = mysqli_query($link, $qry);

	
	// //Check whether the query was successful or not
	// if($result) {
	// 	($count = mysqli_num_rows($result); > 0) {
	// 		//Login Successful
					
	// 				session_regenerate_id();
	// 				$member = mysql_fetch_assoc($result);
	// 				$position = $member['position'];
	// 				$_SESSION['SESS_MEMBER_ID'] = $member['id'];
	// 				$_SESSION['SESS_FIRST_NAME'] = $member['name'];
	// 				$_SESSION['SESS_LAST_NAME'] = $member['position'];
	// 				//$_SESSION['SESS_PRO_PIC'] = $member['profImage'];
	// 		if($position =="admin")
	// 		{
					
					
	// 				header("location: main/index.php");
	// 				exit();
	// 		}
	// 		elseif($position =="Driver")
	// 		{
				
	// 				header("location: main/driver.php");
	// 				exit();	
	// 		}
	// 		session_write_close();
	// 	}else {
	// 		//Login failed
	// 		header("location: index.php");
	// 		exit();
	// 	}
	// }else {
	// 	die("Query failed");
	// }
	// Create query
$qry = "SELECT * FROM user WHERE username='$login' AND password='$password'";
$result = mysqli_query($link, $qry);

// Check whether the query was successful or not
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        // Login Successful
        session_regenerate_id();
        $member = mysqli_fetch_assoc($result);
        $position = $member['position'];
        $_SESSION['SESS_MEMBER_ID'] = $member['id'];
        $_SESSION['SESS_FIRST_NAME'] = $member['name'];
        $_SESSION['SESS_LAST_NAME'] = $member['position'];

        if ($position == "admin") {
            header("location: main/index.php");
            exit();
        } elseif ($position == "Driver") {
            header("location: main/driver.php");
            exit();
        }
        session_write_close();
    } else {
        // Login failed
        header("location: index.php");
        exit();
    }
} else {
    die("Query failed: " . mysqli_error($link));
}

?>