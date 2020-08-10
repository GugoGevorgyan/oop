<?php
namespace app\models;

use vendor\src\Model;

class Order extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->connect();
    }
}