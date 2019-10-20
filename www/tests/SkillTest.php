<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
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
final class SkillTest extends TestCase {

    /**
     * Check if a skill is can be applied
     * @return void
     */
    public function testAlwaysAvailableSkill(): void {
        $skillSet = new SkillSet();
        $rapidStrike = new RapidStrike_Skill();
        $rapidStrike->setChance(100);
        $skillSet->addSkill($rapidStrike);
        $player = new StdPlayer($skillSet);
        $player->setPlayerId("LuckyGuy")
                ->beginAttack()
                ->useSkills();
        
        $this->assertEquals(2, $player->getAttackstrikes());
        
        $magicShield = new MagicShield_Skill();
        $magicShield->setChance(100);
        $player->addSkill($magicShield);
        
        $player->revertSkills()
                ->beginDefense()
                ->useSkills();

        
        $this->assertEquals(0.5, $player->getDamageimpact());
    }

}
