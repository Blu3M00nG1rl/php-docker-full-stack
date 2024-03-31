<?php
set_time_limit(0);
// Heure actuelle
echo "<h2>" . date('h:i:s') . " - Début de l'importation.</h2>";

include("Connexion_ETH.php");

$reponse = $bdd->query('DELETE FROM historique_prix');
$reponse->closeCursor();

$cryptos = array();

//On créé la première requête de sélection pour récupérer les id coins
$result = $bdd->query('SELECT * FROM coins_list ORDER BY no');

	// use $result here as that you PDO::Statement object
    $cryptos = $result->fetchAll(PDO::FETCH_OBJ); 
    $cryptos_count = $result->rowCount();
	$derniere_crypto = $cryptos[$cryptos_count-1]->no;   
    $j=29;
    $i=0;
    $crypt_debut = $cryptos[$i]->no;
    $crypt_fin = $cryptos[$j]->no;   

    // boucle des tranches de coins
	while ($j < $cryptos_count){
		$crypt_debut = $cryptos[$i]->no;
		$ids_debut_fin['debut'][] = ($crypt_debut);
		$crypt_fin = $cryptos[$j]->no;
		$ids_debut_fin['fin'][] = $crypt_fin;
		$i=$i+30;
		$j=$i+30;
	}
		$j = $cryptos_count;
		$crypt_debut = $cryptos[$i]->no;
		$crypt_fin = $derniere_crypto;
		$ids_debut_fin['fin'][] = $crypt_fin;
		$ids_debut_fin['debut'][] = $crypt_debut;


		$ids_Length = count($ids_debut_fin['debut']);
        
        $i = 0;
        $ids_debut = $ids_debut_fin['debut'][0];

        // boucle des données des coins
        while ($i < $ids_Length)
        {
        	$ids_fin = $ids_debut_fin['fin'][$i];
        	
        	//code à mettre ici
            echo "Nos " . $ids_debut ." à " . $ids_fin . "<br />";

            //On créé la première requête de sélection pour récupérer les id coins
			$result2 = $bdd->prepare('SELECT id, no, symbol FROM coins_list WHERE no BETWEEN :debut AND :fin ORDER BY no');
			$result2->bindParam(':debut', $ids_debut);
			$result2->bindParam(':fin', $ids_fin);
			$result2->execute();

				while ($donnees = $result2->fetch())
				{

				$id = $donnees['id'];
				$no = $donnees['no'];
				$symbol = $donnees['symbol'];
				$date_aujourdhui = new DateTime();
				$date_fin =  $date_aujourdhui->format('U');
				$date_aujourdhui_mef = date("Y-m-d", ($date_fin));
				$date_debut = date(strtotime('-1 year'));	
				$json_source=file_get_contents("https://api.coingecko.com/api/v3/coins/".$id."/market_chart/range?vs_currency=eth&from=".$date_debut."&to=".$date_fin);
					$arr = json_decode($json_source, true);

					$json_source2 = file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=".$id."&vs_currencies=eth&include_market_cap=true&include_24hr_vol=true");
					$arr2 = json_decode($json_source2, true);	
					
					for($j=0;$j<count($arr['prices']);$j++)
						{

							$date = $arr['prices'][$j][0];
							$price = $arr['prices'][$j][1];
							$date_mef = date("Y-m-d", substr($date,0,-3));


							$reponse3 = $bdd->prepare('REPLACE INTO historique_prix (id, Jnee, prix) VALUES (:id, :journee, :prix)');
							$reponse3->bindParam(':id', $id);
							$reponse3->bindParam(':journee', $date_mef);
							$reponse3->bindParam(':prix', $price);
							$reponse3->execute();
						}

					$market_cap = (int)$arr2[$id]['eth_market_cap'];
					$vol_24h = (int)$arr2[$id]['eth_24h_vol'];

						$reponse4 = $bdd->prepare('UPDATE historique_prix SET market_cap = :market_cap, total_volume = :vol_24h WHERE Jnee = :date_jour AND id = :id');
						$reponse4->bindParam(':market_cap', $market_cap);
						$reponse4->bindParam(':vol_24h', $vol_24h);
						$reponse4->bindParam(':date_jour', $date_aujourdhui_mef);
						$reponse4->bindParam(':id', $id);
						$reponse4->execute();
		
					$reponse4->closecursor();
					echo $no . " - (" . $symbol . ") - " . $id . " enregistré. <br/>";
					$reponse3->closecursor();
					}



            $ids_debut = $ids_fin+1	;
            $i++;
            echo "<h3>" . date('h:i:s') . "</h3><hr/>";
            sleep(66);
        }
			
	


$result->closecursor();

include("Histo_to_Tbles_MAJ_ETH.php");

echo "<h2>" . date('h:i:s') . " - Fin de l'importation.</h2>";

echo "<a href='index_eth.php'><h2>Retour à l'acceuil</h2></a>";
?>