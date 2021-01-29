<?php   

class Archer extends Character 
{
    private $carquois;
    private $twoArrows = false;
    private $weakPoint = false;

    public function __construct($name) {
        parent::__construct($name);
        $this->carquois = rand(4, 7);
        $this->damage = 18;
    }

    public function turn($target) {
        $rand = rand(1, 10);
        if ($this->carquois == 0) {
            $status = $this->attack($target);
        } else if ($rand > 4 && !$this->twoArrows && !$this->weakPoint) {
            $status = $this->shootArrow($target, false);
        } else if ($rand <= 2 || $this->twoArrows && !$this->weakPoint) {
            $status = $this->shootTwoArrows($target);
        } else if ($rand <= 4 && $rand > 2 || $this->weakPoint) {
            $status = $this->aimWeakPoint($target);
        }
        return $status;
    }

    public function attack($target) {
        $target->setHealthPoints($this->damage*0.5);
        $status = "$this->name poignarde $target->name ! Il reste $target->healthPoints points de vie à $target->name !";
        return $status;
    }

    public function shootArrow($target, $weakPoint) {
        if ($weakPoint) {
            $rand = rand(15, 30)/10;
            $critic_damage = $this->damage * $rand;
            $target->setHealthPoints($critic_damage);
            $status = "$this->name tire une flèche sur le point faible de $target->name ! Il reste $target->healthPoints points de vie à $target->name !";
        } else {
            $target->setHealthPoints($this->damage);
            $status = "$this->name tire une flèche sur $target->name ! Il reste $target->healthPoints points de vie à $target->name !";
        } 
        $this->carquois -= 1;
        return $status;
    }

    public function shootTwoArrows($target) {
        if ($this->twoArrows) {
            $status = $this->shootArrow($target, false);
            $status = $status."<br>";
            $status = $status.$this->shootArrow($target, false);
            $this->twoArrows = false;
            return $status; 
        } else {
            $status = "$this->name se prépare à tirer deux flèches";
            $this->twoArrows = true;
            return $status;
        }
    }

    public function aimWeakPoint($target) {
        if ($this->weakPoint) {
            $status = $this->shootArrow($target, $this->weakPoint);
            $this->weakPoint = false;
            return $status;
        } else {
            $status = "$this->name vise le point faible de $target->name";
            $this->weakPoint = true;
            return $status;
        }
    }
}