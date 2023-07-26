<?php

require "./src/class/DB.class.php";
require "./src/class/Game.class.php";
$db = new DB();

session_start();

# converte file in stringa
function get_include_contents(string $file) {
	if (is_file($file)) {
		extract($GLOBALS, EXTR_REFS);
		ob_start();
		include $file;
		return ob_get_clean();
	}
	throw new ErrorException("$file not found");
}

function generate_table($rows) {
	if (count($rows) == 0) return "";

	$s = "<table><tr>";
	foreach (array_keys($rows[0]) as $e)
		$s .= "<th>" . $e . "</th>";
	$s .= "</tr>";
	foreach ($rows as $e) {
		$s .= "<tr>";
		foreach ($e as $v)
			$s .= "<td>" . ($v ?? "Nessun risultato") . "</td>";
		$s .= "</tr>";
	}
	$s .= "</table>";
	return $s;
}

?>