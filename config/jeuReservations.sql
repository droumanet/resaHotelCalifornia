-- Date au format YYYY-MM-DD
-- Réservations passées
INSERT INTO reservations (client_id, chambre_id, date_arrivee, date_depart) VALUES
(1, 1, '2024-01-15', '2024-01-20'),
(3, 3, '2024-02-05', '2024-02-07');

-- Réservations en cours (ajustez selon la date actuelle)
INSERT INTO reservations (client_id, chambre_id, date_arrivee, date_depart) VALUES
(2, 4, '2025-03-18', '2025-03-25'),
(5, 2, '2025-03-15', '2025-03-22');

-- Réservations futures
INSERT INTO reservations (client_id, chambre_id, date_arrivee, date_depart) VALUES
(4, 6, '2025-04-10', '2025-04-17'),
(6, 8, '2025-05-01', '2025-05-05'),
(7, 5, '2025-06-15', '2025-06-22'),
(8, 9, '2025-07-01', '2025-07-10');

-- Multiple réservations pour la même chambre (à différentes dates)
INSERT INTO reservations (client_id, chambre_id, date_arrivee, date_depart) VALUES
(1, 1, '2025-05-10', '2025-05-15'),
(2, 1, '2025-06-20', '2025-06-25');

-- Multiple réservations pour le même client (différentes chambres)
INSERT INTO reservations (client_id, chambre_id, date_arrivee, date_depart) VALUES
(4, 7, '2025-08-05', '2025-08-12'),
(4, 10, '2025-10-10', '2025-10-15');