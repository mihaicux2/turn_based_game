<?php

namespace Game\Skills;

/**
 * Skill that improves the number attack per turn. Unstackable. 10% chance of applying
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
class RapidStrike_Skill extends AbstractSkill{
    
    /**
     * Class constructor. Initialize the chance and stackable property.
     */
    public function __construct() {
        parent::__construct(10);
        $this->skillName = "RapidStrike";
        $this->usageState = "attack";
        $this->canBeStacked = false;
    }
    
    /**
     * Updates a player's number of attack per turn 
     * @param \Game\Players\AbstractPlayer $player The player that has this skill
     * @return \Game\Skills\AbstractSkill The current skill
     */
    protected function apply(\Game\Players\AbstractPlayer $player): AbstractSkill {
        $player->setAttackStrikes($player->getAttackStrikes() + 1);
        return $this;
    }

    /**
     * Get the list of player stats that the current skill alters
     * @return array
     */
    protected function alteredStates(): array {
        return array(
            "attackstrikes"
        );
    }

}