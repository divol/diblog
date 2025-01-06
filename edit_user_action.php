// edit_user_action.php
<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_POST['id'];
$username = $_POST['username'];
$email = $_POST['email'];
$role = $_POST['role'];

$sql = "UPDATE users SET username='$username', email='$email', role='$role' WHERE id='$user_id'";

if ($conn->query($sql) === TRUE) {
    echo 'Utilisateur mis à jour avec succés.';
} else {
    echo 'Erreur: ' . $conn->error;
}

$conn->close();
header("Location: manage_users.php");
exit();
?>
