<?php
/**
 *
 */
class Monkey {
    private $banana = null;
    private $name = "";
    function __construct(String $name, ?Banana $banana) {
        $this->name = $name;
        $this->banana = $banana;
    }

    public function hasBanana() :string {
        $message = $this->name . ' has ';
        if ($this->banana == null) {
            $message .= 'no banana';
        } else {
            $message .= '1 banana';
        }
        return $message .= '<br />';
    }

    public function getBanana(Banana $banana) {
        $this->banana = $banana;
    }

    public function giveBanana(Monkey $monkey) {
        if ($this->banana != null) {
            $monkey->getBanana($this->banana);
            $this->banana = null;

            return $this->name . ' gives a banana to ' . $monkey->get_name() . "<br />";
        }

        return $this->name . 'has no banana to give <br />';
    }

    public function get_name() {
        return $this->name;
    }

    public function getBananaInfo() {
        if ($this->banana != null) {
            return $this->banana->get_info();
        }
        return $this->name . ' has no banana <br />';
    }

    public function eatBanana() {
        if ($this->banana == null) {
            return $this->name . ' has no banana to eat <br />';
        }
        $this->banana = null;
        return $this->name . ' eats his banana <br />';
    }

    public function findBanana(Banana $banana) {
        $this->banana = $banana;
        return $this->name . ' finds a banana <br />';
    }
}

?>
