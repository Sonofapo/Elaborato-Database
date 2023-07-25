<h1>Partita <?php echo $_VARS["match"] ?> - Esito: <?php echo $_VARS["outcome"] ?></h1>

<!-- <?php
# get all matches and add detail link
$rows = $db->esitoPartite($_SESSION["uid"]);
foreach ($rows as &$r)
	$r["Dettaglio"] = "<a href='?action=stats&mode=detail&id={$r["Codice"]}'>link</a>";
?>

<?php if ($table = generate_table($rows)): echo $table; else: ?>
<div>Non hai ancora giocato partite.</div>
<?php endif ?>
-->

<h2>Funzionalità aggiuntive</h2>
<ul>
	<li><a href="?action=stats&mode=op11&id=<?php echo $_VARS["match"] ?>">Operazione 11</a> - Scontri diretti</li>
	<li><a href="?action=stats&mode=op12&id=<?php echo $_VARS["match"] ?>">Operazione 12</a> - Conteggio eliminazioni per arma</li>
</ul> 