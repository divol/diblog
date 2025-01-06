// edit_post_action.php
<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$post_id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];

$sql = "UPDATE posts SET title='$title', content='$content' WHERE id='$post_id'";

if ($conn->query($sql) === TRUE) {
    echo 'Article mis à jour avec succés.';
} else {
    echo 'Erreur: ' . $conn->error;
}

$conn->close();
header("Location: manage_articles.php");
exit();
?>
