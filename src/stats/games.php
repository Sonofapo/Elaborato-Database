<h1>Storico delle partite giocate</h1>

<?php
# get all matches and add detail link
foreach ($_VARS["rows"] as &$r)
	$r["Dettaglio"] = "<a href='?action=stats&mode=detail&id={$r["Codice"]}'>link</a>";
?>

<?php if ($table = generate_table($_VARS["rows"])): echo $table; else: ?>
<div>Non hai ancora giocato partite.</div>
<?php endif ?>

<h2>Funzionalità aggiuntive</h2>
<ul>
	<li><a href="?action=stats&mode=op4">Operazione 4</a> - Percentuale vittorie</li>
	<li><a href="?action=stats&mode=op5">Operazione 5</a> - Top 3 armi più utilizzate</li>
	<li><a href="?action=stats&mode=op6">Operazione 6</a> - Top 3 mappe più giocate</li>
</ul>