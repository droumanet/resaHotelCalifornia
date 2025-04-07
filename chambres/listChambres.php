<?php
    require_once '../config/db_connect.php';
    require_once '../auth/authFunctions.php';

    if (!hasRole("standard")) {
        $encodedMessage = urlencode("ERREUR : Vous n'avez pas les bonnes permissions.");
        header("Location: /resaHotelCalifornia/index.php?message=$encodedMessage");
        exit;
    }

    $conn = openDatabaseConnection();
    $stmt = $conn->query("SELECT * FROM chambres ORDER BY numero");
    $chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Chambres</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include_once '../assets/gestionMessage.php'; ?>
    <?php include_once '../assets/navbar.php'; ?>
    
    <div class="container">
        <h1>Liste des Chambres</h1>
        <div class="actions">
            <a href="createChambre.php" class="btn btn-success">Ajouter une chambre</a>
        </div>
        <table class="table table-striped" style="width: 60%; min-width: 400px; margin: 0 auto;">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Numéro</th>
                <th scope="col">Capacité</th>
                <th scope="col">Actions</th>
            </tr>
            <?php foreach($chambres as $chambre): ?>
            <tr>
                <td><?php echo $chambre['id'] ?></td>
                <td><?= $chambre['numero'] ?></td>
                <td><?= $chambre['capacite'] ?></td>
                <td>
                    <a href="editChambre.php?id=<?= $chambre['id'] ?>"><i class="fas fa-pen"></i></a>
                    <a href="deleteChambre.php?id=<?= $chambre['id'] ?>" 
                        onclick="return confirm('sûr?')"><i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>