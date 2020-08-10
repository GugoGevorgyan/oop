<?php
namespace vendor\src;
use app\models\User;

abstract class Controller
{
    protected $model;
    public function __construct($model_name) {
        $model = "\\app\\models\\$model_name";
//        echo "->"."<br>";
//        echo $model."<-"."<br>";
        $this->model = new $model();

    }

   abstract public function actionIndex();
}