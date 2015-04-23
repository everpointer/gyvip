<?php
namespace LyfMember;

class Member {
  private $user;
  private $config;
  
  function __construct($user) {
    $this->user = $user;
    $this->config = (require 'config.php');
  }
  
  function bind($mobile, $password)
  {
    $rest = new Rest(array("header" => $this->config["header"]));
    $params = "?username=$mobile&password=$password";
    $result = $rest->get($this->config['api']['bind']['url'] . $params);
    
    return $result;
  }
}