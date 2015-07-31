<?php
  @session_start();
  
  unset($_SESSION['memberInfo']);
  
  header("Location: " . getenv('host'));
  exit;
?>