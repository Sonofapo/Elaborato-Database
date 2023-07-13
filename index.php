<?php

	require "./src/configure.php";

	$_VARS["action"] = isset($_SESSION["uid"]) ? ($_REQUEST["action"] ?? "show") : "user";
	if (isset($_SESSION["uid"])) {
		$_VARS["mode"] = $_REQUEST["mode"] ?? "show";
	} else if (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "subscribe") {
		$_VARS["mode"] = "subscribe";
	} else {
		$_VARS["mode"] = "login";
	}

	
	switch ($_VARS["action"]) {
		case "user":
			require "./src/login/controller.php";
			break;
		case "show":
			require "./src/stats/controller.php";
			break;
	}

			
	require "./src/templates/template.php";

?>