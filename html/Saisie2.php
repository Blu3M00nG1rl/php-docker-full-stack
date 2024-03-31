<?php

//Mise à jour base Euros
	include("Connexion_Euros.php");

	$requeteupdate = $bdd->prepare('UPDATE coins_list SET id = :id, symbol = :symbol, name = :name, date_achat = :date_achat, portefeuille = :portefeuille, montant = :montant, wallet = :wallet, exchange = :exchange, date_verification = :dateverif, observation = :observ WHERE id = :id');
	$dateachat = date("Y-m-d", strtotime($_POST['date_achat']));
	$date_de_verif = date("Y-m-d", strtotime($_POST['date_verif']));
	$requeteupdate->bindParam(':id', $_POST['id_coin']);
	$requeteupdate->bindParam(':symbol', $_POST['symbol_coin']);
	$requeteupdate->bindParam(':name', $_POST['name_coin']);
	$requeteupdate->bindParam(':date_achat', $dateachat);
	$requeteupdate->bindParam(':portefeuille', $_POST['portef']);
	$requeteupdate->bindParam(':montant', $_POST['montant']);
	$requeteupdate->bindParam(':wallet', $_POST['wallet']);
	$requeteupdate->bindParam(':exchange', $_POST['exchange']);
	$requeteupdate->bindParam(':dateverif', $date_de_verif);
	$requeteupdate->bindParam(':observ', $_POST['observation']);

	$requeteupdate->execute();

	$requeteupdate->closeCursor();

//Mise à jour base Bitcoin
	include("Connexion_BTC.php");

	$requeteupdate = $bdd->prepare('UPDATE coins_list SET id = :id, symbol = :symbol, name = :name, date_achat = :date_achat, portefeuille = :portefeuille, montant = :montant, wallet = :wallet, exchange = :exchange, date_verification = :dateverif, observation = :observ WHERE id = :id');
	$dateachat = date("Y-m-d", strtotime($_POST['date_achat']));
	$date_de_verif = date("Y-m-d", strtotime($_POST['date_verif']));
	$requeteupdate->bindParam(':id', $_POST['id_coin']);
	$requeteupdate->bindParam(':symbol', $_POST['symbol_coin']);
	$requeteupdate->bindParam(':name', $_POST['name_coin']);
	$requeteupdate->bindParam(':date_achat', $dateachat);
	$requeteupdate->bindParam(':portefeuille', $_POST['portef']);
	$requeteupdate->bindParam(':montant', $_POST['montant']);
	$requeteupdate->bindParam(':wallet', $_POST['wallet']);
	$requeteupdate->bindParam(':exchange', $_POST['exchange']);
	$requeteupdate->bindParam(':dateverif', $date_de_verif);
	$requeteupdate->bindParam(':observ', $_POST['observation']);

	$requeteupdate->execute();

	$requeteupdate->closeCursor();


//Mise à jour base Ethereum
	include("Connexion_ETH.php");

	$requeteupdate = $bdd->prepare('UPDATE coins_list SET id = :id, symbol = :symbol, name = :name, date_achat = :date_achat, portefeuille = :portefeuille, montant = :montant, wallet = :wallet, exchange = :exchange, date_verification = :dateverif, observation = :observ WHERE id = :id');
	$dateachat = date("Y-m-d", strtotime($_POST['date_achat']));
	$date_de_verif = date("Y-m-d", strtotime($_POST['date_verif']));
	$requeteupdate->bindParam(':id', $_POST['id_coin']);
	$requeteupdate->bindParam(':symbol', $_POST['symbol_coin']);
	$requeteupdate->bindParam(':name', $_POST['name_coin']);
	$requeteupdate->bindParam(':date_achat', $dateachat);
	$requeteupdate->bindParam(':portefeuille', $_POST['portef']);
	$requeteupdate->bindParam(':montant', $_POST['montant']);
	$requeteupdate->bindParam(':wallet', $_POST['wallet']);
	$requeteupdate->bindParam(':exchange', $_POST['exchange']);
	$requeteupdate->bindParam(':dateverif', $date_de_verif);
	$requeteupdate->bindParam(':observ', $_POST['observation']);

	$requeteupdate->execute();

	$requeteupdate->closeCursor();

	header('location: Saisie.php');
