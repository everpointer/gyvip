<?php
namespace LyfMember;

class Member {
  private $user;
  
  function __construct($user) {
    $this->user = $user;
  }
  
  function bind($mobile, $password)
  {
    $rest = new Rest(
      array("header" => array(
        "X-AVOSCloud-Application-Id: 0s4hffciblz94hah0m63rsn0x970m2obrjthz0cwmqwsipdy",
        "X-AVOSCloud-Application-Key: 0b7jsd5h44y4wcv6w4w0alomwmpwufx8odk3irmvk36q2g10"
      ))
    );
    $params = "username=$mobile&password=$password";
    $result = $rest->get("https://leancloud.cn/1.1/login?" . $params);
    
    return $result;
  }
}