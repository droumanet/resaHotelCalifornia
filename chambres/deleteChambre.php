<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';

if (!hasRole("directeur")) {
    $encodedMessage = urlencode("ERREUR : Vous n'avez pas les bonnes permissions.");
    header("Location: /resaHotelCalifornia/index.php?message=$encodedMessage");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier si l'ID est valide
if ($id <= 0) {
    header("Location: listChambres.php");
    exit;
}

$conn = openDatabaseConnection();

// Vérifier si la chambre existe
$stmt = $conn->prepare("SELECT * FROM chambres WHERE id = ?");
$stmt->execute([$id]);
$chambre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chambre) {
    header("Location: listChambres.php");
    exit;
}

// Vérifier si la chambre est utilisée dans des réservations
$stmt = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE chambre_id = ?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

$hasReservations = ($count > 0);

// Traitement de la suppression si confirmée
if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    // Si la chambre a des réservations et que l'utilisateur souhaite les supprimer aussi
    if ($hasReservations && isset($_POST['delete_reservations']) && $_POST['delete_reservations'] === 'yes') {
        $stmt = $conn->prepare("DELETE FROM reservations WHERE chambre_id = ?");
        $stmt->execute([$id]);
    } elseif ($hasReservations) {
        // Si la chambre a des réservations mais l'utilisateur ne veut pas les supprimer
        header("Location: listChambres.php?error=1");
        exit;
    }
    
    // Supprimer la chambre
    $stmt = $conn->prepare("DELETE FROM chambres WHERE id = ?");
    $stmt->execute([$id]);
    
    // Rediriger vers la liste des chambres
    header("Location: listChambres.php?deleted=1");
    exit;
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer une Chambre</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Supprimer une Chambre</h1>
        
        <div class="alert alert-warning">
            <p><i class="fa fa-warning"></i> <strong>Attention :</strong> Vous êtes sur le point de supprimer la chambre numéro <?= htmlspecialchars($chambre['numero']) ?>.</p>
        </div>
        
        <?php if ($hasReservations): ?>
            <div class="alert alert-danger">
                <p><i class="fa-solid fa-skull-crossbones"></i><strong>Cette chambre est associée à <?= $count ?> réservation(s).</strong></p>
                <p>La suppression de cette chambre affectera les réservations existantes.</p>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <?php if ($hasReservations): ?>
                <div class="form-check">
                    <input type="checkbox" id="delete_reservations" name="delete_reservations" value="yes">
                    <label for="delete_reservations">Supprimer également les <?= $count ?> réservation(s) associée(s) à cette chambre</label>
                </div>
            <?php endif; ?>
            
            <p>Êtes-vous sûr de vouloir supprimer cette chambre ?</p>
            
            <div class="actions">
                <input type="hidden" name="confirm" value="yes">
                <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                <a href="listChambres.php" class="btn btn-primary" autofocus>Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
