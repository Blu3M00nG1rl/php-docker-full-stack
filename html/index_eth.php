<?php
	include("en_tete_eth.php");
?>

			<table>
				<thead>
					<th>Symbole</th>
					<th>Nom</th>
					<th>Portefeuille</th>
					<th>Montant</th>
					<th>Projection 6 mois</th>
					<th>Projection 1 an</th>
				</thead>


<?php
	$total_port = 0;
	$total_montant = 0;
	$total_six_mois = 0;
	$total_un_an = 0;

	$reponse2 = $bdd->query('SELECT coins_list.id as Coin, coins_list.symbol, coins_list.name, coins_list.Portefeuille,
								prix_aujourdhui.id, prix_aujourdhui.journee AS JourneeAuj, prix_aujourdhui.prix AS PrixAuj,
								prix_six_mois.id, prix_six_mois.journee AS JourneeSixM, prix_six_mois.prix AS PrixSixM,
								prix_un_an.id, prix_un_an.journee AS JourneeUnAn, prix_un_an.prix AS PrixUnAn,
								prix_premiere_date.id, prix_premiere_date.journee JourneeDebut, prix_premiere_date.prix AS PrixDebut,
								coins_list.Portefeuille * prix_aujourdhui.prix AS Montant
						FROM coins_list
						INNER JOIN prix_aujourdhui ON coins_list.id = prix_aujourdhui.id
						LEFT JOIN prix_six_mois ON coins_list.id = prix_six_mois.id
						LEFT JOIN prix_un_an ON coins_list.id = prix_un_an.id
						INNER JOIN prix_premiere_date ON coins_list.id = prix_premiere_date.id
						WHERE coins_list.Portefeuille > 0
						ORDER BY Montant DESC');

	while ($donnees2 = $reponse2->fetch())
				{
					/*echo "CoinId : " . $donnees2['Coin'] .  " Symbole : " . $donnees2['symbol'] .  " Nom : " . $donnees2['name'] .  " Portefeuille : " . $donnees2['Portefeuille'].  " Début : " . $donnees2['JourneeDebut'] . "  PrixDébut: " . $donnees2['PrixDebut']  .  " Aujourd'hui : " . $donnees2['JourneeAuj'] . "  PrixAuj: " . $donnees2['PrixAuj']  .  " Journee6m : " . $donnees2['JourneeSixM'] . "  Prix6m: " . $donnees2['PrixSixM'] . " JourneeUnAn : " . $donnees2['JourneeUnAn'] . "  PrixUnAn: " . $donnees2['PrixUnAn'] ."<hr/>";*/

					if ($donnees2['PrixDebut'] > $donnees2['PrixSixM'])
					{
					@$projection_six_mois = ($donnees2['Portefeuille']*$donnees2['PrixAuj'])+(($donnees2['Portefeuille']*$donnees2['PrixAuj'])*($donnees2['PrixAuj']-$donnees2['PrixDebut'])/$donnees2['PrixDebut']);
					}
					else
					{
					@$projection_six_mois = ($donnees2['Portefeuille']*$donnees2['PrixAuj'])+(($donnees2['Portefeuille']*$donnees2['PrixAuj'])*($donnees2['PrixAuj']-$donnees2['PrixSixM'])/$donnees2['PrixSixM']);
					}

					if ($donnees2['PrixDebut'] > $donnees2['PrixUnAn'])
					{
					@$projection_un_an = ($donnees2['Portefeuille']*$donnees2['PrixAuj'])+(($donnees2['Portefeuille']*$donnees2['PrixAuj'])*($donnees2['PrixAuj']-$donnees2['PrixDebut'])/$donnees2['PrixDebut']);
					}
					else
					{
					@$projection_un_an = ($donnees2['Portefeuille']*$donnees2['PrixAuj'])+(($donnees2['Portefeuille']*$donnees2['PrixAuj'])*($donnees2['PrixAuj']-$donnees2['PrixUnAn'])/$donnees2['PrixUnAn']);
?>

				<tbody>
<?php
					}
?>
					<tr>
						<td><?php echo $donnees2['symbol'];?></td>
						<td><?php echo $donnees2['name'];?></td>
						<td><?php echo $donnees2['Portefeuille'];?></td>
						<td><?php echo number_format($donnees2['PrixAuj']*$donnees2['Portefeuille'], 2 , "," , ".") . " E";?></td>
						<td><?php echo number_format($projection_six_mois, 2 , "," , " ") . " E";?></td>
						<td><?php echo number_format($projection_un_an, 2 , "," , " ") . " E";?></td>
							<?php $total_port = $total_port + $donnees2['Portefeuille'];
							 $total_montant = $total_montant + $donnees2['PrixAuj']*$donnees2['Portefeuille'];
							 $total_six_mois = $total_six_mois + $projection_six_mois;
							 $total_un_an = $total_un_an + $projection_un_an;
							?>
					</tr>
<?php
				}
	$reponse2->closecursor();
?>
				</tbody>
					<tfoot>
					<td colspan=2>TOTAL</td>
					<td><?php echo number_format($total_port, 2 , "," , " ");?></td>
					<td><?php echo number_format($total_montant, 2 , "," , " ") . " E";?></td>
					<td><?php echo number_format($total_six_mois, 2 , "," , " ") . " E";?></td>
					<td><?php echo number_format($total_un_an, 2 , "," , " ") . " E";?></td>
				</tfoot>
			</table>

	</body>
</html>
