<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="style.css" />
		<title>CryptoBaseETH</title>
	</head>
	<body>

<?php
include("Connexion_ETH.php");

//On crée une nouvelle date à partir de la date du jour
$calcul_aujourdhui = new datetime(date("Y-m-j"));
$calcul_hier = new datetime(date("Y-m-j"));
$calcul_avant_hier = new datetime(date('Y-m-j'));
$calcul_trente_jours = new datetime(date('Y-m-j'));
$calcul_six_mois = new datetime(date('Y-m-j'));
$calcul_un_an = new datetime(date('Y-m-j'));


// On soustrait les jours à la date

$intervalHier = new dateInterval('P1D');
$intervalAvantHier = new dateInterval('P2D');
$intervalTrenteJours = new dateInterval('P1M');
$intervalSixMois = new dateInterval('P180D');
$intervalUnAn = new dateInterval('P365D');



$calcul_hier->sub($intervalHier);
$calcul_avant_hier->sub($intervalAvantHier);
$calcul_trente_jours->sub($intervalTrenteJours);
$calcul_six_mois->sub($intervalSixMois);
$calcul_un_an->sub($intervalUnAn);

// On recrée une variable en string
$date_auj = $calcul_aujourdhui->format('Y-m-d');
$date_hier = $calcul_hier->format('Y-m-d');
$date_avant_hier = $calcul_avant_hier->format('Y-m-d');
$date_trente_jours = $calcul_trente_jours->format('Y-m-d');
$date_six_mois = $calcul_six_mois->format('Y-m-d');
$date_un_an = $calcul_un_an->format('Y-m-d');

/*echo $date_auj . "<br />";
echo $date_hier . "<br />";
echo $date_avant_hier . "<br />";
echo $date_trente_jours . "<br />";
echo $date_six_mois . "<br />";
echo $date_un_an . "<br />";*/


$reponse = $bdd->query('SELECT DAY(journee) AS JourAuj, MONTH(journee) AS MoisAuj, YEAR(journee) AS AnAuj
						FROM prix_aujourdhui');
$donnees = $reponse->fetch();
$nbreId = $reponse->rowCount();
//$DateAuj = DateTime::createFromFormat('Y-m-d', $donnees['JourneeAuj']);
//$FormattedDateAuj = $DateAuj->format('d-m-Y');
?>

		<header>
			<div id="bloc1">
				<div id="MAJ">
					<form action="Import_ETH.php" method="POST">
					<input type="submit" name="Chargement" value="Chargement"/>
					</form>
				</div>

				<nav id="nav_coins">
					<ul>
						<li><a href="index.php"><img src=Images\Euro.png alt="euro" title="euro"/></a></li>
						<li><a href="index_btc.php"><img src=Images\Bitcoin.png alt="bitcoin" title="bitcoin"/></a></li>
						<li><a href="index_eth.php"><img src=Images\Ethereum.png alt="ethereum" title="ethereum"/></a></li>
					</ul>
				</nav>

				<p id="DerMaJ"><?php echo "Dernière mise à jour : <br />" . $donnees['JourAuj'] . "/" . $donnees['MoisAuj'] . "/" . $donnees['AnAuj'] . "<br /> (" . $nbreId . " Coins)";?>
				</p>
			</div>

			<nav id="nav_principale">
          			<a href="index_eth.php">Portefeuille</a>
          			<a href="Export_eth.php">Export</a>
         			<a href="Vente_eth.php">Vente</a>
        			<a href="Achat_eth.php">Achat</a>
        			<a href="vue_densemble_eth.php">Synthèse</a>
			</nav>
		</header>
<?php
$reponse->closecursor();
?>
