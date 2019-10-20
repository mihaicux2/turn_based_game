<?php

namespace Game\Players;
use Game\Players\AbstractPlayer;
use Game\Skills\SkillSet;

/**
 * The HERO player for the EMAGIA task
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
class Orderus extends AbstractPlayer{
    
    /**
     * Class constructor. Initializes the player skillset with skills defined by the EMAGIA Task
     */
    public function __construct() {
        
        $skills = new SkillSet();
        $skills->addSkill(new \Game\Skills\NormalAttack_Skill());
        $skills->addSkill(new \Game\Skills\RapidStrike_Skill());
        $skills->addSkill(new \Game\Skills\MagicShield_Skill());
        
        parent::__construct($skills);
        $this->randomizeStats();
        
        $this->_defaultStats = $this->_stats;
    }

    /**
     * Initialize the player's stats within the values defined by the EMAGIA task
     */
    public function randomizeStats(): void {
        $this->_stats = array(
            "health" => rand(70, 100),
            "strength" => rand(70, 80),
            "defense" => rand(45, 55),
            "speed" => rand(40, 50),
            "luck" => rand(10, 30),
            "attackstrikes" => 1,
            "damageimpact" => 1
        );
    }

}