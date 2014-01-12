<!DOCTYPE html>
<html>
  <head>
    <title>Verify Age Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <?php if (isset($age)): print $age->getHead(); endif; ?>
    <style type="text/css" media="all">
      @import url("css/style.css");
    </style>
  </head>
  <body>
  <div class="container">

  <ul class="nav nav-pills">
    <li><a href="index.php">Home</a></li>
    <li><a href="page-ignored.php">Not Restricted</a></li>
    <li><a href="page-restricted.php">Restricted Page</a></li>
    <li><a href="subfolder/">Subfolder Page</a></li>
  </ul>