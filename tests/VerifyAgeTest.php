<?php
/**
 * @file
 * Tests for the VerifyAge class
 *
 * @ingroup verify_age
 * @{
 */

use \AKlump\VerifyAge;
require_once '../vendor/autoload.php';

class VerifyAgeTest extends PHPUnit_Framework_TestCase {

  public function testIgnores() {
    $storage = new VerifyAge\Storage();
    $age = new VerifyAge\VerifyAge('../config_default.yaml', '/', $storage);
    $age->setConfig('document_root', '../');    
    $age->ignore('/underage.php');
    $age->ignore('/underage.php');
    $age->ignore(array('/alpha.html', '/bravo.html'));
    $this->assertCount(3, $age->getConfig('ignores'));

    $this->assertFalse($age->isIgnored());
    $this->assertNotEmpty($age->getBody());

    $age = new VerifyAge\VerifyAge(NULL, '/underage.php', $storage);
    $age->setConfig('document_root', '../');
    $this->assertTrue($age->isIgnored());
    $this->assertCount(3, $age->getConfig('ignores'));

    $age = new VerifyAge\VerifyAge(NULL, '/alpha.html', $storage);
    $age->setConfig('document_root', '../');
    $this->assertTrue($age->isIgnored());
    $this->assertCount(3, $age->getConfig('ignores'));

    $age = new VerifyAge\VerifyAge(NULL, '/bravo.html', $storage);
    $age->setConfig('document_root', '../');
    $this->assertTrue($age->isIgnored());
    $this->assertCount(3, $age->getConfig('ignores'));
    $this->assertEmpty($age->getBody());

    $age->setConfig('ignores', array('/charlie.html'));
    $this->assertEquals(array('/charlie.html', '/underage.php', '/alpha.html', '/bravo.html'), $age->getConfig('ignores'));
  }

  public function testReturnPath() {
    $age = new VerifyAge\VerifyAge('../config_default.yaml', '/', new VerifyAge\Storage());
    $this->assertSame('/', $age->getConfig('current_page'));

    $age = new VerifyAge\VerifyAge('../config_default.yaml', '/index.php', new VerifyAge\Storage());
    $this->assertSame('/index.php', $age->getConfig('current_page'));

    $age = new VerifyAge\VerifyAge('../config_default.yaml', '/index', new VerifyAge\Storage());
    $this->assertSame('/index', $age->getConfig('current_page'));
  }

  public function test403() {
    $age = new VerifyAge\VerifyAge('../config_default.yaml', '/', new VerifyAge\Storage());
    $age->setConfig('document_root', '../');
    $this->assertSame('/', $age->getConfig('url_403'));
  }

  public function testGetUrl() {
    $age = new VerifyAge\VerifyAge('../config_default.yaml', '/', new VerifyAge\Storage());
    $age->setConfig('document_root', '../');

    // Verify url
    $urls = array(
      $age->getUrl(),
      $age->getUrl('verify'),
    );
    foreach ($urls as $url) {
      $parts = parse_url($url);
      $this->assertEquals('/verify.php', $parts['path']);
      parse_str($parts['query'], $query);
      $this->assertEquals(1, $query['o']);
    }

    // unVerify url
    $url = $age->getUrl('deny');
    $parts = parse_url($url);
    $this->assertEquals('/verify.php', $parts['path']);
    parse_str($parts['query'], $query);
    $this->assertEquals(2, $query['o']);

    $age->setConfig('base_path', '/packages/php/verify_age/demo/verify_age/');

    // Verify url
    $urls = array(
      $age->getUrl(),
      $age->getUrl('verify'),
    );
    foreach ($urls as $url) {
      $parts = parse_url($url);
      $this->assertEquals('/packages/php/verify_age/demo/verify_age/verify.php', $parts['path']);
      parse_str($parts['query'], $query);
      $this->assertEquals(1, $query['o']);
    }

    // unVerify url
    $url = $age->getUrl('deny');
    $parts = parse_url($url);
    $this->assertEquals('/packages/php/verify_age/demo/verify_age/verify.php', $parts['path']);
    parse_str($parts['query'], $query);
    $this->assertEquals(2, $query['o']);    
  }

