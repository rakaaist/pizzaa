<?php


namespace App\Controllers\User\API;


use App\App;
use App\Views\Forms\Admin\Order\OrderCreateForm;
use Core\Api\Response;
use Core\Session;

class OrdersApiController
{
    public function index()
    {
        $response = new Response();

        $orders = App::$db->getRowsWhere('orders', ['email' => App::$session->getUser()['email']]);

        $response->setData($orders);

        return $response->toJson();
    }


    public function create(): string
    {
        // This is a helper class to make sure
        // we use the same API json response structure
        $response = new Response();
        $form = new OrderCreateForm();

        if ($form->validate()) {
            App::$db->insertRow('orders', $form->values());
        } else {
            $response->setErrors($form->getErrors());
        }

        // Returns json-encoded response
        return $response->toJson();
    }
}