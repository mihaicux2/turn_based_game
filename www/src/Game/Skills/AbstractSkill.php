<?php

namespace Game\Skills;

/**
 * Base ABSTRACT class to be used as starting point for future skills
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractSkill {
    /**
     * The chance that the current skill will be applied to a player
     * @var float
     */
    protected $chance;
    
    /**
     * A copy for the initial $chance property
     * @var type float
     */
    protected $initialChance;
    
    /**
     * Whether the current skill can be applied multiple times to the same player
     * @var bool
     */
    protected $canBeStacked = false;
    
    /**
     * Whether the current skill has been applied to a player
     * @var bool
     */
    protected $used = false;
    
    /**
     * A skill can be used only if the player's combat state is the same as this
     * @var string Can be either "attack" or "defend"
     */
    protected $usageState;
    
    /**
     * Each skill should have it's own name
     * @var string 
     */
    protected $skillName = "Abstract Skill";
    
    /**
     * This must be implemented by all classes that extend the current base class
     * This method should return an array with at least one state ( that the skill will alter )
     */
    abstract protected function alteredStates(): array;
    
    /**
     * This must be implemented by all classes that extend the current base class
     * This method should be used to change/alter the player stats
     */
    abstract protected function apply(\Game\Players\AbstractPlayer $player): AbstractSkill;

    /**
     * Class Constructor.
     * @param int $chance The initial value for the $chance property
     */
    public function __construct(int $chance) {
        $this->chance = $chance;
        $this->initialChance = $chance;
    }
    
    /**
     * Getter for the $skillName property
     * @return string
     */
    public function getName(): string{
        return $this->skillName;
    }
    
    /**
     * Get the current skill's usage state. A skill can be used only if the player has the same combat state as the skill's usage state
     * @return string
     */
    public function getUsageState(): string{
        return $this->usageState;
    }


    /**
     * Setter for the $chance property
     * @param int $chance The new value
     * @return \Game\Skills\AbstractSkill The current object
     */
    public function setChance(int $chance): AbstractSkill{
        $this->chance = $chance;
        return $this;
    }
    
    /**
     * Setter for the $used property
     * @param bool $used The new value
     * @return \Game\Skills\AbstractSkill The current object
     */
    public function setUsed(bool $used): AbstractSkill{
        $this->used = $used;
        return $this;
    }
    
    /**
     * Check if the skill is used by the owning player
     * @return bool True if the skill is already used by the player
     */
    public function isUsed(): bool{
        return $this->used;
    }
    
    /**
     * Reset the $chance property to it's initial value
     * @return \Game\Skills\AbstractSkill The current object
     */
    public function resetChance(): AbstractSkill{
        return $this->setChance($this->initialChance);
    }
    
    /**
     * Try to apply the current skill to a given player
     * @param \Game\Players\AbstractPlayer $player The player that has this skill
     * @return bool Returns true if the skill is applied
     */
    public function useSkill(\Game\Players\AbstractPlayer $player): bool{
        if ($player->checkCombatState($this->usageState) && $this->canApplyByState() && $this->canApplyByChance()){
            $this->apply($player)
                 ->setUsed(true);
            return true;
        }
        return false;
    }
    
    /**
     * Revert the effect of skill
     * @param \Game\Players\AbstractPlayer $player
     * @return bool Always true
     */
    public function revertSkill(\Game\Players\AbstractPlayer $player): bool{
        $defaultStats = $player->getDefaultStats();
        foreach ($this->alteredStates() as $alteredState){
            $player->{"set".$alteredState}($defaultStats[$alteredState]);
        }
        $this->setUsed(false);
        return true;
    }
    
    /**
     * A skill can be applied only by chance
     * @return bool True if the skill can be applied, depending on it's chance
     */
    public function canApplyByChance(): bool{
        return (rand(0, 100) <= $this->chance);
    }
    
    /**
     * A skill can be applied only if it's stackable or it hasn't been used yes
     * @return boolean True if the skill can be applied, depending on it's current state. Either $canBeStacked is true or $used is false
     */
    public function canApplyByState(): bool{
        return ($this->canBeStacked || !$this->used);
    }
}