<?php

$to = "soilihikyllian@gmail.com";
$subject = "Demande reprographique";
$message = "Une nouvelle demande reprographique a été soumise.";

// Obtenir les informations du formulaire
$date = $_POST['date'];
$format = $_POST['format'];
$taille = $_POST['taille'];
$utilisateur = $_POST['utilisateur'];

// Chemin du fichier PDF temporaire
$pdfFilePath = $_FILES['pdf']['tmp_name'];

// Lecture du contenu du fichier PDF
$pdfContent = file_get_contents($pdfFilePath);

// Encodage de la pièce jointe en base64
$pdfEncoded = chunk_split(base64_encode($pdfContent));

// Boundary unique pour séparer les différentes parties de l'e-mail
$boundary = md5(uniqid());

// Headers de l'e-mail
$headers = "From: benclash29@gmail.com\r\n"; // Remplacez par votre adresse e-mail
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

// Corps de l'e-mail
$body = "--$boundary\r\n";
$body .= "Content-Type: text/plain; charset=utf-8\r\n";
$body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$body .= "$message\r\n";
$body .= "Date: $date\r\n";
$body .= "Format: $format\r\n";
$body .= "Taille: $taille\r\n";
$body .= "Utilisateur: $utilisateur\r\n";

// Ajout de la pièce jointe
$body .= "--$boundary\r\n";
$body .= "Content-Type: application/pdf; name=\"fichier.pdf\"\r\n";
$body .= "Content-Disposition: attachment; filename=\"fichier.pdf\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
$body .= "$pdfEncoded\r\n";

$body .= "--$boundary--\r\n";

// Envoi de l'e-mail
if (mail($to, $subject, $body, $headers)) {
    echo "Demande envoyée avec succès.";
} else {
    echo "Erreur d'envoi de la demande.";
}
?>
