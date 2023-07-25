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

	/** USER FUNCTIONS */
	public function subscribe($username, $password) {
		if ($this->login($username, $password) === false) {
			$this->query("INSERT INTO utente (username, password) VALUES (?, ?)", [$username, $password], "ss");
			return true;
		}
		return false;
	}

	public function login($username, $password) {
		$res = $this->query("SELECT * FROM utente WHERE username = ? AND password = ?",
			[$username, $password] , "ss");
		return count($res) == 1;
	}

	public function deleteUser($username) {
		$this->query("UPDATE utente SET password = null WHERE username = ?", [$username] , "s");
	}

	/** SIMULTATION FUNCTIONS */
	public function getRandomUsers() {
		return $this->query("SELECT Username from utenti ORDER BY RAND() LIMIT 10");
	}

	public function getRandomMap() {
		return $this->query("SELECT Nome, NumeroSiti from mappe ORDER BY RAND() LIMIT 1");
	}

	public function getRandomAgents() {
		return $this->query("SELECT Nome from agenti ORDER BY RAND() LIMIT 5");
	}

	public function getRandomWeapon($tipo) {
		return $this->query("SELECT Nome from armi WHERE Tipologia = ? ORDER BY RAND() LIMIT 1",
			[$tipo], "s");
	}

	public function createMatch($mappa) {
		# Codice auto-increment, other values default to null
		$this->query("INSERT INTO partite (Data, NomeMappa) VALUES (CURRENT_TIMESTAMP, ?)", [$mappa], "s");
		return $this->query("SELECT Codice from partite ORDER BY Codice DESC LIMIT 1")[0]["Codice"];
	}

	public function finalizeMatch($codice, $durata, $squadra, $roundT, $roundV) {
		$this->query("UPDATE partite SET DurataTotale = ?, SquadraVincente = ?, RoundTotali = ?, RoundVinti = ?
			WHERE Codice = ?", [$durata, $squadra, $roundT, $roundV, $codice], "ssiii");
	}

	public function createPlayer($username, $agente, $partita, $squadra) {
		# CodiceGiocatore auto-increment
		$this->query("INSERT INTO giocatori (UsernameUtente, CodicePartita, NomeAgente, NomeSquadra)
			VALUES (?, ?, ?, ?)", [$username, $agente, $partita, $squadra], "siss");
		return $this->query("SELECT Codice from giocatori ORDER BY Codice DESC LIMIT 1")[0]["Codice"];
	}

	public function saveRound($codice, $numero, $durata, $squadra, $ruolo) {
		$this->query("INSERT INTO round (CodicePartita, Numero, Durata, SquadraVincente, RuoloVincente)
			VALUES (?, ?, ?, ?, ?)", [$codice, $numero, $durata, $squadra, $ruolo], "iisss");
	}

	public function saveAction($codice, $round, $tipo, $giocatore, $sito) {
		$this->query("INSERT INTO azioni (CodicePartitaRound, NumeroRound, Tipo, CodiceGiocatore, Sito)
			VALUES (?, ?, ?, ?, ?)", [$codice, $round, $tipo, $giocatore, $sito], "iisis");
	}

	public function savePurchase($codice, $round, $arma, $giocatore) {
		$this->query("INSERT INTO possessi (CodicePartitaRound, NumeroRound, NomeArma, CodiceGiocatore)
			VALUES (?, ?, ?, ?)", [$codice, $round, $arma, $giocatore], "iisi");
	}

	public function saveKill($codice, $round, $giocatoreS, $giocatoreC, $tempo, $arma) {
		$this->query("INSERT INTO uccisioni (CodicePartitaRound, NumeroRound, CodiceGiocatoreS,
			CodiceGiocatoreC, Istante, NomeArma) VALUES (?, ?, ?, ?, ?, ?)",
			[$codice, $round, $giocatoreS, $giocatoreC, $tempo, $arma], "iiiiss");
	}

	/** STATISTIC FUNCTIONS */


}

?>