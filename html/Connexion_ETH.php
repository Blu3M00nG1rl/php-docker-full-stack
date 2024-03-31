<?php
try
{
	$bdd = new PDO('mysql:host=db;dbname=cryptos_eth;charset=utf8','root','', array(PDO::ATTR_TIMEOUT => 5400, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}