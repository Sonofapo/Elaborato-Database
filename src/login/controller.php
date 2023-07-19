<?php

$_VARS["body"] = get_include_contents("./src/login/view.php");

switch ($_VARS["mode"]) {
	case "login":
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($db->login($_POST["usr"], $_POST["psw"])) {
				$_SESSION["uid"] = $_POST["usr"];
				header("Location: index.php");
			}
		}
		$_VARS["body"] = get_include_contents("./src/login/view.php");
		break;
	case "subscribe":
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($db->subscribe($_POST["usr"], $_POST["psw"])) {
				$_SESSION["uid"] = $_POST["usr"];
				header("Location: index.php");
			} else
				die("Utente già esistente");
		}
		break;
	case "logout":
		session_unset();
		session_destroy();
		header("Location: index.php");
	case "delete":
		$db->deleteUser($_SESSION["uid"]);
		break;
}


?>