<?php

class Game {

	const A_TEAM_KILL = 0; # all attackers are killed
	const D_TEAM_KILL = 1; # all defenders are killed

	private $DB;       # connection to the database to save game data
	private $Codice;   # match code
	private $sites;    # how/which site this map has
	private $round;    # current round
	private $role;     # always refer to Alpha team
	private $score;    # map to score teams score
	private $duration; # match total duration
	private $Alpha;    # alpha team members (do not delete, it is used with variable name)
	private $Beta;     # beta team members (do not delete, it is used with variable name)

	function __construct($DB, $username) {
		$this->DB = $DB;
		$map = $this->DB->getRandomMap()[0];
		$this->sites = array_slice([ 'A', 'B', 'C' ], 0, $map["NumeroSiti"]); # N -> [A..C]
		$this->Codice = $this->DB->createMatch($map["Nome"]);
		$this->composeTeams($username);
		$this->round = 1;
		$this->score = [ "Alpha" => 0, "Beta" => 0 ];
		$this->role = [ "Attacco", "Difesa" ][rand() % 2]; # randomly choose alpha team role
	}

	public function composeTeams($username) {
		$users = array_combine([ "Alpha", "Beta" ], array_chunk($this->DB->getRandomUsers($username), 5));
		$this->Alpha = $this->DB->getRandomAgents();
		$this->Beta  = $this->DB->getRandomAgents();
		foreach ([ "Alpha", "Beta" ] as $team)
			for ($i = 0; $i < count($this->$team); $i++)
				$this->$team[$i] = $this->generatePlayer($users[$team][$i], $this->$team[$i], $team);
	}

	public function generatePlayer($username, $agente, $squadra) {
		$codice = $this->DB->createPlayer($username, $agente, $this->Codice, $squadra);
		$player = [ "c" => $codice, "a" => [] ];
		return $player;
	}

	public function generateRound() {
		$this->DB->createRound($this->Codice, $this->round);

		foreach ([ "Alpha", "Beta" ] as $team) { # random purchases for each team
			foreach ($this->$team as &$t) { # and for each member
				$t["a"] = [$armaS = $this->DB->getRandomWeapon("Secondaria")[0]["Nome"]];
				$this->DB->savePurchase($this->Codice, $this->round, $armaS, $t["c"]);
				if (rand() % 100 < 70) {
					$t["a"][] = $armaP = $this->DB->getRandomWeapon("Primaria")[0]["Nome"];
					$this->DB->savePurchase($this->Codice, $this->round, $armaP, $t["c"]);
				}
			}
		}

		$attackers = $this->role == "Attacco" ? "Alpha" : "Beta";
		$defenders = $this->role == "Difesa"  ? "Alpha" : "Beta";

		$outcome = $this->generateRandomKills($this->$attackers, $this->$defenders, $durata);
		# let's consider attackers winners without plant, D_TEAM_KILL skipped for this reason
		$squadra = $attackers;
		$ruolo = "Attacco";
		if ($outcome === Game::A_TEAM_KILL) {
			$squadra = $defenders;
			$ruolo = "Difesa";
		} else if ($outcome !== Game::D_TEAM_KILL) { # no team kill, try to plant spike
			$sito = $this->sites[rand() % count($this->sites)]; # select random site
			$giocatoreA = $outcome[0][array_rand($outcome[0])]["c"]; # who will plant
			$giocatoreD = $outcome[1][array_rand($outcome[1])]["c"]; # who will defuse
			if (rand() % 100 < 80) { # spike planted
				$this->DB->saveAction($this->Codice, $this->round, "Innesco", $giocatoreA, $sito);
				# attackers were previously set as winners
				if (rand() % 100 < 30) { # successful defuse, defenders win
					$this->DB->saveAction($this->Codice, $this->round, "Disinnesco", $giocatoreD, $sito);
					$squadra = $defenders;
					$ruolo = "Difesa";
				}
			}
		}

		# save round
		$this->score[$squadra]++; # update score
		$this->duration = $this->duration + $durata;
		$this->DB->saveRound($this->Codice, $this->round, gmdate("H:i:s", $durata), $squadra, $ruolo);
		
		# check for round conditions
		if ($this->round == 12) { # role swap
			$this->role = $this->role == "Attacco" ? "Difesa" : "Attacco";
		} else if (max($this->score) == 13 || rand() % 100 < 2) { # team wins or sudden ending
			$maxR =  max($this->score);
			if ($this->score["Alpha"] == $this->score["Beta"]) # tie
				$team = null;
			else
				$team = array_search($maxR, $this->score);
			return [
				"durata"  => gmdate("H:i:s", $this->duration),
				"squadra" => $team,
				"roundT"  => $this->round,
				"roundV"  => $maxR,
			];
		}

		$this->round = $this->round + 1;
		return true;
	}

	function simulate() {
		while (($r = $this->generateRound()) === true);
		$this->DB->finalizeMatch($this->Codice, ...$r);
	}

	function generateRandomKills($attackers, $defenders, &$duration) {
		$teams = [ 0 => $attackers, 1 => $defenders ]; # copy and remap
		$kills = rand(3, 10); # number of kills in this round
		
		$duration = rand(15, 25); # random time at first kill
		# until players exist or enough kills are generated
		for ($i = 0; count($teams[0]) && count($teams[1]) && $i < $kills; $i++) {
			$c = rand(0, 1); # randomly choose offender side
			$s = !$c;
			
			$gc = $teams[$c][array_rand($teams[$c], 1)]; # offender player
			$gs = $teams[$s][$key = array_rand($teams[$s], 1)]; # killed player
			unset($teams[$s][$key]); # remove killed player
				
			$arma = $gc["a"][rand() % count($gc["a"])]; # select random weapon
			$this->DB->saveKill($this->Codice, $this->round, $gs["c"], $gc["c"], gmdate("H:i:s", $duration), $arma);
			$duration = $duration + rand(15, 30); # new random time for next kill
			if ($duration > 180) { # round timeout, defenders win
				$duration = 180;
				return Game::A_TEAM_KILL;
			}
		}

		if (!count($teams[0])) return Game::A_TEAM_KILL;
		if (!count($teams[1])) return Game::D_TEAM_KILL;
		return $teams;
	}
}

?>