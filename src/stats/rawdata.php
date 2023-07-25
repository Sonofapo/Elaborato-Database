<h1>Operazione <?php echo substr($_VARS["op"], 2) ?></h1>

<h2>Risultato: </h2>
<?php if ($table = generate_table($_VARS["rows"])): echo $table; else: ?>
<div>Non ci sono risultati.</div>
<?php endif ?>