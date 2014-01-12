<?php
namespace AKlump\VerifyAge;

/**
 * Represents a session object.
 */
class Storage implements StorageInterface {
  
  public function init() {
  }
  
  public function set($key, $value) {
    $this->{$key} = $value;
  }

  public function get($key) {
    return isset($this->{$key}) ? $this->{$key} : NULL;
  }
}