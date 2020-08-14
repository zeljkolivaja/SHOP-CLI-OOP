<?php

class SHOPPINGCART
{

    public $stage;

    public $shoppingArray = [];

    private static $_instance = null;

    private function __construct($stage = null)
    {
        $this->stage = $stage;
    }

    public function productExist($sku)
    {
        if (isset($this->shoppingArray[$sku])) {
            return true;
        }
        return false;
    }

    public function ADD($sku, $quantity)
    {

        $newProduct = ["sku" => $sku, "quantity" => $quantity];
        $this->shoppingArray[$sku] = $newProduct;

    }

    public static function getInstance($stage = null)
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new SHOPPINGCART;
        }
        return self::$_instance;
    }

}