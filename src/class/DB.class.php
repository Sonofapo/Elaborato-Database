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
	# operazione 1
	public function subscribe($username, $password) {
		if ($this->login($username, $password) === false) {
			$this->query("INSERT INTO utenti (username, password) VALUES (?, ?)", [$username, $password], "ss");
			return true;
		}
		return false;
	}

	# operazione 2
	public function deleteUser($username) {
		$this->query("UPDATE utenti SET password = null WHERE username = ?", [$username] , "s");
	}

	public function login($username, $password) {
		$res = $this->query("SELECT * FROM utenti WHERE username = ? AND password = ?",
			[$username, $password] , "ss");
		return count($res) == 1;
	}

	/** SIMULTATION FUNCTIONS */
	public function getRandomUsers($username) {
		$res = $this->query("(SELECT Username FROM utenti WHERE Username = ?) UNION
			(SELECT Username FROM utenti WHERE Username <> ? ORDER BY RAND() LIMIT 9) ORDER BY RAND()",
			[$username, $username], "ss");
		return array_map(fn($x) => $x['Username'], $res); # remap array to username list
	}

	public function getRandomMap() {
		return $this->query("SELECT Nome, NumeroSiti from mappe ORDER BY RAND() LIMIT 1");
	}

	public function getRandomAgents() {
		$res = $this->query("SELECT Nome from agenti ORDER BY RAND() LIMIT 5");
		return array_map(fn($x) => $x['Nome'], $res);
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
			VALUES (?, ?, ?, ?)", [$username, $partita, $agente, $squadra], "siss");
		return $this->query("SELECT Codice from giocatori ORDER BY Codice DESC LIMIT 1")[0]["Codice"];
	}

	# operazione 7.0
	public function createRound($codice, $numero) {
		$this->query("INSERT INTO round (CodicePartita, Numero) VALUES (?, ?)", [$codice, $numero], "ii");
	}

	# operazione 7.0
	public function saveRound($codice, $numero, $durata, $squadra, $ruolo) {
		$this->query("UPDATE round SET Durata = ?, SquadraVincente = ?, RuoloVincente = ?
			WHERE CodicePartita = ? AND Numero = ?", [$durata, $squadra, $ruolo, $codice, $numero], "sssii");
	}

	# operazione 7.1
	public function saveAction($codice, $round, $tipo, $giocatore, $sito) {
		$this->query("INSERT INTO azioni (CodicePartitaRound, NumeroRound, Tipo, CodiceGiocatore, Sito)
			VALUES (?, ?, ?, ?, ?)", [$codice, $round, $tipo, $giocatore, $sito], "iisis");
	}

	# operazione 7.2
	public function savePurchase($codice, $round, $arma, $giocatore) {
		$this->query("INSERT INTO possessi (CodicePartitaRound, NumeroRound, NomeArma, CodiceGiocatore)
			VALUES (?, ?, ?, ?)", [$codice, $round, $arma, $giocatore], "iisi");
	}

	# operazione 7.3
	public function saveKill($codice, $round, $giocatoreS, $giocatoreC, $tempo, $arma) {
		$this->query("INSERT INTO uccisioni (CodicePartitaRound, NumeroRound, CodiceGiocatoreS,
			CodiceGiocatoreC, Istante, NomeArma) VALUES (?, ?, ?, ?, ?, ?)",
			[$codice, $round, $giocatoreS, $giocatoreC, $tempo, $arma], "iiiiss");
	}

	/** STATISTIC FUNCTIONS */
	# operazione 3
	public function esitoPartite($username) {
		return $this->query('SELECT Codice, Data, NomeAgente, NomeMappa, DurataTotale,
			IF(SquadraVincente IS NULL, "Pareggio", IF(SquadraVincente = NomeSquadra, "Vittoria", "Sconfitta"))
			AS Esito FROM partite_utente WHERE UsernameUtente = ?', [$username], "s");
	}

	# operazione 4
	public function precentualeVittorie($username, $from, $to) {
		return $this->query("SELECT COUNT(*) * 100.0 / (SELECT COUNT(*) FROM partite_utente
			WHERE UsernameUtente = ? AND CAST(Data AS DATE) BETWEEN ? AND ?) AS Percentuale FROM partite_utente
	  		WHERE UsernameUtente = ? AND CAST(Data AS DATE) BETWEEN ? AND ? AND NomeSquadra = SquadraVincente",
			[$username, $from, $to, $username, $from, $to], "ssssss");
	}

	# operazione 5
	public function classificaArmi($username) {
		return $this->query("SELECT u.NomeArma, Utilizzi, COUNT(u.NomeArma) AS TotaleUccisioni
	  		FROM uccisioni u, giocatori g
	  		JOIN (SELECT p.NomeArma, COUNT(p.NomeArma) AS Utilizzi
				FROM giocatori g, possessi p WHERE g.UsernameUtente = ? AND g.Codice = p.CodiceGiocatore
				GROUP BY p.NomeArma ORDER BY Utilizzi DESC LIMIT 3 ) AS top3
	  		WHERE g. UsernameUtente = ? AND u.CodiceGiocatoreC = g.Codice AND u.NomeArma = top3.NomeArma
	  		GROUP BY u.NomeArma ORDER BY Utilizzi DESC", [$username, $username], "ss");
	}

	# operazione 6
	public function classificaMappe($username, $from, $to) {
		return $this->query("SELECT p.NomeMappa, COUNT(*) As Totale FROM partite p, giocatori g
			WHERE g.UsernameUtente = ? AND g.CodicePartita = p.Codice AND CAST(p.Data AS DATE) BETWEEN ? AND ?
			GROUP BY p.NomeMappa ORDER BY Totale DESC LIMIT 3", [$username, $from, $to], "sss");
	}

	# operazione 8
	public function conteggioRound($codice) {
		return $this->query("SELECT Codice, SquadraVincente, RoundVinti, RoundTotali - RoundVinti AS RoundPersi
			FROM partite WHERE Codice = ?", [$codice], "i");
	}

	# operazione 9.0
	public function esitoRound($squadra, $codice, $numero) {
		return $this->query('SELECT Numero, Durata, IF(SquadraVincente = ?, RuoloVincente, 
			IF(RuoloVincente = "Attacco", "Difesa", "Attacco")) AS Ruolo,
			IF(SquadraVincente = ?, "Vittoria", "Sconfitta") AS Esito FROM round WHERE CodicePartita = ?
			AND Numero = ?', [$squadra, $squadra, $codice, $numero], "ssii");
	}

	# operazione 9.1
	public function elencoAzioni($codice, $numero) {
		return $this->query("SELECT g.NomeAgente, g.NomeSquadra, a.Tipo, a.Sito FROM giocatori g, azioni a
			WHERE a.CodicePartitaRound = ? AND a.NumeroRound = ? AND a.CodiceGiocatore = g.Codice",
			[$codice, $numero], "ii");
	}

	# operazione 9.2
	public function elencoUccisioni($codice, $numero) {
		return $this->query('SELECT u.Istante, CONCAT(gc.NomeAgente, " (", gc.NomeSquadra, ")") AS CausataDa,
			CONCAT(gs.NomeAgente, " (", gs.NomeSquadra, ")") AS SubitaDa, u.NomeArma FROM giocatori gc,
			giocatori gs, uccisioni u WHERE u.CodicePartitaRound = ? AND u.NumeroRound = ?
			AND u.CodiceGiocatoreC = gc.Codice AND u.CodiceGiocatoreS = gs.Codice ORDER BY u.Istante',
			[$codice, $numero], "ii");
	}

	# operazione 10
	public function storicoArmi($codice, $giocatore) {
		return $this->query("SELECT p.NumeroRound, p.NomeArma, a.Tipologia FROM possessi p, armi a
			WHERE p.CodicePartitaRound = ? AND p.CodiceGiocatore = ? AND p.NomeArma = a.Nome",
			[$codice, $giocatore], "ii");
	}

	# operazione 11
	public function scontriDiretti($codice, $giocatore) {
		return $this->query('SELECT u.NumeroRound, IF(gc.Codice = ?, gs.NomeAgente, gc.NomeAgente)
			AS Contro, IF(gc.Codice = ?, "Vinto", "Perso") AS Esito, u.NomeArma
			FROM giocatori gc, giocatori gs, uccisioni u WHERE u.CodicePartitaRound = ?
			AND (gc.Codice = ? OR gs.Codice = ?) AND u.CodiceGiocatoreC = gc.Codice
			AND u.CodiceGiocatoreS = gs.Codice',
			[$giocatore, $giocatore, $codice, $giocatore, $giocatore], "iiiii");
	}

	# operazione 12
	public function conteggioEliminazioni($codice, $giocatore) {
		return $this->query("SELECT p.NomeArma, COUNT(u.NumeroRound) AS TotaleUccisioni FROM possessi p
			LEFT JOIN uccisioni u ON u.CodicePartitaRound = p.CodicePartitaRound
			AND u.NumeroRound = p.NumeroRound AND u.CodiceGiocatoreC = p.CodiceGiocatore
			AND u.NomeArma = p.NomeArma WHERE p.CodicePartitaRound = ? AND p.CodiceGiocatore = ?
			GROUP BY p.NomeArma", [$codice, $giocatore], "ii");
	}

	public function getTeamComposition($codice) {
		return $this->query("SELECT UsernameUtente, NomeAgente, NomeSquadra FROM giocatori
		WHERE CodicePartita = ? ORDER BY NomeSquadra, NomeAgente", [$codice], "i");
	}

	/** SUPPORT FUNCTIONS */
	public function getTeam($username, $codice) {
		return $this->query("SELECT NomeSquadra FROM giocatori WHERE UsernameUtente = ? AND CodicePartita = ?",
			[$username, $codice], "si")[0]["NomeSquadra"];
	}

	public function getGiocatore($username, $codice) {
		return $this->query("SELECT Codice FROM giocatori WHERE UsernameUtente = ? AND CodicePartita = ?",
			[$username, $codice], "si")[0]["Codice"];
	}

}

?>