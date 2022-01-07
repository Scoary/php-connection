<?php
	session_start();
	if (isset($_SESSION['connect'])) {
        header ('location: ./');
        exit();
    }

	require ('src/connection.php');

	if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_two'])) {
        
        $email              = htmlspecialchars($_POST['email']);
        $password           = htmlspecialchars($_POST['password']);
        $password_two       = htmlspecialchars($_POST['password_two']);

		
        if ($password != $password_two) {
			header('location: ./inscription.php?error=1&pass=1');
            exit();
        }
		$req1 = $db->prepare('SELECT count(*) as numberEmail FROM user WHERE email = ?');
		$req1->execute(array($email));
		
		while ($email_verification = $req1->fetch()) {
			if ($email_verification['numberEmail'] != 0) {
				header('location: inscription.php?error=1&email=Votre adresse email est déjà utilisée.');
				exit();
			}
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			header('location: inscription.php?error=1&message=Votre adresse email est invalide.');
			exit();
		}
		
		$secret = sha1($email).time();
        $secret = sha1($secret).time().time();
		$password = "aq1".sha1($password."8543tu")."89";
		
		$req = $db->prepare('INSERT INTO user (email, password, secret) VALUES(?, ?, ?)');
        $req->execute(array($email, $password, $secret));
        
		header('location: ./inscription.php?success=1');
		exit();

	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<link rel="icon" type="image/pngn" href="img/favicon.png">
</head>
<body>

	<?php include('src/header.php'); ?>
	
	<section>
		<div id="login-body">
			<h1>S'inscrire</h1>
			<?php if (isset($_GET['error'])) {
				if (isset($_GET['pass'])) { ?>
					<p class="error alert"> Vos mots de passe ne sont pas identiques !</p>
				<?php }
				elseif (isset($_GET['message'])) { ?>
				<p class="error alert">Votre adresse email est invalide.</p>
			<?php }elseif (isset($_GET['email'])) { ?>
				<p class="error alert">Votre adresse email est déjà utilisée.</p>
			<?php }}
				elseif (isset($_GET['success'])){ ?>
				<p class="success alert">Félicitation, vous êtes inscrit ! <a href="index.php">Connectez-vous</a></p>
			<?php } ?>
			<form method="post" action="inscription.php">
				<input type="email" name="email" placeholder="Votre adresse email" required />
				<input type="password" name="password" placeholder="Mot de passe" required />
				<input type="password" name="password_two" placeholder="Retapez votre mot de passe" required />
				<button type="submit">S'inscrire</button>
			</form>

			<p class="grey">Déjà sur Netflix ? <a href="index.php">Connectez-vous</a>.</p>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>