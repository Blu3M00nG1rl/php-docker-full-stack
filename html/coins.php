<?php
	include("en_tete.php");
?>		
	<body>	
		<section>
			<table>
				<thead>
					<th>Symbole</th>
					<th>Date Achat</th>
					<th>Montant</th>
					<th>Portefeuille</th>
					<th>Wallet</th>
					<th>Exchange</th>
					<th>Date de vérification</th>
					<th>Observation</th>
				</thead>
<?php
	$total_port = 0;
	$total_montant = 0;
	$total_six_mois = 0;
	$total_un_an = 0;

	$reponse2 = $bdd->query('SELECT symbol, montant, MONTH(date_achat) AS MoisAchat, DAY(date_achat) AS JourAchat, YEAR(date_achat) AS AnneeAchat, portefeuille, wallet, exchange, MONTH(date_verification) AS MoisVerif, DAY(date_verification) AS JourVerif, YEAR(date_verification) AS AnneeVerif, observation FROM coins_list WHERE portefeuille > 0 ORDER BY date_verification');

	while ($donnees2 = $reponse2->fetch()) 
	{							

?>
				<tbody>
					<tr>
						<td><?php echo $donnees2['symbol'];?></td>
						<td><?php echo $donnees2['JourAchat'] . "/" . $donnees2['MoisAchat'] . "/" . $donnees2['AnneeAchat'];?></td>
						<td><?php echo number_format($donnees2['montant'], 6 , "," , ".") . " €";?></td>
						<td><?php echo number_format($donnees2['portefeuille'], 3 , "," , ".");?></td>
						<td><?php echo $donnees2['wallet'];?></td>
						<td><?php echo $donnees2['exchange'];?></td>
						<td><?php echo $donnees2['JourVerif'] . "/" . $donnees2['MoisVerif'] . "/" . $donnees2['AnneeVerif'];?></td>
						<td><?php echo $donnees2['observation'];?></td>
					</tr>
<?php
	}
	$reponse2->closecursor();
?>
				</tbody>
			</table>
		</section>
	</body>
</html>