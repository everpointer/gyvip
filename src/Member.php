<?php
namespace LyfMember;

class Member {
  private $user;
  private $config;
  
  function __construct($user) {
    $this->user = $user;
    $this->api = new Api();
  }
  
  function bind($mobile, $password)
  {
    $params = array("username" => $mobile, "password" => $password);
    $result = $this->api->call('bind', $params);
    
    return $result;
  }
}