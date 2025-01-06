<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];

// Mettre à jour le profil de l'utilisateur
$sql = "UPDATE users SET username='$username', email='$email' WHERE id='$user_id'";

if ($conn->query($sql) === TRUE) {
    echo 'Votre profil a été mis à jour avec succés.';
} else {
    echo 'Erreur: ' . $conn->error;
}

$conn->close();
header("Location: profile.php");
exit();
?>
