<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="style.css?<?=strtotime("now")?>"/> 
		<title>CryptoBaseEUR</title>
	</head>
	<body>
<?php
set_time_limit(0);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try
{
	$bdd = new PDO('mysql:host=db;dbname=cryptos_eur;charset=utf8','sophie','pass', array(PDO::ATTR_TIMEOUT => 5400, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}
//On crée une nouvelle date à partir de la date du jour
$calcul_aujourdhui = new datetime(date("Y-m-j"));
//echo($calcul_aujourdhui->format('Y-m-d'));

$reponse = $bdd->query('SELECT * FROM coins_list');
$donnees = $reponse->fetch();
$nbreId = $reponse->rowCount();
echo($nbreId);
?>

<body>
</html>
