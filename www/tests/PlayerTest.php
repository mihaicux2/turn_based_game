<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Game\Players\AbstractPlayer;
use Game\Players\StdPlayer;
use Game\Skills\SkillSet;

/**
 * Test class used to check implemented methods for the Player
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class PlayerTest extends TestCase {

    /**
     * Check if the player class can be instanciated
     * @return void
     */
    public function testCanInstanciate(): void {
        $this->assertInstanceOf(StdPlayer::class, new StdPlayer());
    }

    /**
     * Check invalid getters
     * @return void
     */
    public function testInvalidGetter(): void {
        $this->expectException(Exception::class);

        $player = new StdPlayer();
        $player->getLuckx();
    }

    /**
     * Check invalid setters
     * @return void
     */
    public function testInvalidSetter(): void {
        $this->expectException(Exception::class);

        $player = new StdPlayer();
        $player->setLuckx(55);
    }

    /**
     * Check invalid method calls
     * @return void
     */
    public function testInvalidMethod(): void {
        $this->expectException(Exception::class);

        $player = new StdPlayer();
        $player->doSomething();
    }

    /**
     * Check valid setter & getter
     * @return void
     */
    public function testLuck(): void {
        $player = new StdPlayer();
        $player->setLuck(5);

        $this->assertEquals($player->getLuck(), 5);
    }

    /**
     * Check combat state
     * @return void
     */
    public function testCombatState(): void {
        $player = new StdPlayer();

        $player->beginAttack();
        $this->assertEquals("attack", $player->getCombatState());

        $player->beginDefense();
        $this->assertEquals("defend", $player->getCombatState());
    }

    /**
     * Check skillset
     * @return void
     */
    public function testSkillSet(): void {
        $player = new StdPlayer();

        $this->assertInstanceOf(SkillSet::class, $player->getSkillSet());
    }

}
