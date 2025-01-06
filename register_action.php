<?php
include 'db.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$language = $_POST['language'];

$sql = "INSERT INTO users (username, password, language) VALUES ('$username', '$password', '$language')";

if ($conn->query($sql) === TRUE) {
    echo "New user created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: login.php");
exit();
?>
