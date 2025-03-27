<?php
require_once '../config/db_connect.php';

$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM chambres ORDER BY numero");
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Liste des Chambres</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h1>Liste des Chambres</h1>
    <a href="createChambre.php" class="btn btn-primary">Ajouter une chambre</a>
    <table class="table table-primary table-striped">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Numéro</th>
            <th scope="col">Capacité</th>
            <th scope="col">Actions</th>
        </tr>
        <?php foreach($chambres as $chambre): ?>
        <tr>
            <td><?= $chambre['id'] ?></td>
            <td><?= $chambre['numero'] ?></td>
            <td><?= $chambre['capacite'] ?></td>
            <td>
                <a href="editChambre.php?id=<?= $chambre['id'] ?>">Modifier</a>
                <a href="deleteChambre.php?id=<?= $chambre['id'] ?>" 
                    onclick="return confirm('sûr?')">Supprimer
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>

