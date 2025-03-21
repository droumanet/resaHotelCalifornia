// chambres/create.php
<?php
require_once '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = $_POST['capacite'];
    
    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("INSERT INTO chambres (numero, capacite) VALUES (?, ?)");
    $stmt->execute([$numero, $capacite]);
    closeDatabaseConnection($conn);
    
    header("Location: listChambres.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Ajouter une Chambre</title>
</head>
<body>
    <h1>Ajouter une Chambre</h1>
    <form method="post">
        <div>
            <label>Numéro:</label>
            <input type="text" name="numero" required>
        </div>
        <div>
            <label>Capacité:</label>
            <input type="number" name="capacite" required>
        </div>
        <button type="submit">Enregistrer</button>
    </form>
    <a href="listChambres.php">Retour à la liste</a>
</body>
</html>
