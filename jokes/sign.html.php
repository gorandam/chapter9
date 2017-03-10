<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php htmlout($pageTitle); ?></title>
</head>
<body>
	<h1><?php htmlout($pageTitle); ?></h1>
	<?php if (isset($loginError)): ?>
			<p><?php htmlout($loginError); ?></p>
	<?php endif; ?>
	<form action="?<?php htmlout($signin); ?>" method="post">
	<?php if (isset($signup)): ?>
		<div><label for="name">Your Name: <input type="text" name="name" id="name"></label></div>
	<?php endif; ?>
	<div>
		<label for="email">Your Email: <input type="text" name="email" id="email"></label>
	</div>
	<div>
		<label for="password">Set password: <input type="password" name="password" id="password"></label>
	</div>
	<div>
		<input type="hidden" name= "action" value="<?php htmlout($sign); ?>">
		<input type="submit" value="<?php htmlout($button); ?>"></div>
	</form>
</body>
</html>

