<?php

namespace Game\Players;
use Game\Players\AbstractPlayer;
use Game\Skills\SkillSet;

/**
 * The NEMESIS of the HERO from the EMAGIA task
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
class WildBeast extends AbstractPlayer{
    
    /**
     * Class constructor. No skills, as defined by the EMAGIA Task
     */    
    public function __construct() {
        
        $skills = new SkillSet();
        $skills->addSkill(new \Game\Skills\NormalAttack_Skill());
        
        parent::__construct($skills);
        $this->randomizeStats();
        
        $this->_defaultStats = $this->_stats;
    }

    /**
     * Initialize the player's stats within the values defined by the EMAGIA task
     */
    public function randomizeStats(): void {
        $this->_stats = array(
            "health" => rand(60, 90),
            "strength" => rand(60, 90),
            "defense" => rand(40, 60),
            "speed" => rand(40, 60),
            "luck" => rand(25, 40),
            "attackstrikes" => 1,
            "damageimpact" => 1
        );
    }

}