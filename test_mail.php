<?php

$to = "benamiraboudoua@gmail.com";
$subject = "smtp wesh";
$message = "bakaaaaaaaaaa";

$headers = "Content-Type: text/plain; charset=utf-8\r\n";
$headers = "from: benclash29@gmail.com\r\n";

if(mail($to, $subject, $message, $headers))
    echo 'Envoie a ';
    else 
       echo 'Erreur envoi';