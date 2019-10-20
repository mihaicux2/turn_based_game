<?php

namespace Game\Skills;

/**
 * Class that is used to store the list of skills each player can have
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
class SkillSet{
    
    /**
     * The skills stored in the current set
     * @var array
     */
    private $_skills;
    
    /**
     * Class constructor. It simply initializes the $_skills property
     * @param \Game\Skills\AbstractSkill $skill
     */
    public function __construct() {
        $this->_skills = [];
    }
    
    /**
     * Add another skill to the current set
     * @param \Game\Skills\AbstractSkill $skill
     */
    public function addSkill(AbstractSkill $skill): SkillSet{
        $this->_skills[] = $skill;
        return $this;
    }
    
    /**
     * Returns the list of skills in the current set
     * @return array
     */
    public function getSkills(): array{
        return $this->_skills;
    }
    
    /**
     * 
     * @param \Game\Players\AbstractPlayer $player
     */
    public function useSkills(\Game\Players\AbstractPlayer $player): SkillSet{
        foreach ($this->getSkills() as $skill){
            $skill->useSkill($player);
        }
        return $this;
    }
    
    /**
     * Revert the effect of skills applied to a player
     * @param \Game\Players\AbstractPlayer $player
     */
    public function revertSkills(\Game\Players\AbstractPlayer $player): SkillSet{
        foreach ($this->getSkills() as $skill){
            $skill->revertSkill($player);
        }
        return $this;
    }
    
}