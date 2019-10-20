<?php

namespace Game\Skills;

/**
 * Skill that doesn't improve anything. Unstackable
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 19 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
class NormalAttack_Skill extends AbstractSkill{
    
    /**
     * Class constructor. Initialize the chance and stackable property.
     */
    public function __construct() {
        parent::__construct(100);
        $this->skillName = "NormalStrike";
        $this->usageState = "attack";
        $this->canBeStacked = false;
    }
    
    /**
     * Do nothing
     * @param \Game\Players\AbstractPlayer $player The player that has this skill
     * @return \Game\Skills\AbstractSkill The current skill
     */
    protected function apply(\Game\Players\AbstractPlayer $player): AbstractSkill {
        return $this;
    }

    /**
     * Get the list of player stats that the current skill alters
     * @return array
     */
    protected function alteredStates(): array {
        return array();
    }

}