
<!-- Contenu de index.php -->
<h1>Je teste mon site !</h1>
<h4>Tentative de connexion au serveur MySQL depuis PHP...</h4>
<?php
$db_host = 'mysql';
$db_user = $_ENV["DB_USERNAME"];
$db_pass = $_ENV["DB_PASSWORD"];
$conn = new mysqli($db_host, $db_user, $db_pass);

if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
echo  "Connexion réussie à MySQL !";
?>
