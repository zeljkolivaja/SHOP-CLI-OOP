<?php

class PRODUCTS
{

    public $stage;

    public $productsArray = [];

    private static $_instance = null;

    private function __construct($stage = null)
    {
        $this->stage = $stage;
    }

    public function productExist($sku)
    {
        if (isset($this->productsArray[$sku])) {
            return true;
        }
        return false;
    }

    public function ADD($sku, $name = null, $quantity, $price = null)
    {

        $newProduct = ["sku" => $sku, "name" => $name, "quantity" => $quantity, "price" => $price];
        $this->productsArray[$sku] = $newProduct;

    }

    public static function getInstance($stage = null)
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new PRODUCTS;
        }
        return self::$_instance;
    }

}