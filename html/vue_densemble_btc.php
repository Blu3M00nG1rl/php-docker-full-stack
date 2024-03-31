<?php
    include("en_tete_btc.php");

$reponse2 = $bdd->query('SELECT coins_list.id as Coin, coins_list.symbol, coins_list.name, coins_list.Portefeuille, 
                                prix_aujourdhui.id, DAY(prix_aujourdhui.journee) AS JourAuj, MONTH(prix_aujourdhui.journee) AS MoisAuj, YEAR(prix_aujourdhui.journee) AS AnAuj, prix_aujourdhui.prix AS PrixAuj,
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
                        ORDER BY coins_list.no');
$total = $reponse2->rowcount();
$donneesParPage = 30;   
$nombreDePages = ceil($total/$donneesParPage);
$donnees2 = $reponse2->fetch();
?>
            <table>
                <thead>
                    <th>Symbole</th>
                    <th>Nom</th>
                    <th><?php echo $donnees2['JourAuj'] . "/" . $donnees2['MoisAuj'] . "/" . $donnees2['AnAuj'];?></th>
                    <th><?php echo $donnees2['JourHier'] . "/" . $donnees2['MoisHier'] . "/" . $donnees2['AnHier'];?></th>
                    <th><?php echo $donnees2['JourUnAn'] . "/" . $donnees2['MoisUnAn'] . "/" . $donnees2['AnUnAn'];?></th>
                    <th>Evolution</th>
                    <th>Maximum</th>
                    <th>Minimum</th>
                    <th>Fib. Vente</th>
                    <th>Fib. Achat</th>
                </thead>
<?php
    
    if (isset($_GET['page'])) // Si la variable $_GET['page'] existe...
    {
        $pageActuelle=intval($_GET['page']);

        if($pageActuelle>$nombreDePages) // Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $nombreDePages...
        {
          $pageActuelle=$nombreDePages;
        }
    }
    else // Sinon
    {
     $pageActuelle=1; // La page actuelle est la n°1    
    }

    $premiereEntree=($pageActuelle-1)*$donneesParPage; // On calcul la première entrée à lire

    $reponse3 = $bdd->query('SELECT coins_list.id as Coin, coins_list.symbol, coins_list.name, coins_list.Portefeuille, 
                                prix_aujourdhui.id, DAY(prix_aujourdhui.journee) AS JourAuj, MONTH(prix_aujourdhui.journee) AS MoisAuj, YEAR(prix_aujourdhui.journee) AS AnAuj, prix_aujourdhui.prix AS PrixAuj,
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
                        ORDER BY symbol 
                        LIMIT '.$premiereEntree.', '.$donneesParPage.'');
    
        while ($donnees3 = $reponse3->fetch())
                { $evolution = (($donnees3['PrixAuj']-$donnees3['PrixDebut'])/$donnees3['PrixDebut']);
               
?>
<?php //echo $donnees2['id'] . $donnees2['JourneeDebut'];?>
                <tbody>
                    <tr>
                        <td><?php echo $donnees3['symbol'];?></td>
                        <td><?php echo $donnees3['name'];?></td>
                        <td><?php echo number_format($donnees3['PrixAuj'], 10 , "," , " ") . " B";?></td>
                        <td><?php echo number_format($donnees3['PrixHier'], 10 , "," , " ") . " B";?></td>
                        <td><?php echo number_format($donnees3['PrixDebut'], 10 , "," , " ") . " B";?></td>
                        <td><?php echo number_format($evolution * 100, 0, "," , " ") . " %";?></td>
                        <td><?php echo number_format($donnees3['Maxi'], 10 , "," , " ") . " B";?></td>
                        <td><?php echo number_format($donnees3['Mini'], 10 , "," , " ") . " B";?></td>
                        <td><?php echo number_format($donnees3['FibVente'], 10 , "," , " ") . " B";?></td>
                        <td><?php echo number_format($donnees3['FibAchat'], 10 , "," , " ") . " B";?></td>
                    </tr>
<?php
                }
    $reponse2->closecursor();
    $reponse3->closecursor();
?>
            </table>
        </section>
<?php
    echo 'Pages : '; 
for($i=1; $i<=$nombreDePages; $i++) //On fait notre boucle
{
     //On va faire notre condition
     if($i==$pageActuelle) //Si il s'agit de la page actuelle...
     {
         echo ' [ '.$i.' ] '; 
     }  
     else //Sinon...
     {
          echo '<div id="pages"><a href="vue_densemble_btc.php?page='.$i.'">'.$i.'</a></div> ';
     }
}
echo '</p>';
?>
    </body>
</html>