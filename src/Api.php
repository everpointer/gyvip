<?php
namespace LyfMember;

use Exception;

class Api {
  private $config;
  
  public function __construct() {
    $this->config = (require 'config.php');
    $this->rest = new Rest(array("header" => $this->config["header"]));
  }
  
  public function call($api_name, $params) {
    $api = $this->config["api"][$api_name];
    if (!$api) {
      throw_api_error($api_name, "API 没有配置");
    }
    
    foreach ($api['params'] as $param) {
      if (!$params[$param]) {
        throw_api_error($api_name, "参数 $param 缺失");
      }
    }
    
    switch(strtolower($api['method'])) {
      case "get":
        return $this->get($api['url'], $params);
        break;
      default:
        throw_api_error($api_name, "配置选项 method 配置错误");
    }
  }
  
  public function get($url, $params) {
    $query = join('&', array_map(function($key, $value) {
      return "$key=$value";
    }, array_keys($params), array_values($params)));
   
    return $this->rest->get("$url?$query");
  }
  
  protected function throw_api_error($api_name, $msg) {
    throw new Exception("API error: 调用API - $api_name 发生 $msg");
  }
}
