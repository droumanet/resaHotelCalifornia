<?php
    require_once '../config/db_connect.php';
    require_once 'authFunctions.php';

    $error = '';

  
    // Si déjà connecté, rediriger vers l'accueil
    if (isLoggedIn()) {
        header("Location: /resaHotelCalifornia/index.php");
        exit;
    }

    // Traitement du formulaire de connexion
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username=='') {
            logoutUser();
            $encodedMessage = urlencode("SUCCES : Vous êtes désormais déconnecté");
            header("Location: /resaHotelCalifornia/index.php?message=$encodedMessage");
            exit;
        }
        $conn = openDatabaseConnection();
           
        if (authenticateUser($username, $password, $conn)) {
            $encodedMessage = urlencode("SUCCES : Bienvenu $username");
            header("Location: /resaHotelCalifornia/index.php?message=$encodedMessage");
            exit;
        } else {
            error_log("resaHotelCalifornia : authenticate_user = ".authenticateUser($username, $password, $conn));
            $encodedMessage = urlencode("ERREUR : Identifiants incorrects ($username)");
            header("Location: /resaHotelCalifornia/index.php?message=$encodedMessage");
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Identifiant employé:</label>
                <input type="text" name="username" id="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" name="password" id="password" required>
            </div>
            
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>