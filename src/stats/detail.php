<h1>Partita <?php echo $_VARS["match"] ?> - Esito: <?php echo $_VARS["outcome"] ?></h1>

<?php
foreach ($_VARS["rows"] as &$r)
	$r["Dettaglio"] = "<a href='?action=stats&mode=round&id={$_VARS["match"]}&num={$r["Numero"]}'>link</a>";
$table = generate_table($_VARS["rows"]);
echo $table;
?>

<h2>Funzionalità aggiuntive</h2>
<ul>
	<li><a href="?action=stats&mode=op11&id=<?php echo $_VARS["match"] ?>">Operazione 11</a> - Scontri diretti</li>
	<li><a href="?action=stats&mode=op12&id=<?php echo $_VARS["match"] ?>">Operazione 12</a> - Conteggio eliminazioni per arma</li>
</ul> 