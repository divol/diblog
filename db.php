<?php
$servername = "localhost";
$username = "divol";
$password = "divol";
$dbname = "blog_db";

// Include Parsedown
include 'libs/Parsedown.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fonction pour vérifier le rôle de l'utilisateur
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
?>
