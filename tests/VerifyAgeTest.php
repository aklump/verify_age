<?php
/**
 * @file
 * Tests for the VerifyAge class
 *
 * @ingroup verify_age
 * @{
 */

use \AKlump\VerifyAge;
require_once '../verify_age/vendor/autoload.php';

class VerifyAgeTest extends PHPUnit_Framework_TestCase {

  public function test403() {
    $age = new VerifyAge\VerifyAge('../verify_age/config_default.yaml', '/', new VerifyAge\Storage());
    $age->setConfig('document_root', '../');
    $this->assertSame('/underage.html', $age->getConfig('url_403'));
  }

  public function testBody() {
    $age = new VerifyAge\VerifyAge('../verify_age/config_default.yaml', '/', new VerifyAge\Storage());
    $age->setConfig('document_root', '../');
    $age->setConfig('url_403', '/underage.html');

    // Verify url
    $urls = array(
      $age->getUrl(),
      $age->getUrl('verify'),
    );
    foreach ($urls as $url) {
      $parts = parse_url($url);
      $this->assertEquals('/verify_age/verify.php', $parts['path']);
      parse_str($parts['query'], $query);
      $this->assertEquals(1, $query['s']);
    }

    // unVerify url
    $url = $age->getUrl('deny');
    $parts = parse_url($url);
    $this->assertEquals('/verify_age/verify.php', $parts['path']);
    parse_str($parts['query'], $query);
    $this->assertEquals(2, $query['s']);

    // Body text popup when not verified
    $control = <<<EOD
<div class="verify-age background"></div>
<div class="verify-age popup">
  <h1>Are you over 21?</h1>
  <p><a class="verify-age no" href="/verify_age/verify.php?s=2&r=/">No</a></p>
  <p><a class="verify-age yes" href="/verify_age/verify.php?s=1&r=/">Yes</a></p>
</div>

EOD;
    $this->assertEquals($control, $age->getBody());

    // No body text when verified
    $age->verify();

    // Body exit text when verified
    $control = <<<EOD
<p><a class="verify-age no" href="/verify_age/verify.php?s=2&r=/">I am not 21, get me out of here.</a></p>

EOD;
    $this->assertEquals($control, $age->getBody());

    $age->setConfig('snippet_exit', '');
    $this->assertEmpty($age->getBody());
  }

  public function testHead() {
    $age = new VerifyAge\VerifyAge('../verify_age/config_default.yaml', '/', new VerifyAge\Storage());
    $age->setConfig('document_root', '../');

    $control = array(
      '<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>',
      '<script src="/verify_age/scripts/verify_age.min.js"></script>',
      '<style type="text/css" media="all">@import url("/verify_age/stylesheets/verify_age.css");</style>',
    );
    $this->assertEquals(implode(PHP_EOL, $control) . PHP_EOL, $age->getHead());

    $control[2] = '<style type="text/css" media="all">@import url("/age-check-styles.css");</style>';
    $age->setConfig('css', '/age-check-styles.css');
    $this->assertEquals(implode(PHP_EOL, $control) . PHP_EOL, $age->getHead());

    $age->setConfig('jquery_cdn', FALSE);
    $age->setConfig('css', '');
    $this->assertEquals($control[1] . PHP_EOL, $age->getHead());
  }

  public function testConfig() {
    $age = new VerifyAge\VerifyAge('../verify_age/config_default.yaml', '/', new VerifyAge\Storage());
    $age->setConfig('document_root', '../');

    $config = $age->getConfig();
    $this->assertNotEmpty($config['snippet_enter']);
    $this->assertNotEmpty($config['snippet_exit']);
    $this->assertNotEmpty($config['url_403']);
    $this->assertNotEmpty($config['css']);
    $this->assertNotEmpty($config['width']);
    $this->assertNotEmpty($config['height']);
    $this->assertNotEmpty($config['background']);

    $this->assertTrue($age->getConfig('jquery_cdn'));
    $age->setConfig('jquery_cdn', FALSE);
    $this->assertFalse($age->getConfig('jquery_cdn'));
  }
  
  public function testVerify() {
    $age = new VerifyAge\VerifyAge('../verify_age/config_default.yaml', '/', new VerifyAge\Storage());
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

