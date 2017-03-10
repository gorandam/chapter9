<?php
// Here we reversed magic qoutes demage - we include this always with form
include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';

// Code to signup to add joke
if (isset($_GET['signup'])) {
	//We declare variables for adding author form
	$pageTitle = 'Sign up';
	$button = 'Sign Up';
	$sign = 'signup';
	$signup = 'FALSE'; // To get name input field in template
	$signin = ''; // to avoid signin again
	include 'sign.html.php';
	exit();
}

// Code to proccess signup form submission
if (isset($_POST['action']) and $_POST['action'] == "signup") {
	
	 include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php'; // INCLUDE FILE with php that conrcts controler and ijbd database
	
	// Code to insert new joke author and his email in database
	try {
		$sql = 'INSERT INTO author SET
				name = :name,
				email = :email';
		$s = $pdo->prepare($sql);
		$s->bindValue(':name', $_POST['name']);
		$s->bindValue(':email', $_POST['email']);
		$s->execute();
	} catch (PDOException $e) {
		$error = 'Error submitting author';
		include 'error.html.php';
		exit();
	}
	
	$authorid = $pdo->lastInsertId();
	
	// Code to process password form submisition in new autor mode form submission
	
	if ($_POST['password'] != '') {
		
		$password = md5($_POST['password'] . 'ijbd');
 		
		try {
			$sql = 'UPDATE author SET password = :password WHERE id = :id';
			$s = $pdo->prepare($sql);
			$s->bindValue(':password', $password);
			$s->bindValue(':id', $authorid);
			$s->execute();
		} catch (PDOException $e) {
			$error = 'Error setting author password.';
			include 'error.html.php';
			exit();
		}
	}
	header('Location: .');
	exit();
}

// Code to sign in to add jokes

if (isset($_GET['signin'])) {
	
	require_once 'access.inc.php';

	if (!userIsLoggedIn()) {
		//We declare variables for adding author form
		$pageTitle = 'Sign in to add jokes';
		$signin = 'signin'; // to send signin URL query again
		$sign = 'signin';
		$button = 'Sign In';
		include 'sign.html.php';
		exit();
	}
	include 'form.html.php'; // insert joke form submission
	exit();
}

// Code to process insert joke form submision

if (isset($_POST['action']) and $_POST['action'] == "Addjoke") {
	
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php'; 
	// Code to select last inserted maxi id from author - we cant use lastInsertId
	try {
		$result = $pdo->query('SELECT max(id) FROM author'); // We use this query to select max id from author - thats id of last insert author
	} catch (PDOException $e) {
		$error = 'error selecting max id from author';
		include 'error.html.php';
		exit();
	}
	 $row = $result->fetch();
	 $authorid = $row['max(id)'];
	
	//Code to insert new joke in the database
	try {
		$sql = 'INSERT INTO joke SET
				joketext = :joketext,
				jokedate = CURDATE(),
				authorid = :authorid';
		$s = $pdo->prepare($sql);
		$s->bindValue(':joketext', $_POST['joketext']);
		$s->bindValue(':authorid', $authorid);
		$s->execute();
	} catch (PDOException $e) {
		$error = 'Error adding submitted joke: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}
	header('Location: .');
	exit();
}

// Code to list all jokes from the database

  include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';// INCLUDE FILE with php that conrcts controler and ijbd database

try {
	$sql = 'SELECT joke.id, joketext, name, email FROM joke INNER JOIN author ON authorid = author.id WHERE visible = "YES"';
	$result = $pdo->query($sql);
} catch (PDOException $e) {
	$error = 'Error fetching jokes: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

while ($row = $result->fetch()) {
	$jokes[] = array('id' => $row['id'], 'text' => $row['joketext'], 'name' => $row['name'], 'email' => $row['email']);
}
include 'jokes.html.php';