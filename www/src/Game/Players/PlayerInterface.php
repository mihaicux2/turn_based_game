<?php

namespace Game\Players;

/**
 * Basic interface to be implemented by the AbstractPlayer class
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
interface PlayerInterface {
    
    /**
     * Check if a player is still alive
     * @return bool Should return True only if a condition defined by implementing classes is met
     */
    public function isAlive(): bool;
    
}