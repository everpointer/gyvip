<?php
return array(
  'header' => array(
    'X-AVOSCloud-Application-Id: 0s4hffciblz94hah0m63rsn0x970m2obrjthz0cwmqwsipdy',
    'X-AVOSCloud-Application-Key: 0b7jsd5h44y4wcv6w4w0alomwmpwufx8odk3irmvk36q2g10'  
  ),
  'api' => array(
    'bind' => array(
      'method' => 'update',
      'url' => 'https://leancloud.cn/1.1/login',
      'params' => array('username', 'password', 'uid')
    ),
    'register' => array(
      'method' => 'post',
      'url' => 'https://api.leancloud.cn/1.1/users',
      'params' => array('username', 'password', 'uid')
    )
  )
);
