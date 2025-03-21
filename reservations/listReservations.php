<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';

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
    <title>Liste des Réservations</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien vers la feuille de style externe -->
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="navbar">
        <a href="../index.php">Accueil</a>
        <a href="../chambres/listChambres.php">Chambres</a>
        <a href="../clients/listClients.php">Clients</a>
        <a href="listReservations.php">Réservations</a>
    </div>

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
                                <a href="viewReservation.php?id=<?= $reservation['id'] ?>" 
                                   class="btn btn-info">Voir</a>
                                <a href="editReservation.php?id=<?= $reservation['id'] ?>" 
                                   class="btn btn-primary">Modifier</a>
                                <a href="deleteReservation.php?id=<?= $reservation['id'] ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation?');">
                                    Supprimer
                                </a>
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
</body>
</html>

