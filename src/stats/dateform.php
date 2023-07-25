<h1>Operazione <?php echo substr($_VARS["op"], 2) ?></h1>

<?php if (isset($_VARS["rows"])): ?>

<h2>Risultato: </h2>
<?php if ($table = generate_table($_VARS["rows"])): echo $table; else: ?>
<div>Non ci sono risultati.</div>
<?php endif ?>

<?php else: ?>

<div>Seleziona un intervallo: </div><br>
<form action="index.php" method="POST">
	<input type="hidden" name="action" value="stats" />
	<input type="hidden" name="mode" value="<?php echo $_VARS["op"] ?>" />

	<label for="dateFrom">Da: </label>
	<input type="date" id="dateFrom" name="dateFrom" />
	<label for="dateTo">A: </label>
	<input type="date" id="dateTo" name="dateTo" />

	<button type="submit">Esegui</button>
</form>

<?php endif ?>