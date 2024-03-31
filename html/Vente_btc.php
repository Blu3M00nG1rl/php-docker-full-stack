<?php
include("en_tete_btc.php");

$reponse2 = $bdd->query('SELECT coins_list.id as Coin, coins_list.symbol, coins_list.name, coins_list.Portefeuille, 
								prix_aujourdhui.id, prix_aujourdhui.journee AS JourneeAuj, prix_aujourdhui.prix AS PrixAuj, prix_aujourdhui.market_cap, prix_aujourdhui.volume,
								prix_hier.id, prix_hier.journee AS JourneeHier, prix_hier.prix AS PrixHier, 
								fibonacci.id, fibonacci.Mini, fibonacci.Maxi, fibonacci.FibVente, fibonacci.FibAchat			
						FROM coins_list
						INNER JOIN prix_aujourdhui ON coins_list.id = prix_aujourdhui.id 
						INNER JOIN prix_hier ON coins_list.id = prix_hier.id 
						INNER JOIN fibonacci ON coins_list.id = fibonacci.id
						WHERE Portefeuille >0 ORDER BY coins_list.symbol');
?>
			<table>
				<thead>
					<th>Symbole</th>
					<th>Nom</th>
					<th>Portefeuille</th>					
					<th>Prix Aujourd'hui</th>
					<th>Prix Hier</th>
					<th>Evolution 24 h</th>
					<th>Market Cap</th>
					<th>Volume</th>
				</thead>
<?php
	while ($donnees2 = $reponse2->fetch())
				{
					/*echo "CoinId : " . $donnees['Coin'] .  " Symbole : " . $donnees['symbol'] .  " Nom : " . $donnees['name'] .  " Portefeuille : " . $donnees['Portefeuille'].  " Début : " . $donnees['JourneeDebut'] . "  PrixDébut: " . $donnees['PrixDebut']  .  " Aujourd'hui : " . $donnees['JourneeAuj'] . "  PrixAuj: " . $donnees['PrixAuj']  .  " Journee6m : " . $donnees['JourneeSixM'] . "  Prix6m: " . $donnees['PrixSixM'] . " JourneeUnAn : " . $donnees['JourneeUnAn'] . "  PrixUnAn: " . $donnees['PrixUnAn'] ."<hr/>";*/
					

					$evolution_24_heures = @ (($donnees2['PrixAuj']-$donnees2['PrixHier'])/$donnees2['PrixHier']*100);
					if (isset($evolution_24_heures) AND $evolution_24_heures < 0 AND $donnees2['PrixAuj'] > $donnees2['FibVente'])
					{					
?>
				<tbody>
					<tr>
						<td><?php echo $donnees2['symbol'];?></td>
						<td><?php echo $donnees2['name'];?></td>
						<td><?php echo $donnees2['Portefeuille'];?></td>
						<td><?php echo number_format($donnees2['PrixAuj'], 10 , "," , " ") . " B";?></td>
						<td><?php echo number_format($donnees2['PrixHier'], 10 , "," , " ") . " B";?></td>
						<td><?php echo number_format($evolution_24_heures, 2 , "," , " ") . " %";?></td>
						<td><?php echo number_format($donnees2['market_cap'],0 , "," , " ") . " B";?></td>
						<td><?php echo number_format($donnees2['volume'], 0 , "," , " ") . " B";?></td>
					</tr>
<?php
					}
				}
	$reponse2->closecursor();
?>
			</table>
		</section>
	</body>
</html>