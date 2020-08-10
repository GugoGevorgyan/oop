<?php
namespace app\models;
use vendor\src\Model;

class User extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->connect();
    }
  public function getPhoneCodes($phone){
      $this->select("");
      $this->from('country');
      $this->where(['con_type'=>'like','col_name'=>"{$phone}",'col_value'=>"concat(REPLACE( `phonecode`, ' ', '' ), '%') order by length(`phonecode`) desc limit 1"]);
      $this->get();
      return $this->resultToArray();
  }
  public function insertUser($data){
      return  $this->insert('users',$data);
  }
  public function generateToken()
  {
      $bytes = openssl_random_pseudo_bytes(32);
      return bin2hex($bytes);
  }

  public function checkUserToken($token, $email)
  {
       $this->select('id');
       $this->from('users');
       $this->where([
           'col_name'  => 'token',
           'col_value' => $token,
           'con_type'  => '='
       ]);
       $this->andWhere([
           'col_name'  => 'email',
           'col_value' => $email,
           'con_type'  => '='
       ]);
       $this->get();
       return $this->resultToArray();
  }

  public function verify($user_id)
  {
        $this->update('users', ['verify' => 1]);
      return  $this->set();
  }

  public function checkUserPass($mail)
  {
      $this->select(["password","verify"]);
      $this->from('users');
      $this->where(['con_type'=> "=",'col_name' => 'email',
          'col_value' => $mail]);
      $this->get();
      return $this->resultToArray();
  }
}