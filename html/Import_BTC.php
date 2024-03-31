<?php
set_time_limit(0);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include("Connexion_BTC.php");

//Nombre total de coins
$sql2 = $bdd->query('SELECT COUNT(no) FROM coins_list');
$reponse = $sql2->fetchAll();
$coins_nbre = $reponse[0][0];

?>

<div class="nbre_coins"></div>
<div><h3>Nombre total de coins : <?= $coins_nbre ?></h3></div>
<br>
<div><h4>Procédure de mise à jour</h4></div>
<div>Terminal Cryptocompare : 
<br/>node pricesBtc
<br/>node pricesBtc_hier
<br/>node pricesBtc_30j
<br/>node pricesBtc_1an
<br/>cp -r /home/blu3m00n/Documents/Informatique/Developpement/NodeJs/cryptocompare/pricesBtc /home/blu3m00n/Documents/Informatique/Developpement/Docker/cryptos-docker/html
<br/>cp -r /home/blu3m00n/Documents/Informatique/Developpement/NodeJs/cryptocompare/pricesBtc_hier /home/blu3m00n/Documents/Informatique/Developpement/Docker/cryptos-docker/html
<br/>cp -r /home/blu3m00n/Documents/Informatique/Developpement/NodeJs/cryptocompare/pricesBtc_30j /home/blu3m00n/Documents/Informatique/Developpement/Docker/cryptos-docker/html
<br/>cp -r /home/blu3m00n/Documents/Informatique/Developpement/NodeJs/cryptocompare/pricesBtc_1an /home/blu3m00n/Documents/Informatique/Developpement/Docker/cryptos-docker/html
</div>
<div>
<a href="Import_sql_BTC.php"><h2>Mettre à jour la base MySQL</h2></a>
</div>

