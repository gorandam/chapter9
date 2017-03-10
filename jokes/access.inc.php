<?php 

function userIsLoggedIn() {

    // Code for proccessing login form
	if(isset($_POST['action']) and $_POST['action'] == 'signin') {
		
		// Code to check to see if user filed in values for both input fields
		if (!isset($_POST['email']) or $_POST['email'] == '' or !isset($_POST['password']) or $_POST['password'] == '') {
			$GLOBALS['loginError'] = 'Please fill in both fields';
			return FALSE;
		}
		
		//Code to check if user name and password are correct
		$password = md5($_POST['password'] . 'ijbd'); // Code here scrambled submited password to match on database
		
		// Code to set Login user sessions code
		if (databaseContainsUser($_POST['email'], $password)) {
		
			session_start();
			$_SESSION['loggedIn'] = TRUE;
			$_SESSION['email'] = $_POST['email'];
			$_SESSION['password'] = $password;
			return TRUE;
		} else {
		
			session_start();
			unset($_SESSION['loggedIn']);
			unset($_SESSION['email']);
			unset($_SESSION['password']);
			$GLOBALS['loginError'] = 'The specified email address or password was incorrect.';
			return FALSE;
		}
	}
	
	//Code for proccessing logout form
	if (isset($_POST['action']) and $_POST['action'] == 'logout') {
	

		session_start();
		unset($_SESSION['loggedIn']);
		unset($_SESSION['email']);
		unset($_SESSION['password']);
		header('Location: ' . $_POST['goto']);
		exit();
	}
	
	//Code to check if user is already logged in - credentials are checked against the database every time a sensitive page is requested
	session_start();
	if (isset($_SESSION['loggedIn'])) {
		
		return databaseContainsUser($_SESSION['email'], $_SESSION['password']); 
	}
}

// Special custom function to query database for matching author record
		
function databaseContainsUser($email, $password) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php'; 
	try {
		$sql = 'SELECT COUNT(*) FROM author WHERE email = :email AND password = :password';
		$s = $pdo->prepare($sql);
		$s->bindValue(':email', $email);
		$s->bindValue(':password', $password);
		$s->execute();
	} catch (PDOException $e) {
		$error = 'Error searching for user.';
		include 'error.html.php';
		exit();
	}
	$row = $s->fetch();
	if ($row[0] > 0) {
		return TRUE;
	} else {
		return FALSE;
	}
}
	
