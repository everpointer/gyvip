<?php
namespace KMTK;

class Oracle {
  private $dbUserName;
  private $dbPassword;
  private $dbHost;
  private $dbPort;
  private $dbServiceName;
  private $dbCharset;
  private $conn;
  
  public function __construct() {
    $this->dbUserName = 'PAY';
    $this->dbPassword = 'pay123321';
    $this->dbHost     = '218.244.128.178';
    $this->dbPort     = '1521';
    $this->dbServiceName = 'clz';
    $this->charset    = 'utf8';
  }
  
  public function __destruct() {
    oci_close($this->conn);
  }
  
  public function connect() {
    $this->conn = oci_connect($this->dbUserName, $this->dbPassword,
                $this->dbHost . ':' . $this->dbPort . '/' . $this->dbServiceName,
                $this->charset); 
    return $this->conn;
  }
  
  // return assoc array
  public function select($sql) {
    $result = false;
    $stid = oci_parse($this->conn, $sql);
    if ($stid && oci_execute($stid)) {
      $rownums = oci_fetch_all($stid, $result, null, null, OCI_FETCHSTATEMENT_BY_ROW);
      if ($rownums > 0) {
        $result = $result[0];
      }
    }
    oci_free_statement($stid);
    return $result;
  }
  
  // return boolean
  public function exec($sql) {
    $result = false;
    $stid = oci_parse($this->conn, $sql);
    if ($stid) {
      $result = oci_execute($stid);
    } 
    oci_free_statement($stid);
    return $result;
  }
  
  public function error() {
    if (!$this->conn) {
      return oci_error();
    }
  }
  
  public function triggerError() {
    $e = $this->error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }
}

// KMTK 会员接口类
class Member {
  private $tableName;
  
  public function __construct() {
    $this->tableName = 'USER';
    
    $this->ora = new Oracle();
    $this->ora->connect();
  }
  
  public function queryUserByMobile($mobile) {
    $sql = 'SELECT * FROM "' . $this->tableName . '" where mobile=\''. $mobile .'\' and status=1';
    $result = $this->ora->select($sql);
    return $result;
  }
  
  public function error() {
    $this->ora->error();
  }
  public function triggerError() {
    $this->ora->triggerError();
  }
}