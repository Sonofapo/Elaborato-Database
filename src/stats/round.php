<h1>Partita <?php echo $_VARS["match"] ?> - Round <?php echo $_VARS["round"] ?></h1>

<h2>Uccisioni</h2>
<?php echo generate_table($_VARS["kills"]); ?>

<h2>Avvenimenti</h2>
<?php echo generate_table($_VARS["actions"]); ?>