<?php
namespace AKlump\VerifyAge;

/**
 * Represents a session object.
 */
class Session implements StorageInterface {
  
  public function init() {
    if (!session_id()) {
      session_start();
    }
  }
  
  public function set($key, $value) {
    $_SESSION['VerifyAge'][$key] = $value;
  }

  public function get($key) {
    return isset($_SESSION['VerifyAge'][$key]) ? $_SESSION['VerifyAge'][$key] : NULL;
  }
}