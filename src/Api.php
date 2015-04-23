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
      $this->throw_api_error($api_name, "API 没有配置");
    }
    
    foreach ($api['params'] as $param) {
      if (!$params[$param]) {
        $this->throw_api_error($api_name, "参数 $param 缺失");
      }
    }
    
    switch(strtolower($api['method'])) {
      case "get":
        return $this->get($api['url'], $params);
        break;
      case "post":
        return $this->post($api['url'], $params);
        break;
      case "update":
        return $this->update($api['url'], $params);
        break;
      case "delete":
        return $this->delete($api['url'], $params);
        break;
      default:
        $this->throw_api_error($api_name, "配置选项 method 配置错误");
    }
  }
  
  public function get($url, $params) {
    $query = join('&', array_map(function($key, $value) {
      return "$key=$value";
    }, array_keys($params), array_values($params)));
   
    return $this->rest->get("$url?$query");
  }
  public function post($url, $params) {
    return $this->rest->post($url, $params);
  }
  public function update($url, $params) {
    return $this->rest->update($url, $params);
  }
  public function delete($url, $params) {
    return $this->rest->delete($url, $params);
  }
  
  protected function throw_api_error($api_name, $msg) {
    throw new Exception("API error: 调用API - $api_name 发生 $msg");
  }
}
