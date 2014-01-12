<?php
namespace Aklump\VerifyAge;

interface StorageInterface {
  public function init();
  public function set($key, $value);
  public function get($key);
}