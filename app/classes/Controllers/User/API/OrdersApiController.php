<?php


namespace App\Controllers\User\API;


use App\App;
use App\Controllers\Base\UserController;
use Core\Api\Response;

class OrdersApiController extends UserController
{
    public function index()
    {
        $response = new Response();

        $orders = App::$db->getRowsWhere('orders', ['email' => App::$session->getUser()['email']]);

        $response->setData($orders);

        return $response->toJson();
    }


//    public function create(): string
//    {
//        // This is a helper class to make sure
//        // we use the same API json response structure
//        $response = new Response();
//        $form = new OrderCreateForm();
//
//        if ($form->validate()) {
//            App::$db->insertRow('orders', $form->values());
//        } else {
//            $response->setErrors($form->getErrors());
//        }
//
//        // Returns json-encoded response
//        return $response->toJson();
//    }

    public function create(): string
    {
        // This is a helper class to make sure
        // we use the same API json response structure
        $response = new Response();
        $id = $_POST['id'] ?? null;
        $user = App::$session->getUser();

        if ($id === null || $id == 'undefined') {
            $response->appendError('ApiController could not create, since ID is not provided! Check JS!');
        } else {
            $response->setData([
                'id' => $id
            ]);

            $pizza = App::$db->getRowById('pizzas', $id);

            App::$db->insertRow('orders', [
                'pizza_id' => $id,
//                'user name' => $user['user_name'],
                'status' => 'active',
                'pizza name' => $pizza['name'],
                'timestamp' => time(),
                'email' => $user['email']
            ]);
        }
        // Returns json-encoded response
        return $response->toJson();
    }


}