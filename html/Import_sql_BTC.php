<?php
set_time_limit(0);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Début de l'importation
echo "<h2>" . date('H:i:s') . " - Début de la mise à jour.</h2>";

include("Connexion_BTC.php");

$today = new DateTime();
$oneYearAgo = clone $today;
$oneYearAgo->modify('-1 year');
$jnee1an = $oneYearAgo->format('Y-m-d');
$thirtyDaysAgo = clone $today;
$thirtyDaysAgo->modify('-30 days');
$jnee30j = $thirtyDaysAgo->format('Y-m-d');      
$oneDayAgo = clone $today;
$oneDayAgo->modify('-1 days');
$jneeHier = $oneDayAgo->format('Y-m-d');

//--VIDAGE Tables
$sql1 = $bdd->query('DELETE FROM prix_aujourdhui');
$sql1->closeCursor();
$sql3 = $bdd->query('DELETE FROM prix_hier');
$sql3->closeCursor();
$sql4 = $bdd->query('DELETE FROM prix_trente_jours');
$sql4->closeCursor();
$sql5 = $bdd->query('DELETE FROM prix_un_an');
$sql5->closeCursor();
$sql6 = $bdd->prepare('DELETE FROM historique_prix WHERE Jnee < :jnee1an');
$sql6->bindParam(':jnee1an', $jnee1an);
$sql6->execute();
$sql6->closeCursor();
//--------------------------------------------------------*/

if ($handle = opendir('./pricesBtc')) {

    while (false !== ($filename = readdir($handle))) {

        if ($filename != "." && $filename != "..") {
            //echo($filename);
            // Trouver la position du premier "_" dans le nom de fichier
            $pos_first_underscore = strpos($filename, "_");
            // Trouver la position du deuxième "_" à partir de la position suivant le premier "_"
            $pos_second_underscore = strpos($filename, "_", $pos_first_underscore + 1);
            // Trouver la position du premier "." à partir de la position du deuxième "_"
            $pos_first_dot = strpos($filename, ".", $pos_second_underscore);
            // Extraire le texte entre le deuxième "_" et le premier "."
            $symbol = substr($filename, $pos_second_underscore + 1, $pos_first_dot - $pos_second_underscore - 1);
            //echo $symbol; // Cela affichera "xvg"
            $sql2 = $bdd->prepare('SELECT id FROM coins_list WHERE symbol = :symbol');
            $sql2->bindParam(':symbol', $symbol);
            $sql2->execute(); 
            while ($data = $sql2->fetch())
            {
                $id = $data['id'];
                $today = new DateTime();
                $jnee = $today->format('Y-m-d');
                // Charger le fichier JSON
                $json_data = file_get_contents('pricesBtc/cc_price_'.$symbol.'.json');
                // Convertir le JSON en tableau associatif
                $data = json_decode($json_data, true);
                // Parcourir les données et les insérer dans la base de données
                foreach ($data as $coin => $values) {
                    $price = $values['BTC']['PRICE'];
                    $volume = $values['BTC']['TOTALVOLUME24HTO'];
                    $market_cap = $values['BTC']['MKTCAP'];
                    if ($market_cap == 0) {
                        $market_cap = $values['BTC']['CIRCULATINGSUPPLYMKTCAP'];
                    } 
                    $result = $bdd->prepare('INSERT INTO prix_aujourdhui (id, journee, prix, market_cap, volume) VALUES (:id, :journee, :prix, :market_cap, :volume)');
                    $result->bindParam(':id', $id);
                    $result->bindParam(':journee', $jnee);
                    $result->bindParam(':prix', $price);
                    $result->bindParam(':market_cap', $market_cap);
                    $result->bindParam(':volume', $volume);
                    $result->execute();
                    $result2 = $bdd->prepare('REPLACE INTO historique_prix (id, Jnee, prix, market_cap, total_volume) VALUES (:id, :journee, :prix, :market_cap, :total_volume)');
                    $result2->bindParam(':id', $id);
                    $result2->bindParam(':journee', $jnee);
                    $result2->bindParam(':prix', $price);
                    $result2->bindParam(':market_cap', $market_cap);
                    $result2->bindParam(':total_volume', $volume);
                    $result2->execute();
                }
            }
        }
    }
}
closedir($handle);
$sql2->closecursor();

