<?php
/**
 * @file
 * Registers a user as age verified and either) redirects or returns json
 *
 * @ingroup verify_age
 * @{
 */
require_once('vendor/autoload.php');
$age = new AKlump\VerifyAge\VerifyAge('user/config.yaml', __FILE__);
$config   = $age->getConfig();
$redirect = isset($_REQUEST['r']) ? $_REQUEST['r'] : FALSE;

if (!isset($_REQUEST['s'])) {
  $redirect = $age->getConfig('url_403');
}
else {
  switch ($_REQUEST['s'] * 1) {
    case 1:
      $age->verify();
      break;
    
    default:
      $age->deny();
      $redirect = $age->getConfig('url_403');
      break;
  }
}

// Redirect for non-js browsers
if ($redirect) {
  header('Location:' . (string) $redirect);
}

// Print out JSON
else {
  header('Content-Type: application/json');
  $response = $age->getStatus();
  
  // If we send any value for $response->redirect the ajax will
  // redirect the user; we only do that for failures at this time
  if ($age->isVerified()) {
    $response->replaceWith = $age->getBody('verified');
  }
  else {
    $response->redirect = $redirect;
  }
  print json_encode($response);
}

exit();