<?php
namespace vendor\src;

abstract class DBManager
{
    protected $name;
    protected $host;
    protected $user;
    protected $password;
    protected $conn;
    public function __construct($host, $name, $password, $user)
    {
        $this->name = $name;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }
  abstract  public function connect();
}