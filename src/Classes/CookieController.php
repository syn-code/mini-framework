<?php

namespace ShoppingCart\Classes;

use ShoppingCart\Factory\RequestFactory;
use ShoppingCart\Request\Request;
use ShoppingCart\Enums\DirectoryEnum;
use ShoppingCart\Traits\SetCookie;

class CookieController
{
    use SetCookie;

    private array $cartCollection = [
        'total_items' => 0,
        'cart_total' => 0.00,
        'cart_items' => [],
    ];


    public function registerCookie($shoppingCart = [])
    {
        $request = RequestFactory::makeRequest();
        $shoppingCart = json_decode($request->getRequestParameters()['shopping_items'], true);

        if (isset($shoppingCart)) {

            foreach ($shoppingCart as $item) {
                $price = str_replace('£', '', $item['price']);
                $this->cartCollection['cart_total'] += $price;
                $this->cartCollection['cart_items'][] = [
                    'type' => $item['type'],
                    'price' => $item['price'],
                    'colour' => $item['colour'],
                    'material' => $item['material'],
                ];
            }
        }

        if (isset($this->cartCollection)) {
            $this->returnCookieSuccess();
        }

    }

    private function returnCookieSuccess()
    {
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json');
        http_response_code(200);

        $this->setCookie('shopping_list', $this->cartCollection, time()+3600);

        $jsonData = json_encode(['message' => 'Shopping Cart Saved']);

        echo $jsonData;

        exit;
    }


}