<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est un administrateur
check_role('admin');

$id = $_GET['id'];
$sql = "UPDATE users SET role='blocked' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Utilisateur bloqué avec succés.";
} else {
    echo "Erreur lors du blocage de l'utilisateur: " . $conn->error;
}

$conn->close();
header("Location: admin_dashboard.php");
exit();
?>
