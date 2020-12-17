<?php

namespace App\Views\Tables\User;

use App\App;
use Core\Views\Table;

class OrderTable extends Table
{
    public function __construct($orders = [])
    {
         parent::__construct([
            'headers' => [
                'ID',
                'Pizza name',
                'Time Ago',
                'Status'
            ],
            'rows' => $orders
        ]);
    }
}