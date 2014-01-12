<?php
$active_path = isset($active_path) ? $active_path : '';
$base_path = isset($base_path) ? $base_path : '';
?><!DOCTYPE html>
<html>
  <head>
    <title>Verify Age Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?php print $base_path ?>css/bootstrap.min.css" rel="stylesheet">
    <?php if (isset($age)): print $age->getHead(); endif; ?>
    <style type="text/css" media="all">
      @import url("<?php print $base_path ?>css/style.css");
    </style>
  </head>
  <body>
  <a href="https://github.com/aklump/verify_age"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_white_ffffff.png" alt="Fork me on GitHub"></a>
  <div class="container">

  <ul class="nav nav-pills">
<?php
$nav = array(
  array('index.php', 'Home'),
  array('page-ignored.php', 'Not Restricted'),
  array('page-restricted.php', 'Restricted Page'),
  array('subfolder/', 'Subfolder Page'),
);
foreach ($nav as $item) {
  list($path, $title) = $item;
  $class = $active_path === $path ? 'active' : '';
  print '    <li class="' . $class . '"><a href="' . $base_path . $path . '">' . $title . '</a></li>' . PHP_EOL;
}
?>
  </ul>
