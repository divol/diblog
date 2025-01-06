<?php
session_start();
include 'db.php';

// Récupérer toutes les catégories et tags
$categories_result = $conn->query("SELECT id, name FROM categories");
$tags_result = $conn->query("SELECT id, name FROM tags");

// Définir la langue par défaut
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'fr';

// Texte en français
$texts_fr = [
    'create_new_post' => 'Créer un nouveau Post',
    'title' => 'Titre',
    'content' => 'Contenu (Markdown supporté)',
    'categories' => 'Catégories',
    'tags' => 'Tags',
    'submit' => 'Soumettre',
    'back_to_blog' => 'Retour au Blog'
];

// Texte en anglais
$texts_en = [
    'create_new_post' => 'Create New Post',
    'title' => 'Title',
    'content' => 'Content (Markdown supported)',
    'categories' => 'Categories',
    'tags' => 'Tags',
    'submit' => 'Submit',
    'back_to_blog' => 'Back to Blog'
];

// Sélectionner les textes en fonction de la langue
$texts = ($language == 'en') ? $texts_en : $texts_fr;
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts['create_new_post']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css"> <!-- Lien vers votre fichier CSS personnalisé -->
</head>
<body>
    <div class="container">
        <h1 class="my-4"><?php echo $texts['create_new_post']; ?></h1>
        <form action="store_post.php" method="post">
            <div class="form-group">
                <label for="title"><?php echo $texts['title']; ?>:</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content"><?php echo $texts['content']; ?>:</label>
                <textarea id="content" name="content" class="form-control" rows="10" required></textarea>
            </div>
            <div class="form-group">
                <label for="categories"><?php echo $texts['categories']; ?>:</label>
                <select id="categories" name="categories[]" class="form-control" multiple>
                    <?php while($category = $categories_result->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tags"><?php echo $texts['tags']; ?>:</label>
                <select id="tags" name="tags[]" class="form-control" multiple>
                    <?php while($tag = $tags_result->fetch_assoc()): ?>
                        <option value="<?php echo $tag['id']; ?>"><?php echo $tag['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $texts['submit']; ?></button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3"><?php echo $texts['back_to_blog']; ?></a>
    </div>
</body>
</html>
