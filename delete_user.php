<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est un administrateur
function check_role($required_role) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    global $conn;
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT role FROM users WHERE id='$user_id'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if ($user['role'] != $required_role) {
        echo 'Accés refusé.';
        exit();
    }
}

check_role('admin');

$id = $_GET['id'];

// Vérifier que l'utilisateur à supprimer n'est pas un administrateur
$sql = "SELECT role FROM users WHERE id='$id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($user['role'] == 'admin') {
    echo "Vous ne pouvez pas supprimer un administrateur.";
    exit();
}

// Supprimer l'utilisateur
$sql = "DELETE FROM users WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Utilisateur supprimé avec succés.";
} else {
    echo "Erreur lors de la suppression de l'utilisateur: " . $conn->error;
}

$conn->close();
header("Location: admin_dashboard.php");
exit();
?>
