<?php
include 'db.php';
require 'vendor/autoload.php'; // Assurez-vous d'avoir installé PHPMailer via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'];
$token = bin2hex(random_bytes(50));

// Vérifier si l'utilisateur existe
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Insérer le token dans la table password_resets
    $sql = "INSERT INTO password_resets (email, token) VALUES ('$email', '$token')";
    $conn->query($sql);

    // Envoyer l'email de réinitialisation de mot de passe
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Utilisez votre serveur SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com'; // Utilisez votre adresse email
        $mail->Password = 'your-email-password'; // Utilisez votre mot de passe email
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@yourdomain.com', 'Blog');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de mot de passe';
        $mail->Body    = "Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href='http://yourdomain.com/reset_password.php?token=$token'>Réinitialiser le mot de passe</a>";

        $mail->send();
        echo 'Un email de réinitialisation de mot de passe a été envoyé.';
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email: {$mail->ErrorInfo}";
    }
} else {
    echo "Aucun utilisateur trouvé avec cet email.";
}

$conn->close();
?>
