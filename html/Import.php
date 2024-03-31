<?php
set_time_limit(0);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include("Connexion_Euros.php");

//On créé la première requête de sélection pour récupérer les id coins
$sql1 = $bdd->query('SELECT id, no, symbol FROM coins_list WHERE no > 0 ORDER BY no');
$coins_list = $sql1->fetchAll();
file_put_contents('coins_nos.json', json_encode($coins_list));
//echo json_encode($coins_list);
//Nombre total de coins
$sql2 = $bdd->query('SELECT COUNT(no) FROM coins_list');
$reponse = $sql2->fetchAll();
$coins_nbre = $reponse[0][0];

?>

<script type="text/javascript" src="coins_nos.json"></script>

<div class="nbre_coins"></div>
<div><h3>Nombre total de coins : <?= $coins_nbre ?></h3></div>
<br>
<div><h4>Procédure de mise à jour</h4></div>
<div>Terminal Cryptocompare : 
<br/>cp /home/blu3m00n/Documents/Informatique/Developpement/Docker/cryptos-docker/html/coins_nos.json /home/blu3m00n/Documents/Informatique/Developpement/NodeJs/cryptocompare
<br/>node prices
<br/>node prices_hier
<br/>node prices_30j
<br/>node prices_1an
<br/>cp -r /home/blu3m00n/Documents/Informatique/Developpement/NodeJs/cryptocompare/prices /home/blu3m00n/Documents/Informatique/Developpement/Docker/cryptos-docker/html
<br/>cp -r /home/blu3m00n/Documents/Informatique/Developpement/NodeJs/cryptocompare/prices_hier /home/blu3m00n/Documents/Informatique/Developpement/Docker/cryptos-docker/html
<br/>cp -r /home/blu3m00n/Documents/Informatique/Developpement/NodeJs/cryptocompare/prices_30j /home/blu3m00n/Documents/Informatique/Developpement/Docker/cryptos-docker/html
<br/>cp -r /home/blu3m00n/Documents/Informatique/Developpement/NodeJs/cryptocompare/prices_1an /home/blu3m00n/Documents/Informatique/Developpement/Docker/cryptos-docker/html
</div>
<div>
<a href="Import_sql.php"><h2>Mettre à jour la base MySQL</h2></a>
</div>