if ($handle = opendir('./pricesBtc_1an')) {

    while (false !== ($filename = readdir($handle))) {

        if ($filename != "." && $filename != "..") {
            //echo($filename);
            // Trouver la position du premier "_" dans le nom de fichier
            $pos_first_underscore = strpos($filename, "_");
            // Trouver la position du deuxième "_" à partir de la position suivant le premier "_"
            $pos_second_underscore = strpos($filename, "_", $pos_first_underscore + 1);
            // Trouver la position du premier "." à partir de la position du deuxième "_"
            $pos_first_dot = strpos($filename, ".", $pos_second_underscore);
            // Extraire le texte entre le deuxième "_" et le premier "."
            $symbol = substr($filename, $pos_second_underscore + 1, $pos_first_dot - $pos_second_underscore - 1);
            //echo $symbol; // Cela affichera "xvg"
            $sql2 = $bdd->prepare('SELECT id FROM coins_list WHERE symbol = :symbol');
            $sql2->bindParam(':symbol', $symbol);
            $sql2->execute(); 
            while ($data = $sql2->fetch())
            {
                $id = $data['id'];
                // Charger le fichier JSON
                $json_data = file_get_contents('pricesBtc_1an/cc_price_'.$symbol.'.json');
                // Convertir le JSON en tableau associatif
                $data = json_decode($json_data, true);
                // Parcourir les données et les insérer dans la base de données
                $price = $data['BTC'];
                $volume = null;
                $market_cap = null;
                if($price !== 0) {
                    $result = $bdd->prepare('INSERT INTO prix_un_an (id, journee, prix, market_cap, volume) VALUES (:id, :journee, :prix, :market_cap, :volume)');
                    $result->bindParam(':id', $id);
                    $result->bindParam(':journee', $jnee1an);
                    $result->bindParam(':prix', $price);
                    $result->bindParam(':market_cap', $market_cap);
                    $result->bindParam(':volume', $volume);
                    $result->execute();
                    $result2 = $bdd->prepare('REPLACE INTO historique_prix (id, Jnee, prix, market_cap, total_volume) VALUES (:id, :journee, :prix, :market_cap, :total_volume)');
                    $result2->bindParam(':id', $id);
                    $result2->bindParam(':journee', $jnee1an);
                    $result2->bindParam(':prix', $price);
                    $result2->bindParam(':market_cap', $market_cap);
                    $result2->bindParam(':total_volume', $volume);
                    $result2->execute();
                }
            }
        }
    }
}
closedir($handle);
$sql2->closecursor();
// Ajout des coins manquants à partir de la table historique
$data1 = $bdd->prepare('SELECT coins_list.id as Coin, prix_un_an.id, Jnee, historique_prix.prix, historique_prix.market_cap, historique_prix.total_volume  
FROM historique_prix 
INNER JOIN coins_list ON historique_prix.id = coins_list.id
LEFT JOIN prix_un_an ON historique_prix.id = prix_un_an.id 
WHERE historique_prix.Jnee = :journee AND prix_un_an.id IS NULL');
$data1->bindParam(':journee', $jnee1an);
$data1->execute();
while ($data2 = $data1->fetch())
				{
					
					$data3 = $bdd->prepare('INSERT INTO prix_un_an (id, journee, prix, market_cap, volume) VALUES (:coin, :journee, :prix, :market_cap, :volume)');
					$data3->bindParam(':coin', $data2['Coin']);
					$data3->bindParam(':journee', $data2['Jnee']);
					$data3->bindParam(':prix', $data2['prix']);
					$data3->bindParam(':market_cap', $data2['market_cap']);
					$data3->bindParam(':volume', $data2['total_volume']);
					$data3->execute();

				}	
$data1->closeCursor();
$data3->closeCursor();

if ($handle = opendir('./pricesBtc_30j')) {

    while (false !== ($filename = readdir($handle))) {

        if ($filename != "." && $filename != "..") {
            //echo($filename);
            // Trouver la position du premier "_" dans le nom de fichier
            $pos_first_underscore = strpos($filename, "_");
            // Trouver la position du deuxième "_" à partir de la position suivant le premier "_"
            $pos_second_underscore = strpos($filename, "_", $pos_first_underscore + 1);
            // Trouver la position du premier "." à partir de la position du deuxième "_"
            $pos_first_dot = strpos($filename, ".", $pos_second_underscore);
            // Extraire le texte entre le deuxième "_" et le premier "."
            $symbol = substr($filename, $pos_second_underscore + 1, $pos_first_dot - $pos_second_underscore - 1);
            //echo $symbol; // Cela affichera "xvg"
            $sql2 = $bdd->prepare('SELECT id FROM coins_list WHERE symbol = :symbol');
            $sql2->bindParam(':symbol', $symbol);
            $sql2->execute(); 
            while ($data = $sql2->fetch())
            {
                $id = $data['id'];
                // Charger le fichier JSON
                $json_data = file_get_contents('pricesBtc_30j/cc_price_'.$symbol.'.json');
                // Convertir le JSON en tableau associatif
                $data = json_decode($json_data, true);
                // Parcourir les données et les insérer dans la base de données
                $price = $data['BTC'];
                $volume = null;
                $market_cap = null;
                if($price !== 0) {
                    $result = $bdd->prepare('INSERT INTO prix_trente_jours (id, journee, prix, market_cap, volume) VALUES (:id, :journee, :prix, :market_cap, :volume)');
                    $result->bindParam(':id', $id);
                    $result->bindParam(':journee', $jnee30j);
                    $result->bindParam(':prix', $price);
                    $result->bindParam(':market_cap', $market_cap);
                    $result->bindParam(':volume', $volume);
                    $result->execute();
                    $result2 = $bdd->prepare('REPLACE INTO historique_prix (id, Jnee, prix, market_cap, total_volume) VALUES (:id, :journee, :prix, :market_cap, :total_volume)');
                    $result2->bindParam(':id', $id);
                    $result2->bindParam(':journee', $jnee30j);
                    $result2->bindParam(':prix', $price);
                    $result2->bindParam(':market_cap', $market_cap);
                    $result2->bindParam(':total_volume', $volume);
                    $result2->execute();
                }
            }
        }
    }
}
closedir($handle);
$sql2->closecursor();
// Ajout des coins manquants à partir de la table historique
$data1 = $bdd->prepare('SELECT coins_list.id as Coin, prix_trente_jours.id, Jnee, historique_prix.prix, historique_prix.market_cap, historique_prix.total_volume  
FROM historique_prix 
INNER JOIN coins_list 
ON historique_prix.id = coins_list.id
LEFT JOIN prix_trente_jours ON historique_prix.id = prix_trente_jours.id 
WHERE historique_prix.Jnee = :journee AND prix_trente_jours.id IS NULL');
$data1->bindParam(':journee', $jnee30j);
$data1->execute();
while ($data2 = $data1->fetch())
				{
					
					$data3 = $bdd->prepare('INSERT INTO prix_trente_jours (id, journee, prix, market_cap, volume) VALUES (:coin, :journee, :prix, :market_cap, :volume)');
					$data3->bindParam(':coin', $data2['Coin']);
					$data3->bindParam(':journee', $data2['Jnee']);
					$data3->bindParam(':prix', $data2['prix']);
					$data3->bindParam(':market_cap', $data2['market_cap']);
					$data3->bindParam(':volume', $data2['total_volume']);
					$data3->execute();

				}	
$data1->closeCursor();
$data3->closeCursor();

if ($handle = opendir('./pricesBtc_hier')) {

    while (false !== ($filename = readdir($handle))) {

        if ($filename != "." && $filename != "..") {
            //echo($filename);
            // Trouver la position du premier "_" dans le nom de fichier
            $pos_first_underscore = strpos($filename, "_");
            // Trouver la position du deuxième "_" à partir de la position suivant le premier "_"
            $pos_second_underscore = strpos($filename, "_", $pos_first_underscore + 1);
            // Trouver la position du premier "." à partir de la position du deuxième "_"
            $pos_first_dot = strpos($filename, ".", $pos_second_underscore);
            // Extraire le texte entre le deuxième "_" et le premier "."
            $symbol = substr($filename, $pos_second_underscore + 1, $pos_first_dot - $pos_second_underscore - 1);
            //echo $symbol; // Cela affichera "xvg"
            $sql2 = $bdd->prepare('SELECT id FROM coins_list WHERE symbol = :symbol');
            $sql2->bindParam(':symbol', $symbol);
            $sql2->execute(); 
            while ($data = $sql2->fetch())
            {
                $id = $data['id'];
                // Charger le fichier JSON
                $json_data = file_get_contents('pricesBtc_hier/cc_price_'.$symbol.'.json');
                // Convertir le JSON en tableau associatif
                $data = json_decode($json_data, true);
                // Parcourir les données et les insérer dans la base de données
                $price = $data['BTC'];
                $volume = null;
                $market_cap = null;
                if($price !== 0) {
                    $result = $bdd->prepare('INSERT INTO prix_hier (id, journee, prix, market_cap, volume) VALUES (:id, :journee, :prix, :market_cap, :volume)');
                    $result->bindParam(':id', $id);
                    $result->bindParam(':journee', $jneeHier);
                    $result->bindParam(':prix', $price);
                    $result->bindParam(':market_cap', $market_cap);
                    $result->bindParam(':volume', $volume);
                    $result->execute();
                    $result2 = $bdd->prepare('REPLACE INTO historique_prix (id, Jnee, prix, market_cap, total_volume) VALUES (:id, :journee, :prix, :market_cap, :total_volume)');
                    $result2->bindParam(':id', $id);
                    $result2->bindParam(':journee', $jneeHier);
                    $result2->bindParam(':prix', $price);
                    $result2->bindParam(':market_cap', $market_cap);
                    $result2->bindParam(':total_volume', $volume);
                    $result2->execute();
                }
            }
        }
    }
}
closedir($handle);
$sql2->closecursor();
// Ajout des coins manquants à partir de la table historique
$data1 = $bdd->prepare('SELECT coins_list.id as Coin, prix_hier.id, Jnee, historique_prix.prix, historique_prix.market_cap, historique_prix.total_volume  
FROM historique_prix 
INNER JOIN coins_list 
ON historique_prix.id = coins_list.id
LEFT JOIN prix_hier ON historique_prix.id = prix_hier.id 
WHERE historique_prix.Jnee = :journee AND prix_hier.id IS NULL');
$data1->bindParam(':journee', $jneeHier);
$data1->execute();
while ($data2 = $data1->fetch())
				{
					
					$data3 = $bdd->prepare('INSERT INTO prix_hier (id, journee, prix, market_cap, volume) VALUES (:coin, :journee, :prix, :market_cap, :volume)');
					$data3->bindParam(':coin', $data2['Coin']);
					$data3->bindParam(':journee', $data2['Jnee']);
					$data3->bindParam(':prix', $data2['prix']);
					$data3->bindParam(':market_cap', $data2['market_cap']);
					$data3->bindParam(':volume', $data2['total_volume']);
					$data3->execute();

				}	
$data1->closeCursor();
$data3->closeCursor();

//Mise à jour des prix première journée
$reponse2 = $bdd->query('DELETE FROM prix_premiere_date');
$reponse2->closeCursor();

$reponse3 = $bdd->query('SELECT id, Min(Jnee) AS Jee FROM historique_prix GROUP BY id');

    while ($donnees2 = $reponse3->fetch())
            {
                $id = $donnees2['id'];
                $je = $donnees2['Jee'];

                $reponse4 = $bdd->prepare('SELECT id, Jnee, Prix, market_cap, total_volume FROM historique_prix WHERE Jnee = ? AND id = ?');
                $reponse4->execute(array($je, $id));

                while ($donnees3 = $reponse4->fetch())
                {
                    $reponse5 = $bdd->prepare('INSERT INTO prix_premiere_date (id, journee, prix) VALUES (:coin, :journee, :prix)');
                    $reponse5->bindParam(':coin', $donnees3['id']);
                    $reponse5->bindParam(':journee', $donnees3['Jnee']);
                    $reponse5->bindParam(':prix', $donnees3['Prix']);
                    $reponse5->execute();	
                }			
            }
$reponse5->closeCursor();	

//Mise à jour Fibonnaci	
$reponse2 = $bdd->query('DELETE FROM fibonacci');
$reponse2->closeCursor();

$reponse3 = $bdd->prepare('SELECT coins_list.id as Coin, Min(Prix) AS Mini, Max(Prix) AS Maxi FROM historique_prix INNER JOIN coins_list ON historique_prix.id = coins_list.id WHERE historique_prix.Jnee > :journee GROUP BY Coin');
$reponse3->bindParam(':journee', $jnee30j);
$reponse3->execute();

while ($donnees2 = $reponse3->fetch())
    {
        if(empty($donnees2['Mini'])){
            $donnees2['Mini']=0;
        }
        if(empty($donnees2['Maxi'])){
            $donnees2['Maxi']=0;
        }
        $Fib_Vente = (($donnees2['Maxi']-$donnees2['Mini'])*0.618) + $donnees2['Mini'];
        $Fib_Achat = (($donnees2['Maxi']-$donnees2['Mini'])*0.382)+$donnees2['Mini'];
        //echo $donnees2['Coin'] . " - " .$donnees2['Mini'] . " - " . $donnees2['Maxi'] . " - " . $Fib_Vente . " - " . $Fib_Achat . "<br />";
        $reponse4 = $bdd->prepare('INSERT INTO fibonacci (id, Mini, Maxi, FibVente, FibAchat) VALUES (:coin, :mini, :maxi, :fibvente, :fibachat)');
        $reponse4->bindParam(':coin', $donnees2['Coin']);
        $reponse4->bindParam(':mini', $donnees2['Mini']);
        $reponse4->bindParam(':maxi', $donnees2['Maxi']);
        $reponse4->bindParam(':fibvente', $Fib_Vente);
        $reponse4->bindParam(':fibachat', $Fib_Achat);
        $reponse4->execute();
    }
                
$reponse4->closeCursor();
$reponse3->closeCursor();

echo "<h2>" . date('H:i:s') . " - Fin de l'importation.</h2>";
echo "<a href='index_btc.php'><h2>Retour à l'acceuil</h2></a>";

?>