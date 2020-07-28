<?php

namespace Game\Engine;

use Game\Players\AbstractPlayer;

/**
 * Class used to simulate the battle between two opponents
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.9
 * @since 28 Jul 2020
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
class GameEngine {

    /**
     * The game's "Player One" - The hero
     * @var AbstractPlayer
     */
    private $hero;
    
    /**
     * The game's "Player Two" - The Nemesis
     * @var AbstractPlayer
     */
    private $animal;
    
    /**
     * The ( maximum ) total number of rounds for each game
     * @var int
     */
    private $gameRounds = 20;

    /**
     * Class constructor. 
     * Using dependency injection, the simulation works with any type of players that extend the base Player class
     * @param AbstractPlayer $hero
     * @param AbstractPlayer $animal
     */
    public function __construct(AbstractPlayer $hero, AbstractPlayer $animal) {
        $this->hero = $hero;
        $this->animal = $animal;
    }

    /**
     * Setter for the total number of rounds
     * @param int $gameRounds
     * @throws \Exception
     */
    public function setGameRounds(int $gameRounds) {
        if ($gameRounds > 0) {
            $this->gameRounds = $gameRounds;
        } else {
            throw new \Exception("Please input a positive integer!");
        }
    }

    /**
     * Function that parses the gameplay scenario and returns an easy to read "text" script
     * @param array $scenario
     * @return array
     */
    public function writeScript(array $scenario): array {
        $script = array();
        $heroName = $scenario["player1"]["id"];
        $beastName = $scenario["player2"]["id"];
        
        $script["intro"] = array(
            "A wild " . $beastName . " appears!",
            "It's time for battle!!",
            "First player: ".$scenario["firstPlayer"]
        );
        
        if ($scenario["error"]) {
            $script["error"] = $scenario["error"];
            return $script;
        }
        if ($scenario["warning"]) {
            $script["warning"] = $scenario["warning"];
        }

        if (!empty($scenario["gameplay"])) {
            foreach ($scenario["gameplay"] as $it => $turn) {
                $script["attacks"][$it] = array();
//                $attack = $turn["attacker"]["id"] . " attacks " . $turn["defender"]["id"];
                $attack = $turn["attacker"]["id"] . " attacks";
                if (!empty($turn["attacker"]["usedSkills"])) {
                    $attack .= " using [" . implode(", ", $turn["attacker"]["usedSkills"]) . "]";
                }
                $script["attacks"][$it][] = $attack;
                if ($turn["attacker"]["realDamage"] > $turn["attacker"]["maxDamage"]) {
                    $script["attacks"][$it][] = "It's super effective!";
                    $script["attacks"][$it][] = "Damage dealt: ".$turn["attacker"]["realDamage"];
                } elseif ($turn["attacker"]["realDamage"] == $turn["attacker"]["maxDamage"]) {
                    $script["attacks"][$it][] = "It works!";
                    $script["attacks"][$it][] = "Damage dealt: ".$turn["attacker"]["realDamage"];
                } elseif ($turn["attacker"]["realDamage"] < $turn["attacker"]["maxDamage"]) {
                    if ($turn["attacker"]["realDamage"] > 0) { // this means the defender used a skill to lower it's damage impact
                        $script["attacks"][$it][] = "It's not very effective!";
                        if (!empty($turn["defender"]["usedSkills"])) {
                            $script["attacks"][$it][] = $turn["defender"]["id"] . " used [" . implode(", ",$turn["defender"]["usedSkills"]) . "] for defense";
                        } elseif ($turn["attacker"]["hits"] < $turn["attacker"]["availableHits"]) {
                            $misses = $turn["attacker"]["availableHits"] - $turn["attacker"]["hits"];
                            $script["attacks"][$it][] = $turn["defender"]["id"] . " avoided " . $misses . " strike(s)!";
                        }
                        $script["attacks"][$it][] = "Damage dealt: ".$turn["attacker"]["realDamage"];
                    } else {
                        $script["attacks"][$it][] = "It doesn't work!";
                        $script["attacks"][$it][] = $turn["defender"]["id"] . " avoided ".$turn["attacker"]["id"]."'s attack!";
                    }
                }
            }
        }
        if ($scenario["winner"]){
            if ($scenario["winner"] == $heroName){
                $script["ending"] = $heroName." defeats ".$beastName." in another epic battle!";
            } else {
                $script["ending"] = $heroName." lost today, but there's still hope! He can challenge ".$beastName." another time, when the odds are in his favour.";
            }
        }

        return $script;
    }
    
    /**
     * Function that runs the game simulation and returns the gameplay
     * @return array
     */
    public function runGame(): array {

        $scenario = array(
            "maxRounds" => $this->gameRounds,
            "roundsPlayed" => 0,
            "firstPlayer" => false,
            "winner" => false,
            "error" => false,
            "warning" => false,
            "player1" => array(
                "id" => $this->hero->getPlayerId(),
                "initialStats" => $this->hero->getStats()
            ),
            "player2" => array(
                "id" => $this->animal->getPlayerId(),
                "initialStats" => $this->animal->getStats()
            ),
            "gameplay" => array()
        );

        $p1Strength = $this->hero->getStrength();
        $p1Defense = $this->hero->getDefense();

        $p2Strength = $this->animal->getStrength();
        $p2Defense = $this->animal->getDefense();

        if ($p1Strength <= $p2Defense) { // we care only about our hero ( here, player one )
            $scenario["warning"] = $scenario["player1"]["id"] . " is too weak to beat " . $scenario["player2"]["id"];
        }

        $players = array(
            $this->hero, // 0
            $this->animal // 1
        );

        // get initial player
        $p1Speed = $this->hero->getSpeed();
        $p2Speed = $this->animal->getSpeed();
        if ($p1Speed > $p2Speed) {
            $attacker = 0;
            $defender = 1;
        } elseif ($p1Speed < $p2Speed) {
            $attacker = 1;
            $defender = 0;
        } else {
            $p1Luck = $this->hero->getLuck();
            $p2Luck = $this->animal->getLuck();
            if ($p1Luck > $p2Luck) {
                $attacker = 0;
                $defender = 1;
            } elseif ($p1Luck < $p2Luck) {
                $attacker = 1;
                $defender = 0;
            }
        }

        // nothing defined in the task specifications....
        if (!isset($attacker)) {
            $scenario["error"] = "We don't have a scenario defined for players with identical stats!";
            return $scenario;
        }

        $scenario["firstPlayer"] = $players[$attacker]->getPlayerId();

        $turn = 0;
        $winner = false;
        // "run" battle :)
        while ($turn < $this->gameRounds && !$winner) {
            $turn++;

            $scenario["roundsPlayed"] ++;

            $players[$attacker]->revertSkills();
            $players[$defender]->revertSkills();

            $players[$attacker]->beginAttack();
            $players[$attacker]->useSkills();

            $players[$defender]->beginDefense();
            $players[$defender]->useSkills();

            $scenario["gameplay"][$turn - 1] = array(
                "attacker" => array(
                    "id" => $players[$attacker]->getPlayerId(),
                    "stats" => $players[$attacker]->getStats(),
                    "usedSkills" => $players[$attacker]->getUsedSkills()
                ),
                "defender" => array(
                    "id" => $players[$defender]->getPlayerId(),
                    "stats" => $players[$defender]->getStats(),
                    "newStats" => array(),
                    "usedSkills" => $players[$defender]->getUsedSkills()
                )
            );

            $availableStrikes = $players[$attacker]->getAttackstrikes();
            $attack = $players[$attacker]->attack($players[$defender]);
            $availableHits = count($attack);
            $maxDamage = $availableHits * ($players[$attacker]->getStrength() - $players[$defender]->getDefense());
            $dealtDamage = array_sum($attack);
            $hits = 0;
            foreach ($attack as $strike) {
                if ($strike) {
                    $hits++;
                }
            }

            $scenario["gameplay"][$turn - 1]["defender"]["newStats"] = $players[$defender]->getStats();
            $scenario["gameplay"][$turn - 1]["attacker"]["hits"] = $hits;
            $scenario["gameplay"][$turn - 1]["attacker"]["availableHits"] = $availableHits;
            $scenario["gameplay"][$turn - 1]["attacker"]["maxDamage"] = $maxDamage;
            $scenario["gameplay"][$turn - 1]["attacker"]["realDamage"] = $dealtDamage;

            if (!$players[$defender]->isAlive()) {
                $scenario["winner"] = $players[$attacker]->getPlayerId();
                $winner = true;
                break;
            }

            if ($attacker == 0) {
                $attacker = 1;
                $defender = 0;
            } else {
                $attacker = 0;
                $defender = 1;
            }
        }

        return $scenario;
    }

}
