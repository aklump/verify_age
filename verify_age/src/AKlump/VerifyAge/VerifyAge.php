<?php
namespace AKlump\VerifyAge;
use \Symfony\Component\Yaml\Yaml;

/**
 * Represents an age verification solution.
 */
class VerifyAge {
  protected $return_path, $config_dir, $config_file, $config, $storage;

  /**
   * Constructor
   *
   * @param string $config_file
   *   (Optional, if previously set.) The relative path to the config file, from the
   *   the file in which this is being called. See $file.  If NULL we'll call
   *   $this->storage->get('config_file').
   * @param string $return_url
   *   (Optional.)The URL of the current page.  You may omit this
   *   and $_SERVER['REQUEST_URI'] will be used; if you have problems then
   *   use __FILE__ in the constructor like this: new VerifyAge(__FILE__, ...);
   *   The browser will redirect to this path on certain processes.
   * @param StorageInterface $session
   *   (Optional.) The session handling object.
   */
  public function __construct($config_file = NULL, $return_path = NULL, StorageInterface $storage = NULL) {
    // Start the session if not already running
    if ($storage === NULL) {
      $storage = new Session();
    }
    $this->storage = $storage;
    $this->storage->init();
    
    $this->return_path = $return_path;
    
    // Set or remember the last config file
    $this->config_file = $config_file === NULL ? $this->storage->get('config_file') : $config_file;
    $this->config_file = realpath($this->config_file);
    $this->storage->set('config_file', $this->config_file);

    $this->config = array();
    $this->getConfig();
  }

  public function verify() {
    $this->storage->set('status', 'verified');
  }

  public function deny() {
    $this->storage->set('status', 'denied');
  }

  public function getUrl($type = 'verify') {
    
    $base_path = $this->getConfig('base_path');
    $return    = $this->getConfig('return_path');

    switch ($type) {
      case 'verify':
        return "{$base_path}verify.php?o=1&r=$return";
      
      case 'deny':
        return "{$base_path}verify.php?o=2";
    }
  }

  public function isVerified() {
    return $this->storage->get('status') === 'verified' ? TRUE : FALSE;
  }

  /**
   * Return a status object for json transfer
   *
   * @return array
   */
  public function getStatus() {
    return (object) array(
      'verified' => $this->isVerified(),
      'status' => $this->storage->get('status') ? $this->storage->get('status') :'unverified', 
    );
  }

  public function setConfig($key, $value) {
    $this->config[$key] = $value;
  }

  /**
   * Return the contents of a file path
   */
  protected function getFileContents($path) {
    $path = $this->config['document_root'] . trim($path, '/');
    $path = trim(file_get_contents($path)) . PHP_EOL;

    return $path;
  }

  public function getConfig($key = NULL) {
    if (empty($this->config) && $this->config_file) {
      $yaml = file_get_contents($this->config_file);
      $this->config = yaml::parse($yaml);

      // This is the file path to the webroot of the website this is
      // installed in.
      if (empty($this->config['document_root'])) {
        $this->config['document_root'] = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/';
      }

      // This is the url to access this package files; it is used to build
      // urls to files such as verify.php      
      if (empty($this->config['base_path'])) {
        $this->config['base_path'] = '/verify_age/';
      }

      // Determine the return path
      if ($this->return_path === NULL && isset($_SERVER['REQUEST_URI'])) {
        $return = $_SERVER['REQUEST_URI'];
      }
      else {
        // This handles when __FILE__ is used to remove doc root
        $return = str_replace(rtrim($this->config['document_root'], '/'), '', $this->return_path);
      }

      $this->config['return_path'] = $return;
    }

    if ($key === NULL) {
      return $this->config;
    }

    return isset($this->config[$key]) ? $this->config[$key] : NULL;
  }

  public function getHead() {
    $base_path = $this->getConfig('base_path');
    $head = array();
    if ($this->getConfig('jquery_cdn')) {
      $head[] = '<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>';
    }
    $head[] = '<script src="' . $base_path . 'scripts/verify_age.min.js"></script>';

    
    // Generate dynamic CSS
    $width = $this->getConfig('width');
    $half_width = round($width / 2);
    
    $height = $this->getConfig('height');
    $half_height = round($height / 2);

    $overlay = $this->getConfig('overlay');

    $head[] = <<<EOD
<style type="text/css" media="all">@import url("{$base_path}stylesheets/verify_age.css");.verify-age-background{background-color:{$overlay};}.verify-age-dialog{width:{$width}px;height:{$height}px;margin-top:-{$half_height}px;margin-left:-{$half_width}px;}</style>
EOD;

    return implode(PHP_EOL, $head) . PHP_EOL;
  }

  /**
   * Return markup for the HTML body
   *
   * @param  string $template NULL|enter|exit|403
   *   If NULL, then body will be returned based on status
   *
   * @return [type]           [description]
   */
  public function getBody($template = NULL) {
    if ($template === NULL) {
      $status = $this->getStatus();
      $template = $status->status;
    }

    $body = '';
    switch ($template) {
      case 'verified':
        if (($file = $this->getConfig('snippet_exit'))
          && ($file = $this->getFileContents($file))) {
          $body = $file;
        }
        break;

      case '403':
        if (($file = $this->getConfig('snippet_403'))
          && ($file = $this->getFileContents($file))) {
        $body = $file;
        }        
        break;

      case 'unverified':
      default;      
        if (($file = $this->getConfig('snippet_enter'))
          && ($file = $this->getFileContents($file))) {
        $body = $file;
        }
        break;

    }
    
    if ($body) {
      // twig style replacements of urls
      $tokens = array(
        'deny.href' => $this->getUrl('deny'),
        'verify.href' => $this->getUrl('verify'),
        'min_age' => $this->getConfig('min_age'), 
      );
      foreach ($tokens as $token => $value) {
        $body = preg_replace('/{{ ' . (preg_quote($token)) . ' }}/', $value, $body);
      }    
    }

    return $body;
  }
}