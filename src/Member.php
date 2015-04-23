<?php
namespace LyfMember;

class Member {
  private $config;
  
  public function __construct() {
    $this->api = new Api();
  }
  
  public function bind($params) {
    $result = $this->api->call('bind', $params);
    return $result;
  }
  
  public function register($params) {
    $result = $this->api->call('register', $params);
    return $result;
  }
}