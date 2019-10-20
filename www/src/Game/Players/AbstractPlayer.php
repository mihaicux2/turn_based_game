<?php

namespace Game\Players;
use Game\Players\PlayerInterface;
use Game\Skills\SkillSet;
use Game\Skills\AbstractSkill;

/**
 * Abstract class to serve as a base for the future PLAYER objects.
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractPlayer implements PlayerInterface{
    
    /**
     * The list of stats for a player
     * Currently, as defined by the EMAGIA task:
     *  - health: the player health
     *  - strength: the player's attack strength
     *  - defense: the player's resistance to other player's attack
     *  - speed: basically, a player's initial response time
     *  - luck: the chance that the player can avoid another player's attack
     * Defined in order to complete the EMAGIA tasl:
     *  - attackstrikes: the number of times a player can hit another player. Affected by the RapidStrike skill
     *  - damageimpact: scaler for another player's attack. Affected by the MagicShield skill
     * @var array
     */
    protected $_stats = array(
        "health" => 0, // player health
        "strength" => 0, // player strength => used for attack
        "defense" => 0, // player defense => used to reduce other players's attack
        "speed" => 0, // set to check whether or not a player should strike first
        "luck" => 0, // chance for the player to be missed by an aooponent's attack
        "attackstrikes" => 1, // the total number of attacks each player is allowed to perform during it's turn
        "damageimpact" => 1 // the damage taken (from another player's attack) is multiplied by this factor ( ie. shield will decrease the impact )
    );
    
    protected static $_PLAYERS = 0;
    
    /**
     * Clone variable for the initial stats
     * @var array 
     */
    protected $_defaultStats;
    
    /**
     * The list of skills that a player posses
     * @var Game\Skills\SkillSet 
     */
    protected $_skillSet;    
    
    /**
     * The player's combat state. 
     * @var string Either "attack" or "defend"
     */
    protected $combatState;
    
    /**
     * An identifier for the current player
     * @var string
     */
    protected $playerId;
    
    /**
     * Static list of possible combat states 
     * @var array 
     */
    protected static $_COMBAT_STATES = array(
        "attack",
        "defend"
    );
    
    /**
     * This method should be implemented by the extending classes.
     * It should set random values for the existing stats
     */
    abstract public function randomizeStats(): void;
    
    /**
     * Class constructor. Initialize the skillset, if not null
     * @param SkillSet $skills
     */
    public function __construct(SkillSet $skills = null) {
        if (!is_null($skills)){
            $this->_skillSet = $skills;
        } else {
            $this->_skillSet = new SkillSet(null);
        }
        static::$_PLAYERS++;
        
        $this->playerId = "Player".static::$_PLAYERS;
    }
    
    /**
     * Getter for the $playerId property
     * @return string
     */
    public function getPlayerId(): string{
        return $this->playerId;
    }
    
    /**
     * Setter for the $playerId property
     * @param string $playerId
     * @return \Game\Players\AbstractPlayer The current object
     */
    public function setPlayerId(string $playerId): AbstractPlayer{
        $this->playerId = $playerId;
        return $this;
    }
    
    /**
     * Getter for the $combatState property
     * @return string
     */
    public function getCombatState(): string{
        return $this->combatState;
    }
    
    /**
     * Setter for the $combatState property
     * @param string $combatState
     * @return \Game\Players\AbstractPlayer The current object
     */
    protected function setCombatState(string $combatState): AbstractPlayer{
        if (in_array($combatState, static::$_COMBAT_STATES)){
            $this->combatState = $combatState;
        }        
        return $this;
    }
    
    /**
     * Set the player in the "attack" combat state
     * @return \Game\Players\AbstractPlayer The current object
     */
    public function beginAttack(): AbstractPlayer{
        $this->setCombatState("attack");
        return $this;
    }
    
    /**
     * Set the player in the "defend" combat state
     * @return \Game\Players\AbstractPlayer The current object
     */
    public function beginDefense(): AbstractPlayer{
        $this->setCombatState("defend");
        return $this;
    }
    
    /**
     * Check if the player is in the given combat state
     * @param type $combatState
     * @return bool True if the current combat state equals to the input value
     */
    public function checkCombatState($combatState): bool{
        return $this->combatState == $combatState;
    }
    
    /**
     * Getter for the $_stats property
     * @return array
     */
    public function getStats(): array{
        return $this->_stats;
    }
    
    public static function statsAsString(array $stats): string{
        $ret = array();
        foreach ($stats as $stat => $val){
            $ret[ucfirst($stat)] = $val;
        }
        return json_encode($ret);
    }
    
    /**
     * Getter for the $__defaultStats property
     * @return array
     */
    public function getDefaultStats(): array{
        return $this->_defaultStats;
    }
    
    /**
     * Setter for the $_stats property
     * @param array $stats
     * @return \Game\Players\AbstractPlayer The current object
     */
    public function setStats(array $stats): AbstractPlayer{
        $this->_stats = $stats;
        return $this;
    }
    
    /**
     * Getter for the player's skillset
     * @return SkillSet
     */
    public function getSkillSet(): SkillSet{
        return $this->_skillSet;
    }
    
    /**
     * Return the list of skills, as found in the current player's skillset
     * @return array
     */
    public function getSkills(): array{
        return $this->getSkillSet()->getSkills();
    }
    
    public function getUsedSkills(): array{
        $ret = array();
        $skills = $this->getSkills();
        if ($skills){
            foreach ($skills as $skill){
                if ($skill->isUsed()){
                    $ret[] = $skill->getName();
                }
            }
        }
        return $ret;
    }
    
    /**
     * Apply the current skillset to the current player
     * @return \Game\Players\AbstractPlayer The current object
     */
    public function useSkills(): AbstractPlayer{
        $this->getSkillSet()->useSkills($this);
        return $this;
    }
    
    /**
     * Reset stats to their previous values
     * @return \Game\Players\AbstractPlayer The current object
     */
    public function revertSkills(): AbstractPlayer{
        $this->getSkillSet()->revertSkills($this);
        return $this;
    }
    
    /**
     * Add a new skill in the player's skillset
     * @param AbstractSkill $skill
     * @return \Game\Players\AbstractPlayer The current object
     */
    public function addSkill(AbstractSkill $skill): AbstractPlayer{
        $this->getSkillSet()->addSkill($skill);
        return $this;
    }
    
    /**
     * Magic method to get defined properties
     * @param string $attr The property to return
     * @return mixed Depending of the property to return
     * @throws \Exception In case the property does not exist
     */
    public function __get(string $attr){
        if (method_exists($this, "get".ucfirst($attr))){
            return $this->{"get".ucfirst($attr)};
        } elseif (isset($this->_stats[$attr])){
            return $this->_stats[$attr];
        }
        if (property_exists($this, $attr)){
            return $this->$attr;
        }
        throw new \Exception("GET::Property not found: ".$attr);
    }
    
    /**
     * Magic method to set defined properties
     * @param string $attr The property to be updated
     * @param mixed $value The value to be set
     * @return \Game\Players\AbstractPlayer The current object
     * @throws \Exception In case the property does not exist
     */
    public function __set(string $attr, $value): AbstractPlayer{
        $canSet = false;
        if (method_exists($this, "set".ucfirst($attr))){
            $this->{"set".ucfirst($attr)};
            $canSet = true;
        } elseif (isset($this->_stats[$attr])){
            $this->_stats[$attr] = $value;
            $canSet = true;
        }
        if (property_exists($this, $attr)){
            $this->$attr = $value;
            $canSet = true;
        }
        if ($canSet){
            return $this;
        } else {
            throw new \Exception("SET::Property not found: ".$attr);
        }
    }
    
    /**
     * Magic method to call defined methods
     * @param string $method The method to call
     * @param array $params The list of arguments for the method to call
     * @return mixed The return type of the method to be called
     * @throws \Exception In case the method does not exist
     */
    public function __call(string $method, array $params){
        $method = strtolower($method);
        $prefix = substr($method, 0, 3);
        $attr = substr($method, 3);
        if ($prefix == "get"){
            return $this->__get($attr);
        } elseif ($prefix == "set"){
            return $this->__set($attr, $params[0]);
        } elseif (method_exists($this, $method)){
            return $this->$method($params);
        }
        throw new \Exception("CALL::Method not found: ".$method);
    }
    
    /**
     * Attack another player. Defined as multiple strikes (if the current stats allow is)
     * @param \Game\Players\AbstractPlayer $otherPlayer The player that is attacked
     * @return array A list of each strike's result
     */
    public function attack(AbstractPlayer $otherPlayer): array{
        $ret = [];
        $availableStrikes = $this->getAttackstrikes();
        for ($i = 0; $i < $availableStrikes; $i++){
            $ret[] = $this->strike($otherPlayer);
        }
        return $ret;
    }
    
    /**
     * Strike another player
     * @param \Game\Players\AbstractPlayer $otherPlayer The player that is attacked
     * @return float The damage dealt to the other player
     */
    private function strike(AbstractPlayer $otherPlayer): float{
        $luck = $otherPlayer->getLuck();
        $willMiss = (rand(0, 100) < $luck);
        if (!$willMiss){
            $damage = $this->getStrength() - $otherPlayer->getDefense();
            $damage *= $otherPlayer->getDamageImpact();
            if ($damage > 0){
                $remainingHealth = $otherPlayer->getHealth() - $damage;
                $otherPlayer->setHealth($remainingHealth);
                return $damage;
            }
        }
        return 0;
    }
    
    /**
     * Reset the current player's stats to default
     * @return \Game\Players\AbstractPlayer The current object
     */
    public function resetStats(): AbstractPlayer{
        $this->_stats = $this->_defaultStats;
        return $this;
    }
    
    /**
     * Check if a player is still alive
     * @return bool True if the player's health is above 0
     */
    public function isAlive(): bool{
        return $this->_stats["health"] > 0;
    }
    
}