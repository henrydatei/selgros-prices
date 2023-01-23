<?php
include("master/dbconnect.php");
include('master/masterfunctions.php');
 ?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <title>Selgros - Dashboard</title>
  <link rel="stylesheet" href="style/template.css">
  <link rel="stylesheet" href="style/masterstyle.css">
</head>
  <body>
    <div id="inhalt">
      <ul>
        <li class="listitem"><a href="">Home</a></li>
      </ul>
    </div>
    <main>
      <div id="ueberschrift">
        <h1>Selgros</h1>
      </div>
      <div class="text">
        <table id="tabelle">
          <tr>
            <th>Produkt</th><th>niedrigster Preis</th><th>aktueller Preis</th><th>Update</th>
          </tr>
          <?php
            $cheaptestPrices = array();
            $cheaptestPricesDate = array();
            $sql = "SELECT MIN(x.a) AS `minPrice`, `added`, `selgrosID`
            FROM (
              SELECT `selgrosID`, `preis` a, `added` FROM preise
              UNION
              SELECT `selgrosID`, `angebotspreis` a, `added` FROM preise
            ) x
            GROUP BY `selgrosID`";
            $back = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($back)) {
              $cheaptestPrices[$row["selgrosID"]] = $row["minPrice"];
              $cheaptestPricesDate[$row["selgrosID"]] = $row["added"];
            }

            $sql = "SELECT x.preis, x.added, timestampdiff(HOUR, x.added, NOW()) AS `diff`, produkte.name, produkte.selgrosID 
            FROM ( 
              SELECT preis, max(added) as added, selgrosID 
              FROM preise 
              GROUP BY selgrosID 
            ) x 
            INNER JOIN produkte ON x.selgrosID = produkte.selgrosID";
            $back = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($back)) {
              $currentPrice = $row["preis"];
              $cheapestPrice = $cheaptestPrices[$row["selgrosID"]];
              $cheapestPriceDate = $cheaptestPricesDate[$row["selgrosID"]];
              print("<tr>");
              print("<td>".$row["name"]."</td>");
              if ($cheapestPrice < $currentPrice) {
                $rabatt = round((1 - $cheapestPrice/$currentPrice) * 100);
                $ago = get_date_diff($cheapestPriceDate, date("c"), 1);
                print("<td>$cheapestPrice <span class=\"rabatt\">(-$rabatt %, vor $ago)</span></td>");
              } else {
                print("<td>$cheapestPrice</td>");
              }
              print("<td>$currentPrice</td>");
              print("<td>vor ".$row["diff"]." Stunden</td>");
              print("</tr>");
            }
          ?>
        </table>
      </div>
    </main>
  </body>
</html>
