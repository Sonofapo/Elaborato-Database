<?php

class Game {

	const A_TEAM_KILL = 0;
	const D_TEAM_KILL = 1;

	private $database;
	private $Codice;
	private $sites;
	private $Alpha;
	private $Beta;
	private $round;
	private $role; # always refer to Alpha

	function __construct($database) {
		$this->database = $database;
		$map = $this->database->getRandomMap()[0];
		# convert N to first N letter of alphabet array (caps)
		$this->sites = @array_splice(range('A', 'C'), 0, $map["NumeroSiti"]);
		$this->Codice = $this->database->createMatch($map["Nome"]);
		$this->composeTeams();
		$this->round = 1;
		$this->role = ["Attacco", "Difesa"][rand() % 2];
	}

	public function composeTeams() {
		# select 10 random users
		$users = $this->database->getRandomUsers();
		# select 5 random agents for each team
		$this->Alpha = $this->database->getRandomAgents();
		$this->Beta = $this->database->getRandomAgents();

		# create a player with username-agent pair. [0-4]: Alpha, [5-9]: Beta
		for ($i = 0; $i < count($this->Alpha); $i++)
			$this->Alpha[$i] = $this->generatePlayer($users[$i], $this->Alpha[$i], "Alpha");
		for ($i = 0; $i < count($this->Beta); $i++)
			$this->Beta[$i] = $this->generatePlayer($users[$i+5], $this->Beta[$i], "Beta");
	}

	public function generatePlayer($username, $agente, $squadra) {
		$codice = $this->database->createPlayer($username, $agente, $this->Codice, $squadra);
		$player = [ "c" => $codice, "a" => [] ];
		return $player;
	}

	public function generateRound() {
		# random purchases for each team member
		foreach (["Alpha", "Beta"] as $team) {
			for ($i = 0; $i < count($this->$team); $i++) {
				if (rand() % 100 < 60) {
					$armaP = $this->database->getRandomWeapon("Primaria");
					$this->$team[$i]["a"][$armaP];
					$this->database->savePurchase($this->Codice, $this->round, $armaP, $this->$team[$i]["c"]);
				}
				if (rand() % 100 < 40) {
					$armaS = $this->database->getRandomWeapon("Secondaria");
					$this->$team[$i]["a"][$armaS];
					$this->database->savePurchase($this->Codice, $this->round, $armaS, $this->$team[$i]["c"]);
				}
			}
		}

		$durata = rand(60, 180);
		$attackers = $this->role == "Attacco" ? "Alpha" : "Beta";
		$defenders = $this->role == "Difesa"  ? "Alpha" : "Beta";

		$outcome = $this->generateRandomKills($this->$attackers, $this->$defenders, $durata);
		if ($outcome === Game::A_TEAM_KILL) {
			$squadra = $defenders;
			$ruolo = "Difesa";
		} else if ($outcome === Game::D_TEAM_KILL) {
			$squadra = $attackers;
			$ruolo = "Attacco";
		} else { # no team kill, try to plant spike
			$sito = $this->sites[rand() % count($this->sites)];
			$giocatoreA = $outcome[0][rand() % count($outcome[0])]["c"]; # attackers
			$giocatoreD = $outcome[1][rand() % count($outcome[1])]["c"]; # defenders
			if (rand() % 100 > 50) { # spike planted
				$this->database->saveAction($this->Codice, $this->round, "Innesco", $giocatoreA, $sito);
				if (rand() % 100 > 50) # successful defuse
					$this->database->saveAction($this->Codice, $this->round, "Disinnesco", $giocatoreD, $sito);
			}
		}

		# save round
		$this->database->saveRound($this->Codice, $this->round, gmdate("H:i:s", $durata), $squadra, $ruolo);
		$this->round = $this->round + 1;
	}

	function generateRandomKills($attackers, $defenders, $duration) {
		$teams = [ 0 => $attackers, 1 => $defenders ]; # copy and remap
		$kills = rand(1, 10); # number of kills in this round
		
		# until players exist or enough kills are generated
		for ($i = 0; count($teams[0]) && count($teams[1]) && $i < $kills; $i++) {
			$c = rand() % 100 > 50; # randomly choose offender side
			$s = !$c;
			
			$gc = $teams[$c][array_rand($teams[$c], 1)]; # offender player id
			$gs = $teams[$s][$key = array_rand($teams[$s], 1)]; # killed player id
			unset($teams[$s][$key]); # remove killed player
				
			$arma = $gc["a"][rand() % 2]; # select random weapon
			# random uniform distribution
			$time = 10 + intval($duration / ($kills * 1.5)) * (1 + $i) + ((rand() % 100 > 50) ? rand(1, 10) : rand(-10, -1));
			if ($time > $duration) $time = $duration;
			$this->database->saveKill($this->Codice, $this->round, $gs["c"], $gc["c"], gmdate("H:i:s", $time), $arma);
		}
		
		if (!count($teams[0])) return Game::A_TEAM_KILL;
		if (!count($teams[1])) return Game::D_TEAM_KILL;
		return $teams;
	}
}

?>