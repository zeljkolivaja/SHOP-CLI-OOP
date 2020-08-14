<?php

include 'STAGE.php';
include 'PRODUCTS.php';
include 'SHOPPINGCART.php';

$productsObj = PRODUCTS::getInstance();
$shoppingCartObj = SHOPPINGCART::getInstance();
$stage = STAGE::getInstance();

//loop the application while the $stage != 2
do {

    //get the user input and extract it to array so we can assign it to parameters
    $selection = fgets(STDIN);
    $arguments = explode(" ", $selection);
    $command = trim($arguments[0]);
    $sku = isset($arguments[1]) ? trim($arguments[1]) : null;

    /*if the user sent ADD command and the if the $stage == null it means we are
    in the adding products stage, and the user is allowed to enter products */

    if ($command == "END") {

        $stage->END();

    } else if ($command == "ADD" && $stage->stage == null) {

        if (count($arguments) !== 5) {
            echo "wrong number of parameters";
            echo "\n";

        } else {

            $name = isset($arguments[2]) ? trim($arguments[2]) : null;
            $quantity = isset($arguments[3]) ? trim($arguments[3]) : null;
            $price = isset($arguments[4]) ? trim($arguments[4]) : null;

            $productExist = $productsObj->productExist($sku);
            echo $productExist;

            if ($productExist == false) {

                $productsObj->ADD($sku, $name, $quantity, $price);

            } else {
                echo "product already exists";
                echo "\n";
            }

        }

        /* when the user enters END command the first time he is sent to shopping cart stage
    when the user enters END command second time app is closed */
    }
    /* if the user enters ADD command while in shopping cart stage (1) he is adding products
    to the shopping cart */

    elseif ($command == "ADD" && $stage->stage == 1) {

        if (count($arguments) !== 3) {
            echo "wrong number of parameters";
            echo "\n";
        } else {
            $quantity = isset($arguments[2]) ? trim($arguments[2]) : null;
        }

        /* first check do we have enough products then
        if the shopping cart is empty add the product to it,
        if its not empty check does the product already exists, if it does update its quantity,
        if the product is not found within shopping cart insert it */

        $productExist = $productsObj->productExist($sku);

        if ($productExist == false) {
            echo "requested product does not exist";
            echo "\n";

        } elseif ($productsObj->productsArray[$sku]["quantity"] - $quantity >= 0) {

            if (empty($shoppingCartObj->shoppingArray)) {

                $shoppingCartObj->ADD($sku, $quantity);

            } else {

                $skuS = array_column($shoppingCartObj->shoppingArray, 'sku');

                if (in_array($sku, $skuS)) {

                    $tempProductNumber = $shoppingCartObj->shoppingArray[$sku]["quantity"] + $quantity;
                    if ($tempProductNumber > $productsObj->productsArray[$sku]["quantity"]) {
                        echo "not enough products availible";
                    } else {

                        $newQUantity = $shoppingCartObj->shoppingArray[$sku]["quantity"] + $quantity;
                        $shoppingCartObj->shoppingArray[$sku]["quantity"] = $newQUantity;
                    }

                } else {
                    echo "tu sam";
                    $shoppingCartObj->ADD($sku, $quantity);
                    var_dump($shoppingCartObj);

                }
            }
        } else {
            echo "not enough products availible";
            echo "\n";
        }

        /* if the command REMOVE is entered ($stage must be 1) we check does the product exist in
    the  shopping cart if it do we update its quantity, if it doesnt exist we display the error,
    if the user is trying to remove more products then he added we set the quantity to 0  */
    } elseif ($command == "REMOVE" && $stage->stage == 1) {

        if (count($arguments) !== 3) {
            echo "wrong number of parameters";
            echo "\n";
        }

        $quantity = isset($arguments[2]) ? trim($arguments[2]) : null;
        $skuS = array_column($shoppingCartObj->shoppingArray, 'sku');

        if (in_array($sku, $skuS)) {
            $newQUantity = $shoppingCartObj->shoppingArray[$sku]["quantity"] - $quantity;
            if ($newQUantity <= 0) {
                $newQUantity = 0;
            }

            $shoppingCartObj->shoppingArray[$sku]["quantity"] = $newQUantity;

        } else {
            echo "No such product in the shopping cart";
            echo "\n";
        }

        /* if the command is CHECKOUT and the stage==1 we get all the products from the shopping cart,
    then we match them with products object to get the rest of the data, we calculate the bill
    and empty the shopping cart   */
    } elseif ($command == "CHECKOUT" && $stage->stage == 1) {

        foreach ($shoppingCartObj->shoppingArray as $key => $value) {

            $quantity = $value["quantity"];

            $productSKU = $value["sku"];

            $name = $productsObj->productsArray[$productSKU]["name"];

            $price = $productsObj->productsArray[$productSKU]["price"];

            $newProductsQuantity = $productsObj->productsArray[$productSKU]["quantity"] - $quantity;

            $productsObj->productsArray[$productSKU]["quantity"] = $newProductsQuantity;

            $productPrice = $quantity * $price;

            if ($quantity > 0) {
                echo $name . " " . $price . " * " . $quantity . " = " . $productPrice;
                echo "\n";
            }

        }

        $shoppingCartObj->shoppingArray = [];

    } else {

        echo "please insert valid command";
        echo "\n";
    }

} while (strcmp($stage->stage, "2") !== 0);

exit(0);