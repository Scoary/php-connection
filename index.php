<?php
	session_start();
	require ('src/connection.php');

	if (!empty($_POST['email']) && !empty($_POST['password'])){
        
        $email = $_POST['email'];
        $password  = $_POST['password'];
        $password = "aq1".sha1($password."8543tu")."89";
        
        $req = $db->prepare('SELECT * FROM user WHERE email = ?');
        $req->execute(array($email));
        
        while ($user = $req->fetch()) {
            if ($password == $user['password']) {
                $_SESSION['connect'] = 1;
                if (isset($_POST['connect'])) {
                    setcookie('session', $user['secret'], time() + 365*24*3600, '/', null, false, true);
                }
                header('location: ./index.php?success=1');
                exit();
            }
        }
        header('location: ./index.php?error=1');
        exit();
    }

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<link rel="icon" type="image/png" href="img/favicon.png">
</head>
<body>

	<?php include('src/header.php'); ?>
	
	<section>
		<div id="login-body">
			<?php if (isset($_SESSION['connect'])) { ?>
				<h1>Bonjour !</h1><br>
				<p>Que voulez vous regarder aujourd'hui ?</p>
				<a class="alert error" href="disconnection.php">Déconnexion</a>
				<?php } else { 
				if (isset($_GET['error'])) {
                    echo '<p class="alert error">L\'adresse email ou le mot de passe est incorrect !</p>';
                } elseif (isset($_GET['success'])) {
                    echo '<p class="alert success">Vous êtes bien connecté !</p>';
                }
               ?>
			   	<h1>S'identifier</h1>
				<form method="post" action="index.php">
					<input type="email" name="email" placeholder="Votre adresse email" required />
					<input type="password" name="password" placeholder="Mot de passe" required />
					<button type="submit">S'identifier</button>
					<label id="option"><input type="checkbox" name="connect" checked />Se souvenir de moi</label>
				</form>
			

				<p class="grey">Première visite sur Netflix ? <a href="inscription.php">Inscrivez-vous</a>.</p>
		</div>
		<?php }?>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>