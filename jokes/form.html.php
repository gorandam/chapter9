<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Add Joke</title>
	<style type="text/css">
		textarea {
		display: block;
		width: 100%;
		}
	</style>
</head>
<body>
	<form action="?" method="post">
	<div>
		<label for="joketext">Type your joke here:</label>
		<textarea id="joketext" name="joketext" rows="3" cols="40"></textarea>
	</div>
	<div>
		<input type="hidden" name="action" value="Addjoke">
		<input type="submit" value="Addjoke"></div>
	</form>
	<?php include 'logout.inc.html.php' ?>
</body>
</html>

