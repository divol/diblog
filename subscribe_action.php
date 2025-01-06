<?php
include 'db.php';

$email = $_POST['email'];

// Insérer l'abonnement dans la base de données
$sql = "INSERT INTO subscriptions (email) VALUES ('$email')";

if ($conn->query($sql) === TRUE) {
    echo "Vous êtes maintenant abonné aux notifications.";
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: index.php");
exit();
?>
