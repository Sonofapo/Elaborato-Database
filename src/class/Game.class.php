<?php

class Game {

	private $database;
	private $Codice;
	private $TeamA;
	private $TeamB;

	function __construct($database) {
		$this->database = $database;
		$this->Codice = $this->database->createMatch($this->database->getRandomMap()[0]["Nome"]);
		$this->composeTeams();
	}

	public function composeTeams() {
		# select 10 random users
		$users = $this->database->getRandomUsers();
		# select 5 random agents for each team
		$this->TeamA = $this->database->getRandomAgents();
		$this->TeamB = $this->database->getRandomAgents();

		# create a player with username-agent pair. [0-4]: teamA, [5-9]: teamB
		for ($i = 0; $i < count($this->TeamA); $i++)
			$this->TeamA[$i] = $this->generatePlayer($users[$i], $this->TeamA[$i], "Alpha");
		for ($i = 0; $i < count($this->TeamB); $i++)
			$this->TeamB[$i] = $this->generatePlayer($users[$i+5], $this->TeamB[$i], "Beta");
	}

	public function generatePlayer($username, $agente, $squadra) {
		$player = [
			"Codice" => 0,
			"UsernameUtente" => $username,
			"CodicePartita" => $this->Codice,
			"NomeAgente" => $agente,
			"NomeSquadra" => $squadra,
		];
		$this->database->createPlayer($username, $agente, $this->Codice, $squadra);
		return $player;
	}
}

?>