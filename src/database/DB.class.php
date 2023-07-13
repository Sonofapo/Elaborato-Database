<?php

class DB {
	private $connection;

	public function __construct() {
		$this->connection = new mysqli("127.0.0.1", "utente", "utente1!", "elaborato");
	}

	private function query($statement, $vars = [], $types = "") {
		$q = $this->connection->prepare($statement);
		if ($vars && $types)
			$q->bind_param($types, ...$vars);
		$success = $q->execute();
		if ($res = $q->get_result())
			return $res->fetch_all(MYSQLI_ASSOC);
		return $success;
	}

	public function subscribe($username, $password) {
		if ($this->login($username, $password) === false) {
			$this->query("INSERT INTO utente (username, password) VALUES (?, ?)", [$username, $password], "ss");
			return true;
		}
		return false;
	}

	public function login($username, $password) {
		$res = $this->query("SELECT * FROM utente WHERE username = ? AND password = ?",	[$username, $password] , "ss");
		return count($res) == 1;
	}

}

?>