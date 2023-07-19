<h1>Storico delle partite giocate</h1>

<?php
# TODO: REMOVE
$rows = [
	[ "ID" => "1", "mappa" => "Ascent", "esito" => "Vittoria", "durata" => "6:45" ],
	[ "ID" => "2", "mappa" => "Heaven", "esito" => "Sconfitta", "durata" => "3:30" ],
];

for ($i = 0; $i < count($rows); $i++) {
	$id = $rows[$i]["ID"];
	$rows[$i]["link"] = "<a href='?action=stats&mode=detail&id=$id'>Link</a>";
}
?>

<?php if ($table = generate_table($rows)): echo $table; else: ?>
<div>Non hai ancora giocato partite. <a href="index.php">Torna al menu</a></div>
<?php endif ?>


<h1>Funzionalità aggiuntive</h1>
<ul>
	<li><a href="?action=stats&mode=perc">Percentuale vittorie</a></li>
	<li><a href="?action=stats&mode=weapon3">Top 3 armi più utilizzate</a></li>
	<li><a href="?action=stats&mode=maps3">Top 3 mappe più giocate</a></li>
</ul>