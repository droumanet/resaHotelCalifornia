<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';

if (!hasRole("standard")) {
    $encodedMessage = urlencode("ERREUR : Vous n'avez pas les bonnes permissions.");
    header("Location: /resaHotelCalifornia/index.php?message=$encodedMessage");
    exit;
}

// Fonction pour formater les dates
function formatDate($date) {
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

// Récupération des réservations avec les informations des clients et des chambres
$conn = openDatabaseConnection();

$query = "SELECT r.id, r.date_arrivee, r.date_depart, 
                 c.nom AS client_nom, c.telephone AS client_telephone, c.email AS client_email, c.nombre_personnes,
                 ch.numero AS chambre_numero, ch.capacite AS chambre_capacite
          FROM reservations r 
          JOIN clients c ON r.client_id = c.id 
          JOIN chambres ch ON r.chambre_id = ch.id 
          ORDER BY r.date_arrivee DESC";

$stmt = $conn->query($query);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Réservations</title>
    <!-- Lien vers la feuille de style externe -->
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once '../assets/gestionMessage.php'; ?>
    <?php include '../assets/navbar.php'; ?>

    <div class="container">
        <h1>Liste des Réservations</h1>
        
        <div class="actions">
            <a href="createReservation.php" class="btn btn-success">Nouvelle Réservation</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Contact</th>
                    <th>Chambre</th>
                    <th>Personnes</th>
                    <th>Arrivée</th>
                    <th>Départ</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reservations) > 0): ?>
                    <?php foreach($reservations as $reservation): ?>
                        <?php 
                            $aujourd_hui = date('Y-m-d');
                            $statut = '';
                            
                            if ($reservation['date_depart'] < $aujourd_hui) {
                                $statut_class = 'status-past';
                                $statut = 'Terminée';
                            } elseif ($reservation['date_arrivee'] <= $aujourd_hui && $reservation['date_depart'] >= $aujourd_hui) {
                                $statut_class = 'status-active';
                                $statut = 'En cours';
                            } else {
                                $statut_class = '';
                                $statut = 'À venir';
                            }
                        ?>
                        <tr>
                            <td><?= $reservation['id'] ?></td>
                            <td><?= htmlspecialchars($reservation['client_nom']) ?></td>
                            <td>
                                <strong>Tél:</strong> <?= htmlspecialchars($reservation['client_telephone']) ?><br>
                                <strong>Email:</strong> <?= htmlspecialchars($reservation['client_email']) ?>
                            </td>
                            <td>N° <?= htmlspecialchars($reservation['chambre_numero']) ?> 
                                (<?= $reservation['chambre_capacite'] ?> pers.)</td>
                            <td><?= $reservation['nombre_personnes'] ?></td>
                            <td><?= formatDate($reservation['date_arrivee']) ?></td>
                            <td><?= formatDate($reservation['date_depart']) ?></td>
                            <td class="<?= $statut_class ?>"><?= $statut ?></td>
                            <td>
                                <a href="viewReservation.php?id=<?= $reservation['id'] ?>"><i class="fas fa-eye"></i></a>
                                <a href="editReservation.php?id=<?= $reservation['id'] ?>"><i class="fas fa-pen"></i></a>
                                <a href="deleteReservation.php?id=<?= $reservation['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">Aucune réservation trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>

