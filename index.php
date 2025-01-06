<?php
session_start();
include 'db.php';

// Définir le nombre d'articles par page
$articles_per_page = 5;

// Obtenir le numéro de la page actuelle à partir de l'URL, par défaut à 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $articles_per_page;

// Requête de recherche
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql = "SELECT DISTINCT posts.id, posts.title, posts.content, posts.created_at
            FROM posts
            LEFT JOIN post_categories ON posts.id = post_categories.post_id
            LEFT JOIN categories ON post_categories.category_id = categories.id
            LEFT JOIN post_tags ON posts.id = post_tags.post_id
            LEFT JOIN tags ON post_tags.tag_id = tags.id
            WHERE posts.title LIKE '%$search_query%'
               OR posts.content LIKE '%$search_query%'
               OR categories.name LIKE '%$search_query%'
               OR tags.name LIKE '%$search_query%'
            ORDER BY posts.created_at DESC
            LIMIT $start, $articles_per_page";
} else {
    // Récupérer les articles pour la page actuelle
    $sql = "SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC LIMIT $start, $articles_per_page";
}
$result = $conn->query($sql);

// Récupérer le nombre total d'articles de la recherche
if (isset($_GET['search'])) {
    $total_articles_sql = "SELECT COUNT(DISTINCT posts.id)
                           FROM posts
                           LEFT JOIN post_categories ON posts.id = post_categories.post_id
                           LEFT JOIN categories ON post_categories.category_id = categories.id
                           LEFT JOIN post_tags ON posts.id = post_tags.post_id
                           LEFT JOIN tags ON post_tags.tag_id = tags.id
                           WHERE posts.title LIKE '%$search_query%'
                              OR posts.content LIKE '%$search_query%'
                              OR categories.name LIKE '%$search_query%'
                              OR tags.name LIKE '%$search_query%'";
} else {
    $total_articles_sql = "SELECT COUNT(*) FROM posts";
}
$total_articles_result = $conn->query($total_articles_sql);
$total_articles = $total_articles_result->fetch_row()[0];

// Calculer le nombre total de pages
$total_pages = ceil($total_articles / $articles_per_page);

// Définir la langue par défaut
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'fr';

// Texte en français
$texts_fr = [
    'blog_posts' => 'Articles de blog',
    'create_new_post' => 'Créer un nouveau post',
    'hello' => 'Bonjour',
    'logout' => 'Déconnexion',
    'login' => 'Connexion',
    'register' => 'Inscription',
    'previous' => 'Précédent',
    'next' => 'Suivant',
    'categories' => 'Catégories',
    'tags' => 'Tags',
    'search' => 'Rechercher',
    'admin_dashboard' => 'Administration'
];

// Texte en anglais
$texts_en = [
    'blog_posts' => 'Blog Posts',
    'create_new_post' => 'Create New Post',
    'hello' => 'Hello',
    'logout' => 'Logout',
    'login' => 'Login',
    'register' => 'Register',
    'previous' => 'Previous',
    'next' => 'Next',
    'categories' => 'Categories',
    'tags' => 'Tags',
    'search' => 'Search',
    'admin_dashboard' => 'Admin dashboard'
];

// Sélectionner les textes en fonction de la langue
$texts = ($language == 'en') ? $texts_en : $texts_fr;
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Blog</title>
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
                        <a class="nav-link" href="profile.php"><?php echo $texts['hello']; ?>, <?php echo $_SESSION['username']; ?></a>
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
                

<?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'):  ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php"><?php echo $texts['admin_dashboard']; ?></a>
                    </li>
<?php else: ?>
<?php endif; ?>

            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="my-4"><?php echo $texts['blog_posts']; ?></h1>

        <!-- Formulaire de recherche -->
        <form class="form-inline mb-4" method="GET" action="index.php">
            <input class="form-control mr-sm-2" type="search" name="search" placeholder="<?php echo $texts['search']; ?>" aria-label="Search" value="<?php echo htmlspecialchars($search_query); ?>">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><?php echo $texts['search']; ?></button>
        </form>

        <?php
        $Parsedown = new Parsedown();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='blog-post'>";
                echo "<h2><a href='post.php?id=" . $row["id"] . "'>" . $row["title"]. "</a></h2>";
                echo "<p>" . $Parsedown->text($row["content"]). "</p>";

                // Récupérer les catégories et tags pour chaque article
                $categories_sql = "SELECT categories.name FROM categories
                                   JOIN post_categories ON categories.id = post_categories.category_id
                                   WHERE post_categories.post_id = " . $row["id"];
                $categories_result = $conn->query($categories_sql);

                $tags_sql = "SELECT tags.name FROM tags
                             JOIN post_tags ON tags.id = post_tags.tag_id
                             WHERE post_tags.post_id = " . $row["id"];
                $tags_result = $conn->query($tags_sql);

                // Afficher les catégories
                if ($categories_result->num_rows > 0) {
                    echo "<p><strong>" . $texts['categories'] . ":</strong> ";
                    while($category = $categories_result->fetch_assoc()) {
                        echo $category['name'] . " ";
                    }
                    echo "</p>";
                }

                // Afficher les tags
                if ($tags_result->num_rows > 0) {
                    echo "<p><strong>" . $texts['tags'] . ":</strong> ";
                    while($tag = $tags_result->fetch_assoc()) {
                        echo $tag['name'] . " ";
                    }
                    echo "</p>";
                }

                echo "<small>Posté le " . $row["created_at"]. "</small><hr>";
                echo "</div>";
            }
        } else {
            echo "0 résultats";
        }
        ?>

        <!-- Affichage des liens de pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item<?php if($page <= 1){ echo ' disabled'; } ?>">
                    <a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "?page=" . ($page - 1); } ?>"><?php echo $texts['previous']; ?></a>
                </li>
                <?php for($i = 1; $i <= $total_pages; $i++ ): ?>
                    <li class="page-item<?php if($page == $i){ echo ' active'; } ?>">
                        <a class="page-link" href="index.php?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item<?php if($page >= $total_pages){ echo ' disabled'; } ?>">
                    <a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=" . ($page + 1); } ?>"><?php echo $texts['next']; ?></a>
                </li>
            </ul>
        </nav>

        <a href="create_post.php" class="btn btn-primary"><?php echo $texts['create_new_post']; ?></a>
        <a href="subscribe.php" class="btn btn-secondary mt-3">S'abonner aux notifications</a>
    </div>
</body>
</html>
