<?php


$_VARS["body"] = get_include_contents("./src/stats/menu.php");

switch ($_VARS["mode"]) {
	case "games":
		$_VARS["rows"] = $db->esitoPartite($_SESSION["uid"]);
		$_VARS["body"] = get_include_contents("./src/stats/games.php");
		break;
	case "detail";
		$_VARS["match"] = $_GET["id"];
		$res = $db->conteggioRound($_GET["id"])[0];
		if ($res["SquadraVincente"] == $db->getTeam($_SESSION["uid"], $_GET["id"])[0]["NomeSquadra"])
			$_VARS["outcome"] = "{$res["RoundVinti"]}-{$res["RoundPersi"]}";
		else
			$_VARS["outcome"] = "{$res["RoundPersi"]}-{$res["RoundVinti"]}";
		$_VARS["body"] = get_include_contents("./src/stats/detail.php");
		break;
	case "op4": # time span win ratio
		$_VARS["op"] = "op4";
		if ($_SERVER["REQUEST_METHOD"] == "POST")
			$_VARS["rows"] = $db->precentualeVittorie($_SESSION["uid"], $_POST["dateFrom"], $_POST["dateTo"]);
		$_VARS["body"] = get_include_contents("./src/stats/dateform.php");
		break;
	case "op5": # top 3 weapon
		$_VARS["op"] = "op5";
		$_VARS["rows"] = $db->classificaArmi($_SESSION["uid"]);
		$_VARS["body"] = get_include_contents("./src/stats/rawdata.php");
		break;
	case "op6": # top 3 maps
		$_VARS["op"] = "op6";
		if ($_SERVER["REQUEST_METHOD"] == "POST")
			$_VARS["rows"] = $db->classificaMappe($_SESSION["uid"], $_POST["dateFrom"], $_POST["dateTo"]);
		$_VARS["body"] = get_include_contents("./src/stats/dateform.php");
		break;
	case "op11":
		$_VARS["op"] = "op11";
		$_VARS["rows"] = $db->scontriDiretti($_GET["id"], $db->getGiocatore($_SESSION["uid"], $_GET["id"]));
		$_VARS["body"] = get_include_contents("./src/stats/rawdata.php");
		break;
	case "op12":
		$_VARS["op"] = "op12";
		$_VARS["rows"] = $db->conteggioEliminazioni($_GET["id"], $db->getGiocatore($_SESSION["uid"], $_GET["id"]));
		$_VARS["body"] = get_include_contents("./src/stats/rawdata.php");
		break;
	case "play":
		$link = '<a href="index.php">Torna indietro</a>';
		$game = new Game($db, $_SESSION["uid"]);
		$game->simulate();
		die("La partita è stata simultata. $link");
}

?>