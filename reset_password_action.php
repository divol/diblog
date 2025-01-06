<?php
include 'db.php';

$token = $_POST['token'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// Vérifier le token
$sql = "SELECT * FROM password_resets WHERE token='$token'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'];

    // Mettre à jour le mot de passe de l'utilisateur
    $sql = "UPDATE users SET password='$password' WHERE email='$email'";
    $conn->query($sql);

    // Supprimer le token
    $sql = "DELETE FROM password_resets WHERE token='$token'";
    $conn->query($sql);

    echo 'Votre mot de passe a été réinitialisé avec succés.';
} else {
    echo 'Token invalide.';
}

$conn->close();
?>
