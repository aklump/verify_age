<?php
/**
 * @file
 * Registers a user as age verified and either) redirects or returns json
 *
 * @ingroup verify_age
 * @{
 */
require_once('vendor/autoload.php');
$age      = new AKlump\VerifyAge\VerifyAge(__FILE__);
$config   = $age->getConfig();
$redirect = isset($_REQUEST['r']) ? $_REQUEST['r'] : FALSE;
$json     = isset($_REQUEST['s']) && !$redirect;
$op       = isset($_REQUEST['s']) ? $_REQUEST['s'] * 1 : NULL;
$response = array();

if ($op) {
  switch ($op) {
    case 1:
      $age->verify();
      break;

    case 2:
      $age->deny();
      break;

    case 3:
      $response['mode'] = 'inquiry';
      $json = TRUE;
      break;
  }
}

// https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag
header('X-Robots-Tag: noindex');

// Redirect for non-js browsers
if ($json) {
  header('Content-Type: application/json');
  $response += (array) $age->getStatus();
  
  // If we send any value for $response->redirect the ajax will
  // redirect the user; we only do that for failures at this time
  if ($age->isVerified()) {
    $response['replaceWith'] = $age->getBody('verified');
  }
  else {
    $response['redirect'] = $redirect;
  }
  print json_encode((object)$response);
}

// Redirection
elseif ($redirect) {
  header('Location:' . (string) $redirect);
}

// Not found
else {
  header('HTTP/1.0 403 Not Found');
  print $age->getBody('403');
}

exit();