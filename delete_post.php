<?php
// delete_post.php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'];
$sql = "DELETE FROM posts WHERE id='$post_id'";

if ($conn->query($sql) === TRUE) {
    echo 'Article supprimé avec succés.';
} else {
    echo 'Erreur: ' . $conn->error;
}

$conn->close();
header("Location: manage_articles.php");
exit();
?>
