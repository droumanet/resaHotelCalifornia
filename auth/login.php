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

        $conn = openDatabaseConnection();
           
        if (authenticateUser($username, $password, $conn)) {
            // Vérifier si le rôle est déjà défini dans la session
            error_log("ROLE ".$_SESSION['role']);
            if (!isset($_SESSION['role'])) {
                // Récupérer le rôle de l'utilisateur depuis la base de données
                $conn = openDatabaseConnection();
                $query = "SELECT role FROM employes WHERE username = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$username]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                error_log("Result = ".$result);
                if ($result) {
                    // Ajouter le rôle à la session
                    $_SESSION['role'] = $result['role'];
                    //FIXME Role qui ne s'enregistre pas semble que $result ne contienne rien
                    error_log("Role enregistré : ".$result['role']);
                } else {
                    // Si aucun rôle n'est trouvé, définir un rôle par défaut
                    $_SESSION['role'] = 'guest'; // Exemple : rôle par défaut
                }
            }
            $encodedMessage = urlencode("SUCCES : Bienvenue $username");
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