<?php
namespace LyfMember;
require_once 'Rest.php';

use Exception;

class Api {
  private $config;
  
  public function __construct() {
    $this->config = (require 'config.php');
    $this->rest = new Rest(array("header" => $this->config["header"]));
    if (isset($config['kmtk']['debug']) && $config['kmtk']['debug'] == true) {
      $this->debug = true;   
    } else {
      $this->debug = false;
    }

  }
  
  public function call($api_name, $params) {
    $api = $this->config["api"][$api_name];
    if (!$api) {
      $this->throw_api_error($api_name, "API 没有配置");
    }
    $api['name'] = $api_name;
    return $this->_call($api, $params);
  }
  // only for leancloud
  public function callExtUrl($api_name, $params, $ext) {
    $api = $this->config["api"][$api_name];
    if (!$api) {
      $this->throw_api_error($api_name, "API 没有配置");
    }
    // url is a format string
    $api['url'] = sprintf($api['url'], $ext);
    $api['name'] = $api_name;
    return $this->_call($api, $params);
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
  public function put($url, $params) {
    return $this->rest->set($url, $params);
  }
  public function delete($url, $params) {
    return $this->rest->delete($url, $params);
  }
  
  protected function throw_api_error($api_name, $msg) {
    throw new Exception("API error: 调用API - $api_name 发生 $msg");
  }
  
  private function _call($api, $params) {
    $api_name = $api['name'];
    foreach ($api['params'] as $param) {
      if (!isset($params[$param])) {
        $this->throw_api_error($api_name, "参数 $param 缺失");
      }
    }
    
    if (isset($api['optionParams']) && !empty($api['optionParams'])) {
      $hasOptionParam = false;
      foreach ($api['optionParams'] as $param) {
        if (isset($params[$param])) {
          $hasOptionParam = true;
        }
      }
      
      if (!$hasOptionParam) {
        $this->throw_api_error($api_name, "可选参数缺失, 至少填写一个");
      }
    }
    
    switch(strtolower($api['method'])) {
      case "get":
        return $this->get($api['url'], $params);
        break;
      case "post":
        return $this->post($api['url'], $params);
        break;
      case "put":
        return $this->put($api['url'], $params);
        break;
      case "delete":
        return $this->delete($api['url'], $params);
        break;
      default:
        $this->throw_api_error($api_name, "配置选项 method 配置错误");
    }
  }
  
}
