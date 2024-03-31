<?php
include("en_tete.php");
?>
<form action="Saisie.php" method="post">
 <label for="recherche">Symbole :</label>
 <input type="search" name="search"/>
 <input type="submit" value="Rechercher" />
</form>

<?php

if(!empty($_POST) && !empty($_POST['search']))
{
  $resultat = $_POST['search'];
  extract($_POST);
  $req = $bdd->query("SELECT * FROM coins_list WHERE symbol LIKE '$resultat'");
  if($req->rowCount()>0)
  {
   while($data = $req->fetch(PDO::FETCH_OBJ))
   {
    $observation = $data->observation;
        		//echo '<h2>'.$data->name.'</h2>';
    ?>
    <form action="Saisie2.php" method="POST" class="saisie">
     <div id="crypto">
     <input type="text" name="id_coin" id="id_coin" value="<?php echo ''.$data->id.'';?>"/>
     <input type="text" name="symbol_coin" id="symbol_coin" value="<?php echo ''.$data->symbol.'';?>"/>
     <input type="text" name="name_coin" id="name_coin" value="<?php echo ''.$data->name.'';?>"/>
     <input id="MajCoin" type="submit" name="maj_coin" value="Modifier"/>

     <div id="regroupement">
      <fieldset id="Achat"><legend>Achat</legend>
       <label for="DateAch">Date d'achat : </label><input type="date" name="date_achat" id="date_achat" value="<?php echo ''.$data->date_achat.'';?>"/>
       <label for="Portef">Portefeuille : </label><input type="nombre" name="portef" id="portef" value="<?php echo ''.$data->portefeuille.'';?>"/>
       <label for="Montant">Montant : </label><input type="nombre" name="montant" id="montant" value="<?php echo number_format((float)$data->montant, 12, '.', '');?>"/>
     </fieldset>

     <fieldset id="stockage"><legend>Stockage</legend>
       <label for="wallet">Wallet :</label><input type="text" name="wallet" id="wallet" value="<?php echo ''.$data->wallet.'';?>"/>
       <label for="exchange">Exchange :</label><input type="text" name="exchange" id="exchange" value="<?php echo ''.$data->exchange.'';?>"/>
     </fieldset>

     <fieldset id="maintenance"><legend>Maintenance</legend>
       <label for="DateVerif">Date de vérification : </label><input type="date" name="date_verif" id="date_verif" value="<?php echo ''.$data->date_verification.'';?>"/><br />
       <label for="Observation">Observation : </label><textarea name="observation" id="Observation" cols="80" rows="10"value="<?php echo ''.$observation.'';?>"><?php echo ''.$observation.'';?></textarea>
     </fieldset>
   </div>
 </form>
 <?php
}
}
}
?>

</body>
</html>
