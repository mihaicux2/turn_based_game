<?php

namespace Game\Skills;

/**
 * Skill that improves the damage (absorbtion) impact. Unstackable. 20% chance of applying
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
class MagicShield_Skill extends AbstractSkill{

    /**
     * Class constructor. Initialize the chance and stackable property.
     */
    public function __construct() {
        parent::__construct(20);
        $this->skillName = "MagicShield";
        $this->usageState = "defend";
        $this->canBeStacked = false;
    }
    
    /**
     * Updates a player's damage (absorbtion) impact
     * @param \Game\Players\AbstractPlayer $player The player that has this skill
     * @return \Game\Skills\AbstractSkill The current skill
     */
    protected function apply(\Game\Players\AbstractPlayer $player): AbstractSkill {
        $player->setDamageImpact(0.5 * $player->getDamageImpact());
        return $this;
    }

    /**
     * Get the list of player stats that the current skill alters
     * @return array
     */
    protected function alteredStates(): array {
        return array(
            "damageimpact"
        );
    }

}