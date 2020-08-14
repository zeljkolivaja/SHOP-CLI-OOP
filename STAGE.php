<?php

class STAGE
{

    public $stage;
    private static $_instance = null;

    private function __construct($stage = null)
    {
        $this->stage = $stage;
    }

    public function END()
    {
        if ($this->stage !== null && $this->stage == 1) {
            $this->stage = 2;
        } else {
            $this->stage = 1;
        }
    }

    public static function getInstance($stage = null)
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Stage($stage);
        }
        return self::$_instance;
    }

}