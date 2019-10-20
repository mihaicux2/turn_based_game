<?php

namespace Game\Players;
use Game\Players\AbstractPlayer;

/**
 * A simple extension of the AbstractPlayer class. Can be used for generalized tests, where players can have any given skillset
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
class StdPlayer extends AbstractPlayer{
    
    /**
     * Class constructor. Initializes the player skillset with granted skills
     * @param \Game\Skills\SkillSet $skills
     */
    public function __construct(\Game\Skills\SkillSet $skills = null) {
        
        parent::__construct($skills);
        $this->randomizeStats();
        
        $this->_defaultStats = $this->_stats;
    }

    /**
     * Initialize the player's stats within random values
     */
    public function randomizeStats(): void {
        $this->_stats = array(
            "health" => rand(10, 100),
            "strength" => rand(10, 100),
            "defense" => rand(5, 50),
            "speed" => rand(10, 70),
            "luck" => rand(0, 100),
            "attackstrikes" => 1,
            "damageimpact" => 1
        );
    }

}