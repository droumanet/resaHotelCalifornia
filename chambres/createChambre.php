<?php
    require_once '../config/db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $numero = $_POST['numero'];
        $capacite = $_POST['capacite'];
        if (empty($numero) || empty($capacite) || !is_numeric($numero) || !is_numeric($capacite))  {
            $encodedMessage = urlencode("ERREUR : une ou plusieurs valeurs erronnée(s).");
            header("Location: listChambres.php?message=$encodedMessage");
        } else {
            $conn = openDatabaseConnection();
            $stmt = $conn->prepare("INSERT INTO chambres (numero, capacite) VALUES (?, ?)");
            $stmt->execute([$numero, $capacite]);
            closeDatabaseConnection($conn);
            
            $encodedMessage = urlencode("SUCCES : ajout effectuée.");
            header("Location: listChambres.php?message=$encodedMessage");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Chambre</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets.style.css">
</head>
<body>
    <?php include '../assets/navbar.php'; ?>

    <div class="container">
        <h1>Ajouter une Chambre</h1> 
        <div class="container mt-3">
        <!-- Formulaire -->
        <form method="POST">
            <!-- Champ Numéro -->
            <div class="row mb-3 ">
                <label for="numero" class="col-2 col-form-label text-end">Numéro</label>
                <div class="col-4">
                    <input type="text" id="numero" name="numero" class="form-control" placeholder="Entrez le numéro">
                </div>
            </div>
            <!-- Champ Capacité -->
            <div class="row mb-3 ">
                <label for="capacite" class="col-2 col-form-label text-end">Capacité</label>
                <div class="col-4">
                    <input type="text" id="capacite" name="capacite" class="form-control" placeholder="Entrez la capacité">
                </div>
            </div>
            <div class="row">
                <div class="col-2 text-end">
                    <!-- Bouton de retour -->
                    <a href="listChambres.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="col-4 text-end">
                    <!-- Bouton -->
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
