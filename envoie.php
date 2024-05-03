<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Récupération des données du formulaire
$date = $_POST['date'];
$format = $_POST['format'];
$taille = $_POST['taille'];
$utilisateur = $_POST['utilisateur'];
$message = $_POST['message'];

// Chemin du fichier PDF temporaire
$pdfFilePath = $_FILES['pdf']['tmp_name'];
$jpgFilePath = $_FILES['jpg']['tmp_name'];
// Lecture du contenu du fichier PDF
$pdfContent = file_get_contents($pdfFilePath);

// Encodage de la pièce jointe en base64
$pdfEncoded = chunk_split(base64_encode($pdfContent));

// Boundary unique pour séparer les différentes parties de l'e-mail
$boundary = md5(uniqid());

// Headers de l'e-mail
$headers = "From: benclash29@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

// Initialisation de la variable $body
$body = '';

// Construction du corps de l'e-mail
$body .= "--$boundary\r\n";
$body .= "Content-Type: text/plain; charset=utf-8\r\n";
$body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$body .= "$message\r\n";
$body .= "Date: $date\r\n";
$body .= "Format: $format\r\n";
$body .= "Taille: $taille\r\n";
$body .= "Utilisateur: $utilisateur\r\n";

// Ajout de la pièce jointe PDF
$body .= "--$boundary\r\n";
$body .= "Content-Type: application/pdf; name=\"document.pdf\"\r\n";
$body .= "Content-Disposition: attachment; filename=\"document.pdf\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
$body .= "$pdfEncoded\r\n";

// Ajout de la pièce jointe JPG si nécessaire
if ($jpgFilePath) {
    $jpgContent = file_get_contents($jpgFilePath);
    $jpgEncoded = chunk_split(base64_encode($jpgContent));
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: image/jpeg; name=\"image.jpg\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"image.jpg\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= "$jpgEncoded\r\n";
}

$body .= "--$boundary--\r\n";

$to = $_POST['utilisateur'];
$subject = "Demande reprographique";

// Envoi de l'e-mail
if (mail($to, $subject, $body, $headers)) {
    // L'e-mail a été envoyé avec succès

    // Insertion des données dans la base de données
    $sql = "INSERT INTO emails (date, format, taille, utilisateur, message) 
            VALUES ('$date', '$format', '$taille', '$utilisateur', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Les données ont été ajoutées avec succès à la base de données

        // Redirection vers la page de l'historique
        header("Location: historique.php");
        exit();
    } else {
        echo "Erreur lors de l'insertion des données dans la base de données.";
    }
} else {
    echo "Erreur d'envoi de la demande.";
}

// Fermeture de la connexion à la base de données
$conn->close();
?>
