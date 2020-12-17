<?php


namespace App\Controllers\User\API;


use App\App;
use App\Controllers\Base\UserController;
use Core\Api\Response;

class OrdersApiController extends UserController
{
    private function timeStampResult($row)
    {
        $timeStamp = date('Y-m-d H:i:s', $row['timestamp']);
        $difference = abs(strtotime("now") - strtotime($timeStamp));
        $days = floor($difference / (3600 * 24));
        $hours = floor($difference / 3600);
        $minutes = floor(($difference - ($hours * 3600)) / 60);
        $seconds = floor($difference % 60);

        if ($days) {
            $hours = $hours - 24;
            $result = "{$days}d {$hours}:{$minutes} H";
        } elseif ($minutes) {
            $result = "{$minutes} min";
        } elseif ($hours) {
            $result = "{$hours}:{$minutes} H";
        } else {
            $result = "{$seconds} seconds";
        }

        return $result;
    }

    public function index()
    {
        $response = new Response();

        $orders = App::$db->getRowsWhere('orders', ['email' => App::$session->getUser()['email']]);

        foreach ($orders as $id => &$row) {

            $row = [
                'id' => $id,
                'name' => $row['pizza_name'],
                'timestamp' => $this->timeStampResult($row),
                'status' => $row['status']
            ];
        }

        $response->setData($orders);

        return $response->toJson();
    }


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
                'user_name' => $user['user_name'],
                'status' => 'active',
                'pizza_name' => $pizza['name'],
                'timestamp' => time(),
                'email' => $user['email']
            ]);
        }
        // Returns json-encoded response
        return $response->toJson();
    }


}