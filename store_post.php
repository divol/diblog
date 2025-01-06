<?php
include 'db.php';

$title = $_POST['title'];
$content = $_POST['content'];
$categories = $_POST['categories'];
$tags = $_POST['tags'];

// Insérer le post
$sql = "INSERT INTO posts (title, content) VALUES ('$title', '$content')";

if ($conn->query($sql) === TRUE) {
    $post_id = $conn->insert_id;

    // Insérer les catégories
    foreach ($categories as $category_id) {
        $conn->query("INSERT INTO post_categories (post_id, category_id) VALUES ('$post_id', '$category_id')");
    }

    // Insérer les tags
    foreach ($tags as $tag_id) {
        $conn->query("INSERT INTO post_tags (post_id, tag_id) VALUES ('$post_id', '$tag_id')");
    }

    // Récupérer les abonnés
    $sql_subscribers = "SELECT email FROM subscriptions";
    $result_subscribers = $conn->query($sql_subscribers);

    // Préparer l'email
    $subject = "Nouveau article publié: $title";
    $message = "Un nouveau article a été publié sur le blog: \n\n$title\n\n$content\n\nVisitez: http://votre-site.com/post.php?id=$post_id";
    $headers = "From: no-reply@votre-site.com";

    // Envoyer l'email à chaque abonné
    if ($result_subscribers->num_rows > 0) {
        while($subscriber = $result_subscribers->fetch_assoc()) {
            mail($subscriber['email'], $subject, $message, $headers);
        }
    }

    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: index.php");
exit();
?>
