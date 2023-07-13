<?php

require "./src/database/DB.class.php";
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

?>