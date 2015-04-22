<?php
namespace LyfMember;

class User {
  private $uid;
  private $platform;
  
  function __construct($uid, $platform) {
    $this->uid = $uid;
    $this->platform = $platform;
  }
}