  public function testBody() {
    $age = new VerifyAge\VerifyAge('../config_default.yaml', '/', new VerifyAge\Storage());
    $age->setConfig('document_root', '../');
    $age->setConfig('url_403', '/underage.php');

    // Body text popup when not verified
    $control = <<<EOD
<div class="verify-age unverified verify-age-background">
  <div class="verify-age-dialog">
    <div class="verify-age-inner">
      <h1>Are you over 21?</h1>
      <p>
        <a class="verify-age-exit" href="/verify.php?o=2" rel="nofollow">No - Leave</a>
        <a class="verify-age-enter" href="/verify.php?o=1&amp;r=/" rel="nofollow">Yes - Enter</a>
      </p>
      <div class="verify-age-ajaxing">One moment...</div>
    </div>
  </div>
</div>

EOD;
    $this->assertEquals($control, $age->getBody());

    // No body text when verified
    $age->verify();

    // Body exit text when verified
    $control = <<<EOD
<div class="verify-age verified">
  <p><a class="verify-age-exit" href="/verify.php?o=2" rel="nofollow">I'm not yet 21, get me out of here.</a></p>
  <div class="verify-age-ajaxing">One moment...</div>
</div>

EOD;
    $this->assertEquals($control, $age->getBody());

    $age->setConfig('snippet_exit', '');
    $this->assertEmpty($age->getBody());
  }

  public function testHead() {
    $age = new VerifyAge\VerifyAge('../config_default.yaml', '/', new VerifyAge\Storage());
    $age->setConfig('document_root', '../');
    $age->setConfig('optional_css', TRUE);
    $age->setConfig('width', 200);
    $age->setConfig('height', 200);
    $age->setConfig('overlay', '#000');

    $control = array(
      '<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>',
      '<script type="text/javascript" src="/js/verify_age.min.js"></script>',
      '<style type="text/css" media="all">@import url("/css/verify_age.css");@import url("/css/verify_age_optional.css");.verify-age-background{background-color:#000;}.verify-age-dialog{width:200px;height:200px;margin-top:-100px;margin-left:-100px;}</style>',
    );
    $this->assertEquals(implode(PHP_EOL, $control) . PHP_EOL, $age->getHead());

    $control = array(
      '<script type="text/javascript" src="/js/verify_age.min.js"></script>',
      '<style type="text/css" media="all">@import url("/css/verify_age.css");@import url("/css/verify_age_optional.css");.verify-age-background{background-color:#000;}.verify-age-dialog{width:200px;height:200px;margin-top:-100px;margin-left:-100px;}</style>',
    );
    $age->setConfig('jquery_cdn', FALSE);
    $this->assertEquals(implode(PHP_EOL, $control) . PHP_EOL, $age->getHead());

    $control = array(
      '<script type="text/javascript" src="/js/verify_age.min.js"></script>',
      '<style type="text/css" media="all">@import url("/css/verify_age.css")</style>',
    );
    $age->setConfig('optional_css', FALSE);

    $this->assertEquals(implode(PHP_EOL, $control) . PHP_EOL, $age->getHead());

  }

  public function testConfig() {
    $age = new VerifyAge\VerifyAge('../config_default.yaml', '/', new VerifyAge\Storage());
    $age->setConfig('document_root', '../');

    $config = $age->getConfig();
    $this->assertNotEmpty($config['snippet_enter']);
    $this->assertNotEmpty($config['snippet_exit']);
    $this->assertNotEmpty($config['url_403']);
    $this->assertNotEmpty($config['width']);
    $this->assertNotEmpty($config['height']);
    $this->assertNotEmpty($config['overlay']);

    $this->assertTrue($age->getConfig('jquery_cdn'));
    $age->setConfig('jquery_cdn', FALSE);
    $this->assertFalse($age->getConfig('jquery_cdn'));
  }
  
  public function testVerify() {
    $age = new VerifyAge\VerifyAge('../config_default.yaml', '/', new VerifyAge\Storage());
    $age->setConfig('document_root', '../');

    $status = $age->getStatus();
    $this->assertFalse($age->isVerified());
    $this->assertEquals('unverified', $status->status);
    $this->assertFalse($status->verified);
    
    $age->verify();
    $status = $age->getStatus();
    $this->assertTrue($age->isVerified());
    $this->assertEquals('verified', $status->status);
    $this->assertTrue($status->verified);

    $age->deny();
    $status = $age->getStatus();
    $this->assertFalse($age->isVerified());
    $this->assertEquals('denied', $status->status);
    $this->assertFalse($status->verified);    
  }
}

