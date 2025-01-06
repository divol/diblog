<?php
session_start();
include 'db.php';

// Obtenir les données du formulaire
$post_id = $_POST['post_id'];
$user_id = $_SESSION['user_id'];
$content = $_POST['content'];

// Insérer le commentaire dans la base de données
$sql = "INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', '$user_id', '$content')";

if ($conn->query($sql) === TRUE) {
    echo "New comment added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

// Rediriger vers l'article
header("Location: post.php?id=$post_id");
exit();
?>
