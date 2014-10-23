<?php
/**
 * @file
 * Updates certain files with web package info: version, etc.
 */

// plugin file(s)
$files = array('js/verify_age.js');
foreach ($files as $file) {

  // Make the variable value replacement in the file
  $contents = $update = file_get_contents($file);

  // The header comment
  $regex = "/(jQuery JavaScript Plugin v)[^\s]*/i";
  $update = preg_replace($regex, "\${1}$argv[2]", $update);

  // The version function
  $regex = "/(version\s*=\s*function\s*\(\s*\)\s*{\s*return\s+['\"])[^']*(['\"]\s*;?\s*})/is";
  $update = preg_replace($regex, "\${1}$argv[2]\${2}", $update);

  // The name
  $regex = '/(\*\s*)(.*?)(\s+jQuery JavaScript Plugin)/i';
  $update = preg_replace($regex, "\${1}$argv[3]\${3}", $update);

  // Replace line 2 with url
  $lines = explode("\n", $update);
  $lines[2] = " * $argv[5]";
  // Replace line 5 with description
  $lines[4] = " * $argv[4]";
  $update = implode("\n", $lines);

  // Update the date
  $regex = '/(\*\s*Date:\s*)(.*)/i';
  $date = new DateTime('now', new DateTimeZone('America/Los_Angeles'));
  $date = $date->format('r');
  $update = preg_replace($regex, "\${1}$date", $update);

  if ($update && $contents !== $update) {
    $fh = fopen($file, 'w');
    fwrite($fh, $update);
    fclose($fh);
    echo "$file has been updated to $argv[2].\n";
  }
}
  