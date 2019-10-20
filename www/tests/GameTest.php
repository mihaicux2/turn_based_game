<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Game\Players\WildBeast;
use Game\Players\Orderus;
use Game\Engine\EMagia;
use Game\Players\StdPlayer;
use Game\Skills\SkillSet;
use Game\Skills\MagicShield_Skill;
use Game\Skills\RapidStrike_Skill;

/**
 * Test class used to check different games results
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class GameTest extends TestCase {

    /**
     * Check that a random hero can beat a very weak animal
     * @return void
     */
    public function testWeakling(): void {
        $hero = new Orderus();
        $hero->setPlayerId("Orderus");

        $animal = new WildBeast();
        $animal->setPlayerId("Cancelus")
                ->setHealth(10)
                ->setDefense(0)
                ->setStrength(1)
                ->setLuck(0);

        $game = new EMagia($hero, $animal);
        $scenario = $game->runGame();

        $this->assertEquals($hero->getPlayerId(), $scenario["winner"]);
    }

    /**
     * Check the unscripted scenario => same speed and same luck
     * @return void
     */
    public function testPlayerMirror(): void {
        $hero = new Orderus();
        $hero->setPlayerId("Orderus");

        $game = new EMagia($hero, $hero);
        $scenario = $game->runGame();

        $this->assertNotEmpty($scenario["error"]);
    }

    /**
     * A hopeless battle...
     * @return void
     */
    public function testPlayerHopeless(): void {

        $hero = new Orderus();
        $hero->setPlayerId("Orderus");

        $skillSet = new SkillSet();
        $rapidStrike = new RapidStrike_Skill();
        $magicShield = new MagicShield_Skill();
        $rapidStrike->setChance(100);
        $magicShield->setChance(100);
        $skillSet->addSkill($magicShield);
        $skillSet->addSkill($rapidStrike);
        $boss = new StdPlayer($skillSet);
        $boss->setPlayerId("Storno")
                ->setHealth(1000)
                ->setDefense(85)
                ->setStrength(1000);

        $game = new EMagia($hero, $boss);
        $scenario = $game->runGame();

        $this->assertEquals($boss->getPlayerId(), $scenario["winner"]);
    }

    /**
     * Check players luck: Always lucky vs. never lucky
     * @return void
     */
    public function testPlayerLuck(): void {
        $hero = new Orderus();
        $hero->setPlayerId("LuckyGuy")
                ->setLuck(100)
                ->beginDefense()
                ->useSkills();

        $initialHeroHealth = $hero->getHealth();

        $beast = new WildBeast();
        $beast->setPlayerId("StormTrooper")
                ->setLuck(0)
                ->beginAttack()
                ->useSkills()
                ->attack($hero);
        $beast->attack($hero);
        $beast->attack($hero);
        $beast->attack($hero);
        $beast->attack($hero);

        $initialBeastHealth = $beast->getHealth();

        $beast->beginDefense();
        $hero->beginAttack()
                ->attack($beast);

        $remainingHeroHealth = $hero->getHealth();
        $remainingBeastHealth = $beast->getHealth();

        $this->assertEquals($initialHeroHealth, $remainingHeroHealth);
        
        $this->assertGreaterThan($remainingBeastHealth, $initialBeastHealth);
        
    }

}
