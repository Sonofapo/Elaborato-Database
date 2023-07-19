<?php


$_VARS["body"] = get_include_contents("./src/stats/menu.php");

switch ($_VARS["mode"]) {
	case "games":
		$_VARS["body"] = get_include_contents("./src/stats/games.php");
		break;
	case "detail";
		# TODO: GLOBAL VAR
		$_VARS["body"] = get_include_contents("./src/stats/detail.php");
		break;
	case "perc":
		break;
	case "weapon3":
		break;
	case "maps3":
		break;
	case "play":
		$link = '<a href="index.php">Torna indietro</a>';
		die("La partita è stata simultata. $link");
}

?>