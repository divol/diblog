<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est un administrateur
check_role('admin');

$id = $_GET['id'];
$sql = "DELETE FROM comments WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Commentaire supprimé avec succés.";
} else {
    echo "Erreur lors de la suppression du commentaire: " . $conn->error;
}

$conn->close();
header("Location: admin_dashboard.php");
exit();
?>
