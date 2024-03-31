<?php
header('Content-type: text/csv;');
header("Content-disposition: attachment; filename=vue_densemble_export.csv");

include("Connexion_Euros.php");

$reponse2 = $bdd->prepare('
SELECT coins_list.id as Coin, coins_list.symbol, coins_list.name, coins_list.Portefeuille,
								prix_aujourdhui.id, DAY(prix_aujourdhui.journee) AS JourAuj, MONTH(prix_aujourdhui.journee) AS MoisAuj, YEAR(prix_aujourdhui.journee) AS AnAuj, prix_aujourdhui.prix AS PrixAuj, prix_aujourdhui.market_cap AS MarkAuj, prix_aujourdhui.volume AS VolAuj,
								prix_hier.id, DAY(prix_hier.journee) AS JourHier, MONTH(prix_hier.journee) AS MoisHier, YEAR(prix_hier.journee) AS AnHier, prix_hier.prix AS PrixHier,
								prix_un_an.id, DAY(prix_un_an.journee) AS JourUnAn, MONTH(prix_un_an.journee) AS MoisUnAn, YEAR(prix_un_an.journee) AS AnUnAn, prix_un_an.journee AS JourneeUnAn, prix_un_an.prix AS PrixUnAn,
								prix_premiere_date.id, prix_premiere_date.journee JourneeDebut, prix_premiere_date.prix AS PrixDebut,
								fibonacci.id, fibonacci.Mini, fibonacci.Maxi, fibonacci.FibVente, fibonacci.FibAchat
						FROM coins_list
						INNER JOIN prix_aujourdhui ON coins_list.id = prix_aujourdhui.id
						INNER JOIN prix_hier ON coins_list.id = prix_hier.id
						LEFT JOIN prix_un_an ON coins_list.id = prix_un_an.id
						INNER JOIN prix_premiere_date ON coins_list.id = prix_premiere_date.id
						INNER JOIN fibonacci ON coins_list.id = fibonacci.id
						ORDER BY prix_aujourdhui.market_cap DESC, prix_aujourdhui.volume DESC, prix_aujourdhui.prix DESC
');

$reponse2->execute();

$donnees2 = $reponse2->fetchAll();
//var_dump($donnees2);


?>

"Crypto";"ID";"Port";"PrixduJour";"Capitalisation";"Volume";"Prix_Jm365";"N°";

<?php
	
	$i = 0;

	foreach($donnees2 as $d) {
		$i++;

		// Vérifie si les valeurs sont nulles avant d'appeler number_format()
		$portefeuille = ($d['Portefeuille'] !== null) ? number_format($d['Portefeuille'], 3, ",", " ") : '';
		$prixAuj = ($d['PrixAuj'] !== null) ? number_format($d['PrixAuj'], 12 , "," , " ") : '';
		$markAuj = ($d['MarkAuj'] !== null) ? number_format($d['MarkAuj'], 0 , "," , " ") : '';
		$volAuj = ($d['VolAuj'] !== null) ? number_format($d['VolAuj'], 0 , "," , " ") : '';
		$prixDebut = ($d['PrixDebut'] !== null) ? number_format($d['PrixDebut'], 12 , "," , " ") : '';
	 
		echo "\n".'"'.$d['name'].'";"'.$d['symbol'].'";"'.$portefeuille .'";"'.$prixAuj .'";"'.$markAuj  . '";"'.$volAuj  . '";"'.$prixDebut . '";"'. $i. '"';
	}
?>


