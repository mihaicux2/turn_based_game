<?php
include("vendor/autoload.php");

use Game\Players\WildBeast;
use Game\Players\Orderus;
use Game\Loggers\FileLogger;
use Game\Engine\GameEngine;

$turns = 20;
$turn = 0;

$heroes = array(
    "Pikachu",
    "Bulbasaur",
    "Charmander",
    "Squirtle"
);

$beasts = array(
    "Meowth",
    "Magikarp",
    "Ekans",
    "Geodude"
);

$heroIt = rand(0, 3);
$beastIt = rand(0, 3);

$hero = new Orderus();
//$hero->setHealth(500);
$hero->setPlayerId($heroes[$heroIt]);

$beast = new WildBeast();
//$hero->setHealth(500);
$beast->setPlayerId($beasts[$beastIt]);

$game = new GameEngine($hero, $beast);
//$game->setGameRounds(100);
$scenario = $game->runGame();

$script = $game->writeScript($scenario);

header("Content-Type: text/json");
echo json_encode(array(
    "heroIt" => $heroIt,
    "beastIt" => $beastIt,
    "script" => $script,
    "scenario" => $scenario,
));