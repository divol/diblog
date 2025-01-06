<?php
session_start();
include 'db.php';

// Obtenir l'ID de l'article depuis l'URL
$post_id = $_GET['id'];

// Récupérer l'article
$sql = "SELECT * FROM posts WHERE id='$post_id'";
$result = $conn->query($sql);
$post = $result->fetch_assoc();

// Récupérer les commentaires
$sql_comments = "SELECT comments.content, comments.created_at, users.username 
                 FROM comments 
                 JOIN users ON comments.user_id = users.id 
                 WHERE post_id='$post_id' 
                 ORDER BY comments.created_at DESC";
$result_comments = $conn->query($sql_comments);

// Définir la langue par défaut
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'fr';

// Texte en français
$texts_fr = [
    'comments' => 'Commentaires',
    'add_comment' => 'Ajouter un commentaire',
    'submit' => 'Soumettre',
    'content' => 'Contenu',
    'login_to_comment' => 'Connectez-vous pour commenter',
    'no_comments' => 'Aucun commentaire'
];

// Texte en anglais
$texts_en = [
    'comments' => 'Comments',
    'add_comment' => 'Add a Comment',
    'submit' => 'Submit',
    'content' => 'Content',
    'login_to_comment' => 'Login to comment',
    'no_comments' => 'No comments'
];

// Sélectionner les textes en fonction de la langue
$texts = ($language == 'en') ? $texts_en : $texts_fr;
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['title']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css"> <!-- Lien vers votre fichier CSS personnalisé -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">Simple Blog</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?php echo $texts['hello']; ?>, <?php echo $_SESSION['username']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><?php echo $texts['logout']; ?></a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><?php echo $texts['login']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php"><?php echo $texts['register']; ?></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="my-4"><?php echo $post['title']; ?></h1>
        <p><?php echo $post['content']; ?></p>
        <hr>
        <h2><?php echo $texts['comments']; ?></h2>
        <?php if ($result_comments->num_rows > 0): ?>
            <?php while($comment = $result_comments->fetch_assoc()): ?>
                <div class="comment">
                    <p><?php echo $comment['content']; ?></p>
                    <small><?php echo $comment['username']; ?> - <?php echo $comment['created_at']; ?></small>
                    <hr>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p><?php echo $texts['no_comments']; ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['username'])): ?>
            <form action="add_comment.php" method="post">
                <div class="form-group">
                    <label for="content"><?php echo $texts['content']; ?>:</label>
                    <textarea id="content" name="content" class="form-control" rows="3" required></textarea>
                </div>
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <button type="submit" class="btn btn-primary"><?php echo $texts['submit']; ?></button>
            </form>
        <?php else: ?>
            <p><?php echo $texts['login_to_comment']; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
