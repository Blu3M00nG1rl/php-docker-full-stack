<?php

include("en_tete_btc.php");

$reponse2 = $bdd->query('SELECT coins_list.id as Coin, coins_list.symbol, coins_list.name, coins_list.Portefeuille,
								prix_aujourdhui.id, prix_aujourdhui.journee AS JourneeAuj, prix_aujourdhui.prix AS PrixAuj, prix_aujourdhui.market_cap, prix_aujourdhui.volume,
								prix_hier.id, prix_hier.journee AS JourneeHier, prix_hier.prix AS PrixHier,
								prix_un_an.id, prix_un_an.journee AS JourneeUnAn, prix_un_an.prix AS PrixUnAn,
								fibonacci.id, fibonacci.Mini, fibonacci.Maxi, fibonacci.FibVente, fibonacci.FibAchat,
								prix_premiere_date.id, prix_premiere_date.journee AS JourneeDebut, prix_premiere_date.prix AS PrixDebut
						FROM coins_list
						INNER JOIN prix_aujourdhui ON coins_list.id = prix_aujourdhui.id
						INNER JOIN prix_hier ON coins_list.id = prix_hier.id
						LEFT JOIN prix_un_an ON coins_list.id = prix_un_an.id
						INNER JOIN fibonacci ON coins_list.id = fibonacci.id
						INNER JOIN prix_premiere_date ON coins_list.id = prix_premiere_date.id
						ORDER BY prix_aujourdhui.market_cap DESC');

//Requête pour déterminer l'évolution du bitcoin
$reponse3 = $bdd->query('SELECT coins_list.id as Coin, coins_list.symbol, coins_list.name, prix_aujourdhui.id, prix_aujourdhui.journee AS JourneeAuj, prix_aujourdhui.prix AS PrixAuj, prix_aujourdhui.market_cap,
								prix_un_an.id, prix_un_an.journee JourneeUnAn, prix_un_an.prix AS PrixUnAn
						FROM coins_list
						INNER JOIN prix_aujourdhui ON coins_list.id = prix_aujourdhui.id
						LEFT JOIN prix_un_an ON coins_list.id = prix_un_an.id
						ORDER BY prix_aujourdhui.market_cap DESC');
$donnees3 = $reponse3->fetch();


?>
			<table>
				<thead>
					<th>Symbole</th>
					<th>Nom</th>
					<th>Evolution 24 h</th>
					<th>Prix Aujourd'hui</th>
					<th>Prix Hier</th>
					<th>Evolution 1 an</th>
					<th>Market Cap</th>
					<th>Volume</th>
				</thead>
<?php
	while ($donnees2 = $reponse2->fetch())
				{
					$evolution_24_heures = @(($donnees2['PrixAuj']-$donnees2['PrixHier'])/$donnees2['PrixHier']*100);
					//echo($donnees2['symbol']. " - ".$evolution_24_heures."<hr/>");
					if ($donnees2['JourneeDebut'] > $donnees2['JourneeUnAn'])
					{
					$donnees2['PrixUnAn'] = $donnees2['PrixDebut'];
					$evolution = @(($donnees2['PrixAuj']-$donnees2['PrixDebut'])/$donnees2['PrixDebut']);
					}
					else
					{
					$evolution = @(($donnees2['PrixAuj']-$donnees2['PrixUnAn'])/$donnees2['PrixUnAn']);
					}
						if
						($evolution_24_heures > 0 AND $donnees2['PrixAuj'] < $donnees2['FibAchat'] AND $evolution*100 >= 1)
						{
?>
						<tbody>
							<tr>
								<td><?php echo $donnees2['symbol'];?></td>
								<td><?php echo $donnees2['name'];?></td>
								<td><?php echo number_format($evolution_24_heures, 2 , "," , " ") . " %";?></td>
								<td><?php echo number_format($donnees2['PrixAuj'], 10 , "," , " ") . " B";?></td>
								<td><?php echo number_format($donnees2['PrixHier'], 10 , "," , " ") . " B";?></td>
								<td><?php echo number_format($evolution * 100, 2, "," , " ") . " %";?></td>
								<td><?php echo number_format($donnees2['market_cap'], 0 , "," , " ") . " B";?></td>
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
