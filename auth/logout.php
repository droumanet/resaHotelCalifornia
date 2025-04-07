<?php
    require_once '../config/db_connect.php';
    require_once 'authFunctions.php';

    logoutUser();
    error_log("resaHotelCalifornia : disconnect user");
    $encodedMessage = urlencode("SUCCES: Vous êtes maintenant déconnecté.");
    header("Location: /resaHotelCalifornia/index.php?message=$encodedMessage");

?>