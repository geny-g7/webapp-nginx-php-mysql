
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

date_default_timezone_set('America/New_York');
echo  "Connexion réussie à MySQL ! Date : " . date('Y-M-d H:i:s');
?>
